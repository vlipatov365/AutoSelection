<?php

if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();
$isFirst = true;



foreach ($arResult['value'] as $value) {
    if (!$isFirst) {
        print '<br/>';
    }
    if ($value)
    $isFirst = false;
    print(!empty($value) ? $value['BRAND']: '');
}