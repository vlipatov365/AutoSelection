<?php

namespace Me\AutoSelection\Helpers;

use Bitrix\Iblock\IblockTable;
use Bitrix\Main\SystemException;
use Me\AutoSelection\Helper;

class Iblock extends Helper
{
    public static function getIblock(
        $filter = [],
        $select = []
    ): array
    {
        $res = IblockTable::getRow(
            [
                'filter' => $filter,
                'select' => $select
            ]
        );
        if (isset($res) && !empty($res))
            return $res;
        return [];
    }
    public static function createIblock($fields)
    {
        $ob = new \CIBlock();
        return $ob->Add($fields);
    }

    public static function deleteIblock($existId)
    {
        $ob = new \CIBlock();
        return $ob::Delete($existId);
    }

    public static function getIblockId(
        $filter = []
    ) : int
    {
        $arIblockId = self::getIblock(
            $filter,
            ['ID']
        );
        $iBlockId = (int) $arIblockId['ID'];
        return $iBlockId;
    }

    public static function getEntity(int $iBlockId) :object
    {
        if (empty($iBlockId) || $iBlockId < 1) {
            throw new SystemException("Справочник не найден");
        }
        $iBlock = IblockTable::getById($iBlockId)->fetch();
        $entity = IblockTable::compileEntity('meAutoSelection');
        return $entity;
    }

    public static function getEntityDataClass(int $iBlockId)
    {
        return self::getEntity($iBlockId)->getDataClass();
    }
    public static function getFieldsMap(int $iBlockId)
    {
        return self::getEntity($iBlockId)->getFields();
    }
}