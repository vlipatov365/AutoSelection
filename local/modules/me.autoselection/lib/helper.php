<?php

namespace Me\AutoSelection;

use Bitrix\Main\Loader;
class Helper
{

    public static function initModules(array $modules)
    {
        if (!empty($modules)) {
            foreach ($modules as $module) {
                if (!Loader::includeModule($module)) {
                    throw new SystemException('module' . $module . ' not installed');
                }
            }
        }
    }

    public static function getModuleId()
    {
        return 'me.autoselection';
    }
}