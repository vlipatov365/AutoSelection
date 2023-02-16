<?php

namespace Me\AutoSelection\Handlers;

use Bitrix\Main\UI\Extension;
use Bitrix\Main\Context;
class BuildOnBeforeProlog
{
    public static function bootstrapOn()
    {
        $request = Context::getCurrent()->getRequest();
        if (!$request->isAdminSection())
            Extension::load('ui.bootstrap4');
    }
}