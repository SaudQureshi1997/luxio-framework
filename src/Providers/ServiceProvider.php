<?php

namespace elphis\Providers;

use elphis\Contracts\ServiceProviderInterface;
use elphis\Utils\Container;

abstract class ServiceProvider implements ServiceProviderInterface
{
    protected $app;

    public function __construct(Container $container)
    {
        $this->app = $container;
    }

    public function boot()
    { }
}
