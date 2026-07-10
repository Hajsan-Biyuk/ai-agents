#!/usr/bin/env python3
"""
Конвертер файлов поставщика → канонический JSON (Атриум).

Использование:
    python3 convert.py --supplier artkera [--out data/normalized/]

Читает профиль suppliers/{key}/profile.yaml + categories.yaml,
разбирает Excel-файлы по синонимам колонок и пишет канонический JSON
+ отчёт по ошибкам (дубликаты, неизвестные категории, пустые поля).
"""

from __future__ import annotations

import argparse
import fnmatch
import json
import re
import sys
from datetime import datetime, timezone
from pathlib import Path
from typing import Any

import pandas as pd
import yaml

IMPORT_DIR = Path(__file__).resolve().parent
ATRIUM_DIR = IMPORT_DIR.parent

PHOTO_RE = re.compile(r"^Фото(\d*)( \(.*\))?$")


def load_profile(supplier: str) -> tuple[dict, dict]:
    sup_dir = IMPORT_DIR / "suppliers" / supplier
    profile = yaml.safe_load((sup_dir / "profile.yaml").read_text(encoding="utf-8"))
    categories_path = sup_dir / "categories.yaml"
    categories = {}
    if categories_path.exists():
        categories = yaml.safe_load(categories_path.read_text(encoding="utf-8")) or {}
    return profile, categories


def build_column_map(columns: list[str], synonyms: dict[str, list[str]]) -> dict[str, str]:
    """колонка файла → поле канонической модели (по точному совпадению из синонимов)."""
    mapping: dict[str, str] = {}
    stripped_cols = {str(col).strip(): str(col) for col in columns}
    for field, names in synonyms.items():
        for name in names:
            key = str(name).strip()
            if key in stripped_cols:
                mapping[stripped_cols[key]] = field
                break
    return mapping


def normalize_path(raw: str, profile: dict) -> str:
    if not raw:
        return ""
    norm = profile.get("normalize", {})
    sep = norm.get("path_separator", "///")
    path = raw.strip()
    if norm.get("path_strip_catalog"):
        path = path.replace("Каталог///", "")
    if norm.get("path_fix_double_slash"):
        path = re.sub(r"/{2,}", sep, path.replace("//", sep))
    return path


def parse_in_stock(value: str, normalize_stock: dict) -> bool:
    if value in normalize_stock:
        return bool(normalize_stock[value])
    if value.isdigit():
        return int(value) > 0
    return bool(value)


def parse_price(value: Any) -> float | None:
    if value is None or (isinstance(value, float) and pd.isna(value)):
        return None
    s = str(value).replace("\u00a0", "").replace(" ", "").replace(",", ".")
    s = re.sub(r"[^\d.]", "", s)
    if not s:
        return None
    try:
        return round(float(s), 2)
    except ValueError:
        return None


def cell(value: Any) -> str:
    if value is None or (isinstance(value, float) and pd.isna(value)):
        return ""
    s = str(value).strip()
    return "" if s.lower() == "nan" else s


# «(9 шт в уп/64,8 м в пал)», «(13 шт. в коробке)», незакрытая «(…» в конце
PACK_RE = re.compile(r"\s*\((?:[^)]*(?:шт|уп\b|пал|коробк)[^)]*)\)\s*$|\s*\([^)]*$", re.IGNORECASE)


def clean_name(name: str) -> str:
    return re.sub(r"\s{2,}", " ", PACK_RE.sub("", name)).strip()


def convert_file(
    path: Path,
    profile: dict,
    categories: dict,
    errors: list[dict],
    unknown_categories: dict[str, int],
    unknown_columns: set[str],
    sheet_name: str | int | None = None,
) -> list[dict[str, Any]]:
    df = pd.read_excel(path, sheet_name=0 if sheet_name is None else sheet_name)
    columns = [str(c) for c in df.columns]
    col_map = build_column_map(columns, profile["synonyms"])
    ignore = set(profile.get("ignore", []))
    normalize_stock = profile.get("normalize", {}).get("in_stock", {})
    photo_limit = int(profile.get("photo_limit", 16))

    photo_cols: list[str] = []
    attr_cols: list[str] = []
    for col in columns:
        if col in col_map or col.strip() in ignore or col.startswith("Unnamed"):
            continue
        if PHOTO_RE.match(col.strip()):
            photo_cols.append(col)
        else:
            attr_cols.append(col)

    # Фото / Фото2..N — в порядке номера
    def photo_order(c: str) -> int:
        m = PHOTO_RE.match(c.strip())
        return int(m.group(1)) if m and m.group(1) else 1

    base_photo = next((c for c, f in col_map.items() if f == "photo"), None)
    if base_photo:
        photo_cols.insert(0, base_photo)
    photo_cols.sort(key=photo_order)

    for col in attr_cols:
        unknown_columns.discard(col)  # известные текущему файлу
    products: list[dict[str, Any]] = []

    for row_idx, row in df.iterrows():
        get = lambda field: next(
            (cell(row[c]) for c, f in col_map.items() if f == field), ""
        )
        article = get("article").rstrip(".")
        if not article:
            errors.append({"file": path.name, "row": int(row_idx) + 2, "message": "пустой артикул"})
            continue

        price = parse_price(get("price_retail"))
        if price is None:
            errors.append({"file": path.name, "row": int(row_idx) + 2, "article": article, "message": "нет цены"})
            continue

        sections: list[list[str]] = []
        raw_paths = [normalize_path(p, profile) for p in (get("path"), get("path2")) if p]
        for raw in raw_paths:
            if raw in categories:
                sec = categories[raw]
                if sec not in sections:
                    sections.append(sec)
            else:
                unknown_categories[raw] = unknown_categories.get(raw, 0) + 1
        if not sections and profile.get("default_section"):
            sections.append(profile["default_section"])

        stock_raw = get("in_stock")
        in_stock = parse_in_stock(stock_raw, normalize_stock) if normalize_stock else bool(stock_raw)

        photos = []
        photo_split = profile.get("normalize", {}).get("photo_split")
        for c in photo_cols:
            url = cell(row[c])
            if not url:
                continue
            parts = [url]
            if photo_split and photo_split in url:
                parts = [p.strip() for p in url.split(photo_split) if p.strip().startswith("http")]
            for part in parts:
                if part.startswith("http") and part not in photos:
                    photos.append(part)
            if len(photos) >= photo_limit:
                break

        attributes = {}
        for c in attr_cols:
            v = cell(row[c])
            if v:
                attributes[c.strip()] = v

        qty = get("quantity")
        brand = get("brand") or profile.get("defaults", {}).get("brand") or profile.get("brand_default", "")
        if profile.get("normalize", {}).get("brand_trim"):
            brand = brand.strip()
        products.append(
            {
                "article": article,
                "supplier": profile["key"],
                "brand": brand,
                "name": clean_name(get("name")) or article,
                "price_retail": price,
                "price_opt": None,
                "unit": get("unit") or "Штука",
                "in_stock": in_stock,
                "quantity": int(qty) if qty.isdigit() else profile.get("defaults", {}).get("quantity", 0),
                "sections": sections,
                "sections_raw": raw_paths,
                "collection": get("collection"),
                "description": get("description"),
                "photos": photos,
                "attributes": attributes,
                "meta": {"source_file": path.name},
            }
        )
    return products


def iter_workbook_sheets(path: Path, profile: dict) -> list[str | int]:
    skip = {s.lower() for s in profile.get("skip_sheets", ["meta", "оглавление"])}
    if profile.get("reader") == "flat_excel" and path.suffix.lower() in (".xls", ".xlsx"):
        xl = pd.ExcelFile(path)
        sheets = [s for s in xl.sheet_names if s.lower() not in skip]
        return sheets or [0]
    return [0]


def convert_workbook(
    path: Path,
    profile: dict,
    categories: dict,
    errors: list[dict],
    unknown_categories: dict[str, int],
    unknown_columns: set[str],
) -> list[dict[str, Any]]:
    products: list[dict[str, Any]] = []
    for sheet in iter_workbook_sheets(path, profile):
        products.extend(
            convert_file(path, profile, categories, errors, unknown_categories, unknown_columns, sheet_name=sheet)
        )
    return products


def collect_files(profile: dict, files_dir: str | None) -> list[Path]:
    """Файлы поставщика: из указанной папки (inbox) или по маске профиля."""
    if files_dir:
        d = Path(files_dir)
        return sorted(p for p in d.iterdir() if p.suffix.lower() in (".xls", ".xlsx"))
    pattern = profile.get("files", "")
    if not pattern:
        return []
    return sorted(
        p for p in ATRIUM_DIR.rglob("*")
        if p.is_file() and fnmatch.fnmatch(str(p.relative_to(ATRIUM_DIR)), pattern)
    )


def convert_supplier(supplier: str, files_dir: str | None = None, out_dir: str | None = None) -> dict:
    """Программный вход для run_import.py. Возвращает отчёт конвертации."""
    profile, categories = load_profile(supplier)
    files = collect_files(profile, files_dir)
    if not files:
        raise FileNotFoundError(f"Файлы поставщика «{supplier}» не найдены")

    errors: list[dict] = []
    unknown_categories: dict[str, int] = {}
    unknown_columns: set[str] = set()
    all_products: list[dict] = []
    seen: dict[str, str] = {}

    for f in files:
        products = convert_workbook(f, profile, categories, errors, unknown_categories, unknown_columns)
        for p in products:
            if p["article"] in seen:
                errors.append({
                    "file": f.name,
                    "article": p["article"],
                    "message": f"дубликат артикула (уже в {seen[p['article']]})",
                })
                continue
            seen[p["article"]] = f.name
            all_products.append(p)

    out = Path(out_dir) if out_dir else IMPORT_DIR / "data" / "normalized"
    out.mkdir(parents=True, exist_ok=True)
    stamp = datetime.now(timezone.utc).astimezone().strftime("%Y-%m-%d_%H%M")
    out_path = out / f"{supplier}_{stamp}.json"
    payload = {
        "meta": {
            "supplier": supplier,
            "generated_at": datetime.now(timezone.utc).isoformat(),
            "source_files": [f.name for f in files],
            "total": len(all_products),
        },
        "products": all_products,
    }
    out_path.write_text(json.dumps(payload, ensure_ascii=False, indent=1), encoding="utf-8")

    return {
        "supplier": supplier,
        "profile": profile,
        "files": [str(f) for f in files],
        "total": len(all_products),
        "errors": errors,
        "unknown_categories": unknown_categories,
        "json_path": str(out_path),
        "products": all_products,
    }


def main() -> None:
    ap = argparse.ArgumentParser()
    ap.add_argument("--supplier", required=True)
    ap.add_argument("--files-dir", default=None, help="папка с файлами (напр. inbox/artkera)")
    ap.add_argument("--out", default=str(IMPORT_DIR / "data" / "normalized"))
    args = ap.parse_args()

    profile, categories = load_profile(args.supplier)
    files = collect_files(profile, args.files_dir)
    if not files:
        print("Файлы не найдены")
        sys.exit(1)

    errors: list[dict] = []
    unknown_categories: dict[str, int] = {}
    unknown_columns: set[str] = set()
    all_products: list[dict] = []
    seen: dict[str, str] = {}

    for f in files:
        products = convert_workbook(f, profile, categories, errors, unknown_categories, unknown_columns)
        for p in products:
            if p["article"] in seen:
                errors.append(
                    {
                        "file": f.name,
                        "article": p["article"],
                        "message": f"дубликат артикула (уже в {seen[p['article']]})",
                    }
                )
                continue
            seen[p["article"]] = f.name
            all_products.append(p)
        print(f"{f.name}: {len(products)} товаров")

    out_dir = Path(args.out)
    out_dir.mkdir(parents=True, exist_ok=True)
    stamp = datetime.now(timezone.utc).astimezone().strftime("%Y-%m-%d")
    out_path = out_dir / f"{args.supplier}_{stamp}.json"

    payload = {
        "meta": {
            "supplier": args.supplier,
            "generated_at": datetime.now(timezone.utc).isoformat(),
            "source_files": [f.name for f in files],
            "total": len(all_products),
        },
        "products": all_products,
    }
    out_path.write_text(json.dumps(payload, ensure_ascii=False, indent=1), encoding="utf-8")

    report = {
        "supplier": args.supplier,
        "total": len(all_products),
        "errors": errors,
        "unknown_categories": unknown_categories,
        "output": str(out_path),
    }
    report_path = out_dir.parent / "reports" / f"{args.supplier}_{stamp}_convert.json"
    report_path.parent.mkdir(parents=True, exist_ok=True)
    report_path.write_text(json.dumps(report, ensure_ascii=False, indent=2), encoding="utf-8")

    print(f"\nИтого товаров: {len(all_products)}")
    print(f"Ошибок: {len(errors)}")
    if unknown_categories:
        print("НЕИЗВЕСТНЫЕ КАТЕГОРИИ (дополните categories.yaml):")
        for k, v in unknown_categories.items():
            print(f"  {k} — {v} товаров")
    print(f"JSON: {out_path}")
    print(f"Отчёт: {report_path}")


if __name__ == "__main__":
    main()
