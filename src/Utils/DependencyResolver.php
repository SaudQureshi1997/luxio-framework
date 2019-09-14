<?php

namespace Elphis\Utils;

use ReflectionMethod;
use ReflectionClass;
use Elphis\Exceptions\BindingResolutionException;

class DependencyResolver
{
    /**
     * resolve class constructor dependencies and returns it
     * in case of facades the it resolves the underlying class that facade proxies to.
     *
     * @param string $class
     * @throws BindingResolutionException
     * @return array|null
     */
    public static function resolveClass(string $class, $params = null)
    {
        $method = (new ReflectionClass($class))->getConstructor();

        if ($method) {
            $resolved = [];

            if (!($constructorParams = $method->getParameters())) return null;

            foreach ($constructorParams as $param) {
                if ($class = $param->getClass()) {
                    $resolved[] = Container::getInstance()->resolve($class->getName());
                } else {
                    if ($params) {
                        $resolved[] = $params[$param->getName()];
                    }
                }
            }

            return $resolved;
        }

        return null;
    }

    /**
     * resolves class method dependencies
     *
     * @param string $class
     * @param string $method
     * @param array|null $params
     * @throws BindingResolutionException|BadMethodEception
     * @return array|null
     */
    public static function resolveMethod(string $class, string $method, $params = null)
    {
        if (!class_exists($class)) {
            throw new BindingResolutionException("$class does not exist", 500);
        }

        $method = new ReflectionMethod($class, $method);

        $resolved = [];
        if (!($params = $method->getParameters())) return null;

        foreach ($params as $param) {
            if ($class = $param->getClass()) {
                $resolved[] = Container::getInstance()->resolve($class->getName());
            } else {
                if ($params) {
                    $resolved[] = $params[$param->getName()];
                }
            }
        }

        return $resolved;
    }
}
