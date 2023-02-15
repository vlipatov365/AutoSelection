<?php

namespace Me\AutoSelection\Handlers;

use Bitrix\Main\UI\Extension;

class BuildOnBeforeProlog
{
    public static function bootstrapOn()
    {
        $request = \Bitrix\Main\Context::getCurrent()->getRequest();
        if (!$request->isAdminSection())
            Extension::load('ui.bootstrap4');
    }
}