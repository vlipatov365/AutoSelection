<?php

if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();

use \Bitrix\Main\Component\BaseUfComponent;
use Me\AutoSelection\UserFields\Type\AutobrandsType;

class AutobrandsUfComponent extends BaseUfComponent
{

    /**
     * @inheritDoc
     */
    protected static function getUserTypeId(): string
    {
        return AutobrandsType::USER_TYPE_ID;
    }
}