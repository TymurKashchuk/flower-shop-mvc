<?php

/** @var \core\Router $router */

use app\Controllers\HomeController;
use app\Controllers\CatalogController;

$router->get('/', [HomeController::class, 'index']);

$router->get('/catalog', [CatalogController::class, 'index']);
$router->get('/catalog/search', [CatalogController::class, 'search']);
$router->get('/catalog/{slug}', [CatalogController::class, 'show']);