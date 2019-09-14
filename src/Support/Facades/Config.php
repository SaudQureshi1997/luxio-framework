<?php

namespace Elphis\Support\Facades;

use Elphis\Utils\Config as ConfigCore;

class Config extends Facade
{
    public static function getFacadeAccessor()
    {
        return ConfigCore::class;
    }
}
