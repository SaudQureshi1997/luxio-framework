<?php

namespace elphis\Support\Facades;

use elphis\Utils\Config as ConfigCore;

class Config extends Facade
{
    public static function getFacadeAccessor()
    {
        return ConfigCore::class;
    }
}
