#!/bin/bash
# Cron-обёртка импорта Атриум (вставить в панель Beget: расписание */30 * * * *)
cd /home/s/sheer26/atriumceramic.sheer26.ru/import
echo "=== cron $(date '+%Y-%m-%d %H:%M:%S') ===" >> reports/cron.log
/usr/bin/python3 run_import.py >> reports/cron.log 2>&1
