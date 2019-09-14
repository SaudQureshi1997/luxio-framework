<?php

namespace elphis\Support\Facades;

use elphis\Utils\Container;
use BadMethodCallException;
use RuntimeException;

abstract class Facade
{
    abstract public static function getFacadeAccessor();

    public static function __callStatic($name, $arguments)
    {
        $accessor = Container::getInstance()->resolve(static::getFacadeAccessor());
        if (!method_exists($accessor, $name)) {
            throw new BadMethodCallException("$name doesn't exist on " . get_class($accessor));
        }

        return \call_user_func_array([$accessor, $name], $arguments);
    }

    public function __call($name, $arguments)
    {
        $accessor = Container::getInstance()->resolve(static::getFacadeAccessor());
        if (!method_exists($accessor, $name)) {
            throw new BadMethodCallException("$name doesn't exist on " . get_class($accessor));
        }

        return \call_user_func_array([$accessor, $name], $arguments);
    }

    public function __get($name)
    {
        $accessor = Container::getInstance()->resolve(static::getFacadeAccessor());
        if (!\property_exists($accessor, $name)) {
            throw new RuntimeException("$name does not exist on class " . static::getFacadeAccessor());
        }

        return $accessor->$name;
    }

    public function __set($name, $value)
    {
        $accessor = Container::getInstance()->resolve(static::getFacadeAccessor());
        if (!\property_exists($accessor, $name)) {
            throw new RuntimeException("$name does not exist on class " . static::getFacadeAccessor());
        }

        $accessor->$name = $value;
    }
}
