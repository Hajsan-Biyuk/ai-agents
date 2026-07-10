#!/usr/bin/env python3
"""Снимок inbox и сравнение с последним запуском (для cron)."""

from __future__ import annotations

import hashlib
import json
from datetime import datetime
from pathlib import Path

EXCEL_SUFFIXES = (".xls", ".xlsx")


def list_inbox_excel(inbox_dir: Path) -> list[Path]:
    if not inbox_dir.is_dir():
        return []
    return sorted(p for p in inbox_dir.iterdir() if p.suffix.lower() in EXCEL_SUFFIXES)


def _file_entry(path: Path) -> dict:
    data = path.read_bytes()
    stat = path.stat()
    return {
        "name": path.name,
        "sha256": hashlib.sha256(data).hexdigest(),
        "size": stat.st_size,
        "mtime": int(stat.st_mtime),
    }


def inbox_snapshot(inbox_dir: Path) -> dict:
    entries = [_file_entry(path) for path in list_inbox_excel(inbox_dir)]
    payload = json.dumps(entries, ensure_ascii=False, sort_keys=True).encode()
    return {
        "fingerprint": hashlib.sha256(payload).hexdigest(),
        "files": entries,
        "captured_at": datetime.now().isoformat(),
    }


def state_path(state_dir: Path, supplier: str) -> Path:
    return state_dir / f"{supplier}.json"


def load_state(state_dir: Path, supplier: str) -> dict | None:
    path = state_path(state_dir, supplier)
    if not path.exists():
        return None
    try:
        return json.loads(path.read_text(encoding="utf-8"))
    except (json.JSONDecodeError, OSError):
        return None


def save_state(
    state_dir: Path,
    supplier: str,
    snapshot: dict,
    status: str,
    report_file: str | None = None,
) -> None:
    state_dir.mkdir(parents=True, exist_ok=True)
    payload = {
        "supplier": supplier,
        "fingerprint": snapshot["fingerprint"],
        "files": snapshot["files"],
        "captured_at": snapshot["captured_at"],
        "last_run_at": datetime.now().isoformat(),
        "last_status": status,
        "last_report": report_file,
    }
    state_path(state_dir, supplier).write_text(
        json.dumps(payload, ensure_ascii=False, indent=2),
        encoding="utf-8",
    )


def clear_state(state_dir: Path, supplier: str) -> None:
    path = state_path(state_dir, supplier)
    path.unlink(missing_ok=True)


def inbox_changed(state_dir: Path, supplier: str, inbox_dir: Path) -> tuple[bool, dict, dict | None]:
    current = inbox_snapshot(inbox_dir)
    previous = load_state(state_dir, supplier)
    if not current["files"]:
        return False, current, previous
    if previous is None:
        return True, current, None
    return current["fingerprint"] != previous.get("fingerprint"), current, previous
