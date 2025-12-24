<?php
declare(strict_types=1);

use App\Application;
use App\Application\Account\UseCase\TwitchAuth;
use App\Domain\Account\AccountRepositoryInterface;
use App\Http\Router\Router;
use App\Infrastructure\Database\ConnectionFactory;
use App\Infrastructure\Persistence\MySQL\Account\AccountRepository;
use App\Shared\Container\Container;
use App\Http\Controller\AuthController;
use App\Infrastructure\Twitch\TwitchApiClient;
use GuzzleHttp\Client;

require __DIR__ . '/../vendor/autoload.php';

define('DB_CONFIG', require __DIR__ . '/../src/config/database.php');

$container = new Container();

$container->set(PDO::class, function () {
    return (new ConnectionFactory())->create();
});

$container->set(AccountRepositoryInterface::class, function (Container $c) {
    return new AccountRepository($c->get(PDO::class));
});

$container->set(TwitchAuth::class, function (Container $c) {
    return new TwitchAuth($c->get(AccountRepositoryInterface::class));
});

$container->set(TwitchApiClient::class, function (Container $c) {
    $config = require __DIR__ . '/../src/config/twitch.php';
    return new TwitchApiClient(
        $config['clientId'],
        $config['clientSecret'],
        $config['redirectUri'],
        new Client()
    );
});

$container->set(AuthController::class, function (Container $c) {
    return new AuthController(
        $c->get(TwitchApiClient::class),
        $c->get(TwitchAuth::class)
    );
});

$container->set(Router::class, function () {
    return new Router();
});

return new Application($container);
