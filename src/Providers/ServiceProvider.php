<?php

namespace Luxio\Providers;

use Luxio\Contracts\ServiceProviderInterface;
use Luxio\Utils\Container;

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
