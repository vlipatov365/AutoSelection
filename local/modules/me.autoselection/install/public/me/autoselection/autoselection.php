<?php
require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/modules/main/include/prolog_before.php");
defined('B_PROLOG_INCLUDED') || die;
require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/modules/main/include/prolog_after.php");

use Bitrix\Main\Loader;

global $APPLICATION;

if(!Loader::includeModule('me.autoselection'))
    return;
$APPLICATION->IncludeComponent('me:autoselection.grid', '.default');

require($_SERVER["DOCUMENT_ROOT"] . BX_ROOT . "/modules/main/include/epilog.php");
?>