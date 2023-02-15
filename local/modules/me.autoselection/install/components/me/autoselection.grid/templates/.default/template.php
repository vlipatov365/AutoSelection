<?php
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();


echo '<pre>';
////print_r($arResult['HEADERS']);
//echo "<br>";
//print_r($arResult['ROWS']);
//echo "<br>";
////print_r($arResult['FILTER']);
//echo "<br>";
////print_r($arResult['FILTER_ID']);
//echo "<br>";
//print_r($arResult['SORT']);
echo '</pre>';

$APPLICATION->includeComponent(
    'bitrix:main.ui.grid',
    '',
    [
        'GRID_ID' => $arResult['GRID_ID'],
        'COLUMNS' => $arResult['HEADERS'],
        'ROWS' => $arResult['ROWS'],
        'FILTER_ID' => $arResult['FILTER_ID'],
        "NAV_OBJECT" => $arResult['NAV_OBJECT'],
        "TOTAL_ROWS_COUNT" => $arResult['TOTAL_ROWS_COUNT'],
        "PAGE_SIZES" => $arResult['GRID_PAGE_SIZES'],
        "AJAX_MODE" => 'Y',
        "AJAX_ID" => CAjax::getComponentID('bitrix:main.ui.grid', '.default', ''),
        "ENABLE_NEXT_PAGE" => true,
        "ACTION_PANEL" => $arResult['ACTION_PANEL'],
        "AJAX_OPTION_JUMP" => 'N',
        "SHOW_CHECK_ALL_CHECKBOXES" => true,
        "SHOW_ROW_CHECKBOXES" => true,
        "SHOW_ROW_ACTIONS_MENU" => true,
        "SHOW_GRID_SETTINGS_MENU" => true,
        "SHOW_NAVIGATION_PANEL" => true,
        "SHOW_PAGINATION" => true,
        "SHOW_SELECTED_COUNTER" => true,
        "SHOW_TOTAL_COUNTER" => true,
        "SHOW_PAGESIZE" => true,
        "SHOW_ACTION_PANEL" => true,
        "ALLOW_COLUMNS_SORT" => true,
        "ALLOW_COLUMNS_RESIZE" => true,
        "ALLOW_HORIZONTAL_SCROLL" => true,
        "ALLOW_SORT" => true,
        "ALLOW_PIN_HEADER" => true,
        "AJAX_OPTION_HISTORY" => "N"
    ],
    $component, ["HIDE_ICONS" => "Y"]
);
require($_SERVER["DOCUMENT_ROOT"] . BX_ROOT . "/modules/main/include/epilog.php");