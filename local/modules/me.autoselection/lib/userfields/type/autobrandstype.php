<?php

namespace Me\Autoselection\UserFields\Type;

use Bitrix\Main\Localization\Loc;
use Bitrix\Main\UserField\Types\StringType;
use CUserTypeManager;

class AutobrandsType extends StringType
{
    public const
        USER_TYPE_ID = 'autobrands',
        RENDER_COMPONENT = 'me:autoselection.field.autobrands';

    /**
     * Описание пользовательского поля: описывающий класс, текстовое описание, базовый тип
     * @return array
     */
    public static function getDescription(): array
    {
        return [
            'CLASS_NAME' => __CLASS__,
            'DESCRIPTION' => Loc::getMessage('PROPERTY_TITLE'),
            'BASE_TYPE' => CUserTypeManager::BASE_TYPE_STRING
        ];
    }

    /**
     * Указывает тип данных в БД для данного тип пользовтельского поля
     * @return string
     */
    /**
     * Метод возвращающий массив с параметрами экземпляра пользовательского поля к привязанному объекту
     * @param array $userField
     * @return array
     */
    public static function prepareSettings(array $userField): array
    {
        return [];
    }

    /**
     * @param array $userField
     * @param string|array $value
     * @return array
     */
    public static function checkFields(array $arUserField, $value): array
    {
        return [];
    }

//    /**
//     * Метод получения вариантов пользовательского для свойств типа список
//     * @param array $arUserField
//     * @return \CDBResult|false|null
//     */
//    public static function GetList(array $arUserField)
//    {
//        $obEnum = new \CUserFieldEnum();
//        $rsEnum = $obEnum->GetList(array(),
//            [
//                "USER_FIELD_ID" => $arUserField['ID']
//            ]);
//        return $rsEnum;
//    }



    /**
     * Вывод HTML-кода в форме редактирование значений в публичной части
     */
    public static function getSettingsHtml($userField, ?array $additionalParameters, $varsFromForm): string
    {
        return '';
    }

    /**
     * Метода для формирование строки в поисковый индекс
     * @param array $userField
     * @return string|null
     * @throws \Bitrix\Main\LoaderException
     */
    public static function onSearchIndex(array $userField): ?string
    {
        return '';
    }

    /**
     * Вывод HTML-кода в форме редактирование значений в административной части
     * @param $arUserField
     * @param $arHtmlControl
     * @return array
     */
    public static function getEditFormHtml(array $userField, ?array $additionalParameters): string
    {
        return parent::getEditFormHtml($userField, $additionalParameters);
    }

//    /**
//     * Вывод HTML-кода в форме редактирование значений в фильтре
//     * @param $arUserField
//     * @param $arHtmlControl
//     * @return array
//     */
//    function GetFilterHTML($arUserField, $arHtmlControl)
//    {
//        return [];
//    }
//    /**
//     * Вывод HTML-кода в списке при просмотре в административной части
//     * @param $arUserField
//     * @param $arHtmlControl
//     * @return array
//     */
//    public static function GetAdminListViewHTML($arUserField, $arHtmlControl)
//    {
//        return [];
//    }
//    /**
//     * Вывод HTML-кода в списке при редактиовании в административной части
//     * @param $arUserField
//     * @param $arHtmlControl
//     * @return array
//     */
//    function GetAdminListEditHTML($arUserField, $arHtmlControl)
//    {
//        return [];
//    }
    /**
     * @param array $userField
     * @param $value
     * @return string|null
     */
    function onBeforeSave(array $userField, $value)
    {
        return $value;
    }

}