<?php

namespace Me\AutoSelection\Helpers;

use Bitrix\Iblock\IblockTable;
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
}