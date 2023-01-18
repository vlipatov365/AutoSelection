<?php

namespace Me\AutoSelection\Integration;

use Bitrix\Main\Localization\Loc;
use Me\Autoselection\UserFields\Type\AutobrandsType;

Loc::loadMessages(__FILE__);

class AutobrandsProperty
{
    public static function getUserTypeDescription()
    {
        $arFieldTypeDescription = AutobrandsType::getUserTypeDescription();

        $className = get_called_class();
        return [
            "PROPERTY_TYPE" => 'S',
            'USER_TYPE' => AutobrandsType::USER_TYPE_ID,
            'DESCRIPTION' => $arFieldTypeDescription['DESCRIPTION'],

            'GetPublicEditHTML' => [$className, 'getPublicEditHTML'],
            'GetPublicEditHTMLMulty' => [$className, 'getPublicEditHTMLMulty'],

            'GetPublicViewHTML' => [$className, 'getPublicViewHTML'],
            'GetPublicViewHTMLMulty' => [$className, 'getPublicViewHTMLMulty'],

            'GetPropertyFieldHtml' => [$className, 'getPropertyFieldHtml'],
            'GetPropertyFieldHtmlMulty' => [$className, 'getPropertyFieldHtmlMulty'],

            'GetAdminListViewHTML' => [$className, 'getAdminListViewHTML'],

            'PrepareSettings' => [$className, 'prepareSettings'],
            'GetSettingsHTML' => [$className, 'getSettingsHTML'],

            'CheckFields' => [$className, 'checkFields'],
            'GetLength' => [$className, 'getLength'],
            'GetValuePrintable' => [$className, 'getValuePrintable'],
            /*
            'GetUIFilterProperty' => [$className, 'getUIFilterProperty'],
            'GetUIEntityEditorProperty' => [$className, 'GetUIEntityEditorProperty'],
            'GetUIEntityEditorPropertyViewHtml' => [$className, 'GetUIEntityEditorPropertyViewHtml'],
            'GetUIEntityEditorPropertyEditHtml' => [$className, 'GetUIEntityEditorPropertyEditHtml'],
            */
        ];
    }

    public static function getPublicEditHTML($property, $value, $controlSettings)
    {
        return static::getPublicEditHTMLMulty($property, $value, $controlSettings);
    }

    public static function getPublicEditHTMLMulty($property, $value, $controlSettings)
    {
        $html = "";
        $fieldName = !empty($controlSettings['VALUE']) ? $controlSettings['VALUE'] : '';
        $formLable = !empty($controlSettings['DESCRIPTION']) ? $controlSettings['DESCRIPTION'] : '';
        $multiple = !empty($controlSettings['MULTIPLE']) ? $controlSettings['MULTIPLE'] : $property['MULTIPLE'];
        $isRequired = !empty($property['IS_REQUIRED']) ? $property['IS_REQUIRED'] : 'N';
        $createNewEntity = true;
        $listValue = array();
        if (!empty($value['VALUE'])) {
            if (!is_array($value['VALUE']))
                $value['VALUE'] = array($value['VALUE']);
            $listValue = $value['VALUE'];
        } elseif (is_array($value)) {
            foreach ($value as $dataValue) {
                if (isset($dataValue['VALUE'])) {
                    if (is_array($dataValue['VALUE']))
                        $listValue = $dataValue['VALUE'];
                    else
                        $listValue[] = $dataValue['VALUE'];
                } else {
                    $listValue[] = $dataValue;
                }
            }
        }

        if (is_array($property['PROPERTY_USER_TYPE'])) {
            $userType = $property['PROPERTY_USER_TYPE'];
        } else {
            $userType = array();
            if (!empty($property['USER_TYPE'])) {
                $userType['USER_TYPE'] = $property['USER_TYPE'];
                $createNewEntity = false;
            } else {
                return '';
            }
        }
        $userField = array(
            'ENTITY_ID' => 'BIND_CRM_ELEMENT_' . $property['IBLOCK_ID'],
            'FIELD_NAME' => $fieldName,
            'USER_TYPE_ID' => AutobrandsType::USER_TYPE_ID,
            'MULTIPLE' => $multiple,
            'MANDATORY' => $isRequired,
            'EDIT_FORM_LABEL' => $formLable,
            'VALUE' => $listValue,
            'SETTINGS' => $property['USER_TYPE_SETTINGS'],
            'USER_TYPE' => $userType
        );
        ob_start();
        $GLOBALS["APPLICATION"]->IncludeComponent(
            'bitrix:system.field.edit',
            AutobrandsType::USER_TYPE_ID,
            array(
                'arUserField' => $userField,
                'bVarsFromForm' => false,
                'form_name' => $controlSettings['FORM_NAME'],
                'createNewEntity' => $createNewEntity
            ),
            false,
            array('HIDE_ICONS' => 'Y')
        );
        $html .= ob_get_contents();
        ob_end_clean();
        return $html;
    }

    public static function getPublicViewHTML($property, $value, $controlSettings)
    {
        return static::getPublicViewHTMLMulty($property, $value, $controlSettings);
    }

    public static function getPublicViewHTMLMulty($property, $value, $controlSettings)
    {
        global $APPLICATION;

        $fieldName = !empty($controlSettings['VALUE']) ? $controlSettings['VALUE'] : '';
        $formLable = !empty($controlSettings['DESCRIPTION']) ? $controlSettings['DESCRIPTION'] : '';
        $multiple = !empty($controlSettings['MULTIPLE']) ? $controlSettings['MULTIPLE'] : $property['MULTIPLE'];
        $isRequired = !empty($property['IS_REQUIRED']) ? $property['IS_REQUIRED'] : 'N';
        $listValue = array();
        if (!empty($value['VALUE'])) {
            if (!is_array($value['VALUE']))
                $value['VALUE'] = array($value['VALUE']);
            $listValue = $value['VALUE'];
        } elseif (is_array($value)) {
            foreach ($value as $dataValue) {
                if (isset($dataValue['VALUE'])) {
                    if (is_array($dataValue['VALUE'])) {
                        $listValue = $dataValue['VALUE'];
                    } else {
                        $listValue[] = $dataValue['VALUE'];
                    }
                }
            }
        }

        if (is_array($property['PROPERTY_USER_TYPE'])) {
            $userType = $property['PROPERTY_USER_TYPE'];
        } else {
            $userType = array();
            if (!empty($property['USER_TYPE'])) {
                $userType['USER_TYPE'] = $property['USER_TYPE'];
            }
        }

        $userField = array(
            'ENTITY_ID' => 'BIND_CRM_ELEMENT_' . $property['IBLOCK_ID'],
            'FIELD_NAME' => $fieldName,
            'USER_TYPE_ID' => AutobrandsType::USER_TYPE_ID,
            'MULTIPLE' => $multiple,
            'MANDATORY' => $isRequired,
            'EDIT_FORM_LABEL' => $formLable,
            'VALUE' => $listValue,
            'SETTINGS' => is_array($property['USER_TYPE_SETTINGS']) ? $property['USER_TYPE_SETTINGS'] : [],
            'USER_TYPE' => $userType
        );
        ob_start();
        $APPLICATION->includeComponent(
            'bitrix:system.field.view',
            AutobrandsType::USER_TYPE_ID,
            array(
                'arUserField' => $userField,
                'bVarsFromForm' => false,
                'form_name' => $controlSettings['FORM_NAME']
            ),
            false,
            array('HIDE_ICONS' => 'Y')
        );
        $html = ob_get_contents();
        ob_end_clean();
        return $html;
    }

    public static function getPropertyFieldHtml($property, $value, $controlSettings)
    {
        return static::getPropertyFieldHtmlMulty($property, $value, $controlSettings);
    }

    public static function getPropertyFieldHtmlMulty($property, $value, $controlSettings)
    {
        return static::getPublicEditHTMLMulty($property, $value, $controlSettings);
    }

    public static function getAdminListViewHtml($userField, $additionalParameters)
    {
        return AutobrandsType::renderAdminListView($userField, $additionalParameters);
    }

    public static function prepareSettings($property)
    {
        if (!is_array($property['USER_TYPE_SETTINGS']))
            $property['USER_TYPE_SETTINGS'] = [];

        return $property;
    }

    public static function getSettingsHTML($property, $controlSettings, &$propertyFields)
    {
        return AutobrandsType::renderSettings($property, $controlSettings, $propertyFields);
    }

    public static function checkFields($userField, $value)
    {
        return AutobrandsType::checkFields($userField, $value);
    }

    public static function getLength($property, $value)
    {
        if (is_array($value['VALUE'])) {
            $value['VALUE'] = array_diff($value['VALUE'], array(''));
            $value['VALUE'] = implode(',', $value['VALUE']);
            return mb_strlen(trim($value['VALUE'], "\n\r\t"));
        } else {
            return mb_strlen(trim($value['VALUE'], "\n\r\t"));
        }
    }


    public static function getValuePrintable($property, array $listValue, $formatSeparator)
    {
        $result = '';

        $defaultType = '';
        if (is_array($property['USER_TYPE_SETTINGS'])) {
            foreach ($property['USER_TYPE_SETTINGS'] as $typeName => $flag) {
                if ($flag === 'Y') {
                    $defaultType = $typeName;
                    break;
                }
            }
        }
        if ($defaultType === '')
            $defaultType = 'LEAD';

        $valueView = array();
        foreach ($listValue as $value)
            static::prepareValueView($value, $defaultType, $valueView);

        foreach ($valueView as $entityType => $listEntity) {
            $result .= '[b]' . Loc::getMessage('CRM_IBLOCK_PROPERTY_ENTITY_' . $entityType) . ': [/b]';
            $result .= implode($formatSeparator, $listEntity) . ' ';
        }

        return $result;
    }

    /*
    public static function getUIFilterProperty($property, $strHTMLControlName, &$field)
    {
        $field["type"] = "entity_selector";
        $field['params'] = [
            'multiple' => 'Y',
            'dialogOptions' => [
                'multiple' => false,
                'dropdownMode' => false,
                'showAvatars' => false,
                'compactView' => false,
                'enableSearch' => true,
                'preload' => true,
                'context' => 'st_competencies',
                'entities' => [
                    [
                        'id' => 'st_competencies',
                        'options' => [
                        ],
                    ]
                ],
                'tabs' => [
                    [
                        'id' => 'st_competencies',
                        'title' => 'BR_EF_ALL_ELEMENTS',
                        "itemOrder" => [
                            'title' => 'asc'
                        ]
                    ],
                ],
            ],
        ];

        $field["filterable"] = "";
    }

    public static function GetUIEntityEditorProperty($settings, $value)
    {
        return [
            'type' => 'custom'
        ];
    }

    public static function GetUIEntityEditorPropertyViewHtml(array $params = [])
    {
        if (!empty($params['VALUE'])) {
            return static::getPublicViewHTML($params['SETTINGS'], ['VALUE' => $params['VALUE']], ['VALUE' => $params['FIELD_NAME']]);
        }

        return '';
    }

    public static function GetUIEntityEditorPropertyEditHtml(array $params = [])
    {
        if (is_array($params['VALUE'])) {
            foreach ($params['VALUE'] as $element) {
                $value[] = ['VALUE' => $element];
            }
        } else {
            $value = ['VALUE' => $params['VALUE']];
        }
        return static::getPublicEditHTML($params['SETTINGS'], $value, ['VALUE' => $params['FIELD_NAME']]);
    }

    public static function isUsePrefix(array $property)
    {
        if (is_array($property['USER_TYPE_SETTINGS'])) {
            if (array_key_exists('VISIBLE', $property['USER_TYPE_SETTINGS']))
                unset($property['USER_TYPE_SETTINGS']['VISIBLE']);
            $tmpArray = array_filter($property['USER_TYPE_SETTINGS'], function ($mark) {
                return $mark == "Y";
            });
            if (count($tmpArray) == 1) {
                return false;
            }
        }

        return true;
    }


    protected static function prepareValueView($value, $defaultType = '', array &$valueView)
    {
        $parts = explode('_', $value);
        if (count($parts) > 1) {
            $entityName = \CCrmOwnerType::getCaption(
                \CCrmOwnerType::resolveID(\CCrmOwnerTypeAbbr::resolveName($parts[0])), $parts[1], false);

            $defaultType = mb_strtolower(static::$listDefaultEntityKey[$parts[0]]);
            $entityUrl = \CComponentEngine::makePathFromTemplate(
                Option::get('crm', 'path_to_' . $defaultType . '_show'), array('' . $defaultType . '_id' => $parts[1]));

            $valueView[mb_strtoupper($defaultType)][] = '[url=' . $entityUrl . ']' . $entityName . '[/url]';
        } elseif ($defaultType !== '') {
            $entityName = \CCrmOwnerType::getCaption(
                \CCrmOwnerType::resolveID($defaultType),
                $value,
                false
            );

            $defaultType = mb_strtolower($defaultType);
            $entityUrl = \CComponentEngine::makePathFromTemplate(
                Option::get('crm', 'path_to_' . $defaultType . '_show'), array('' . $defaultType . '_id' => $value));

            $valueView[mb_strtoupper($defaultType)][] = '[url=' . $entityUrl . ']' . $entityName . '[/url]';
        }
    }*/
}