<?php

if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) {
    die();
}

echo CAdminMessage::ShowNote(
    'Модуль Sheer WebP установлен. Откройте Настройки → Настройки продукта → Настройки модулей → Sheer WebP.'
);
echo '<p>Профиль каталога Aspro: массово обрабатываются оригиналы в <code>/upload/iblock</code>, <code>/upload/CMax</code> и медиабиблиотеке. Превью <code>resize_cache</code> ставятся в очередь при просмотре, миниатюры Aspro до 120 px не трогаются.</p>';
echo '<p>Оригиналы не изменяются. Рядом появляется <code>имя.jpg.webp</code>. SVG, GIF и иконки пропускаются.</p>';
echo '<p>Нужны PHP GD с <code>imagewebp</code> или ImageMagick с WEBP.</p>';
echo '<p>На хите посетителя модуль <b>не кодирует</b> картинки — только очередь. Нужен <b>cron-агент</b>. На nginx оставьте подмену URL в HTML или вставьте сниппет.</p>';
