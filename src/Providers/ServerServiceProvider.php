<?php

namespace Elphis\Providers;

use Elphis\Providers\ServiceProvider;
use Elphis\Utils\Config;
use Swoole\Http\Server;

class ServerServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->app->singleton(Server::class, function ($app) {
            $config = $app->resolve(Config::class);
            $server = new Server($config->get('app.host'), $config->get('app.port'));

            $server->set(
                $config->get('server')
            );

            return $server;
        });
    }
}
