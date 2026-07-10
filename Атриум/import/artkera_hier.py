#!/usr/bin/env python3
"""
Иерархический прайс Арткеры → 6 плоских Excel по брендам (для convert.py).

Вызов из run_import.py, если в inbox один файл без колонки «Артикул».
Обогащение: опциональный кэш data/reference/artkera/ + фото с сайтов поставщиков.
"""

from __future__ import annotations

import json
import re
import time
from pathlib import Path
from typing import Any
from urllib.error import HTTPError, URLError
from urllib.request import Request, urlopen

import pandas as pd

IMPORT_DIR = Path(__file__).resolve().parent
DEFAULT_REFERENCE_DIR = IMPORT_DIR / "data" / "reference" / "artkera"

BRAND_MARKERS = [b["marker"] for b in []]  # заполняется ниже

BRANDS = [
    {
        "key": "ALMA_ceramica",
        "file": "ALMA_ceramica.xls",
        "marker": "ТОВАРНАЯ ПРОГРАММА ALMA CERAMICA",
        "brand_name": "Alma Ceramica",
        "engine": "xlwt",
        "sheet": "элементы - ПАРАМЕТРЫ",
    },
    {
        "key": "LCM",
        "file": "LCM.xlsx",
        "marker": "ТОВАРНАЯ ПРОГРАММА LCM",
        "brand_name": "LCM",
        "engine": "openpyxl",
        "sheet": "Лист1",
    },
    {
        "key": "NewTrend",
        "file": "NewTrend.xls",
        "marker": "ТОВАРНАЯ ПРОГРАММА NEWTREND",
        "brand_name": "NewTrend",
        "engine": "xlwt",
        "sheet": "элементы - ПАРАМЕТРЫ",
    },
    {
        "key": "AltaCera",
        "file": "AltaCera.xls",
        "marker": "ТОВАРНАЯ ПРОГРАММА ALTACERA",
        "brand_name": "AltaCera",
        "engine": "xlwt",
        "sheet": "элементы - ПАРАМЕТРЫ",
    },
    {
        "key": "Delacora",
        "file": "Delacora.xlsx",
        "marker": "ТОВАРНАЯ ПРОГРАММА DELACORA",
        "brand_name": "Delacora",
        "engine": "openpyxl",
        "sheet": "элементы - ПАРАМЕТРЫ",
    },
    {
        "key": "Арткера",
        "file": "Арткера.xls",
        "marker": "ТОВАРНАЯ ПРОГРАММА Artkera Group",
        "brand_name": "Artkera Group",
        "engine": "xlwt",
        "sheet": "TDSheet",
    },
]

BRAND_MARKERS = [b["marker"] for b in BRANDS]

ARTKERA_FACES = "https://artkera.ru/sites/default/files/faces/{art}/{art}_{n:02d}.jpg"

PHOTO_PATTERNS = {
    "AltaCera": ["https://altacera.ru/files/items/{art}.jpg"],
    "Alma Ceramica": [
        "https://www.almaceramica.ru/sites/default/files/2021-03/{art_lower}.jpg",
        "https://almaceramica.ru/sites/default/files/2021-03/{art_lower}.jpg",
    ],
    "Artkera Group": [ARTKERA_FACES],
    "Delacora": [ARTKERA_FACES],
    "LCM": [ARTKERA_FACES],
    "NewTrend": [ARTKERA_FACES],
}

DEFAULTS = {
    "В наличии": "Да",
    "Количество": 500,
    "Сорт": "1 Сорт",
    "Статус": "Y",
    "Единица измерения": "Штука",
    "Коэффициент единицы измерения": 1,
    "Коэфициент": 1,
}

FALLBACK_COLUMNS = [
    "Артикул",
    "Бренд",
    "Наименование",
    "Цена, руб.",
    "Единица измерения",
    "В наличии",
    "Количество",
    "Сорт",
    "Статус",
    "Фото",
]


def normalize_article(value: Any) -> str:
    if pd.isna(value):
        return ""
    return str(value).strip().rstrip(".")


def parse_price(value: Any) -> float | None:
    if pd.isna(value):
        return None
    s = str(value).replace("\u00a0", "").replace(" ", "").replace(",", ".")
    s = re.sub(r"[^\d.]", "", s)
    if not s:
        return None
    return round(float(s), 2)


def _is_image_response(resp: Any) -> bool:
    if resp.status != 200:
        return False
    content_type = resp.headers.get("Content-Type", "")
    return content_type.startswith("image/")


def url_exists(url: str, timeout: int = 8) -> bool:
    req = Request(url, method="HEAD", headers={"User-Agent": "ParsBot/1.0"})
    try:
        with urlopen(req, timeout=timeout) as resp:
            return _is_image_response(resp)
    except HTTPError as e:
        if e.code == 405:
            req = Request(url, headers={"User-Agent": "ParsBot/1.0"})
            try:
                with urlopen(req, timeout=timeout) as resp:
                    return _is_image_response(resp)
            except (HTTPError, URLError, TimeoutError):
                return False
        return False
    except (URLError, TimeoutError):
        return False


def discover_photos(brand_name: str, article: str) -> list[str]:
    patterns = PHOTO_PATTERNS.get(brand_name, [])
    found: list[str] = []
    art = article.strip()
    art_lower = art.lower()
    for pattern in patterns:
        if "{n:" in pattern:
            for n in range(1, 17):
                url = pattern.format(art=art, art_lower=art_lower, n=n)
                if url_exists(url):
                    if url not in found:
                        found.append(url)
                elif n > 1:
                    break
                time.sleep(0.05)
        else:
            url = pattern.format(art=art, art_lower=art_lower)
            if url_exists(url) and url not in found:
                found.append(url)
            time.sleep(0.05)
    return found


def is_hierarchical_price(path: Path) -> bool:
    """Файл без заголовка «Артикул», но с маркерами брендов в теле таблицы."""
    try:
        header_df = pd.read_excel(path, nrows=2)
        if any("Артикул" in str(c) for c in header_df.columns):
            return False
        raw = pd.read_excel(path, header=None, nrows=200)
        text = " ".join(str(v) for v in raw.values.flatten() if pd.notna(v)).upper()
        return any(marker.upper() in text for marker in BRAND_MARKERS)
    except Exception:  # noqa: BLE001
        return False


def parse_source_products(source: Path) -> tuple[dict[str, list[dict[str, Any]]], list[dict[str, Any]]]:
    df = pd.read_excel(source, header=None)
    markers = {b["marker"]: b["key"] for b in BRANDS}
    marker_rows: list[tuple[int, str]] = []
    for i, row in df.iterrows():
        col0 = str(row[0]).strip() if pd.notna(row[0]) else ""
        for marker, key in markers.items():
            if marker.upper() in col0.upper():
                marker_rows.append((i, key))
                break

    marker_rows.sort()
    result: dict[str, list[dict[str, Any]]] = {b["key"]: [] for b in BRANDS}
    errors: list[dict[str, Any]] = []
    seen_articles: dict[str, tuple[str, int]] = {}

    for idx, (start_row, brand_key) in enumerate(marker_rows):
        end_row = marker_rows[idx + 1][0] if idx + 1 < len(marker_rows) else len(df)
        for i in range(start_row, end_row):
            row = df.iloc[i]
            article = normalize_article(row[1])
            if not article or not re.match(r"^[A-Z0-9]", article):
                continue
            price = parse_price(row[2])
            if price is None:
                continue
            if article in seen_articles:
                prev_brand, prev_row = seen_articles[article]
                errors.append(
                    {
                        "row": int(i),
                        "article": article,
                        "message": f"дубликат артикула (уже встречался: {prev_brand}, строка {prev_row})",
                    }
                )
                continue
            seen_articles[article] = (brand_key, int(i))
            name = str(row[0]).strip() if pd.notna(row[0]) else article
            unit = str(row[3]).strip() if pd.notna(row[3]) else "шт"
            result[brand_key].append(
                {
                    "Артикул": article,
                    "Наименование": name,
                    "Цена, руб.": price,
                    "Единица измерения": "Штука" if unit == "шт" else unit,
                }
            )
    return result, errors


def load_reference_cache(brand: dict, reference_dir: Path | None) -> tuple[list[str], dict[str, pd.Series]]:
    ref_path = (reference_dir / brand["file"]) if reference_dir else None
    if ref_path and ref_path.exists():
        df = pd.read_excel(ref_path)
        columns = list(df.columns)
        cache: dict[str, pd.Series] = {}
        for _, row in df.iterrows():
            art = normalize_article(row.get("Артикул"))
            if art:
                cache[art] = row
        return columns, cache
    return FALLBACK_COLUMNS.copy(), {}


def price_column_name(columns: list[str]) -> str:
    for c in columns:
        if "Цена" in str(c):
            return c
    return "Цена, руб."


def brand_column_name(columns: list[str]) -> str | None:
    for c in columns:
        if str(c).strip() == "Бренд":
            return c
    return None


def build_row_from_source(
    columns: list[str],
    brand: dict,
    source_item: dict[str, Any],
    photos: list[str],
) -> dict[str, Any]:
    row = {col: "" for col in columns}
    bcol = brand_column_name(columns)
    if bcol:
        row[bcol] = brand["brand_name"]
    row["Артикул"] = source_item["Артикул"]
    row[price_column_name(columns)] = source_item["Цена, руб."]
    if "Наименование" in columns:
        row["Наименование"] = source_item["Наименование"]
    if "Единица измерения" in columns:
        row["Единица измерения"] = source_item.get("Единица измерения", "Штука")
    for key, val in DEFAULTS.items():
        if key in columns and row.get(key, "") == "":
            row[key] = val
    for i, url in enumerate(photos[:16], start=1):
        col = "Фото" if i == 1 else f"Фото{i}"
        if col in columns:
            row[col] = url
    return row


def merge_reference_row(
    columns: list[str],
    brand: dict,
    source_item: dict[str, Any],
    ref_row: pd.Series,
) -> dict[str, Any]:
    row = {col: ref_row[col] if col in ref_row.index and pd.notna(ref_row[col]) else "" for col in columns}
    bcol = brand_column_name(columns)
    if bcol:
        row[bcol] = brand["brand_name"]
    row["Артикул"] = source_item["Артикул"]
    row[price_column_name(columns)] = source_item["Цена, руб."]
    return row


def enrich_brand(
    brand: dict,
    source_items: list[dict[str, Any]],
    reference_dir: Path | None,
    with_photos: bool,
    report: dict,
) -> pd.DataFrame:
    columns, cache = load_reference_cache(brand, reference_dir)
    rows: list[dict[str, Any]] = []
    stats = {"from_reference": 0, "from_web": 0, "photos_found": 0}

    for item in source_items:
        art = item["Артикул"]
        if art in cache:
            row = merge_reference_row(columns, brand, item, cache[art])
            stats["from_reference"] += 1
        else:
            photos = discover_photos(brand["brand_name"], art) if with_photos else []
            if photos:
                stats["photos_found"] += 1
            row = build_row_from_source(columns, brand, item, photos)
            stats["from_web"] += 1
        rows.append(row)

    report[brand["key"]] = {
        "source_count": len(source_items),
        "output_count": len(rows),
        **stats,
    }
    return pd.DataFrame(rows, columns=columns)


def write_excel(df: pd.DataFrame, out_path: Path, engine: str, sheet_name: str) -> None:
    if engine == "xlwt":
        import xlwt

        wb = xlwt.Workbook()
        ws = wb.add_sheet(sheet_name)
        for col_idx, col_name in enumerate(df.columns):
            ws.write(0, col_idx, str(col_name))
        for row_idx, row in enumerate(df.itertuples(index=False), start=1):
            for col_idx, value in enumerate(row):
                if pd.isna(value) or value == "":
                    continue
                if isinstance(value, (int, float)) and not isinstance(value, bool):
                    ws.write(row_idx, col_idx, value)
                else:
                    ws.write(row_idx, col_idx, str(value))
        wb.save(str(out_path))
        return
    with pd.ExcelWriter(out_path, engine=engine) as writer:
        df.to_excel(writer, index=False, sheet_name=sheet_name)


def preprocess_hierarchical(
    source_path: Path,
    output_dir: Path,
    reference_dir: Path | None = None,
    with_photos: bool = True,
) -> dict[str, Any]:
    """
    Иерархический прайс → 6 файлов по брендам в output_dir.
    Возвращает отчёт для run_import.py.
    """
    output_dir.mkdir(parents=True, exist_ok=True)
    ref_dir = reference_dir if reference_dir and reference_dir.is_dir() else None

    source_by_brand, source_errors = parse_source_products(source_path)
    report: dict[str, Any] = {
        "source": str(source_path),
        "output_dir": str(output_dir),
        "reference_dir": str(ref_dir) if ref_dir else None,
        "with_photos": with_photos,
        "price_field": "РРЦ → Цена, руб.",
        "errors": source_errors,
        "brands": {},
        "output_files": [],
    }

    total = 0
    for brand in BRANDS:
        items = source_by_brand[brand["key"]]
        df = enrich_brand(brand, items, ref_dir, with_photos, report["brands"])
        out_path = output_dir / brand["file"]
        write_excel(df, out_path, brand["engine"], brand["sheet"])
        report["output_files"].append(str(out_path))
        total += len(df)

    report["total_rows"] = total
    report_path = output_dir / "hier_preprocess.json"
    report_path.write_text(json.dumps(report, ensure_ascii=False, indent=2), encoding="utf-8")
    return report
