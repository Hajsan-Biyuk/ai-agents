#!/usr/bin/env python3
"""Сжатие PNG/JPG/JPEG до <=500KB без изменения размеров в пикселях."""

from __future__ import annotations

import io
import os
import shutil
import subprocess
import sys
import tempfile
from pathlib import Path

from PIL import Image

MAX_BYTES = 500 * 1024
ROOT = Path(__file__).resolve().parents[1]
PNGQUANT = shutil.which("pngquant")
SKIP_DIRS = {"_compress_test", ".git", "node_modules", "scripts"}


def has_real_alpha(img: Image.Image) -> bool:
    if "A" not in img.getbands():
        return False
    lo, _hi = img.getchannel("A").getextrema()
    return lo < 255


def jpeg_bytes(img: Image.Image, quality: int, subsampling: int) -> bytes:
    buf = io.BytesIO()
    img.convert("RGB").save(
        buf,
        format="JPEG",
        quality=quality,
        optimize=True,
        progressive=True,
        subsampling=subsampling,
    )
    return buf.getvalue()


def best_jpeg_payload(img: Image.Image, max_bytes: int) -> tuple[int, int, bytes]:
    rgb = img.convert("RGB")

    for subsampling in (2, 1, 0):
        lo, hi = 12, 95
        best_q = None
        best_data = None

        while lo <= hi:
            mid = (lo + hi) // 2
            data = jpeg_bytes(rgb, mid, subsampling)
            if len(data) <= max_bytes:
                best_q = mid
                best_data = data
                lo = mid + 1
            else:
                hi = mid - 1

        if best_data is not None:
            return best_q, subsampling, best_data

    raise RuntimeError("Не удалось уложиться в лимит без изменения размеров")


def pngquant_payload(src: Path, max_bytes: int) -> bytes | None:
    if not PNGQUANT:
        return None

    for min_q in (90, 85, 80, 75, 70, 65, 60):
        with tempfile.NamedTemporaryFile(suffix=".png", delete=False) as tmp:
            out = Path(tmp.name)

        try:
            cmd = [
                PNGQUANT,
                f"--quality={min_q}-100",
                "--speed",
                "1",
                "--force",
                "--output",
                str(out),
                str(src),
            ]
            subprocess.run(cmd, check=True, capture_output=True)
            data = out.read_bytes()
            if len(data) <= max_bytes:
                return data
        except subprocess.CalledProcessError:
            pass
        finally:
            if out.exists():
                out.unlink()

    return None


def compress_image(path: Path) -> dict:
    before = path.stat().st_size
    quality_used = None

    with Image.open(path) as img:
        orig_size = img.size

        if has_real_alpha(img):
            payload = pngquant_payload(path, MAX_BYTES)
            if payload is None:
                quality_used, _subsampling, payload = best_jpeg_payload(img, MAX_BYTES)
        else:
            quality_used, _subsampling, payload = best_jpeg_payload(img, MAX_BYTES)

        tmp = path.with_suffix(path.suffix + ".tmp")
        tmp.write_bytes(payload)
        with Image.open(tmp) as check:
            if check.size != orig_size:
                tmp.unlink(missing_ok=True)
                raise RuntimeError(f"{path}: размер изменился {orig_size} -> {check.size}")

        tmp.replace(path)

    after = path.stat().st_size
    result = {
        "path": str(path.relative_to(ROOT)),
        "before_kb": before // 1024,
        "after_kb": after // 1024,
        "dims": f"{orig_size[0]}x{orig_size[1]}",
    }
    if quality_used is not None:
        result["quality"] = quality_used
    return result


def iter_targets() -> list[Path]:
    targets: dict[str, Path] = {}

    for dirpath, dirnames, filenames in os.walk(ROOT):
        dirnames[:] = [d for d in dirnames if d not in SKIP_DIRS]
        for name in filenames:
            if not name.lower().endswith((".png", ".jpg", ".jpeg")):
                continue
            path = Path(dirpath) / name
            if path.stat().st_size <= MAX_BYTES:
                continue
            rel = path.relative_to(ROOT).as_posix()
            # Приоритет: img/ > корень > maintheme/img/
            priority = 0
            if rel.startswith("img/"):
                priority = 3
            elif "/" not in rel:
                priority = 2
            elif rel.startswith("maintheme/img/"):
                priority = 1
            key = rel.replace("maintheme/", "", 1) if rel.startswith("maintheme/img/") else rel
            prev = targets.get(key)
            if prev is None or priority > prev[0]:
                targets[key] = (priority, path)

    return [item[1] for item in targets.values()]


def sync_to_maintheme(source: Path) -> None:
    rel = source.relative_to(ROOT)
    if rel.parts[:1] != ("img",):
        return
    dest = ROOT / "maintheme" / "img" / rel.name
    if dest.exists():
        shutil.copy2(source, dest)


def main() -> int:
    results = []
    errors = []

    for path in sorted(iter_targets()):
        try:
            result = compress_image(path)
            sync_to_maintheme(path)
            results.append(result)
            print(
                f"OK {result['path']}: {result['before_kb']}KB -> {result['after_kb']}KB ({result['dims']}, q={result.get('quality', '?')})"
            )
        except Exception as exc:  # noqa: BLE001
            errors.append((str(path.relative_to(ROOT)), str(exc)))
            print(f"ERR {path.relative_to(ROOT)}: {exc}", file=sys.stderr)

    print(f"\nСжато: {len(results)}, ошибок: {len(errors)}")
    return 1 if errors else 0


if __name__ == "__main__":
    raise SystemExit(main())
