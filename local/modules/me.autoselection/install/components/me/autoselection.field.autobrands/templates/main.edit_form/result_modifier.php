<?php
if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();

use Bitrix\Highloadblock\HighloadBlock;

$arHighloadblocks = HighloadBlock::getList(
    [
        'select' => ['ID', 'NAME']
    ]
)->fetchAll();

if (!empty($arHighloadblocks)) {
    foreach ($arHighloadblocks as $highloadblock) {
        $arResult['HIGHLOADBLOCKS'][$highloadblock['ID']] = $highloadblock['NAME'];
    }
}
