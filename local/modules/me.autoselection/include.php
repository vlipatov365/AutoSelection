<?php
defined('B_PROLOG_INCLUDED') || die;

\Bitrix\Main\Loader::registerAutoLoadClasses(
    'me.autoselection',
    [
        'Me\AutoSelection\AutoselectionTable' => 'lib/autoselectiontable.php',
//        'Me\AutoSelection\Migrations\Iblock' => 'lib/migrations/iblock.php',
    ]
);