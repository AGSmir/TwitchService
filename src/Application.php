<?php
declare(strict_types=1);

namespace App;

use App\Http\Router\Router;
use Psr\Container\ContainerInterface;

readonly class Application
{
    public function __construct(
        private ContainerInterface $container
    ) {
    }

    public function run(): void
    {
        /** @var Router $router */
        $router = $this->container->get(Router::class);
        require_once __DIR__ . '/Http/routes.php';
        $router->dispatch($_SERVER['REQUEST_METHOD'], $_SERVER['REQUEST_URI'], $this->container);
    }
}
