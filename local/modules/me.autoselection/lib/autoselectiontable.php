<?php

namespace Me\AutoSelection;

use Bitrix\Main\Localization\Loc;
use Bitrix\Main\ORM\Data\DataManager;
use Bitrix\Main\ORM\Fields;

class AutoselectionTable extends DataManager
{
    public static function getTableName()
    {
        return 'me_autoselection';
    }

    public static function getMap()
    {
        return [
            (new Fields\IntegerField('ID'))
                ->configurePrimary()
                ->configureAutoComplete()
                ->configureTitle(Loc::getMessage('AS_ID')),
            (new Fields\StringField('BRAND'))
                ->configureRequired()
                ->configureSize(255)
                ->configureTitle('AS_BRAND'),
            (new Fields\EnumField('CONDITION'))
                ->configureRequired()
                ->configureValues(['used', 'new'])
                ->configureTitle(Loc::getMessage('AS_CONDITION')),
            (new Fields\IntegerField('YEAR'))
                ->configureRequired()
                ->configureTitle(Loc::getMessage('AS_YEAR')),
            (new Fields\IntegerField('PRICE'))
                ->configureRequired()
                ->configureTitle(Loc::getMessage('AS_PRICE')),
            (new Fields\BooleanField('RAIN_SENSOR'))
                ->configureRequired()
                ->configureTitle(Loc::getMessage('AS_RAIN_SENSOR'))
        ];
    }
    //TODO сделать валидаторы для полей CONDITION, YEAR
}