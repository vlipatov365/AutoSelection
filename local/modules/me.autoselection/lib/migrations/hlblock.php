<?php

namespace Me\AutoSelection\Migrations;

use Bitrix\Highloadblock\HighloadBlockLangTable;
use Bitrix\Highloadblock\HighloadBlockTable;
use Bitrix\Main\Diag\Debug;
use Bitrix\Main\SystemException;
use Bitrix\Main\Localization\Loc;
use Me\Autoselection\Helpers;
use Me\AutoSelection\Helper;

class HlBlock
{
    public function getHlBlockName()
    {
        return "MeAutoSelectionBrands";
    }

    public static function up($site)
    {
        Helper::initModules(['highloadblock']);

        $exists = Helpers\HighloadBlock::getHlblock(
            ['NAME' => self::getHlBlockName()],
            ['ID']
        );
        if (empty($exists)) {
            $result = HighloadBlockTable::add([
                'NAME' => 'MeAutoSelectionBrands',
                'TABLE_NAME' => 'me_autoselection_brands'
            ]);
            if ($result->isSuccess()) {
                $id = $result->getId();
                Helpers\Options::setParam('ME_ATSLCTN_BRANDS_ID', $id);
                HighloadBlockLangTable::add([
                    'ID' => $id,
                    'LID' => $site,
                    'NAME' => Loc::getMessage('ME_ATSLCTN_BRANDS')
                ]);

                self::addUserTypeEntity(self::getUfArFields($id, [
                    'FIELD_NAME' => 'UF_BRANDNAME',
                    'USER_TYPE_ID' => 'string',
                    'SORT' => 100,
                    'LABEL' => Loc::getMessage("UF_BRANDNAME")
                ]));
                self::addUserTypeEntity(self::getUfArFields($id, [
                    'FIELD_NAME' => 'UF_XML_ID',
                    'USER_TYPE_ID' => 'string',
                    'SORT' => 200,
                    'LABEL' => 'UF_XML_ID'
                ]));
                self::addHlElements($id);
            } else {
                throw new SystemException(implode(';', $result->getErrorMessages()));
            }
        } else {
            Helpers\Options::setParam('ME_ATSLCTN_BRANDS_ID', $exists['ID']);
        }
    }

    public static function down()
    {
        Helper::initModules(['highloadblock']);
        $exists = Helpers\HighloadBlock::getHlblock(
            ['NAME' => 'MeAutoSelectionBrands'],
            ['ID']
        );
        if ($exists) {
            $result = HighloadBlockTable::delete($exists['ID']);
            if (!$result->isSuccess()) {
                throw new SystemException(implode(';', $result->getErrorMessages()));
            }
        }
    }

    protected static function getUfArFields($hlid, $data)
    {
        $arFields = [
            'ENTITY_ID' => 'HLBLOCK_' . $hlid,
            'FIELD_NAME' => $data['FIELD_NAME'],
            'USER_TYPE_ID' => $data['USER_TYPE_ID'],
            'XML_ID' => $data['FIELD_NAME'],
            'SORT' => $data['SORT'] ?: 100,
            'MULTIPLE' => $data['MULTIPLE'] ?: 'N',
            'MANDATORY' => $data['MANDATORY'] ?: 'N',
            'SHOW_FILTER' => $data['SHOW_FILTER'] ?: 'Y',
            'SHOW_IN_LIST' => $data['SHOW_IN_LIST'] ?: 'Y',
            'EDIT_IN_LIST' => $data['EDIT_IN_LIST'] ?: 'Y',
            'IS_SEARCHABLE' => $data['IS_SEARCHABLE'] ?: 'N',
            'EDIT_FORM_LABEL' => ['ru' => $data['LABEL']],
            'LIST_COLUMN_LABEL' => ['ru' => $data['LABEL']],
            'LIST_FILTER_LABEL' => ['ru' => $data['LABEL']],
            'ERROR_MESSAGE' => ['ru' => '',],
            'HELP_MESSAGE' => ['ru' => '',],
        ];

        switch ($data['USER_TYPE_ID']) {
            case 'boolean':
                $arFields['SETTINGS'] = [
                    'DEFAULT_VALUE' => 1,
                    'DISPLAY' => 'CHECKBOX',
                    'LABEL' => ['', ''],
                    'LABEL_CHECKBOX' => '',

                ];
            case 'integer':
                $arFields['SETTINGS'] = [
                    'SIZE' => 20,
                    'MIN_VALUE' => 0,
                    'MAX_VALUE' => 0,
                    'DEFAULT_VALUE' => ''
                ];
            case 'string':
                $arFields['SETTINGS'] = [
                    'SIZE' => 20,
                    'ROWS' => 1,
                    'REGEXP' => '',
                    'MIN_LENGTH' => 0,
                    'MAX_LENGTH' => 0,
                    'DEFAULT_VALUE' => ''
                ];
                break;
        }
        return $arFields;
    }

    function addUserTypeEntity($field)
    {
        $obUserField = new \CUserTypeEntity;
        $obUserField->Add($field);
    }

    function hlElements()
    {
        return [
            'Audi',
            'BMW',
            'Subaru',
            'Tesla'
        ];
    }

    public static function addHlElements($id)
    {
        $hlEntityDataClass = Helpers\HighloadBlock::getEntityDataClass($id);
        $elements = self::hlElements();
        foreach ($elements as $element) {
            $hlEntityDataClass::add([
                "UF_BRANDNAME" => $element,
                "UF_XML_ID" => $element
            ]);
        }
    }
}