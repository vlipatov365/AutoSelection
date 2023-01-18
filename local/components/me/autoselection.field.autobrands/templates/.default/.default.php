<?php

if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();

\Bitrix\Main\Diag\Debug::dump($arResult);
//$isFirst = true;
//
//foreach ($arResult['value'] as $value) {
//    if (!$isFirst) {
//        print '<br/>';
//    }
//
//    $isFirst = false;
//    print(!empty($value) ? $arResult['userField']['USER_TYPE']['FIELDS'][$value] : '');
//}