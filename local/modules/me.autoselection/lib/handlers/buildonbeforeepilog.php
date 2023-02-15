<?php

namespace Me\AutoSelection\Handlers;

use Bitrix\Main\Page\Asset;

class BuildOnBeforeEpilog
{
    public static function addHeaderButton()
    {
        $asset = Asset::getInstance();
        $asset->addJs('/local/components/me/autoselection.grid/script.js', true);
    }
}