<?php

namespace Me\AutoSelection\Migrations;


use Bitrix\Main\Diag\Debug;
use Bitrix\Main\Localization\Loc;
use Bitrix\Main\SiteTable;
use Me\AutoSelection\Helpers;
use Me\AutoSelection\Helper;

class Iblock
{
    public static function up()
    {
        $exists = [];
        Helper::initModules(['lists']);
        $exists = Helpers\Iblock::getIblock(
            ['CODE' => 'me_autoselection'],
            ['ID']
        );
        //TODO сделать проверку на существование инфоблока при установке. Лучше отдельный метод.
        //TODO вынести в step1 при установке запрос на выбор сайта($arFields['SITE_ID']) для которого устанавливается Инфоблок
        if (empty($exists)) {
//            $siteList = SiteTable::getList(
//                [
//                    'select' => ['LID']
//                ]
//            )->fetchAll();
            $arFields = [
                'NAME' => Loc::getMessage('MIG_IBLOCK_NAME'),
                'CODE' => 'me_autoselection',
                'API_CODE' => 'meAutoSelection',
                'IBLOCK_TYPE_ID' => 'lists',
                'LIST_PAGE_URL' => '',
                'DETAIL_PAGE_URL' => '',
                'SITE_ID' => 's1',
                'DESCRIPTION' => '',
                'WORKFLOW' => 'N',
                'BIZPROC' => 'Y',
                'FIELDS' => [
                ]
            ];
            //TODO определить какие поля надо создать.
            $iblockId = Helpers\Iblock::createIblock($arFields);
            if ($iblockId > 0) {
                $obList = new \CList($iblockId);
                foreach (self::newFields() as $newField) {
                    $obList->addField($newField);
                }
                global $CACHE_MANAGER;
                $CACHE_MANAGER->ClearByTag('list_list_' . $iblockId);
                $CACHE_MANAGER->ClearByTag('list_list_any');
                $CACHE_MANAGER->CleanDir('menu');
            }
        }
    }

    public static function down()
    {
        $exists = [];
        Helper::initModules(['lists']);
        $exists = Helpers\Iblock::getIblock(
            ['CODE' => 'me_autoselection'],
            ['ID']
        );
        if (!empty($exists)) {
            \CIBlock::Delete($exists['ID']);
        }
    }

    protected static function fieldDefaultSettings() :array
    {
        return [
            'IS_REQUIRED' => 'Y',
            'MULTIPLE' => 'N',
            'DEFAULT_VALUE' => '',
            'USER_TYPE_SETTINGS' => NULL,
            'SETTINGS' => [
                'SHOW_ADD_FORM' => 'Y',
                'SHOW_EDIT_FORM' => 'Y',
                'ADD_READ_ONLY_FIELD' => 'N',
                'EDIT_READ_ONLY_FIELD' => 'N',
                'SHOW_FIELD_PREVIEW' => 'N',
            ]
        ];
    }

    protected static function newFields() :array
    {
        $newFields = [];
        $newFieldProperties = [
            self::fieldBrand(),
            self::fieldCondition(),
            self::fieldYear(),
            self::fieldPrice(),
            self::fieldRainSensor()
        ];
        foreach ($newFieldProperties as $fieldProperty) {
            $newFields[] = array_merge(self::fieldDefaultSettings(), $fieldProperty);
        }
        return $newFields;
    }

    protected static function fieldBrand() :array
    {
        return [
            'SORT' => 20,
            'NAME' => Loc::getMessage('IBLOCK_BRAND_FIELD'),
            'CODE' => 'BRAND',
            'TYPE' => 'S:autobrands'
        ];
    }

    protected static function fieldCondition() :array
    {
        return [
            'SORT' => 30,
            'NAME' => Loc::getMessage('IBLOCK_CONDITION_FIELD'),
            'CODE' => 'CONDITION',
            'TYPE' => 'S'
        ];
    }

    protected static function fieldYear() :array
    {
        return [
            'SORT' => 40,
            'NAME' => Loc::getMessage('IBLOCK_YEAR_FIELD'),
            'CODE' => 'YEAR',
            'TYPE' => 'N'
        ];
    }

    protected static function fieldPrice() :array
    {
        return [
            'SORT' => 50,
            'NAME' => Loc::getMessage('IBLOCK_PRICE_FIELD'),
            'CODE' => 'PRICE',
            'TYPE' => 'N'
        ];
    }

    protected static function fieldRainSensor() :array
    {
        return [
            'SORT' => 60,
            'NAME' => Loc::getMessage('IBLOCK_RAINSENS_FIELD'),
            'CODE' => 'RAINSENS',
            'TYPE' => 'S'
        ];
    }
}