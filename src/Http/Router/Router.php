<?php
declare(strict_types=1);

namespace App\Http\Router;

use Psr\Container\ContainerInterface;

class Router
{
    private array $routes = [];

    public function get(string $path, callable|array $handler): void
    {
        $this->addRoute('GET', $path, $handler);
    }

    public function post(string $path, callable|array $handler): void
    {
        $this->addRoute('POST', $path, $handler);
    }

    private function addRoute(string $method, string $path, callable|array $handler): void
    {
        $this->routes[$method][$path] = $handler;
    }

    public function dispatch(string $method, string $uri, ContainerInterface $container): void
    {
        $path = parse_url($uri, PHP_URL_PATH);

        if (false !== $pos = strpos($path, '?')) {
            $path = substr($path, 0, $pos);
        }

        $handler = $this->routes[$method][$path] ?? null;

        if (!$handler) {
            http_response_code(404);
            echo "404 Not Found";
            return;
        }

        if (is_array($handler)) {
            [$controllerClass, $methodName] = $handler;
            $controller = $container->get($controllerClass);
            $controller->$methodName();
        } elseif (is_callable($handler)) {
            call_user_func($handler);
        }
    }
}
