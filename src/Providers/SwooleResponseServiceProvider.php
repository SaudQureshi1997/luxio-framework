<?php

namespace elphis\Providers;

use Swoole\Http\Request;
use Swoole\Http\Response;

class SwooleResponseServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->app->bind(Response::class, function ($app) {
            $request = $app->resolve(Request::class);

            return Response::create($request->fd);
        });
    }
}
