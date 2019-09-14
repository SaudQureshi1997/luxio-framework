<?php

namespace Elphis\Http\Foundation;

use Elphis\Utils\Container;
use Swoole\Http\{Server, Request, Response};
use Elphis\Http\{Routing\Router, Foundation\BaseKernel};
use Config;
use Elphis\Utils\Logger;

final class Kernel extends BaseKernel
{

    protected function onServerStart(Server $server)
    {
        echo sprintf('Swoole http server is started at http://%s:%s' . PHP_EOL, Config::get('app.host_name'), Config::get('app.port'));
    }

    protected function onRequest(Request $request, Response $response)
    {
        $this->registerGlobals(
            $request->get,
            $request->post,
            $request->files,
            $request->cookie,
            $request->server,
            $request->header,
            $request->rawContent(),
        );

        $container = Container::getInstance();

        $container->bind(get_class($request), function ($app) use ($request) {
            return $request;
        });

        $router = $container->resolve(Router::class);

        try {

            $router->handleRequest($_SERVER['REQUEST_METHOD'], $_SERVER['REQUEST_URI']);
        } catch (\Throwable $th) {

            $response->status($th->getCode());
            $response->header('Content-Type', 'application/json');

            $error['message'] = is_json($th->getMessage()) ? json_decode($th->getMessage()) : $th->getMessage();

            return $response->end(json_encode($error));
        }
    }
}
