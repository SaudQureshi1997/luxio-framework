<?php

namespace Elphis\Utils;

use Closure;
use Psr\Container\ContainerInterface;
use Elphis\Exceptions\{ContainerException, BindingResolutionException};

class Container implements ContainerInterface
{
    protected static $instance;

    protected $bindings = [];
    protected $singletons = [];

    private function __construct()
    { }

    public static function preLoad($providers = [])
    {
        if (!self::$instance) {
            self::$instance = new self;
        }

        $providers = array_map(function ($provider) {
            return new $provider(self::$instance);
        }, $providers);

        foreach ($providers as $provider) {
            $provider->register();
        }

        foreach ($providers as $provider) {
            if (method_exists($provider, 'boot')) {
                $provider->boot();
            }
        }
    }

    public static function load()
    {
        if (!self::$instance) {
            self::$instance = new self;
        }

        $config = self::$instance->resolve(Config::class);
        $app = $config->get('app');
        $providers = [];


        foreach ($app['providers'] as $provider) {
            $providers[] = new $provider(self::$instance);
        }

        foreach ($providers as $provider) {
            $provider->register();
        }

        foreach ($providers as $provider) {
            $provider->boot();
        }

        $aliases = $app['aliases'];

        foreach ($aliases as $alias => $original) {
            class_alias($original, $alias);
        }
    }

    /**
     * get container instance
     *
     * @return return self
     */
    public static function getInstance()
    {
        return self::$instance;
    }

    /**
     * binds a service class to the container
     * 
     * @param string $label
     * @param string|Closure $service
     * @throws ContainerException
     * @return mixed
     */
    public function bind(string $label, $service)
    {
        if (!($service instanceof Closure) and (!\is_string($service) or !class_exists($service))) {
            throw new ContainerException("Service must be an instance of closure or a valid class name");
        }

        $this->bindings[$label] = $service;
    }

    /**
     * bind a singleton to the container
     *
     * @param string $label
     * @param Closure|string $singleton
     * @return void
     */
    public function singleton(string $label, $singleton)
    {

        if (!($singleton instanceof Closure) and (!\is_string($singleton) or !class_exists($singleton))) {
            throw new ContainerException("Singleton must be an instance of closure or a valid class name");
        }

        if ($singleton instanceof Closure) {
            $this->singletons[$label] = $singleton($this);
        } else {
            $this->singletons[$label] = new $singleton();
        }
    }

    /**
     * resolve service from the container
     *
     * @param string $label
     * @return mixed
     */
    public function resolve($label)
    {

        if ($service = $this->get($label)) {
            if (is_string($service)) {
                return new $service();
            }

            if ($service instanceof Closure) {
                return $service($this);
            }

            if (is_object($service) && !($service instanceof Closure)) {
                return $service;
            }
        }

        if (class_exists($label)) {
            return new $label();
        }

        throw new BindingResolutionException("$label could not be resolved", 500);
    }

    /**
     * get a service from the container by it's label
     *
     * @param string $label
     * @throws ContainerException
     * @return mixed
     */
    public function get($label)
    {
        if ($this->has($label)) {
            $services = $this->bindings + $this->singletons;

            return $services[$label];
        }
        return null;
    }

    public function has($label)
    {
        return array_key_exists($label, $this->bindings + $this->singletons);
    }
}
