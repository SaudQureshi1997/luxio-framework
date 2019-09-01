<?php

namespace Luxio\Providers;

use Luxio\Utils\Config;

class ConfigServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->app->singleton(Config::class, function () {
            return new Config(
                base_path('config')
            );
        });
    }
}
