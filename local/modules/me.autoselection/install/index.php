<?php

use \Bitrix\Main;
use \Bitrix\Main\Loader;
use Me\AutoSelection as Aslctn;
use Bitrix\Main\Localization\Loc;
use Bitrix\Main\ModuleManager;
use Bitrix\Main\IO;
use Bitrix\Main\SystemException;

Loc::loadMessages(__FILE__);

class me_autoselection extends CModule
{
    var $MODULE_ID;
    var $MODULE_VERSION;
    var $MODULE_VERSION_DATE;
    var $MODULE_NAME;
    var $MODULE_DESCRIPTION;

    var $REQUEST;

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

        $context = Main\Application::getInstance()->getContext();
        $this->REQUEST = $context->getRequest();
    }

    //region Установка и удаление
    function DoInstall()
    {
        global $APPLICATION, $step, $site;
        $step = IntVal($step);
        if ($step < 2) {
            $this->checkBeforeInstall();
            $APPLICATION->includeAdminFile(
                'Установка',
                $this->getPath() . '/install/step1.php'
            );
        } elseif ($step === 2) {
            ModuleManager::registerModule($this->MODULE_ID);
            $arSites = \Bitrix\Main\SiteTable::getList()->fetchAll();
            $arSitesToInstall = [];
            foreach ($arSites as $site) {
                if ($site['LID'] = $this->REQUEST[$site['LID']]) {
                    $arSitesToInstall [] = [
                        $site['LID']
                    ];
                }
                foreach ($arSitesToInstall as $siteToInstall) {
                    $this->InstallIblock($siteToInstall);
                    $this->InstallHlBlock($siteToInstall);
                }
            }
            //TODO решить какой вариант проверки лучше. 1) Проверка каждого компонента в конце установки. 2) Отдельная проверка каждого компонента после его установки
            //TODO возможно стоит изменить обращение к таблице сайтов на одно единственное в самом начале.
            $this->InstallDB();
            $this->InstallDirectories();
            $this->InstallFiles();
            $this->InstallEvents();
        }
    }

    function DoUnInstall()
    {
        global $APPLICATION;
        $this->UnInstallIblock();
        ModuleManager::unRegisterModule($this->MODULE_ID);
    }
    //endregion Установка и удаление
    //region Инфо-блок
    public function InstallIblock($site)
    {
        Loader::includeModule($this->MODULE_ID);
        return Aslctn\Migrations\Iblock::up($site);
    }

    public function UnInstallIblock()
    {
        Loader::includeModule($this->MODULE_ID);
        return Aslctn\Migrations\Iblock::down();
    }
    //endregion Инфо-блок
    //region HL-блок
    public function InstallHlBlock($site)
    {
        Loader::includeModule($this->MODULE_ID);
        return Aslctn\Migrations\Hlblock::up($site);
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
    function InstallEvents()
    {
        $eventManager = Main\EventManager::getInstance();
        /** Регистрация своего типа пользовательского поля */
        $eventManager->RegisterEventHandler(
            'main',
            'OnUserTypeBuildList',
            $this->MODULE_ID,
            'Me\Autoselection\UserFields\Type\AutobrandsType',
            'getUserTypeDescription'
        );
        /** Установка типа свойства Инфоблока */
        $eventManager->registerEventHandler(
            'iblock',
            'OnIblockPropertyBuildList',
            $this->MODULE_ID,
            'Me\Autoselection\Integration\AutobrandsProperty',
            'getUserTypeDescription'
        );
        /**Установка события для добавления кнопки меню*/
        $eventManager->registerEventHandler(
            'main',
            'OnEpilog',
            $this->MODULE_ID,
            'Me\AutoSelection\Handlers\BuildOnBeforeProlog',
            'addHeaderButton'
        );
    }

    function UnInstallEvents()
    {
        $eventManager = Main\EventManager::getInstance();
        $eventManager->unRegisterEventHandler(
            'main',
            'OnUserTypeBuildList',
            $this->MODULE_ID,
            'Me\Autoselection\UserField\Type\AutobrandsType',
            'getUserTypeDescription'
        );
        /** Удаление типа свойства Инфоблока */
        $eventManager->unRegisterEventHandler(
            'iblock',
            'OnIblockPropertyBuildList',
            $this->MODULE_ID,
            'Me\Autoselection\Integration\AutobrandsProperty',
            'getUserTypeDescription'
        );
        /**Удаление события для добавления кнопки меню*/
        $eventManager->unRegisterEventHandler(
            'main',
            'OnEpilog',
            $this->MODULE_ID,
            'Me\AutoSelection\Handlers\BuildOnBeforeProlog',
            'addHeaderButton'
        );
    }
    //endregion События
    //region Файлы и Папки
    function InstallDirectories()
    {
        copyDirFiles(
            __DIR__ . "/components",
            Main\Application::getDocumentRoot() . '/local/components',
            true,
            true
        );
    }

    function UnInstallDirectories()
    {
        $folders = [
            Main\Application::getDocumentRoot() . '/local/components/me'
        ];
        foreach ($folders as $folder) {
            IO\Directory::deleteDirectory($folder);
        }
    }

    //endregion Файлы и Папки
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

    protected function checkBeforeInstall()
    {
        global $APPLICATION;
        if (!$this->isVersionD7()) {
            throw new SystemException(Loc::getMessage('ME_AS_VERSION_ERROR'));
        }
    }

    protected function context()
    {
        return Main\Application::getInstance()->getContext();
    }

    protected function connection()
    {
        return Main\Application::getConnection();
    }

    protected function siteSelection()
    {

    }
}