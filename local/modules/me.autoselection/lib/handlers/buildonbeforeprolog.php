<?php

namespace Me\AutoSelection\Handlers;

use Bitrix\Main\Web\DOM\Document;
use \Bitrix\Main\Page\Asset;
use Bitrix\Main\UI\Extension;

class BuildOnBeforeProlog
{
    public static function addHeaderButton()
    {
        Extension::load('ui.bootstrap4');
        $asset = Asset::getInstance();
        $asset->addJs('/local/components/me/autoselection/scripts/headerbutton/script.js', true);
        echo 'Kkkkkkkkk';
    }
}