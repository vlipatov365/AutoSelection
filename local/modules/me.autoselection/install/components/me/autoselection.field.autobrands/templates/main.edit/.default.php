<?php
if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();

$isMulti = $arResult['userField']['MULTIPLE'] === 'Y';
?>

<select name="<?=$arResult['fieldName'];?>" <?($isMulti)? print 'multiple':''?>>
    <option value=""></option>
    <?
    if (!empty($arResult['HIGHLOADBLOCKS'])) {
        foreach ($arResult['HIGHLOADBLOCKS'] as $comId => $compName) {
            $isSelected = false;
            if (in_array($comId, $arResult['value'])) {
                $isSelected = true;
            }
            ?>
            <option value="<?= $comId; ?>"<? ($isSelected)? print 'selected':'';?>><?= $compName?></option>
            <?
        }
    }
    ?>
</select>
