#!/usr/bin/env python3
"""
Оркестратор импорта Атриум (запускается по cron на сервере).

Цикл: inbox/{supplier}/ → convert → валидация → dry-run → импорт → processed/ + отчёт.
Файлы с ошибками → quarantine/ и импорт НЕ выполняется.

Запуск:
    python3 run_import.py                  # cron: только если файлы в inbox изменились
    python3 run_import.py --supplier artkera
    python3 run_import.py --dry-run-only   # только dry-run, без записи на сайт
    python3 run_import.py --supplier artkera --confirm   # ручное подтверждение импорта
    python3 run_import.py --force          # игнорировать проверку «файлы не менялись»

Конфиг: config.yaml рядом со скриптом (url, token, пороги).
"""

from __future__ import annotations

import argparse
import json
import shutil
import sys
import time
import urllib.request
from datetime import datetime
from pathlib import Path

import yaml

IMPORT_DIR = Path(__file__).resolve().parent
INBOX = IMPORT_DIR / "inbox"
PROCESSED = IMPORT_DIR / "processed"
QUARANTINE = IMPORT_DIR / "quarantine"
REPORTS = IMPORT_DIR / "reports"
LOCK_FILE = IMPORT_DIR / ".lock"
PROGRESS_LOG = REPORTS / "progress.log"

sys.path.insert(0, str(IMPORT_DIR))
from convert import convert_supplier  # noqa: E402
from artkera_hier import is_hierarchical_price, preprocess_hierarchical  # noqa: E402
from raw_utils import is_flat_excel  # noqa: E402
from rgw_raw import is_raw_rgw_price, preprocess_raw_rgw  # noqa: E402
from santehcentr_raw import (  # noqa: E402
    is_raw_sc_keramogranit,
    is_raw_sc_prices,
    preprocess_raw_santehcentr,
)
from inbox_state import clear_state, inbox_changed, inbox_snapshot, save_state  # noqa: E402

STAGING = IMPORT_DIR / "data" / "staging"
STATE_DIR = IMPORT_DIR / "data" / "state"
STATUSES_SAVE_STATE = {"dry_run_only", "awaiting_confirm", "imported", "imported_with_errors"}
EXCEL_SUFFIXES = (".xls", ".xlsx")


def load_config() -> dict:
    return yaml.safe_load((IMPORT_DIR / "config.yaml").read_text(encoding="utf-8"))


def progress_write(message: str) -> None:
    REPORTS.mkdir(parents=True, exist_ok=True)
    line = f"{datetime.now().strftime('%Y-%m-%d %H:%M:%S')} {message}\n"
    with PROGRESS_LOG.open("a", encoding="utf-8") as fh:
        fh.write(line)


def post_batch(config: dict, products: list, dry_run: bool, with_photos: bool) -> dict:
    payload = json.dumps(
        {"dry_run": dry_run, "with_photos": with_photos, "products": products},
        ensure_ascii=False,
    ).encode()
    req = urllib.request.Request(
        config["url"].rstrip("/") + "/local/tools/import/receive.php",
        data=payload,
        method="POST",
        headers={"X-Import-Token": config["token"], "Content-Type": "application/json"},
    )
    last_error = None
    for attempt in range(3):
        try:
            with urllib.request.urlopen(req, timeout=280) as resp:
                return json.loads(resp.read())
        except Exception as e:  # noqa: BLE001
            last_error = e
            time.sleep(5)
    raise RuntimeError(f"endpoint недоступен после 3 попыток: {last_error}")


def send_all(
    config: dict,
    products: list,
    dry_run: bool,
    with_photos: bool,
    log: list,
    supplier: str = "",
    phase: str = "",
) -> dict:
    batch_size = int(config.get("batch_size", 100))
    total_batches = max(1, (len(products) + batch_size - 1) // batch_size)
    total = {"created": 0, "updated": 0, "skipped": 0, "errors": []}
    for i in range(0, len(products), batch_size):
        batch_no = i // batch_size + 1
        r = post_batch(config, products[i:i + batch_size], dry_run, with_photos)
        total["created"] += r.get("created", 0)
        total["updated"] += r.get("updated", 0)
        total["skipped"] += r.get("skipped", 0)
        total["errors"].extend(r.get("errors", []))
        log.append(f"партия {batch_no}: +{r.get('created', 0)}/~{r.get('updated', 0)} err={len(r.get('errors', []))}")
        if supplier:
            progress_write(
                f"[{supplier}] {phase} партия {batch_no}/{total_batches}: "
                f"+{r.get('created', 0)}/~{r.get('updated', 0)} "
                f"(всего +{total['created']}/~{total['updated']})"
            )
        time.sleep(1)
    return total


def quarantine_files(supplier: str, files: list, reason: str, report: dict) -> None:
    qdir = QUARANTINE / supplier
    qdir.mkdir(parents=True, exist_ok=True)
    for f in files:
        src = Path(f)
        if src.exists():
            shutil.move(str(src), str(qdir / src.name))
    (qdir / "REASON.txt").write_text(
        f"{datetime.now().isoformat()}\n{reason}\n\n{json.dumps(report, ensure_ascii=False, indent=2)[:5000]}",
        encoding="utf-8",
    )
    clear_state(STATE_DIR, supplier)


def list_inbox_excel(inbox_dir: Path) -> list[Path]:
    return sorted(p for p in inbox_dir.iterdir() if p.suffix.lower() in EXCEL_SUFFIXES)


def _resolve_reference_dir(sup_conf: dict, key: str, supplier: str) -> Path | None:
    raw_conf = sup_conf.get(key, {})
    reference_dir = raw_conf.get("reference_dir")
    ref_path = Path(reference_dir) if reference_dir else IMPORT_DIR / "data" / "reference" / supplier
    return ref_path if ref_path.is_dir() else None


def _prepare_staging_dir(supplier: str) -> Path:
    staging_dir = STAGING / supplier
    if staging_dir.exists():
        shutil.rmtree(staging_dir)
    staging_dir.mkdir(parents=True, exist_ok=True)
    return staging_dir


def prepare_inbox(
    supplier: str,
    inbox_dir: Path,
    sup_conf: dict,
    log: list[str],
    enrich_photos: bool = False,
) -> tuple[str, list[Path], dict | None]:
    """
    Подготовка файлов к конвертации.
    - artkera: иерархический прайс → 6 плоских файлов
    - rgw / santehcentr: сырой прайс → плоские файлы в staging
    """
    files = list_inbox_excel(inbox_dir)
    if not files:
        return str(inbox_dir), [], None

    flat_files = [f for f in files if is_flat_excel(f)]
    raw_files = [f for f in files if f not in flat_files]

    if supplier == "artkera":
        hier_files = [f for f in raw_files if is_hierarchical_price(f)]
        if not hier_files:
            return str(inbox_dir), files, None
        if len(hier_files) > 1:
            raise ValueError(
                f"в inbox несколько иерархических прайсов ({len(hier_files)}); оставьте один файл"
            )
        if flat_files:
            log.append(
                f"игнорированы плоские файлы ({len(flat_files)}): найден иерархический прайс "
                f"«{hier_files[0].name}»"
            )

        hier_conf = sup_conf.get("hier", {})
        ref_path = _resolve_reference_dir(sup_conf, "hier", supplier)
        with_photos = (
            enrich_photos
            and bool(sup_conf.get("with_photos", True))
            and bool(hier_conf.get("with_photos", True))
        )
        staging_dir = _prepare_staging_dir(supplier)
        preprocess_report = preprocess_hierarchical(
            hier_files[0],
            staging_dir,
            reference_dir=ref_path,
            with_photos=with_photos,
        )
        log.append(
            f"иерархический прайс «{hier_files[0].name}» → {preprocess_report['total_rows']} товаров, "
            f"{len(preprocess_report['output_files'])} файлов"
        )
        if preprocess_report["errors"]:
            log.append(f"предупреждений при разборе прайса: {len(preprocess_report['errors'])}")
        return str(staging_dir), hier_files, preprocess_report

    if supplier == "rgw":
        raw_rgw = [f for f in raw_files if is_raw_rgw_price(f)]
        if not raw_rgw:
            return str(inbox_dir), files, None
        if len(raw_rgw) > 1:
            raise ValueError(f"в inbox несколько сырых прайсов RGW ({len(raw_rgw)}); оставьте один файл")
        if flat_files:
            log.append(
                f"игнорированы обработанные файлы ({len(flat_files)}): найден сырой прайс "
                f"«{raw_rgw[0].name}»"
            )

        ref_path = _resolve_reference_dir(sup_conf, "raw", supplier)
        staging_dir = _prepare_staging_dir(supplier)
        preprocess_report = preprocess_raw_rgw(raw_rgw[0], staging_dir, reference_dir=ref_path)
        log.append(
            f"сырой прайс RGW «{raw_rgw[0].name}» → {preprocess_report['total_rows']} товаров, "
            f"эталон {preprocess_report['stats']['from_reference']}, "
            f"без пути {preprocess_report['stats']['without_path']}"
        )
        if preprocess_report["errors"]:
            log.append(f"предупреждений при разборе прайса: {len(preprocess_report['errors'])}")
        return str(staging_dir), raw_rgw, preprocess_report

    if supplier == "santehcentr":
        raw_sc = [f for f in raw_files if is_raw_sc_prices(f) or is_raw_sc_keramogranit(f)]
        if not raw_sc:
            return str(inbox_dir), files, None
        if flat_files:
            log.append(
                f"игнорированы обработанные файлы ({len(flat_files)}): найдены сырые прайсы "
                f"({', '.join(f.name for f in raw_sc)})"
            )

        raw_conf = sup_conf.get("raw", {})
        ref_path = _resolve_reference_dir(sup_conf, "raw", supplier)
        staging_dir = _prepare_staging_dir(supplier)
        preprocess_report = preprocess_raw_santehcentr(
            raw_sc,
            staging_dir,
            reference_dir=ref_path,
            category_paths=raw_conf.get("category_paths"),
        )
        log.append(
            f"сырые прайсы ({len(raw_sc)} шт.) → {preprocess_report['total_rows']} товаров, "
            f"эталон {preprocess_report['stats']['from_reference']}, "
            f"без пути {preprocess_report['stats']['without_path']}"
        )
        if preprocess_report["errors"]:
            log.append(f"предупреждений при разборе прайса: {len(preprocess_report['errors'])}")
        return str(staging_dir), raw_sc, preprocess_report

    return str(inbox_dir), files, None


def archive_files(supplier: str, files: list) -> None:
    pdir = PROCESSED / supplier
    pdir.mkdir(parents=True, exist_ok=True)
    stamp = datetime.now().strftime("%Y-%m-%d_%H%M")
    for f in files:
        src = Path(f)
        if src.exists():
            shutil.move(str(src), str(pdir / f"{stamp}_{src.name}"))


def process_supplier(supplier: str, config: dict, dry_run_only: bool, confirm: bool = False) -> dict | None:
    inbox_dir = INBOX / supplier
    if not inbox_dir.is_dir():
        return None
    inbox_files = list_inbox_excel(inbox_dir)
    if not inbox_files:
        return None

    log: list[str] = [f"файлов в inbox: {len(inbox_files)}"]
    progress_write(f"[{supplier}] старт, файлов в inbox: {len(inbox_files)}")
    result: dict = {"supplier": supplier, "started_at": datetime.now().isoformat(), "log": log}

    sup_conf = config.get("suppliers", {}).get(supplier, {})
    max_errors = int(sup_conf.get("max_convert_errors", 100))
    max_create = int(sup_conf.get("max_create_auto", 500))
    with_photos = bool(sup_conf.get("with_photos", True))
    auto_confirm = bool(sup_conf.get("auto_confirm", False))

    enrich_photos = with_photos and (confirm or auto_confirm)

    # 0. Сырой / иерархический прайс → staging
    archive_targets = inbox_files
    try:
        convert_dir, archive_targets, hier_report = prepare_inbox(
            supplier, inbox_dir, sup_conf, log, enrich_photos=enrich_photos
        )
        if hier_report:
            result["preprocess"] = {
                "source": hier_report.get("source"),
                "total_rows": hier_report.get("total_rows"),
                "errors_count": len(hier_report.get("errors", [])),
                "reference_dir": hier_report.get("reference_dir"),
                "stats": hier_report.get("stats"),
            }
    except Exception as e:  # noqa: BLE001
        result["status"] = "quarantine"
        result["reason"] = f"ошибка препроцессинга прайса: {e}"
        quarantine_files(supplier, inbox_files, result["reason"], result)
        return result

    # 1. Конвертация
    try:
        conv = convert_supplier(supplier, files_dir=convert_dir, out_dir=str(IMPORT_DIR / "data" / "normalized"))
    except Exception as e:  # noqa: BLE001
        result["status"] = "quarantine"
        result["reason"] = f"ошибка конвертации: {e}"
        quarantine_files(supplier, archive_targets, result["reason"], result)
        return result

    log.append(f"товаров: {conv['total']}, ошибок конвертации: {len(conv['errors'])}")
    progress_write(f"[{supplier}] конвертация: {conv['total']} товаров, ошибок {len(conv['errors'])}")
    result["convert_errors"] = len(conv["errors"])
    result["unknown_categories"] = conv["unknown_categories"]

    # 2. Валидация
    if conv["total"] == 0 or len(conv["errors"]) > max_errors:
        result["status"] = "quarantine"
        result["reason"] = f"валидация: товаров {conv['total']}, ошибок {len(conv['errors'])} (лимит {max_errors})"
        quarantine_files(supplier, archive_targets, result["reason"], {"errors": conv["errors"][:50]})
        return result

    # 3. Dry-run на сайте
    progress_write(f"[{supplier}] dry-run: {conv['total']} товаров")
    dry = send_all(config, conv["products"], dry_run=True, with_photos=False, log=log, supplier=supplier, phase="dry-run")
    result["dry_run"] = {k: v for k, v in dry.items() if k != "errors"}
    result["dry_run"]["errors_count"] = len(dry["errors"])
    log.append(f"dry-run: create={dry['created']} update={dry['updated']} err={len(dry['errors'])}")

    if dry["errors"]:
        result["status"] = "quarantine"
        result["reason"] = f"dry-run вернул {len(dry['errors'])} ошибок"
        quarantine_files(supplier, archive_targets, result["reason"], {"errors": dry["errors"][:50]})
        return result

    if dry["created"] > max_create:
        result["status"] = "quarantine"
        result["reason"] = f"dry-run: создаётся {dry['created']} товаров (> лимита {max_create}) — нужен ручной запуск"
        quarantine_files(supplier, archive_targets, result["reason"], result)
        return result

    if dry_run_only or (not auto_confirm and not confirm):
        result["status"] = "awaiting_confirm" if not dry_run_only else "dry_run_only"
        result["reason"] = "импорт не выполнен: " + (
            "флаг --dry-run-only" if dry_run_only
            else "auto_confirm=false (запустите с --confirm)"
        )
        # файлы остаются в inbox до ручного подтверждения/запуска
        return result

    # 4. Боевой импорт
    progress_write(f"[{supplier}] импорт на сайт: {conv['total']} товаров")
    real = send_all(config, conv["products"], dry_run=False, with_photos=with_photos, log=log, supplier=supplier, phase="import")
    result["import"] = {k: v for k, v in real.items() if k != "errors"}
    result["import"]["errors_count"] = len(real["errors"])
    result["import_errors"] = real["errors"][:50]
    result["status"] = "imported" if not real["errors"] else "imported_with_errors"

    archive_files(supplier, archive_targets)
    clear_state(STATE_DIR, supplier)
    progress_write(f"[{supplier}] готово: {result['status']}")
    return result


def main() -> None:
    ap = argparse.ArgumentParser()
    ap.add_argument("--supplier", default=None)
    ap.add_argument("--dry-run-only", action="store_true")
    ap.add_argument("--confirm", action="store_true", help="выполнить импорт даже при auto_confirm=false")
    ap.add_argument("--force", action="store_true", help="обработать inbox даже если файлы не менялись")
    args = ap.parse_args()

    # lock от параллельных запусков cron
    if LOCK_FILE.exists() and time.time() - LOCK_FILE.stat().st_mtime < 3600 * 3:
        print("уже выполняется (lock)")
        sys.exit(0)
    LOCK_FILE.write_text(str(datetime.now()))

    try:
        config = load_config()
        suppliers = [args.supplier] if args.supplier else sorted(
            p.name for p in INBOX.iterdir() if p.is_dir()
        )
        REPORTS.mkdir(parents=True, exist_ok=True)
        STATE_DIR.mkdir(parents=True, exist_ok=True)

        watch_inbox = bool(config.get("watch_inbox", True))
        manual_run = args.force or args.confirm or args.dry_run_only
        check_changes = watch_inbox and not manual_run

        ran = 0
        skipped = 0
        for supplier in suppliers:
            inbox_dir = INBOX / supplier
            if not inbox_dir.is_dir():
                continue
            if not list_inbox_excel(inbox_dir):
                continue

            snapshot = inbox_snapshot(inbox_dir)
            if check_changes:
                changed, snapshot, previous = inbox_changed(STATE_DIR, supplier, inbox_dir)
                if not changed:
                    prev_status = (previous or {}).get("last_status", "—")
                    prev_at = (previous or {}).get("last_run_at", "—")
                    print(
                        f"[{supplier}] skipped — файлы не изменились "
                        f"(последний запуск: {prev_status}, {prev_at})"
                    )
                    skipped += 1
                    continue

            result = process_supplier(supplier, config, args.dry_run_only, confirm=args.confirm)
            if result is None:
                continue
            ran += 1
            result["finished_at"] = datetime.now().isoformat()
            if check_changes or manual_run:
                result["inbox_snapshot"] = {
                    "fingerprint": snapshot["fingerprint"],
                    "files": [item["name"] for item in snapshot["files"]],
                }
            stamp = datetime.now().strftime("%Y-%m-%d_%H%M%S")
            report_path = REPORTS / f"{stamp}_{supplier}.json"
            report_path.write_text(json.dumps(result, ensure_ascii=False, indent=2), encoding="utf-8")
            print(f"[{supplier}] {result.get('status')} — {result.get('reason', 'ok')} → {report_path.name}")
            progress_write(f"[{supplier}] отчёт {result.get('status')} → {report_path.name}")

            status = result.get("status")
            if status in STATUSES_SAVE_STATE and list_inbox_excel(inbox_dir):
                save_state(STATE_DIR, supplier, snapshot, status, report_path.name)

        if ran == 0 and skipped == 0:
            print("inbox пуст — нечего делать")
        elif ran == 0 and skipped > 0:
            print(f"обновлённых файлов нет — пропущено поставщиков: {skipped}")
    finally:
        LOCK_FILE.unlink(missing_ok=True)


if __name__ == "__main__":
    main()
