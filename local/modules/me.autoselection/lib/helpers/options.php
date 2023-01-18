<?php

namespace Me\AutoSelection\Helpers;

use Bitrix\Dav\Application;
use Bitrix\Main\Config\Option;

class Options
{
    const moduleId = 'me.autoselection';

    public static function getModuleId()
    {
        return self::moduleId;
    }
    /**
     * Получить путь к модулю
     * @param bool $absolute
     * @return string
     */
    public static function getModuleDir(bool $absolute=false)
    {
        if ($absolute) {
            return str_replace('lib/helpers', '', __DIR__);
        }

        return str_replace([Application::getDocumntRoot(), 'lib/helpers'], "", __DIR__);
    }

    public static function getParam($code, $default = null)
    {
        return trim(Option::get(self::getModuleId(), $code, $default));
    }

    public static function setParam($code, $value, $default = null)
    {
        Option::set(self::getModuleId(), $code, $value, $default);
    }
}