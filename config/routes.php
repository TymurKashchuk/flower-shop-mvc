<?php

/** @var \core\Router $router */

use app\Controllers\CartController;
use app\Controllers\HomeController;
use app\Controllers\CatalogController;

$router->get('/', [HomeController::class, 'index']);

$router->get('/catalog', [CatalogController::class, 'index']);
$router->get('/catalog/search', [CatalogController::class, 'search']);
$router->get('/catalog/{slug}', [CatalogController::class, 'show']);

// Кошик
$router->get('/cart', [CartController::class, 'index']);
$router->post('/cart/add', [CartController::class, 'add']);
$router->post('/cart/update', [CartController::class, 'update']);
$router->post('/cart/remove', [CartController::class, 'remove']);
$router->post('/cart/clear', [CartController::class, 'clear']);