<?php
defined('B_PROLOG_INCLUDED') || die;

use Bitrix\Main\Loader,
    Bitrix\Main\Config\Option,
    Bitrix\Main\Localization\Loc,
    Bitrix\Main\Diag\Debug,
    Hive\Mymodule\Helpers;
global $APPLICATION, $USER;

$APPLICATION->SetTitle('Мой модуль');

if (!$USER->isAdmin()) {
    return;
}

$myModuleID = 'hive.mymodule';
Loader::includeModule($myModuleID);

//region preparedata
$arHlbBlocks = Helpers\HighloadBlock::getHlblocks([], ['*']);

$hlbValues = [];
if (!empty($arHlbBlocks)) {
    foreach ($arHlbBlocks as $hlbCode => $arHlb) {
        $hlbValues[$arHlb['ID']] = $arHlb['NAME'];
    }
}

$arIBlocks = Helpers\Iblock::getIblocks([],['*']);
$ibValues = [];
if (!empty($arIBlocks)) {
    foreach ($arIBlocks as $ibCode => $arIb) {
        $ibValues[$arIb['ID']]=$arIb['NAME'];
    }
}

//endregion preparedata


//region options
$tabs = array(
    array(
        'DIV' => 'general',
        'TAB' => Loc::getMessage('HM_TAB_GENERAL_NAME'),
        'TITLE' => Loc::getMessage('HM_TAB_GENERAL_TITLE')
    ),
    array(
        'DIV' => 'extra',
        'TAB' => Loc::getMessage('HM_TAB_EXTRA_NAME'),
        'TITLE' => Loc::getMessage('HM_TAB_EXTRA_TITLE')
    )
);

$curModuleHlB = Option::get($myModuleID, 'HM_HLBT');
$curModuleIB = Option::get($myModuleID, 'HM_IBLOCK');

$options['general'] = array(
    array(
        'HM_HLBT',
        Loc::getMessage('HM_HLBT'),
        $curModuleHlB,
        array('selectbox', $hlbValues)
    ),
    array(
        'HM_IBLOCK',
        Loc::getMessage('HM_BLOCK'),
        $curModuleIB,
        array('selectbox', $ibValues)
    )
);

$arSelectBoxVariants = [
    1 => Loc::getMessage('HM_VAR1'),
    2 => Loc::getMessage('HM_VAR2'),
    3 => Loc::getMessage('HM_VAR3'),
    4 => Loc::getMessage('HM_VAR4')
];

$textResult = Option::get($myModuleID, 'HM_OPTION_TEXT');
$textAreaResult = Option::get($myModuleID, 'HM_OPTION_TEXTAREA');
$arSelectBoxVariant = Option::get($myModuleID, 'HM_OPTION_SELECT_BOX');
$arMultiSelectBoxVariant = Option::get($myModuleID, 'HM_OPTION_MULTI_SELECT_BOX');
$arCheckbox = Option::get($myModuleID, 'HM_OPTION_CHECK_BOX');
//region options['general']
/*$options['general'] = array(
    Loc::getMessage('HM_OPTION_SEPARATOR'),
    array(
        'HM_OPTION_TEXT',
        Loc::getMessage('HM_OPTION_TEXT'),
        $textResult,
        array('text', '20'),
        '', ''
    ),
    array(
        'HM_OPTION_PASSWORD',
        Loc::getMessage('HM_OPTION_PASSWORD'),
        $textResult,
        array('password', '20'),
        '', ''
    ),
    array(
        'HM_OPTION_TEXTAREA',
        Loc::getMessage('HM_OPTION_TEXTAREA'),
        $textResult,
        array('textarea', '5', '30'),
        '', ''
    ),
    array(
        'HM_OPTION_SELECT_BOX',
        Loc::getMessage('HM_OPTION_SELECT_BOX'),
        $hlbValues,
        array('selectbox', $arSelectBoxVariants),
        '', ''
    ),
    array(
        'HM_OPTION_MULTI_SELECT_BOX',
        Loc::getMessage('HM_OPTION_MULTI_SELECT_BOX'),
        $arSelectBoxVariants,
        array('multiselectbox', $arSelectBoxVariants),
        '', ''
    ),
    array(
        'HM_OPTION_CHECK_BOX',
        Loc::getMessage('HM_VAR1'),
        $arSelectBoxVariants[1],
        array('checkbox')
    ), array(
        'HM_OPTION_CHECK_BOX',
        Loc::getMessage('HM_VAR2'),
        $arSelectBoxVariants[2],
        array('checkbox')
    ), array(
        'HM_OPTION_CHECK_BOX',
        Loc::getMessage('HM_VAR3'),
        $arSelectBoxVariants[3],
        array('checkbox')
    ), array(
        'HM_OPTION_CHECK_BOX',
        Loc::getMessage('HM_VAR4'),
        $arSelectBoxVariants[4],
        array('checkbox')
    ),
    array(
        'HM_OPTION_STATICTEXT',
        Loc::getMessage('HM_OPTION_STATICTEXT'),
        Loc::getMessage('HM_OPTION_STATICTEXT_VALUE'),
        array('statictext'),
        " ", " "
    ),
    array(
        'HM_OPTION_STATICTEXT',
        Loc::getMessage('HM_OPTION_STATICHTML'),
        Loc::getMessage('HM_OPTION_STATICHTML_VALUE'),
        array('statichtml'),
        " ", " "
    ),
    //TODO дописать ещё опций ColorPicker и чето там ещё
);*/
//endregion options['general']

//endregion options

if (check_bitrix_sessid() && strlen($_POST['save']) > 0) {
    foreach ($options as $option) {
        __AdmSettingsSaveOptions($myModuleID, $option);
    }
    LocalRedirect($APPLICATION->GetCurPage());
}
/*
 * Отрисовка формы
 */
$tabControl = new CAdminTabControl('tabControl', $tabs);
$tabControl->Begin();
?>

<form method="post"
      action="<? echo $APPLICATION->getCurPage() ?>?mid=<?= htmlspecialcharsbx($mid) ?>&lang=<?= LANGUAGE_ID ?>"
      id="baseexchange_form">
    <?php
    foreach ($options as $option) {
        $tabControl->BeginNextTab();
        __AdmSettingsDrawList($myModuleID, $option);
    }
    $tabControl->Buttons(array('btnApply' => false, 'btnCancel' => false, 'btnSaveAndAdd' => false));
    echo bitrix_sessid_post();
    $tabControl->End();
    ?>
</form>