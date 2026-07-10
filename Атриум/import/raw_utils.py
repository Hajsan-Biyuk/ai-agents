#!/usr/bin/env python3
"""Общие утилиты для препроцессинга сырых прайсов."""

from __future__ import annotations

import re
from pathlib import Path
from typing import Any

import pandas as pd

EXCEL_SUFFIXES = (".xls", ".xlsx")

FLAT_COLUMNS = [
    "Артикул",
    "Бренд",
    "Наименование",
    "Цена, руб.",
    "В наличии",
    "Количество",
    "Путь",
    "Фото",
    "Фото2",
    "Фото3",
    "Фото4",
    "Фото5",
    "Фото6",
    "Фото7",
    "Фото8",
    "Фото9",
    "Фото10",
]


def normalize_article(value: Any) -> str:
    if pd.isna(value):
        return ""
    return str(value).strip().rstrip(".")


def strip_columns(df: pd.DataFrame) -> dict[str, Any]:
    return {str(c).strip(): c for c in df.columns}


def parse_price(value: Any) -> float | None:
    if pd.isna(value):
        return None
    s = str(value).replace("\u00a0", "").replace(" ", "").replace(",", ".")
    s = re.sub(r"[^\d.]", "", s)
    if not s:
        return None
    try:
        return round(float(s), 2)
    except ValueError:
        return None


def normalize_stock(value: Any) -> tuple[str, int | None]:
    if pd.isna(value):
        return "Да", None
    s = str(value).strip()
    if not s or s.lower() == "nan":
        return "Да", None
    if s.lower() == "много":
        return "Много", 500
    if s.startswith(">"):
        return "Да", 500
    if s.isdigit():
        qty = int(s)
        return ("Да" if qty > 0 else "Нет"), qty
    return s, None


def is_category_header(value: str) -> bool:
    if not value or len(value) <= 2:
        return False
    compact = value.replace(".", "")
    return not compact.isdigit()


def workbook_has_path_column(path: Path) -> bool:
    try:
        xl = pd.ExcelFile(path)
    except Exception:  # noqa: BLE001
        return False
    for sheet in xl.sheet_names:
        try:
            df = pd.read_excel(path, sheet_name=sheet, nrows=2)
        except Exception:  # noqa: BLE001
            continue
        if any(str(c).strip() == "Путь" for c in df.columns):
            return True
    return False


def is_flat_excel(path: Path) -> bool:
    return workbook_has_path_column(path)


def load_reference_by_article(reference_dir: Path | None) -> tuple[list[str], dict[str, pd.Series]]:
    if not reference_dir or not reference_dir.is_dir():
        return FLAT_COLUMNS.copy(), {}

    columns: list[str] | None = None
    cache: dict[str, pd.Series] = {}
    for file_path in sorted(reference_dir.rglob("*")):
        if file_path.suffix.lower() not in EXCEL_SUFFIXES:
            continue
        try:
            xl = pd.ExcelFile(file_path)
        except Exception:  # noqa: BLE001
            continue
        for sheet in xl.sheet_names:
            try:
                df = pd.read_excel(file_path, sheet_name=sheet)
            except Exception:  # noqa: BLE001
                continue
            cols = strip_columns(df)
            acol = cols.get("Артикул")
            if not acol:
                continue
            if columns is None:
                columns = list(df.columns)
            for _, row in df.iterrows():
                art = normalize_article(row[acol])
                if art:
                    cache[art] = row
    return (columns if columns else FLAT_COLUMNS.copy()), cache


def price_column_name(columns: list[str]) -> str:
    for col in columns:
        name = str(col).strip()
        if "Цена" in name and "руб" in name:
            return str(col)
    return "Цена, руб."


def source_price(source: dict[str, Any]) -> Any:
    return source.get("Цена, руб.") or source.get("Цена, руб")


def merge_reference_row(
    columns: list[str],
    source: dict[str, Any],
    ref_row: pd.Series | None,
) -> dict[str, Any]:
    row = {str(col): "" for col in columns}
    if ref_row is not None:
        for col in columns:
            val = ref_row[col] if col in ref_row.index else ""
            if pd.notna(val) and str(val).strip() and str(val).lower() != "nan":
                row[str(col)] = val

    price_col = price_column_name(columns)
    price_val = source_price(source)
    if price_val not in ("", None):
        row[price_col] = price_val

    for key, val in source.items():
        if val in ("", None):
            continue
        if key in {"Цена, руб.", "Цена, руб"}:
            continue
        col_key = str(key)
        if col_key in row:
            row[col_key] = val

    for field in ("В наличии", "Количество", "Бренд", "Наименование", "Путь"):
        if field in source and source[field] not in ("", None):
            col = next((str(c) for c in columns if str(c).strip() == field), field)
            if col in row:
                row[col] = source[field]

    art_col = next((str(c) for c in columns if str(c).strip() == "Артикул"), "Артикул")
    row[art_col] = source.get("Артикул", row.get(art_col, ""))
    return row


def build_flat_row(source: dict[str, Any]) -> dict[str, Any]:
    row = {col: "" for col in FLAT_COLUMNS}
    for key, val in source.items():
        if key in row and val not in ("", None):
            row[key] = val
    return row


def write_flat_excel(rows: list[dict[str, Any]], out_path: Path, columns: list[str] | None = None) -> None:
    cols = columns or FLAT_COLUMNS
    df = pd.DataFrame(rows, columns=cols)
    with pd.ExcelWriter(out_path, engine="openpyxl") as writer:
        df.to_excel(writer, index=False, sheet_name="Лист1")
