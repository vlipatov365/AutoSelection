<?php
if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();

use Bitrix\Main\Loader,
    Me\Autoselection\Helpers\HighloadBlock;

if (Loader::includeModule('me.autoselection')) {
    $hlBlockId = HighloadBlock::getHlblock(
        ['NAME' => 'MeAutoSelectionBrands'],
        ['ID']
    );
    $hlBlockId = intval($hlBlockId['ID']);
    $entDataClass = HighloadBlock::getEntityDataClass($hlBlockId);
    $arValues = $entDataClass::getList(
        ['select' => ['*']]
    );
    while ($el = $arValues->fetch()) {
        $arResult['BRAND'][$el['UF_BRANDNAME']] = $el['UF_BRANDNAME'];
    }
}
