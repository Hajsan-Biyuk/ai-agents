#!/usr/bin/env python3
"""
Парсинг прайса Artkera → parsing/ (6 файлов по брендам).
Цена: РРЦ → «Цена, руб.» (как в «с обработкой»).
Обогащение: кэш «с обработкой» + фото с сайтов поставщиков.
"""

from __future__ import annotations

import json
import re
import time
from pathlib import Path
from typing import Any
from urllib.request import Request, urlopen
from urllib.error import HTTPError, URLError

import pandas as pd

BASE = Path(__file__).resolve().parent.parent
SOURCE = BASE / "Исходник" / "Прайс-лист с 01.01.2026.xls"
REFERENCE_DIR = BASE / "с обработкой"
OUTPUT_DIR = BASE / "parsing"
REPORT_PATH = OUTPUT_DIR / "report.json"

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

# Паттерны с {n:02d} сканируются внутренним циклом в discover_photos (не разворачивать в список!)
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
                else:
                    if n > 1:
                        break
                time.sleep(0.05)
        else:
            url = pattern.format(art=art, art_lower=art_lower)
            if url_exists(url) and url not in found:
                found.append(url)
            time.sleep(0.05)
    return found


def parse_source_products() -> tuple[dict[str, list[dict[str, Any]]], list[dict[str, Any]]]:
    df = pd.read_excel(SOURCE, header=None)
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
                        "row": i,
                        "article": article,
                        "message": f"дубликат артикула (уже встречался: {prev_brand}, строка {prev_row})",
                    }
                )
                continue
            seen_articles[article] = (brand_key, i)
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


def load_reference_cache(brand: dict) -> tuple[list[str], dict[str, pd.Series]]:
    ref_path = REFERENCE_DIR / brand["file"]
    df = pd.read_excel(ref_path)
    columns = list(df.columns)
    cache: dict[str, pd.Series] = {}
    for _, row in df.iterrows():
        art = normalize_article(row.get("Артикул"))
        if art:
            cache[art] = row
    return columns, cache


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


def enrich_brand(brand: dict, source_items: list[dict[str, Any]], report: dict) -> pd.DataFrame:
    columns, cache = load_reference_cache(brand)
    rows: list[dict[str, Any]] = []
    stats = {"from_reference": 0, "from_web": 0, "photos_found": 0}

    for item in source_items:
        art = item["Артикул"]
        if art in cache:
            row = merge_reference_row(columns, brand, item, cache[art])
            stats["from_reference"] += 1
        else:
            photos = discover_photos(brand["brand_name"], art)
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


def main() -> None:
    OUTPUT_DIR.mkdir(parents=True, exist_ok=True)
    source_by_brand, source_errors = parse_source_products()
    report: dict[str, Any] = {
        "source": str(SOURCE),
        "output_dir": str(OUTPUT_DIR),
        "price_field": "РРЦ → Цена, руб.",
        "errors": source_errors,
        "brands": {},
    }
    if source_errors:
        print(f"ВНИМАНИЕ: ошибок в источнике: {len(source_errors)} (см. report.json)")

    for brand in BRANDS:
        items = source_by_brand[brand["key"]]
        df = enrich_brand(brand, items, report["brands"])
        out_path = OUTPUT_DIR / brand["file"]
        write_excel(df, out_path, brand["engine"], brand["sheet"])
        print(f"{brand['file']}: {len(df)} rows → {out_path}")

    report["total_rows"] = sum(report["brands"][b["key"]]["output_count"] for b in BRANDS)
    REPORT_PATH.write_text(json.dumps(report, ensure_ascii=False, indent=2), encoding="utf-8")
    print(f"\nReport: {REPORT_PATH}")


if __name__ == "__main__":
    main()
