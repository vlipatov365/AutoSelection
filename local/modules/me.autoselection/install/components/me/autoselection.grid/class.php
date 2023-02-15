<?php
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();

use Bitrix\Main\Loader;
use Bitrix\Main\Localization\Loc;
use Bitrix\Main\Grid\Panel\Snippet;
use Bitrix\Main\Engine\Contract\Controllerable;
use Bitrix\UI\Toolbar\Facade\Toolbar;
use Me\AutoSelection\Helpers;

class AutoselectionComponent extends CBitrixComponent
    implements Controllerable
{
    const GRID_ID = 'me_autoselection_list';
    const EDIT_PATH = '/me/autoselection.php';
    const IBLOCK_CODE = 'me_autoselection';
    const IBLOCK_API_CODE = 'meAutoSelection';
    const HIGHLOADBLOCK_NAME = 'MeAutoSelectionBrands';

    var $id;
    var $elements = [];
    var $title;

    public function checkModules()
    {
        if (!Loader::includeModule('me.autoselection')) {
            throw new \Bitrix\Main\SystemException('Module is not installed');
            return false;
        } else {
            return true;
        }

    }


    public function executeComponent()
    {
        global $APPLICATION;
        if (!$this->checkModules())
            die();
        $this->arResult['GRID_ID'] = self::GRID_ID;
        $this->arResult['FILTER_ID'] = self::GRID_ID;
        $this->makeFilter();
        $this->makeToolbar();
        $this->makeGrid();
        // Параметра для панели слайдера
        $this->arResult['SIDE_PANEL_PARAMS'] = [
            "newWindowsLabel" => Helpers\Options::getParam('SUR_SLIDER_NEW_WINDOWS') == 'Y',
            "copyLinkLabel" => Helpers\Options::getParam('SUR_SLIDER_COPY_LINK') == 'Y',
            "width" => ((int)Helpers\Options::getParam('SUR_SLIDER_COPY_LINK')) ?: 700,
            "cacheable" => false
        ];
        $APPLICATION->SetTitle('Автомобили');

        $this->includeComponentTemplate();
    }

    public function getProperty()
    {
        $properties = [];
        $iBlockId = Helpers\Iblock::getIblockId(['CODE' => self::IBLOCK_CODE]);
        $result = CIBlockElement::GetProperty(
            $iBlockId,
            []
        );
        while ($property = $result->Fetch()) {
            $properties[] = [
                'id' => $property['ID'],
                'name' => $property['NAME'],
                'code' => $property['CODE'],
                'sort' => $property['SORT']
            ];
        }
        return $properties;
    }

    public function makeSelect()
    {
        $arSelect = [];
        $properties = $this->getProperty();
        foreach ($properties as $property) {
            $arSelect[] = 'PROPERTY_' . $property['id'];
        }
        $arSelect = array_merge($arSelect, ['ID']);
        return $arSelect;
    }

    public function makeFilter()
    {
        $filterItem = [];
        $fieldsMap = $this->getProperty();
        foreach ($fieldsMap as $field) {
            $filterItem = [
                'id' => 'PROPERTY_' . $field['id'],
                'name' => $field['name'],
                'code' => $field['code'],
                'default' => 1
            ];
            switch ($filterItem['code']) {
                case 'BRAND':
                    $filterItem['type'] = 'list';
                    $filterItem['items'] = $this->getBrands();
                    $filterItem['params'] = ['multiple' => 'Y'];
                    break;
                case 'CONDITION':
                    $filterItem['type'] = 'list';
                    $filterItem['items'] = ['Поддержанный' => 'Поддержанный', 'Новое' => 'Новое'];
                    $filterItem['params'] = ['multiple' => 'N'];
                    break;
                case 'YEAR':
                    $filterItem['type'] = 'number';
                    break;
                case 'PRICE':
                    $filterItem['type'] = 'number';
                    break;
                case 'RAINSENS':
                    $filterItem['type'] = 'list';
                    $filterItem['items'] = ['Да' => 'Да', 'Нет' => 'Нет'];
                    $filterItem['params'] = ['multiple' => 'N'];
                    break;
            };
            $this->arResult['FILTER'][] = $filterItem;
            $this->arResult['FILEDS_MAP'][] = $filterItem;
        }
    }

    public function makeGrid()
    {
        $this->arResult['GRID_ID'] = self::GRID_ID;

        //количестов элементов на страниц
        $this->arResult['GRID_PAGE_SIZES'] = self::getPageSizes();

        //Заголовкии
        $this->arResult['HEADERS'] = $this->prepareHeaders();

        //region фильтр
        $filter = [];
        foreach ($this->arResult['FILTER'] as $arFilter) {
            $filterable[$arFilter['id']] = $arFilter['filterable'];
        }
        $filterOption = new Bitrix\Main\UI\Filter\Options(self::GRID_ID);
        $filterData = $filterOption->getFilter($this->arResult["FILTER"]);
        foreach ($filterData as $key => $value) {
            if (is_array($value)) {
                if (empty($value))
                    continue;
            } elseif ($value == '')
                continue;
            if (mb_substr($key, -5) == "_from") {
                $new_key = mb_substr($key, 0, -5);
                $op = (!empty($filterData[$new_key . "_numsel"]) && $filterData[$new_key . "_numsel"] == "more") ? ">" : ">=";

            } elseif (mb_substr($key, -3) == "_to") {
                $new_key = mb_substr($key, 0, -3);
                $op = (!empty($filterData[$new_key . "_numsel"]) && $filterData[$new_key . "_numsel"] == "less") ? "<" : "<=";
            } else {
                $op = "";
                $new_key = $key;
            }

            if (array_key_exists($new_key, $filterable)) {
                if ($op == "")
                    $op = $filterable[$new_key];
                $filter[$op . $new_key] = $value;
            }

            if ($key == "FIND" && trim($value)) {
                $op = "*";
                $arFilter[$op . "SEARCHABLE_CONTENT"] = $value;
            }
        }
        $IBlockID = ['IBLOCK_ID' => Helpers\Iblock::getIblockId(['CODE' => self::IBLOCK_CODE])];
        $arFilter = array_merge($IBlockID, $filter);
        //endregion  фильтр

        //Объект грида
        $gridOptions = new Bitrix\Main\Grid\Options(self::GRID_ID);
        //Параметры постраничной навигации
        $navParams = $gridOptions->GetNavParams();
        $nav = new Bitrix\Main\UI\PageNavigation(self::GRID_ID);

        //Настрйоки навигации
        $nav->allowAllRecords(true)
            ->setPageSize($navParams['nPageSize'])
            ->initFromUri();

        //Сортировка
        $gridSort = $gridOptions->GetSorting(["sort" => ["SORT" => "ASC"]]);
        $this->arResult['SORT'] = $gridSort['sort'];
        $this->arResult['SORT_VARS'] = $gridSort['vars'];

        //region Обработка данных
        $autoSelectionRows = CIBlockElement::GetList(
            $this->arResult['SORT'],
            $arFilter,
            false,
            false,
            $this->makeSelect()
        );
        $sidePanelOptions = CUtil::PhpToJSObject($this->arResult['SIDE_PANEL_PARAMS'], false, false, true);
        //Подготовка строк
        while ($autoSelectionRow = $autoSelectionRows->fetch()) {
            $row['data'] = [
                'id' => $autoSelectionRow['ID'],
                'actions' => [
                    [
                        'text' => 'Посмотреть',
                        'onclick' => 'BX.SidePanel.Instance.open("' . self::EDIT_PATH . '?ID=' . $autoSelectionRow['ID'] . '",' . $sidePanelOptions . ');',
                        'default' => true
                    ],
                    [
                        'text' => 'Редактирование',
                        'onclick' => 'BX.SidePanel.Instance.open("' . self::EDIT_PATH . '?ID=' . $autoSelectionRow['ID'] . '&init_mode=edit",' . $sidePanelOptions . ');',
                    ]
                ]
            ];
            //Подготовка полей для вывода
            foreach ($this->arResult['FILEDS_MAP'] as $field) {
                $row['data'][$field['id']] = $autoSelectionRow[$field['id'] . '_VALUE'];
            }
            $this->arResult['ROWS'][] = $row;
        }
        //endregion Обработка данных
    }

    /**
     * Тулбар в шапке
     */
    public function makeToolbar()
    {
        Toolbar::addFilter([
            "FILTER_ID" => $this->arResult["GRID_ID"],
            "GRID_ID" => $this->arResult["GRID_ID"],
            "FILTER" => $this->arResult["FILTER"],
            "ENABLE_LABEL" => true,
            "ENABLE_LIVE_SEARCH" => true
        ]);
    }

    public function prepareHeaders()
    {
        $headers = [];
        $properties = $this->getProperty();
        foreach ($properties as $property) {
            $headers[] = [
                'id' => 'PROPERTY_' . $property['id'],
                'name' => $property['name'],
                'sort' => $property['sort'],
                'default' => 1
            ];
        }
        return $headers;
    }

    /**
     * Формирвоания массива с количеством элементов на странице
     * @return string[][]
     */
    public static function getPageSizes(): array
    {
        return [
            ['NAME' => "5", 'VALUE' => '5'],
            ['NAME' => '10', 'VALUE' => '10'],
            ['NAME' => '20', 'VALUE' => '20'],
            ['NAME' => '50', 'VALUE' => '50'],
            ['NAME' => '100', 'VALUE' => '100']
        ];
    }

    public function getBrands()
    {
        $hlBlockId = Helpers\HighloadBlock::getHlBlockId(['NAME' => self::HIGHLOADBLOCK_NAME]);

        $hlBLockClass = \Me\Autoselection\Helpers\HighloadBlock::getEntityDataClass($hlBlockId);
        $hlBLockList = $hlBLockClass::GetList();
        while ($el = $hlBLockList->Fetch()) {
            $arBrands[$el['UF_BRANDNAME']] = $el['UF_BRANDNAME'];
        }
        if (isset($arBrands) && !empty($arBrands))
            return $arBrands;
    }

    public function configureActions()
    {
        return [];
    }
}