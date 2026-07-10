#!/usr/bin/env python3
"""
Сырой прайс RGW (RGW_Price.xlsx) → плоский Excel для convert.py.
"""

from __future__ import annotations

import json
from pathlib import Path
from typing import Any

import pandas as pd

from raw_utils import (
    FLAT_COLUMNS,
    build_flat_row,
    is_flat_excel,
    load_reference_by_article,
    merge_reference_row,
    normalize_article,
    normalize_stock,
    parse_price,
    strip_columns,
    write_flat_excel,
)

IMPORT_DIR = Path(__file__).resolve().parent
SKIP_SHEETS = {"оглавление"}
HEADER_ROW = 10
BRAND = "RGW"

RGW_SHEET_DEFAULTS = {
    "Душевые кабины": "Сантехника///Душевые двери, ограждения, боксы///Душевые кабины и боксы",
    "Барьер": "Сантехника///Душевые двери, ограждения, боксы///Душевые двери",
    "Унитазы и Биде": "Сантехника///Унитазы, биде, писсуары///Унитазы///Подвесные унитазы",
}

RGW_SUBGROUP_PATHS = {
    ("Ограждения", "Сборные перегородки"): "Каталог///Сантехника///Душевые кабины, ограждения, боксы///Душевые неподвижные перегородки",
    ("Ограждения", "Распашные"): "Каталог///Сантехника///Душевые кабины, ограждения, боксы///Душевые двери",
    ("Ограждения", "Раздвижные"): "Каталог///Сантехника///Душевые кабины, ограждения, боксы///Душевые двери",
    ("Ограждения", "Складные"): "Каталог///Сантехника///Душевые кабины, ограждения, боксы///Душевые двери",
    ("Ограждения", "Трапеция"): "Сантехника///Душевые двери, ограждения, боксы///Душевые уголки",
    ("Ограждения", "Сборные"): "Сантехника///Душевые двери, ограждения, боксы///Душевые уголки",
    ("Ограждения", "Прямоугольные"): "Сантехника///Душевые двери, ограждения, боксы///Душевые уголки",
    ("Ограждения", "Стационарные"): "Сантехника///Ванны и комплектующие///Шторки для ванн",
    ("Ограждения", "Квадратные"): "Сантехника///Душевые двери, ограждения, боксы///Душевые уголки",
    ("Ограждения", "Полукруглые"): "Сантехника///Душевые двери, ограждения, боксы///Душевые уголки",
    ("Ограждения", "Боковые стенки"): "Каталог///Сантехника///Душевые кабины, ограждения, боксы///Душевые неподвижные перегородки",
    ("Душевые поддоны", "Искуственный камень ST"): "Сантехника///Душевые поддоны и комплектующие///Душевые поддоны",
    ("Душевые поддоны", "Искусcтвенный камень STA"): "Сантехника///Душевые поддоны и комплектующие///Душевые поддоны",
    ("Душевые поддоны", "Экраны ST"): "Сантехника///Душевые поддоны и комплектующие///Комплектующие для душевых поддонов",
    ("Душевые поддоны", "Экраны CL"): "Сантехника///Душевые поддоны и комплектующие///Комплектующие для душевых поддонов",
    ("Душевые поддоны", "Экраны STYLE"): "Сантехника///Душевые поддоны и комплектующие///Комплектующие для душевых поддонов",
    ("Душевые поддоны", "Экраны Lux"): "Сантехника///Душевые поддоны и комплектующие///Комплектующие для душевых поддонов",
    ("Душевые поддоны", "Экраны GWS"): "Сантехника///Душевые поддоны и комплектующие///Комплектующие для душевых поддонов",
    ("Душевые поддоны", "Акрил квадрат / прямоуг"): "Сантехника///Душевые поддоны и комплектующие///Душевые поддоны",
    ("Душевые поддоны", "Акрил полукруг / трапеция"): "Сантехника///Душевые поддоны и комплектующие///Душевые поддоны",
    ("Душевые поддоны", "BC-L-S"): "Сантехника///Душевые поддоны и комплектующие///Душевые поддоны",
    ("Душевые поддоны", "Поддоны SMC"): "Сантехника///Душевые поддоны и комплектующие///Душевые поддоны",
    ("Душевые поддоны", "Керамические поддоны"): "Сантехника///Душевые поддоны и комплектующие///Душевые поддоны",
    ("Душевые поддоны", "Стелажи и ножки"): "Сантехника///Душевые поддоны и комплектующие///Комплектующие для душевых поддонов",
    ("Душевые системы", "Комплекты"): "Сантехника///Смесители///Смесители с гигиеническим душем",
    ("Душевые системы", "Верхние души"): "Сантехника///Душевая программа и комплектующие///Верхняя душевая лейка",
    ("Душевые системы", "Смесители"): "Сантехника///Душевая программа и комплектующие///Смесители скрытого монтажа",
    ("Душевые системы", "Душевые стойки"): "Сантехника///Душевая программа и комплектующие///Душевые системы",
    ("Душевые системы", "Комплектующие"): "Сантехника///Душевая программа и комплектующие///Прочее///Кронштейны",
    ("Аксессуары", "Коврики"): "Сантехника///Аксессуары для ванной комнаты///Коврики для ванной и туалета",
    ("Аксессуары", "Полки"): "Сантехника//Аксессуары для ванной комнаты//Полки",
    ("Унитазы и Биде", "Унитазы"): "Сантехника///Унитазы, биде, писсуары///Унитазы///Подвесные унитазы",
}


def is_raw_rgw_price(path: Path) -> bool:
    if is_flat_excel(path):
        return False
    try:
        xl = pd.ExcelFile(path)
    except Exception:  # noqa: BLE001
        return False
    markers = 0
    for sheet in xl.sheet_names:
        if sheet.lower() in SKIP_SHEETS:
            continue
        try:
            df = pd.read_excel(path, sheet_name=sheet, header=HEADER_ROW, nrows=2)
        except Exception:  # noqa: BLE001
            continue
        cols = {str(c).strip() for c in df.columns}
        if "ITP наименование сайт" in cols and "Артикул" in cols and "₽ РРЦ" in cols:
            markers += 1
    return markers >= 1


def resolve_path(sheet: str, subgroup: str | None) -> str:
    if subgroup:
        path = RGW_SUBGROUP_PATHS.get((sheet, subgroup))
        if path:
            return path
    return RGW_SHEET_DEFAULTS.get(sheet, "")


def parse_raw_rows(source: Path) -> tuple[list[dict[str, Any]], list[dict[str, Any]]]:
    rows: list[dict[str, Any]] = []
    errors: list[dict[str, Any]] = []
    seen: dict[str, int] = {}

    for sheet in pd.ExcelFile(source).sheet_names:
        if sheet.lower() in SKIP_SHEETS:
            continue
        df = pd.read_excel(source, sheet_name=sheet, header=HEADER_ROW)
        cols = strip_columns(df)
        acol = cols.get("Артикул")
        ncol = cols.get("ITP наименование сайт")
        pcol = cols.get("₽ РРЦ")
        scol = cols.get("Доступно")
        fcol = cols.get("Фото")
        if not acol or not pcol:
            continue

        subgroup: str | None = None
        product_name = ""
        for row_idx, row in df.iterrows():
            if fcol and pd.notna(row[fcol]) and str(row[fcol]).strip():
                subgroup = str(row[fcol]).strip()
            if ncol and pd.notna(row[ncol]) and str(row[ncol]).strip():
                product_name = str(row[ncol]).strip()

            article = normalize_article(row[acol])
            if not article:
                continue
            price = parse_price(row[pcol])
            if price is None:
                errors.append({"sheet": sheet, "row": int(row_idx) + HEADER_ROW + 2, "article": article, "message": "нет цены"})
                continue
            if article in seen:
                errors.append({"sheet": sheet, "row": int(row_idx) + HEADER_ROW + 2, "article": article, "message": f"дубликат (строка {seen[article]})"})
                continue
            seen[article] = int(row_idx) + HEADER_ROW + 2

            stock_raw = row[scol] if scol else ""
            in_stock, qty = normalize_stock(stock_raw)
            name = product_name or article
            path = resolve_path(sheet, subgroup)
            rows.append(
                {
                    "Артикул": article,
                    "Бренд": BRAND,
                    "Наименование": name,
                    "Цена, руб.": price,
                    "В наличии": in_stock,
                    "Количество": qty if qty is not None else 500,
                    "Путь": path,
                }
            )
    return rows, errors


def preprocess_raw_rgw(
    source_path: Path,
    output_dir: Path,
    reference_dir: Path | None = None,
) -> dict[str, Any]:
    output_dir.mkdir(parents=True, exist_ok=True)
    ref_columns, ref_cache = load_reference_by_article(reference_dir)
    source_rows, errors = parse_raw_rows(source_path)

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
                if col in {"Артикул", "Цена, руб.", "В наличии", "Количество"}:
                    continue
                if col in ref_row.index and pd.notna(ref_row[col]) and str(ref_row[col]).strip():
                    row[col] = ref_row[col]
            stats["from_reference"] += 1
        elif item.get("Путь"):
            stats["from_mapping"] += 1
        else:
            stats["without_path"] += 1
        output_rows.append(row)

    out_file = output_dir / "RGW_Price_flat.xlsx"
    write_flat_excel(output_rows, out_file, columns=[str(c) for c in ref_columns] if ref_columns else FLAT_COLUMNS)

    report = {
        "source": str(source_path),
        "output_dir": str(output_dir),
        "reference_dir": str(reference_dir) if reference_dir else None,
        "total_rows": len(output_rows),
        "errors": errors,
        "stats": stats,
        "output_files": [str(out_file)],
    }
    (output_dir / "rgw_raw_preprocess.json").write_text(
        json.dumps(report, ensure_ascii=False, indent=2),
        encoding="utf-8",
    )
    return report
