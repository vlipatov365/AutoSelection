<?php

use \Bitrix\Main;
use \Bitrix\Main\Loader;
use Me\AutoSelection as Aslctn;
use Bitrix\Main\Localization\Loc;
use Bitrix\Main\ModuleManager;

Loc::loadMessages(__FILE__);

class me_autoselection extends CModule
{
    var $MODULE_ID;
    var $MODULE_VERSION;
    var $MODULE_VERSION_DATE;
    var $MODULE_NAME;
    var $MODULE_DESCRIPTION;

    function __construct()
    {
        global $APPLICATION;
        $arModuleVersion = [];
        include(dirname(__FILE__) . '/version.php');

        $this->MODULE_ID = 'me.autoselection';
        $this->MODULE_VERSION = $arModuleVersion['VERSION'];
        $this->MODULE_VERSION_DATE = $arModuleVersion['VERSION_DATE'];

        $this->MODULE_NAME = Loc::getMessage('MODULE_NAME');
        $this->MODULE_DESCRIPTION = Loc::getMessage('MODULE_DESCRIPTION');
        $this->PARTNER_NAME = Loc::getMessage('PARTNER_NAME');
        $this->PARTNER_URI = Loc::getMessage('PARTNER_URI');
    }

    //region Установка и удаление
    function DoInstall()
    {
        global $APPLICATION;
        ModuleManager::registerModule($this->MODULE_ID);
        $this->InstallDB();
        $this->InstalIblock();
        $this->InstallHlBlock();
    }

    function DoUnInstall()
    {
        global $APPLICATION;
        $this->UnInstallDB();
        $this->UnInstallIblock();
        $this->UnInstallHlBlock();
        ModuleManager::unRegisterModule($this->MODULE_ID);
    }
    //endregion Установка и удаление
    //region Инфо-блок
    public function InstalIblock()
    {
        Loader::includeModule($this->MODULE_ID);
        return Aslctn\Migrations\Iblock::up();
    }

    public function UnInstallIblock()
    {
        Loader::includeModule($this->MODULE_ID);
        return Aslctn\Migrations\Iblock::down();
    }
    //endregion Инфо-блок
    //region HL-блок
    public function InstallHlBlock()
    {
        Loader::includeModule($this->MODULE_ID);
        return Aslctn\Migrations\Hlblock::up();
    }
    public function UnInstallHlBlock()
    {
        Loader::includeModule($this->MODULE_ID);
        return Aslctn\Migrations\Hlblock::down();
    }
    //endregion HL-блок
    //region БД
    function InstallDB()
    {
        global $APPLICATION;
        Loader::includeModule($this->MODULE_ID);
        $entity = Aslctn\AutoselectionTable::getEntity();
        $tableName = $entity->getDBTableName();
        if (!$this->connection()->isTableExists($tableName)) {
            $entity->createDbTable();
        }
    }

    function UnInstallDB()
    {
        global $APPLICATION, $DB;
        $sqlError = $DB->RunSqlBatch(
            $this->getPath() . '/install/db/mysql/uninstall.sql'
        );
        if ($sqlError !== false) {
            $error = array_merge($error, $sqlError);
        }

        if (!empty($error)) {
            $APPLICATION->ThrowException(implode('', $error));
            return false;
        }
    }
    //endregion БД
    //region События
    //endregion События
    protected function isVersionD7()

    {
        return CheckVersion(ModuleManager::getVersion('main'), '14.00.00');
    }

    protected function getPath($notDocumentRoot = false)
    {
        $path = dirname(__DIR__);
        $path = str_replace("\\", "/", $path);
        return ($notDocumentRoot)
            ? preg_replace("#^(.*)\/(local|bitrix)\/modules#", "/$2/modules", $path)
            : $path;
    }

    protected function context()
    {
        return Main\Application::getInstance()->getContext();
    }

    protected function connection()
    {
        return Main\Application::getConnection();
    }
}