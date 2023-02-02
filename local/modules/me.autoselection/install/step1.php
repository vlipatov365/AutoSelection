<?php

use Bitrix\Main\SiteTable,
    Bitrix\Main\Localization\Loc;

global $APPLICATION;
?>
<?php if ($ex = $APPLICATION->GetException()): ?>
    <?php $exString = $ex->GetString();
    CAdminMessage::ShowMessage(
        [
            'TYPE' => 'ERROR',
            'MESSAGE' => $exString,
            'DETAILS' => $ex->GetString(),
            'HTML' => true
        ]
    ); ?>
    <form action="<?= $APPLICATION->GetCurPage(); ?>">
        <input type="hidden" name="lang" value="<?= LANGUAGE_ID ?>">
        <input type="hidden" name="id" value="me.autoselection">
        <input type="hidden" name="step" value="3">
        <input type="hidden" name="uninstall" value="N">
        <input type="submit" name="" value="<?= Loc::getMessage('ME_AS_VERSION_ERROR') ?>">
    </form>
<?php else: ?>
    <?php $arSites = SiteTable::getList()->fetchAll(); ?>
    <div class="adm-detail-block">
        <form action="<?= $APPLICATION->GetCurPage(); ?>">
            <?= bitrix_sessid_post()?>
            <input type="hidden" name="lang" value="<?= LANGUAGE_ID ?>">
            <input type="hidden" name="id" value="me.autoselection">
            <input type="hidden" name="install" value="Y">
            <input type="hidden" name="step" value="2">
            <div class="adm-detail-content-wrap">
                <div class="adm-detail-title"><?= Loc::getMessage('ME_AS_SITE_SELECTION'); ?></div>
                <?php foreach ($arSites as $site): ?>
                    <div class="adm-detail-content-item-block">
                        <label for="$site['LID']"><?= $site['NAME'] ?></label>
                        <input type="checkbox" name="<?= $site['LID'] ?>" value="<?= $site['LID'] ?>">
                    </div>
                <?php endforeach; ?>
                <label for="site"><?= $site['NAME'] ?></label>
                <input type="checkbox" name="site" value="s2">
                <input type="submit" name="" value="Выбрать">
            </div>
        </form>
    </div>
<?php endif;//TODO добавить JS для проверки checked