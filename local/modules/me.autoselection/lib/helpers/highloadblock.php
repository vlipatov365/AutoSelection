<?php

namespace Me\Autoselection\Helpers;

use Bitrix\Highloadblock\HighloadBlockTable;
use Bitrix\Main\Diag\Debug;
use Bitrix\Main\Loader;
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

    public static function getEntityDataClass($HlBlockId)
    {

    }

    public static function getFieldsMap(int $hlBlockId)
    {

    }

    public static function getDocumnetFieldsHL(int $hlBlockId)
    {

    }
}