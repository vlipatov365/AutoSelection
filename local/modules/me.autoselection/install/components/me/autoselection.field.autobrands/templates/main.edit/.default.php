<?php
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();

use Me\Autoselection\Helpers\HighloadBlock;

$hlBlockId = HighloadBlock::getHlblock(
    ['NAME' => 'MeAutoSelectionBrands'],
    ['ID']
);
if (!empty($hlBlockId)) {
    $hlBlockId = intval($hlBlockId['ID']);
} else {
    throw new SystemException('Отсутствует список автомобильных марок');
}
$hlDataClass = HighloadBlock::getEntityDataClass($hlBlockId);
$hlBrandsList = $hlDataClass::getList();
$arBrands = [];
while ($brand = $hlBrandsList->fetch()) {
    $arBrands [] = [
        'ID' => $brand['ID'],
        'BRANDNAME' => $brand['UF_BRANDNAME']
    ];
}
?>

<select name="" id="">
    <?php foreach ($arBrands as $brand): ?>
        <option value="<?=brand['ID']?>"><?=$brand['BRANDNAME']?></option>
    <?php endforeach; ?>
</select>
