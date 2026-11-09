<?php

namespace Sheer\Webp;

use Bitrix\Main\Application;
use Bitrix\Main\Entity\DataManager;
use Bitrix\Main\Entity\DatetimeField;
use Bitrix\Main\Entity\IntegerField;
use Bitrix\Main\Entity\StringField;
use Bitrix\Main\Type\DateTime;

class RegistryTable extends DataManager
{
    public static function getTableName()
    {
        return 'sheer_webp_file';
    }

    public static function pathHash($relative)
    {
        return md5(Path::normalizeRelative($relative));
    }

    public static function getMap()
    {
        return [
            'ID' => new IntegerField('ID', [
                'primary' => true,
                'autocomplete' => true,
            ]),
            'FILE_ID' => new IntegerField('FILE_ID'),
            'ORIGINAL_PATH' => new StringField('ORIGINAL_PATH', [
                'required' => true,
                'size' => 500,
            ]),
            'PATH_HASH' => new StringField('PATH_HASH', [
                'required' => true,
                'size' => 32,
            ]),
            'WEBP_PATH' => new StringField('WEBP_PATH', [
                'required' => true,
                'size' => 500,
            ]),
            'ORIGINAL_SIZE' => new IntegerField('ORIGINAL_SIZE', [
                'default_value' => 0,
            ]),
            'WEBP_SIZE' => new IntegerField('WEBP_SIZE', [
                'default_value' => 0,
            ]),
            'STATUS' => new StringField('STATUS', [
                'default_value' => 'pending',
                'size' => 30,
            ]),
            // Битрикс создаёт колонки NOT NULL — «нет ошибки» пишем пустой строкой.
            'ERROR_TEXT' => new StringField('ERROR_TEXT', [
                'size' => 255,
                'default_value' => '',
            ]),
            'DATE_CREATE' => new DatetimeField('DATE_CREATE'),
            'DATE_UPDATE' => new DatetimeField('DATE_UPDATE'),
        ];
    }

    public static function upsert(array $fields)
    {
        $fields['DATE_UPDATE'] = new DateTime();
        if (empty($fields['ORIGINAL_PATH'])) {
            return 0;
        }

        $fields['ORIGINAL_PATH'] = Path::normalizeRelative($fields['ORIGINAL_PATH']);
        $fields['PATH_HASH'] = self::pathHash($fields['ORIGINAL_PATH']);

        $existing = self::getList([
            'filter' => ['=PATH_HASH' => $fields['PATH_HASH']],
            'select' => ['ID'],
            'limit' => 1,
        ])->fetch();

        if ($existing) {
            self::update((int)$existing['ID'], $fields);
            return (int)$existing['ID'];
        }

        $fields['DATE_CREATE'] = new DateTime();
        $result = self::add($fields);
        if ($result->isSuccess()) {
            return (int)$result->getId();
        }

        $again = self::getList([
            'filter' => ['=PATH_HASH' => $fields['PATH_HASH']],
            'select' => ['ID'],
            'limit' => 1,
        ])->fetch();
        if ($again) {
            self::update((int)$again['ID'], $fields);
            return (int)$again['ID'];
        }

        return 0;
    }

    public static function getByOriginal($relative)
    {
        return self::getList([
            'filter' => ['=PATH_HASH' => self::pathHash($relative)],
            'limit' => 1,
        ])->fetch();
    }

    /**
     * Атомарно забрать строку pending → processing. false — её уже взял
     * другой процесс (cron-агент и цикл в админке работают параллельно).
     */
    public static function claim($id)
    {
        $connection = Application::getConnection();
        $helper = $connection->getSqlHelper();
        $connection->queryExecute(
            'UPDATE ' . $helper->quote(self::getTableName())
            . " SET STATUS = 'processing', ERROR_TEXT = '', DATE_UPDATE = " . $helper->getCurrentDateTimeFunction()
            . ' WHERE ID = ' . (int)$id . " AND STATUS = 'pending'"
        );

        return $connection->getAffectedRowsCount() > 0;
    }

    /** Время последней записи строки (unix), 0 — неизвестно. */
    public static function rowTimestamp($row)
    {
        $value = is_array($row) ? ($row['DATE_UPDATE'] ?? null) : null;
        if ($value instanceof \Bitrix\Main\Type\DateTime) {
            return (int)$value->getTimestamp();
        }
        if (is_int($value)) {
            return $value;
        }
        if (is_string($value) && $value !== '') {
            $ts = strtotime($value);
            return $ts !== false ? (int)$ts : 0;
        }

        return 0;
    }

    public static function failStaleProcessing($seconds = 300)
    {
        try {
            $connection = Application::getConnection();
            $helper = $connection->getSqlHelper();
            $threshold = date('Y-m-d H:i:s', time() - (int)$seconds);
            $connection->queryExecute(
                'UPDATE ' . $helper->quote(self::getTableName())
                . " SET STATUS = 'error', ERROR_TEXT = 'Прервано (таймаут или нехватка памяти)'"
                . " WHERE STATUS = 'processing' AND DATE_UPDATE < '" . $helper->forSql($threshold) . "'"
            );
        } catch (\Throwable $e) {
        }
    }

    public static function getStats()
    {
        $stats = [
            'pending' => 0,
            'processing' => 0,
            'done' => 0,
            'skipped_larger' => 0,
            'skipped_icon' => 0,
            'skipped_type' => 0,
            'error' => 0,
            'total' => 0,
            'original_size' => 0,
            'webp_size' => 0,
            'saved' => 0,
            'converted_percent' => 0,
            'saved_percent' => 0,
        ];

        try {
            $connection = Application::getConnection();
            $res = $connection->query(
                'SELECT STATUS, COUNT(*) AS CNT, SUM(ORIGINAL_SIZE) AS OSIZE, SUM(WEBP_SIZE) AS WSIZE
                 FROM sheer_webp_file
                 GROUP BY STATUS'
            );
            while ($row = $res->fetch()) {
                $status = (string)$row['STATUS'];
                $count = (int)$row['CNT'];
                $stats['total'] += $count;
                if (isset($stats[$status])) {
                    $stats[$status] = $count;
                } else {
                    $stats[$status] = $count;
                }
                if ($status === 'done') {
                    $stats['original_size'] += (int)$row['OSIZE'];
                    $stats['webp_size'] += (int)$row['WSIZE'];
                }
            }
        } catch (\Throwable $e) {
            return $stats;
        }

        $stats['saved'] = max(0, $stats['original_size'] - $stats['webp_size']);
        $stats['converted_percent'] = $stats['total'] > 0
            ? round($stats['done'] * 100 / $stats['total'], 1)
            : 0;
        $stats['saved_percent'] = $stats['original_size'] > 0
            ? round($stats['saved'] * 100 / $stats['original_size'], 1)
            : 0;

        return $stats;
    }
}
