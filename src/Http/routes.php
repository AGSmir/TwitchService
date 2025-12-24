<?php

use App\Http\Controller\AuthController;
use App\Http\Controller\HomeController;
use App\Http\Router\Router;

/** @var Router $router */
$router->get('/', [HomeController::class, 'index']);
$router->get('/auth/login', [AuthController::class, 'login']);
$router->get('/auth/callback', [AuthController::class, 'callback']);
$router->get('/logout', function () {
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }
    session_destroy();
    header('Location: /');
    exit;
});
