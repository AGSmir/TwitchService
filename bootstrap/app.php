<?php
declare(strict_types=1);

use App\Application;
use App\Database\DatabaseConfig;
use App\Services\AccountService;
use App\Repository\Account\AccountRepositoryInterface;
use App\Repository\Account\AccountRepository;
use App\Http\Controller\HomeController;
use App\Http\Router\Router;
use App\Database\ConnectionFactory;
use App\Container\Container;
use App\Http\Controller\AuthController;
use App\Lib\Twitch\TwitchApiClient;
use GuzzleHttp\Client;
use Dotenv\Dotenv;

require __DIR__ . '/../vendor/autoload.php';
Dotenv::createImmutable(__DIR__)->safeLoad();

$container = new Container();
$container->set(PDO::class, function () {
    $config = new DatabaseConfig(
        $_ENV['DB_HOST'],
        $_ENV['DB_NAME'],
        $_ENV['DB_USER'],
        $_ENV['DB_PASS'],
    );
    return (new ConnectionFactory())->create($config);
});

$container->set(AccountRepositoryInterface::class, function (Container $c) {
    return new AccountRepository($c->get(PDO::class));
});

$container->set(AccountService::class, function (Container $c) {
    return new AccountService($c->get(AccountRepositoryInterface::class));
});

$container->set(TwitchApiClient::class, function () {
    return new TwitchApiClient(
        $_ENV['TWITCH_CLIENT_ID'],
        $_ENV['TWITCH_CLIENT_SECRET'],
        $_ENV['TWITCH_REDIRECT_URI'],
        new Client()
    );
});

$container->set(AuthController::class, function (Container $c) {
    return new AuthController(
        $c->get(TwitchApiClient::class),
        $c->get(AccountService::class)
    );
});

$container->set(HomeController::class, function () {
    return new HomeController();
});

$container->set(Router::class, function () {
    return new Router();
});

return new Application($container);
