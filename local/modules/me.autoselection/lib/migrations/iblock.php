<?php

namespace Me\AutoSelection\Migrations;

use Bitrix\Main\Loader;
use Bitrix\Main\Localization\Loc;
use CIBlockElement;
use Me\AutoSelection\Helpers;
use Me\AutoSelection\Helper;

class Iblock
{
    public static function up($site)
    {
        $exists = [];
        Helper::initModules(['lists']);
        $exists = Helpers\Iblock::getIblock(
            ['CODE' => 'me_autoselection'],
            ['ID']
        );
        if (empty($exists)) {
            $arFields = [
                'NAME' => Loc::getMessage('MIG_IBLOCK_NAME'),
                'CODE' => 'me_autoselection',
                'API_CODE' => 'meAutoSelection',
                'IBLOCK_TYPE_ID' => 'lists',
                'LIST_PAGE_URL' => '',
                'DETAIL_PAGE_URL' => '',
                'SITE_ID' => $site,
                'VERSION' => '1',
                'DESCRIPTION' => '',
                'WORKFLOW' => 'N',
                'BIZPROC' => 'N',
                'EDIT_FILE_AFTER' => '/local/public/me/autoselection/form_edit.php'
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

    protected static function fieldDefaultSettings(): array
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
            ],
            'LIST' => [],
            'SEARCHABLE' => 'Y'
        ];
    }

    protected static function newFields(): array
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

    protected static function fieldBrand(): array
    {
        return [
            'SORT' => 20,
            'NAME' => Loc::getMessage('IBLOCK_BRAND_FIELD'),
            'CODE' => 'BRAND',
            'TYPE' => 'S:autobrands'
        ];
    }

    protected static function fieldCondition(): array
    {
        return [
            'SORT' => 30,
            'NAME' => Loc::getMessage('IBLOCK_CONDITION_FIELD'),
            'CODE' => 'CONDITION',
            'TYPE' => 'S'
        ];
    }

    protected static function fieldYear(): array
    {
        return [
            'SORT' => 40,
            'NAME' => Loc::getMessage('IBLOCK_YEAR_FIELD'),
            'CODE' => 'YEAR',
            'TYPE' => 'N'
        ];
    }

    protected static function fieldPrice(): array
    {
        return [
            'SORT' => 50,
            'NAME' => Loc::getMessage('IBLOCK_PRICE_FIELD'),
            'CODE' => 'PRICE',
            'TYPE' => 'N'
        ];
    }

    protected static function fieldRainSensor(): array
    {
        return [
            'SORT' => 60,
            'NAME' => Loc::getMessage('IBLOCK_RAINSENS_FIELD'),
            'CODE' => 'RAINSENS',
            'TYPE' => 'S'
        ];
    }

    public static function addElements()
    {
        Loader::includeModule('me.autoselection');
        $PROPS = [];
        $iblockId = Helpers\Iblock::getIblockId(['CODE' => 'me_autoselection']);
        $properties = CIBlockElement::GetProperty($iblockId, []);
        while ($property = $properties->Fetch()) {
            $PROPS[$property['CODE']] = $property['ID'];
        }

        $properties = CIBlockElement::GetProperty($iblockId, []);
        $arProps = [
            [
                'NAME' => 'А8',
                $PROPS['BRAND'] => 'Audi',
                $PROPS['CONDITION'] => 'Поддержанный',
                $PROPS['YEAR'] => 2003,
                $PROPS['PRICE'] => 300000,
                $PROPS['RAINSENS'] => 'Да'
            ],
            [
                'NAME' => 'M3',
                $PROPS['BRAND'] => 'BMW',
                $PROPS['CONDITION'] => 'Поддержанный',
                $PROPS['YEAR'] => 2010,
                $PROPS['PRICE'] => 450000,
                $PROPS['RAINSENS'] => 'Нет'
            ],
            [
                'NAME' => 'Outback',
                $PROPS['BRAND'] => 'Subaru',
                $PROPS['CONDITION'] => 'Новое',
                $PROPS['YEAR'] => 2023,
                $PROPS['PRICE'] => 44450000,
                $PROPS['RAINSENS'] => 'Да'
            ],
            [
                'NAME' => 'Model S',
                $PROPS['BRAND'] => 'Tesla',
                $PROPS['CONDITION'] => 'Новое',
                $PROPS['YEAR'] => 2022,
                $PROPS['PRICE'] => 50000000,
                $PROPS['RAINSENS'] => 'Нет'
            ],
            [
                'NAME' => 'XV',
                $PROPS['BRAND'] => 'Subaru',
                $PROPS['CONDITION'] => 'Поддержанный',
                $PROPS['YEAR'] => 2013,
                $PROPS['PRICE'] => 12450000,
                $PROPS['RAINSENS'] => 'Нет'
            ],
            [
                'NAME' => 'X6',
                $PROPS['BRAND'] => 'BMW',
                $PROPS['CONDITION'] => 'Поддержанный',
                $PROPS['YEAR'] => 2007,
                $PROPS['PRICE'] => 1150000,
                $PROPS['RAINSENS'] => 'Нет'
            ],
        ];

        foreach ($arProps as $props) {
            $arFields = [
                'IBLOCK_ID' => $iblockId,
                "IBLOCK_SECTION_ID" => false,
                "PROPERTY_VALUES" => $props,
                'NAME' => $props['NAME']
            ];
            $el = new CIBlockElement();
            $el->Add($arFields);
        }
    }
}