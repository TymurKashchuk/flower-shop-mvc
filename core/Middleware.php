<?php

namespace core;

use core\Response;

class Middleware
{
    public static function auth(): void
    {
        if (empty($_SESSION['user'])) {
            Response::redirect(BASE_URL . '/login');
            return;
        }

        $user = new \app\Models\User();
        $fresh = $user->find((int)$_SESSION['user']['id']);

        if (!$fresh || $fresh['is_banned']) {
            $_SESSION = [];
            session_destroy();
            Response::redirect(BASE_URL . '/login?banned=1');
            return;
        }

        $_SESSION['user'] = $fresh;
    }

    public static function admin(): void
    {
        self::auth();
        if (($_SESSION['user']['role'] ?? '') !== 'admin') {
            Response::redirect(BASE_URL . '/');
            return;
        }
    }

    public static function csrf(): void
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                $token = $_POST['_csrf'] ?? '';
            $sessionToken = $_SESSION['csrf_token'] ?? '';

            if (!hash_equals($sessionToken, $token)) {
                Response::setStatus(403);
                die("CSRF token mismatch");
            }
        }
    }

    public static function generateCsrf(): string
    {
        if (empty($_SESSION['csrf_token'])) {
            $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
        }
        return $_SESSION['csrf_token'];
    }
}