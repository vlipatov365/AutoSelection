<?php
if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();

use Me\Autoselection\Helpers\HighloadBlock,
    Me\AutoSelection\Helper;

Helper::initModules(['highloadblock']);
$hlBlockId = HighloadBlock::getHlblock(
    ['name' => 'MeAutoSelectionBrands'],
    ['ID']
);
$hlBlockId = $hlBlockId['ID'];

$hlEntityDataClass = HighloadBlock::getEntityDataClass($hlBlockId);
$hlElements = ($hlEntityDataClass::getList([
    'select' => ['UF_BRANDNAME']
])->fetchAll());

if (!empty($hlElements)) {
    foreach ($hlElements as $element) {
        $arResult['BRANDNAMES'][$element['ID']] = $element['UF_BRANDNAME'];
    }
}
