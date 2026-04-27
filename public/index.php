<?php

declare(strict_types=1);
session_start();

define('ROOT_PATH', dirname(__DIR__));
define('APP_PATH', ROOT_PATH . '/app');
define('CACHE_PATH', ROOT_PATH . '/storage/cache');
define('LOG_PATH', ROOT_PATH . '/storage/logs');
define('BASE_URL', 'http://coursework.local');
define('CACHE_TTL', 3600);

spl_autoload_register(function (string $class): void {
    $file = ROOT_PATH . '/' . str_replace('\\', '/', $class) . '.php';

    if (file_exists($file)) {
        require_once $file;
    }
});

set_exception_handler(function (Throwable $e): void {
    error_log(
        '[Error] ' . $e->getMessage() .
        ' in ' . $e->getFile() .
        ' on line ' . $e->getLine() . PHP_EOL,
        3,
        LOG_PATH . '/error.log'
    );

    \core\Response::serverError();

    $request = new \core\Request();
    $controller = new \app\Controllers\ErrorController($request);
    $controller->serverError();
    exit;
});

$request = new \core\Request();

$cacheKey = $request->method . ":" . $request->uri;
\core\Buffer::start($cacheKey);

$router = new \core\Router($request);
require ROOT_PATH . '/config/routes.php';

$router->run();

\core\Buffer::end();