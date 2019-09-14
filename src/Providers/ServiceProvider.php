<?php

namespace Elphis\Providers;

use Elphis\Contracts\ServiceProviderInterface;
use Elphis\Utils\Container;

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
