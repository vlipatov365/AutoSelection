<?php
require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/modules/main/include/prolog_before.php");
defined('B_PROLOG_INCLUDED') || die;

use Bitrix\Main\Localization\Loc;
global $APPLICATION, $USER;
Loc::loadMessages(__FILE__);