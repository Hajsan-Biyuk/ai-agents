#!/usr/bin/env python3
"""
Сырые прайсы Центр Сантехники → плоские Excel для convert.py.

Поддерживает:
- prices.xlsx (много листов по брендам)
- Прайс керамогранит *.xlsx
"""

from __future__ import annotations

import json
from pathlib import Path
from typing import Any

import pandas as pd

from raw_utils import (
    FLAT_COLUMNS,
    build_flat_row,
    is_category_header,
    is_flat_excel,
    load_reference_by_article,
    merge_reference_row,
    normalize_article,
    normalize_stock,
    parse_price,
    strip_columns,
    write_flat_excel,
)

SKIP_SHEETS = {"оглавление"}
PRICES_HEADER_ROW = 3
KERAMO_HEADER_ROW = 1
KERAMO_PATH = "Керамогранит"

DEFAULT_CATEGORY_PATHS = {
    "А8004390": "Сантехника///Раковины///На столешницу",
    "А8009400": "Сантехника///Душевая программа и комплектующие///Душевые системы",
    "А8009953": "Сантехника///Инсталляции///Инсталляции и клавиши",
    "Аксессуары для ванной комнаты": "Сантехника///Аксессуары для ванной комнаты///Полки",
    "Биде": "Сантехника///Унитазы, биде, писсуары///Биде///Напольные",
    "Ванны": "Сантехника///Ванны и комплектующие///Ванны///Акриловые ванны",
    "Душевые кабины, ограждения, поддоны": "Сантехника///Душевые двери, ограждения, боксы///Душевые кабины и боксы",
    "Запчасти, комплектующие и доп принадлежности": "Сантехника///Сифоны и донные клапаны///Донные клапаны",
    "Инсталляции": "Сантехника///Инсталляции///Инсталляции с унитазом (Комплект)",
    "Колонны душевые": "Сантехника///Душевая программа и комплектующие///Душевые системы",
    "Комплектующие": "Сантехника///Инсталляции///Инсталляции и клавиши",
    "Мебель": "Сантехника///Мебель для ванных комнат///Зеркала",
    "Механизмы выпускные": "Сантехника///Сифоны и донные клапаны///Сифоны для душевых поддонов",
    "Ограждения/двери душевые": "Сантехника///Душевые двери, ограждения, боксы///Душевые двери",
    "Полотенцесушители": "Сантехника///Полотенцесушители///Электрические",
    "Радиаторы": "Сантехника///Полотенцесушители///Водяные",
    "радиаторы": "Сантехника///Полотенцесушители///Водяные",
    "Раковины": "Сантехника///Раковины///Раковины-чаши",
    "Сеты": "Сантехника///Инсталляции///Инсталляции с унитазом (Комплект)",
    "Сифоны": "Сантехника///Сифоны и донные клапаны///Сифоны для раковин",
    "Смесители и душевые аксессуары": "Сантехника///Смесители///Смесители для ванны",
    "Трапы, душевые лотки": "Сантехника///Душевые трапы",
    "Унитазы": "Сантехника///Унитазы, биде, писсуары///Унитазы///Подвесные унитазы",
    "Гибкая подводка": "Сантехника///Смесители///Гибкая подводка",
    "Инженерная сантехника": "Сантехника///Смесители///Смесители для раковины",
    "Стенды": "Сантехника///Аксессуары для ванной комнаты///Полки",
    "Керамогранит": KERAMO_PATH,
    "УТ000005": "Сантехника///Душевые трапы///Решетки для душевых трапов",
    "УТ000734": "Сантехника///Унитазы, биде, писсуары///Крышки и сиденья для унитазов",
    "УТ000941": "Сантехника///Смесители///Смесители для ванны",
    "УТ001010": "Сантехника///Аксессуары для ванной комнаты///Ершики",
    "УТ001903": "Сантехника///Смесители///Смесители для раковины",
    "УТ001909": "Сантехника///Душевая программа и комплектующие///Душевые комплекты",
    "УТ002013": "Сантехника///Душевая программа и комплектующие///Прочее///Кронштейны",
    "УТ002242": "Сантехника///Душевая программа и комплектующие///Душевые системы",
    "УТ002557": "Сантехника///Смесители///Смесители для раковины",
    "УТ002896": "Сантехника///Ванны и комплектующие///Ванны///Акриловые ванны",
    "УТ002939": "Сантехника///Раковины///На столешницу",
    "УТ003003": "Сантехника///Унитазы, биде, писсуары///Унитазы///Приставные унитазы",
    "УТ003048": "Сантехника///Унитазы, биде, писсуары///Биде///Подвесные",
    "УТ003114": "Сантехника///Унитазы, биде, писсуары///Унитазы///Напольные унитазы",
    "УТ003192": "Сантехника///Ванны и комплектующие///Панели для ванн",
    "УТ003248": "Сантехника///Раковины///Раковины-чаши",
    "УТ003620": "Сантехника///Ванны и комплектующие///Ванны///Акриловые ванны",
    "УТ003873": "Сантехника///Унитазы, биде, писсуары///Унитазы///Подвесные унитазы",
    "УТ003943": "Сантехника///Инсталляции///Инсталляции и клавиши",
    "УТ004640": "Сантехника///Раковины///Подвесные",
    "УТ004968": "Сантехника///Раковины///Подвесные",
    "УТ005000": "Сантехника///Унитазы, биде, писсуары///Крышки и сиденья для унитазов",
    "УТ005529": "Сантехника///Унитазы, биде, писсуары///Унитазы///Подвесные унитазы",
    "УТ005636": "Сантехника///Унитазы, биде, писсуары///Унитазы///Подвесные унитазы",
    "УТ005661": "Сантехника///Инсталляции///Инсталляции и клавиши",
    "УТ005799": "Сантехника///Инсталляции///Инсталляции и клавиши",
    "УТ005959": "Сантехника///Сифоны и донные клапаны///Сифоны для ванн",
    "УТ006249": "Сантехника///Инсталляции///Инсталляции и клавиши",
    "УТ006383": "Сантехника///Сифоны и донные клапаны///Сифоны для раковин",
    "УТ006526": "Сантехника///Унитазы, биде, писсуары///Унитазы///Подвесные унитазы",
    "УТ006635": "Сантехника///Душевая программа и комплектующие///Смесители скрытого монтажа",
    "УТ007594": "Сантехника///Смесители///Смесители для ванны",
    "УТ007595": "Сантехника///Смесители///Для душа",
    "УТ008629": "Сантехника///Душевая программа и комплектующие///Шланги для душа",
}


def is_raw_sc_prices(path: Path) -> bool:
    if is_flat_excel(path):
        return False
    try:
        xl = pd.ExcelFile(path)
    except Exception:  # noqa: BLE001
        return False
    if "Оглавление" not in xl.sheet_names and "оглавление" not in [s.lower() for s in xl.sheet_names]:
        return False
    for sheet in xl.sheet_names:
        if sheet.lower() in SKIP_SHEETS:
            continue
        try:
            df = pd.read_excel(path, sheet_name=sheet, header=PRICES_HEADER_ROW, nrows=2)
        except Exception:  # noqa: BLE001
            continue
        cols = {str(c).strip() for c in df.columns}
        if "Артикул" in cols and "Номенклатура" in cols and "Цена РРЦ (руб.)" in cols:
            return True
    return False


def is_raw_sc_keramogranit(path: Path) -> bool:
    if is_flat_excel(path):
        return False
    name = path.name.lower()
    if "керамогранит" in name or "keramogranit" in name:
        return True
    try:
        df = pd.read_excel(path, header=KERAMO_HEADER_ROW, nrows=2)
    except Exception:  # noqa: BLE001
        return False
    cols = {str(c).strip() for c in df.columns}
    return "Артикул" in cols and "Номенклатура" in cols and "Цена РРЦ" in cols


def parse_prices_rows(source: Path, category_paths: dict[str, str]) -> tuple[list[dict[str, Any]], list[dict[str, Any]]]:
    rows: list[dict[str, Any]] = []
    errors: list[dict[str, Any]] = []
    seen: dict[str, int] = {}

    for sheet in pd.ExcelFile(source).sheet_names:
        if sheet.lower() in SKIP_SHEETS:
            continue
        df = pd.read_excel(source, sheet_name=sheet, header=PRICES_HEADER_ROW)
        cols = strip_columns(df)
        acol = cols.get("Артикул")
        ncol = cols.get("Номенклатура")
        pcol = cols.get("Цена РРЦ (руб.)")
        scol = cols.get("Остаток")
        if not acol or not ncol or not pcol:
            continue

        category_col = df.columns[0]
        category: str | None = None
        for row_idx, row in df.iterrows():
            cat_val = str(row[category_col]).strip() if pd.notna(row[category_col]) else ""
            if is_category_header(cat_val):
                category = cat_val

            article = normalize_article(row[acol])
            if not article:
                continue
            price = parse_price(row[pcol])
            if price is None:
                errors.append({"sheet": sheet, "row": int(row_idx) + PRICES_HEADER_ROW + 2, "article": article, "message": "нет цены"})
                continue
            if article in seen:
                errors.append({"sheet": sheet, "row": int(row_idx) + PRICES_HEADER_ROW + 2, "article": article, "message": f"дубликат (строка {seen[article]})"})
                continue
            seen[article] = int(row_idx) + PRICES_HEADER_ROW + 2

            stock_raw = row[scol] if scol else ""
            in_stock, qty = normalize_stock(stock_raw)
            path = category_paths.get(category or "", "")
            rows.append(
                {
                    "Артикул": article,
                    "Бренд": sheet.strip(),
                    "Наименование": str(row[ncol]).strip() if pd.notna(row[ncol]) else article,
                    "Цена, руб.": price,
                    "В наличии": in_stock,
                    "Количество": qty if qty is not None else 500,
                    "Путь": path,
                }
            )
    return rows, errors


def parse_keramogranit_rows(source: Path) -> tuple[list[dict[str, Any]], list[dict[str, Any]]]:
    rows: list[dict[str, Any]] = []
    errors: list[dict[str, Any]] = []
    seen: dict[str, int] = {}

    df = pd.read_excel(source, header=KERAMO_HEADER_ROW)
    cols = strip_columns(df)
    acol = cols.get("Артикул")
    ncol = cols.get("Номенклатура")
    pcol = cols.get("Цена РРЦ")
    if not acol or not ncol or not pcol:
        return rows, [{"message": "не найдены колонки Артикул/Номенклатура/Цена РРЦ"}]

    for row_idx, row in df.iterrows():
        article = normalize_article(row[acol])
        if not article:
            continue
        price = parse_price(row[pcol])
        if price is None:
            errors.append({"row": int(row_idx) + KERAMO_HEADER_ROW + 2, "article": article, "message": "нет цены"})
            continue
        if article in seen:
            errors.append({"row": int(row_idx) + KERAMO_HEADER_ROW + 2, "article": article, "message": f"дубликат (строка {seen[article]})"})
            continue
        seen[article] = int(row_idx) + KERAMO_HEADER_ROW + 2

        rows.append(
            {
                "Артикул": article,
                "Бренд": "Azario",
                "Наименование": str(row[ncol]).strip() if pd.notna(row[ncol]) else article,
                "Цена, руб.": price,
                "В наличии": "Да",
                "Количество": 500,
                "Путь": KERAMO_PATH,
            }
        )
    return rows, errors


def enrich_rows(
    source_rows: list[dict[str, Any]],
    ref_columns: list[str],
    ref_cache: dict[str, pd.Series],
) -> tuple[list[dict[str, Any]], dict[str, int]]:
    output_rows: list[dict[str, Any]] = []
    stats = {"from_reference": 0, "from_mapping": 0, "without_path": 0}

    for item in source_rows:
        art = item["Артикул"]
        ref_row = ref_cache.get(art)
        if ref_columns != FLAT_COLUMNS:
            row = merge_reference_row(ref_columns, item, ref_row)
            if ref_row is not None:
                stats["from_reference"] += 1
            elif item.get("Путь"):
                stats["from_mapping"] += 1
            else:
                stats["without_path"] += 1
            output_rows.append(row)
            continue

        row = build_flat_row(item)
        if ref_row is not None:
            for col in FLAT_COLUMNS:
                if col in {"Артикул", "Цена, руб.", "В наличии", "Количество", "Бренд"}:
                    continue
                if col in ref_row.index and pd.notna(ref_row[col]) and str(ref_row[col]).strip():
                    row[col] = ref_row[col]
            stats["from_reference"] += 1
        elif item.get("Путь"):
            stats["from_mapping"] += 1
        else:
            stats["without_path"] += 1
        output_rows.append(row)
    return output_rows, stats


def preprocess_raw_santehcentr(
    source_files: list[Path],
    output_dir: Path,
    reference_dir: Path | None = None,
    category_paths: dict[str, str] | None = None,
) -> dict[str, Any]:
    output_dir.mkdir(parents=True, exist_ok=True)
    paths = {**DEFAULT_CATEGORY_PATHS, **(category_paths or {})}
    ref_columns, ref_cache = load_reference_by_article(reference_dir)

    all_rows: list[dict[str, Any]] = []
    all_errors: list[dict[str, Any]] = []
    stats_total = {"from_reference": 0, "from_mapping": 0, "without_path": 0}
    sources: list[str] = []
    output_files: list[str] = []

    prices_files = [f for f in source_files if is_raw_sc_prices(f)]
    keramo_files = [f for f in source_files if is_raw_sc_keramogranit(f)]

    if prices_files:
        source = prices_files[0]
        sources.append(str(source))
        parsed, errors = parse_prices_rows(source, paths)
        rows, stats = enrich_rows(parsed, ref_columns, ref_cache)
        all_rows.extend(rows)
        all_errors.extend(errors)
        for key in stats_total:
            stats_total[key] += stats[key]
        out_file = output_dir / "prices_flat.xlsx"
        write_flat_excel(rows, out_file, columns=[str(c) for c in ref_columns] if ref_columns else FLAT_COLUMNS)
        output_files.append(str(out_file))

    if keramo_files:
        source = keramo_files[0]
        sources.append(str(source))
        parsed, errors = parse_keramogranit_rows(source)
        rows, stats = enrich_rows(parsed, ref_columns, ref_cache)
        all_rows.extend(rows)
        all_errors.extend(errors)
        for key in stats_total:
            stats_total[key] += stats[key]
        out_file = output_dir / "keramogranit_flat.xlsx"
        write_flat_excel(rows, out_file, columns=[str(c) for c in ref_columns] if ref_columns else FLAT_COLUMNS)
        output_files.append(str(out_file))

    report = {
        "source": sources,
        "output_dir": str(output_dir),
        "reference_dir": str(reference_dir) if reference_dir else None,
        "total_rows": len(all_rows),
        "errors": all_errors,
        "stats": stats_total,
        "output_files": output_files,
    }
    (output_dir / "santehcentr_raw_preprocess.json").write_text(
        json.dumps(report, ensure_ascii=False, indent=2),
        encoding="utf-8",
    )
    return report
