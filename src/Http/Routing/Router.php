<?php

namespace Elphis\Http\Routing;

use FastRoute\Dispatcher;
use FastRoute\RouteCollector;
use Elphis\Utils\DependencyResolver;

/**
 * Custom Router class working around nikic/fastroute
 */
class Router
{
    protected $dispatcher;

    public function __construct()
    {
        $this->dispatcher =  \FastRoute\simpleDispatcher(function (RouteCollector $router) {
            require_once(\base_path('routes.php'));
        });
    }

    /**
     * handles the given request uri and acts accordingly
     *
     * @param string $request_method
     * @param string $request_uri
     * @return void
     */
    public function handleRequest(string $request_method, string $request_uri)
    {
        $dispatched = $this->dispatcher->dispatch($request_method, $request_uri);

        switch ($dispatched[0]) {
            case Dispatcher::NOT_FOUND:
                $result = [
                    'status' => 404,
                    'message' => 'Not Found',
                    'errors' => [
                        sprintf('The URI "%s" was not found', $request_uri)
                    ]
                ];
                break;
            case Dispatcher::METHOD_NOT_ALLOWED:
                $allowedMethods = $dispatched[1];
                $result = [
                    'status' => 405,
                    'message' => 'Method Not Allowed',
                    'errors' => [
                        sprintf('Method "%s" is not allowed', $request_method)
                    ]
                ];
                break;
            case Dispatcher::FOUND:
                $result = call_user_func_array([$this, 'handle'], [$dispatched[1], $dispatched[2]]);
                break;
        }
        return $result;
    }

    /**
     * triggers the given request's callback
     *
     * @param string|Closure $route_handler
     * @param array $routeParams
     * @return void
     */
    public function handle($route_handler, $routeParams)
    {
        if (is_string($route_handler) && substr_count($route_handler, '@') === 1) {

            [$class, $method] = explode('@', $route_handler);

            $class = '\\App\\Controllers\\Http\\' . $class;

            if ($params = DependencyResolver::resolveClass($class)) {
                $controller_object = new $class(...$params);
            } else {
                $controller_object = new $class();
            }

            if ($params = DependencyResolver::resolveMethod($class, $method, $routeParams)) {
                return \call_user_func_array([$controller_object, $method], $params);
            }

            return call_user_func([$controller_object, $method]);
        } elseif ($route_handler instanceof Closure) {

            return call_user_func($route_handler);
        }

        return $route_handler;
    }
}
