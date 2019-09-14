<?php

namespace elphis\Providers;

use elphis\Utils\Config;

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
