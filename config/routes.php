<?php

/** @var \core\Router $router */

use app\Controllers\AdminController;
use app\Controllers\AuthController;
use app\Controllers\CartController;
use app\Controllers\HomeController;
use app\Controllers\CatalogController;
use app\Controllers\NewsController;
use app\Controllers\ReviewController;
use app\Controllers\UserController;
use app\Controllers\ProductController;

$router->get('/', [HomeController::class, 'index']);

$router->get('/about', [HomeController::class, 'about']);

$router->get('/catalog', [CatalogController::class, 'index']);
$router->get('/catalog/search', [CatalogController::class, 'search']);
$router->get('/catalog/{slug}', [CatalogController::class, 'show']);

// Кошик
$router->get('/cart', [CartController::class, 'index']);
$router->post('/cart/add', [CartController::class, 'add']);
$router->post('/cart/update', [CartController::class, 'update']);
$router->post('/cart/remove', [CartController::class, 'remove']);
$router->post('/cart/clear', [CartController::class, 'clear']);
$router->get('/cart/count', [\app\Controllers\CartController::class, 'count']);

//login,register
$router->get('/login', [AuthController::class, 'loginForm']);
$router->post('/login', [AuthController::class, 'login']);
$router->get('/register', [AuthController::class, 'registerForm']);
$router->post('/register', [AuthController::class, 'register']);
$router->get('/logout', [AuthController::class, 'logout']);

$router->get('/profile', [\app\Controllers\ProfileController::class, 'index']);
$router->post('/profile/update', [\app\Controllers\ProfileController::class, 'update']);

//Публічні
$router->get('/news', [NewsController::class, 'index']);
$router->get('/news/{slug}', [NewsController::class, 'show']);

//Адмін - новини
$router->get('/admin/news', [NewsController::class, 'adminIndex']);
$router->get('/admin/news/create', [NewsController::class, 'create']);
$router->post('/admin/news/store', [NewsController::class, 'store']);
$router->get('/admin/news/{id}/edit', [NewsController::class, 'edit']);
$router->post('/admin/news/{id}/update', [NewsController::class, 'update']);
$router->post('/admin/news/{id}/delete', [NewsController::class, 'delete']);

// Відгуки
$router->post('/catalog/{slug}/reviews', [ReviewController::class, 'store']);
$router->get('/admin/reviews', [ReviewController::class, 'adminIndex']);
$router->post('/admin/reviews/{id}/approve', [ReviewController::class, 'approve']);
$router->post('/admin/reviews/{id}/delete', [ReviewController::class, 'delete']);
$router->post('/admin/reviews/{id}/reply', [ReviewController::class, 'reply']);

$router->get('/admin', [AdminController::class, 'dashboard']);

$router->get('/admin/users', [UserController::class, 'adminIndex']);
$router->post('/admin/users/{id}/ban', [UserController::class, 'ban']);
$router->post('/admin/users/{id}/unban', [UserController::class, 'unban']);
$router->post('/admin/users/{id}/delete', [UserController::class, 'delete']);

$router->get('/admin/products', [ProductController::class, 'index']);
$router->get('/admin/products/create', [ProductController::class, 'create']);
$router->post('/admin/products/store', [ProductController::class, 'store']);
$router->get('/admin/products/{id}/edit', [ProductController::class, 'edit']);
$router->post('/admin/products/{id}/update', [ProductController::class, 'update']);
$router->post('/admin/products/{id}/delete', [ProductController::class, 'delete']);

