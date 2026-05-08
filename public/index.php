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

if (!empty($_SESSION['user'])) {
    $db = \core\Database::getInstance();
    $stmt = $db->prepare("SELECT is_banned FROM users WHERE id = ? LIMIT 1");
    $stmt->execute([$_SESSION['user']['id']]);
    $row = $stmt->fetch();
    if (!$row || (int)$row['is_banned'] === 1) {
        $_SESSION = [];
        session_destroy();
        $reason = !$row ? 'deleted' : 'banned';
        header('Location: http://coursework.local/login?reason=' . $reason);
        exit;
    }
}

set_exception_handler(function (Throwable $e): void {
    if (ob_get_level() > 0) {
        ob_end_clean();
    }
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
$router = new \core\Router($request);
require ROOT_PATH . '/config/routes.php';

$noCache = $request->method !== 'GET'
    || str_starts_with($request->uri, 'cart')
    || str_starts_with($request->uri, 'checkout')
    || str_starts_with($request->uri, 'login')
    || str_starts_with($request->uri, 'register')
    || str_starts_with($request->uri, 'logout')
    || str_starts_with($request->uri, 'admin')
    || str_starts_with($request->uri, 'profile')
    || !empty($_SESSION['user']);


if ($noCache) {
    $router->run();
} else {
    $cacheKey = $request->method . ':' . $request->uri . ':' . http_build_query($_GET);
    \core\Buffer::start($cacheKey);
    $router->run();
    \core\Buffer::end();
}