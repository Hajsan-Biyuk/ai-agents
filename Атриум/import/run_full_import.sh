#!/bin/bash
# Полный импорт всех поставщиков (разовый запуск)
set -euo pipefail
cd /home/s/sheer26/atriumceramic.sheer26.ru/import
LOG="reports/full_import_$(date +%Y-%m-%d_%H%M%S).log"
exec >>"$LOG" 2>&1
echo "=== старт $(date '+%Y-%m-%d %H:%M:%S') ==="
for supplier in artkera rgw santehcentr; do
  echo "--- $supplier $(date '+%Y-%m-%d %H:%M:%S') ---"
  /usr/bin/python3 run_import.py --supplier "$supplier" --confirm --force || echo "FAIL: $supplier"
done
echo "=== конец $(date '+%Y-%m-%d %H:%M:%S') ==="
