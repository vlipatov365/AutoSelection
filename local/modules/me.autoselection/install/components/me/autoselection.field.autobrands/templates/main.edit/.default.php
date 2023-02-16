<?php
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();

$isMulti = $arResult['userField']['MULTIPLE'] === 'Y';
?>

<select name="<?=$arResult['fieldName'];?>" <?=($isMulti)? print 'multiple':''?>>
    <option value=""></option>
    <?php
    if (!empty($arResult['BRAND'])) {
        foreach ($arResult['BRAND'] as $BrandId => $BrandName) {
            $isSelected = false;
            if (in_array($BrandId, $arResult['value'])) {
                $isSelected = true;
            }
            ?>
            <option value="<?= $BrandName; ?>"<?=($isSelected)? print 'selected':'';?>><?= $BrandName?></option>
            <?php
        }
    }
    ?>
</select>