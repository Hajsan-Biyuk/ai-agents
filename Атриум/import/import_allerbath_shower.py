#!/usr/bin/env python3
"""
Импорт листа «Душевые ограждения» из Аванти.xls (бренд AllerBath).
Другие листы не читаются. Карточка = артикул, без склейки размеров.

  .venv/bin/python import_allerbath_shower.py --dry-run-only
  .venv/bin/python import_allerbath_shower.py --confirm
"""

from __future__ import annotations

import argparse
import json
import sys
import time
import urllib.request
from datetime import datetime
from pathlib import Path

import yaml

IMPORT_DIR = Path(__file__).resolve().parent
sys.path.insert(0, str(IMPORT_DIR))

from convert import convert_file, load_profile  # noqa: E402

EXCEL = IMPORT_DIR / "data" / "inbox" / "Аванти.xls"
SHEET = "Душевые ограждения"
REPORTS = IMPORT_DIR / "data" / "reports"

SURFACE = {
    "глянцевое": "Глянцевая",
    "матовое": "Матовая",
}


def load_config() -> dict:
    return yaml.safe_load((IMPORT_DIR / "config.yaml").read_text(encoding="utf-8"))


def prepare(product: dict) -> dict:
    """Правки поверх convert: одна опечатка размера и покрытие перегородок."""
    row = dict(product)
    attrs = dict(row.get("attributes") or {})
    sections = [code for chain in (row.get("sections") or []) for code in chain]
    article = str(row.get("article") or "")

    if article == "A-2001C(100)":
        # В ряде 800 / 900 / (100) в файл попали сантиметры и в ячейку, и в название.
        attrs["Ширина, мм"] = "1000"
        attrs["Длина, мм"] = "1000"
        row["name"] = str(row.get("name") or "").replace("100*100*1950", "1000*1000*1950")

    if "dushevye-peregorodki-r" in sections:
        coat = str(attrs.pop("Покрытие профиля", "") or "").strip()
        if coat:
            attrs["Поверхность профиля"] = SURFACE.get(coat.lower(), coat)

    # Нормализатор душевых систем дописывает «Цвет» из названия.
    # В фильтре душевых это чужое свойство «Цвет», цвет профиля уже в своей колонке.
    attrs.pop("Цвет", None)
    attrs.pop("Цвет поддона", None)
    attrs.pop("Название модели", None)
    attrs.pop("Размер верхней лейки, мм", None)
    row["attributes"] = attrs
    # Коллекция пишется отдельно в справочник «Коллекции», не в список PROP_1240.
    row["collection"] = ""
    row["description"] = ""
    row["supplier"] = "allerbath"
    row["type"] = "simple"
    return row


def convert_products() -> tuple[list[dict], dict[str, str]]:
    profile, categories = load_profile("allerbath")
    errors: list = []
    unk_cat: dict = {}
    unk_col: set = set()
    rows = convert_file(EXCEL, profile, categories, errors, unk_cat, unk_col, sheet_name=SHEET)
    if errors:
        raise SystemExit(f"CONVERT ERRORS {errors[:8]}")
    if unk_cat:
        raise SystemExit(f"UNKNOWN CATEGORIES {unk_cat}")
    collections = {str(src.get("article")): str(src.get("collection") or "") for src in rows}
    products = [prepare(p) for p in rows]
    print(
        f"convert rows={len(rows)} sections={sorted({c for p in products for chain in p['sections'] for c in chain})} "
        f"unknown_cols={sorted(unk_col)}",
        flush=True,
    )
    return products, collections


def post_batch(config: dict, products: list, dry_run: bool, with_photos: bool) -> dict:
    payload = json.dumps(
        {
            "dry_run": dry_run,
            "with_photos": with_photos,
            "update_mode": "force_full",
            "products": products,
        },
        ensure_ascii=False,
    ).encode("utf-8")
    req = urllib.request.Request(
        config["url"].rstrip("/") + "/local/tools/import/receive.php",
        data=payload,
        method="POST",
        headers={"X-Import-Token": config["token"], "Content-Type": "application/json"},
    )
    last = None
    for _ in range(3):
        try:
            with urllib.request.urlopen(req, timeout=280) as resp:
                return json.loads(resp.read().decode("utf-8"))
        except Exception as exc:  # noqa: BLE001
            last = exc
            time.sleep(5)
    raise RuntimeError(f"receive unavailable: {last}")


def merge_tot(dst: dict, src: dict) -> None:
    for key in ("created", "updated", "skipped"):
        dst[key] = dst.get(key, 0) + int(src.get(key) or 0)
    dst.setdefault("errors", []).extend(src.get("errors") or [])
    dst.setdefault("log", []).extend(src.get("log") or [])


def main() -> int:
    parser = argparse.ArgumentParser()
    parser.add_argument("--dry-run-only", action="store_true")
    parser.add_argument("--confirm", action="store_true")
    parser.add_argument("--photos-only", action="store_true")
    args = parser.parse_args()

    products, collections = convert_products()
    REPORTS.mkdir(parents=True, exist_ok=True)
    stamp = datetime.now().strftime("%Y-%m-%d_%H%M%S")
    (REPORTS / f"allerbath_collections_{stamp}.json").write_text(
        json.dumps(collections, ensure_ascii=False, indent=2),
        encoding="utf-8",
    )
    sample = next(p for p in products if p["article"] == "A-2001C(100)")
    part = next(p for p in products if "dushevye-peregorodki-r" in [c for chain in p["sections"] for c in chain])
    print("sample corner", sample["article"], sample["name"], sample["attributes"].get("Ширина, мм"), sample["attributes"].get("Длина, мм"))
    print("sample partition surface", part["article"], part["attributes"].get("Поверхность профиля"), "coat", part["attributes"].get("Покрытие профиля"))

    config = load_config()
    if not args.photos_only:
        print("=== DRY-RUN ===", flush=True)
        tot = {"created": 0, "updated": 0, "skipped": 0, "errors": [], "log": []}
        for i in range(0, len(products), 13):
            chunk = products[i : i + 13]
            result = post_batch(config, chunk, dry_run=True, with_photos=False)
            merge_tot(tot, result)
            print(
                f"  dry {i + 1}-{i + len(chunk)} +{result.get('created', 0)}/~{result.get('updated', 0)} err={len(result.get('errors') or [])}",
                flush=True,
            )
            if result.get("errors"):
                print("  ERR", result["errors"][:4], flush=True)
        print(f"dry-run TOTAL create={tot['created']} update={tot['updated']} skip={tot['skipped']} err={len(tot['errors'])}", flush=True)
        (REPORTS / f"allerbath_dry_{stamp}.json").write_text(
            json.dumps({k: tot[k] for k in tot if k != "log"}, ensure_ascii=False, indent=2),
            encoding="utf-8",
        )
        if tot["errors"]:
            return 2
        if args.dry_run_only or not args.confirm:
            print("нужен --confirm для боя", flush=True)
            return 0

        print("=== CONTENT ===", flush=True)
        live = {"created": 0, "updated": 0, "skipped": 0, "errors": [], "log": []}
        for i in range(0, len(products), 10):
            chunk = products[i : i + 10]
            result = post_batch(config, chunk, dry_run=False, with_photos=False)
            merge_tot(live, result)
            print(
                f"  live {i + 1}-{i + len(chunk)} +{result.get('created', 0)}/~{result.get('updated', 0)} err={len(result.get('errors') or [])}",
                flush=True,
            )
            if result.get("errors"):
                print("  ERR", result["errors"][:5], flush=True)
            time.sleep(0.4)
        print(f"content TOTAL create={live['created']} update={live['updated']} err={len(live['errors'])}", flush=True)
        (REPORTS / f"allerbath_import_{stamp}.json").write_text(
            json.dumps({k: live[k] for k in live if k != "log"}, ensure_ascii=False, indent=2),
            encoding="utf-8",
        )
        if live["errors"]:
            return 3

    if not args.confirm:
        return 0

    print("=== PHOTOS ===", flush=True)
    photo_tot = {"created": 0, "updated": 0, "skipped": 0, "errors": [], "log": []}
    for i in range(0, len(products), 4):
        chunk = products[i : i + 4]
        result = post_batch(config, chunk, dry_run=False, with_photos=True)
        merge_tot(photo_tot, result)
        print(
            f"  photo {[p['article'] for p in chunk]} +{result.get('created', 0)}/~{result.get('updated', 0)} err={len(result.get('errors') or [])}",
            flush=True,
        )
        if result.get("errors"):
            print("  ERR", result["errors"][:4], flush=True)
        time.sleep(0.5)
    print(f"photos TOTAL create={photo_tot['created']} update={photo_tot['updated']} err={len(photo_tot['errors'])}", flush=True)
    (REPORTS / f"allerbath_photos_{stamp}.json").write_text(
        json.dumps({k: photo_tot[k] for k in photo_tot if k != "log"}, ensure_ascii=False, indent=2),
        encoding="utf-8",
    )
    return 0 if not photo_tot["errors"] else 4


if __name__ == "__main__":
    raise SystemExit(main())
