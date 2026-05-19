<?php

namespace Araecy\Framework\Http;

use Araecy\Framework\Controllers\AbstractController;
use Araecy\Framework\Database\Connection;
use FastRoute\RouteCollector;
use function FastRoute\simpleDispatcher;

class Kernel
{
    protected ?Connection $connection = null;
    public function __construct() {
        $config = include BASE_PATH . '/database/config.php';

        $this->connection = Connection::create($config['connectionString'], $config['username'], $config['password']);
    }
    public function handle(Request $request): Response
    {
        $dispatcher = simpleDispatcher(function (RouteCollector $routeCollector) {
            $routes = include BASE_PATH . '/routes/web.php';

            foreach ($routes as $route) {
                $routeCollector->addRoute(...$route);
            }
        });

        $routeInfo = $dispatcher->dispatch(
            $request->getMethod(),
            $request->getUri()
        );

        $status = $routeInfo[0];

        if ($status === \FastRoute\Dispatcher::NOT_FOUND) {
            return new Response('404 Not Found', 404);
        }

        if ($status === \FastRoute\Dispatcher::METHOD_NOT_ALLOWED) {
            return new Response('405 Method Not Allowed', 405);
        }

        [, [$controller, $method], $vars] = $routeInfo;

        $controller = new $controller;

        if ($controller instanceof AbstractController) {
            $controller->setRequest($request);
        }

        return call_user_func_array([$controller, $method], array_values($vars));
    }
}