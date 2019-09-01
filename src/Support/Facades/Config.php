<?php

namespace Luxio\Support\Facades;

use Luxio\Utils\Config as ConfigCore;

class Config extends Facade
{
    public static function getFacadeAccessor()
    {
        return ConfigCore::class;
    }
}
