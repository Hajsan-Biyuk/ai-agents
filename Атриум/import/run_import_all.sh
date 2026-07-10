#!/bin/bash
# Последовательный импорт всех поставщиков (фоновый запуск на сервере)
cd /home/s/sheer26/atriumceramic.sheer26.ru/import
rm -f .lock
for s in artkera rgw santehcentr; do
  echo "START $s $(date '+%F %T')" >> reports/import_all.log
  /usr/bin/python3 run_import.py --supplier "$s" --confirm --force >> "reports/import_$s.log" 2>&1 || echo "FAIL $s $(date '+%F %T')" >> reports/import_all.log
  echo "END $s $(date '+%F %T')" >> reports/import_all.log
done
