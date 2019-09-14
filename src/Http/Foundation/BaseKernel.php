<?php

namespace Elphis\Http\Foundation;

use Elphis\Utils\Container;
use Swoole\Http\Server;
use Swoole\Http\Request;
use Swoole\Http\Response;

abstract class BaseKernel
{
    protected $server;

    /**
     * creates the swoole server
     * @return \elphis\Http\BasKernel
     */
    public static function createServer()
    {
        $instance = (new static);

        $instance->server = Container::getInstance()->resolve(Server::class);

        return $instance;
    }

    /**
     * registers swoole server listeners
     *
     * @return void
     */
    protected function registerListeners()
    {
        $this->server->on('start', function (Server $server) {
            $this->onServerStart($server);
        });

        $this->server->on('request', function (Request $request, Response $response) {
            $this->onRequest($request, $response);
        });

        return $this;
    }

    public function getServer()
    {
        return $this->server;
    }

    public function startServer()
    {
        $this->registerListeners();

        return $this->server->start();
    }

    public function registerGlobals($get, $post, $files, $cookie, $server, $headers)
    {
        $_GET = $get ?? [];
        $_POST = $post ?? [];
        $_FILES = $files ?? [];
        $_COOKIE = $cookie ?? [];
        $server = \array_change_key_case($server, CASE_UPPER);
        foreach ($headers as $key => $value) {
            $server['HTTP_' . \mb_strtoupper(\str_replace('-', '_', $key))] = $value;
        }
        $_SERVER = $server;
    }
}
