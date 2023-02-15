<?php

namespace Me\Autoselection\Helpers;

use Bitrix\Highloadblock\HighloadBlockTable;
use Bitrix\Main\Diag\Debug;
use Bitrix\Main\Loader;
use Bitrix\Main\SystemException;
use Me\AutoSelection\Helper;

class HighloadBlock extends Helper
{
    public static function getHlblock(
        $filter = [],
        $select = []
    )
    {
        $res = HighloadBlockTable::getRow([
            'filter' => $filter,
            'select' => $select
        ]);
        if (isset($res) && !empty($res))
            return $res;
        else
            return [];

    }

    public static function getHlBlockId(
        $filter = []
    )
    {
        $res = HighloadBlockTable::getRow([
            'filter' => $filter,
            'select' => ['ID']
        ]);
        if (isset($res) && !empty($res))
            return intval($res['ID']);
        else
            throw new SystemException('Highloadblock не найден');

    }

    public static function getHlblocks(
        $filter = [],
        $select = [],
        $order = [],
        $runtime = null,
        $limit = null,
        $offset = null,
        $group = []
    )
    {
        $res = HighloadBlockTable::getList([
            'filter' => $filter,
            'select' => $select,
        ]);
        if (isset($res) && !empty($res)) {
            return $res;
        } else {
            return [];
        }
    }

    public static function getEntityDataClass(int $hlBlockId)
    {
        return self::getEntity($hlBlockId)->getDataClass();
    }

    public static function getEntity(int $hlBlockId): object
    {
        if (empty($hlBlockId) || $hlBlockId < 1) {
            throw new SystemException("Справочник не найден");
        }
        $hlBlock = HighloadBlockTable::getById($hlBlockId)->fetch();
        $entity = HighloadBlockTable::compileEntity($hlBlock);
        return $entity;
    }

    public static function getFieldsMap(int $hlBlockId)
    {
        return self::getEntity($hlBlockId)->getFields();
    }
}