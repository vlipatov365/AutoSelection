<?php
require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/modules/main/include/prolog_before.php");
defined('B_PROLOG_INCLUDED') || die;

use Bitrix\Main\Localization\Loc;
use Bitrix\Main\Loader;
use Me\AutoSelection\Helpers\Iblock;
global $APPLICATION, $USER;
Loc::loadMessages(__FILE__);
Loader::includeModule('me.autoselection');

$APPLICATION->SetTitle(Loc::getMessage('ME_AS_PUBLIC_TITLE'));
require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/modules/main/include/prolog_after.php");

$iBlockId = Iblock::getIblockId(
    ['CODE'=>'me_autoselection']
);
$APPLICATION->IncludeComponent('bitrix:lists.list','',     Array(
    "IBLOCK_TYPE_ID" => "lists",
    "IBLOCK_ID" => $iBlockId,
    "LISTS_URL" => "lists.lists.php",
    "LIST_EDIT_URL" => "lists.list.edit.php?list_id=#list_id#",
    "LIST_URL" => "lists.list.php?list_id=#list_id#§ion_id=#section_id#",
    "LIST_SECTIONS_URL" => "lists.sections.php?list_id=#list_id#§ion_id=#section_id#",
    "LIST_ELEMENT_URL" => "lists.element.edit.php?list_id=#list_id#§ion_id=#section_id#&element_id=#element_id#",
    "CACHE_TYPE" => "A",
    "CACHE_TIME" => "3600",
//    "BIZPROC_WORKFLOW_START_URL" => "bizproc.workflow.start.php?element_id=#element_id#&list_id=#list_id#&workflow_template_id=#workflow_template_id#"
));


?>
<?php
require($_SERVER["DOCUMENT_ROOT"].BX_ROOT."/modules/main/include/epilog.php");
?>