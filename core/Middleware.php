<?php

namespace core;

class Middleware
{
    public static function auth(): void
    {
        if (empty($_SESSION['user']['id'])) {
            Response::redirect(BASE_URL . '/login');
            return;
        }

        self::refreshAuthenticatedUser();
    }

    public static function syncUserStatus(): void
    {
        if (empty($_SESSION['user']['id'])) {
            return;
        }

        self::refreshAuthenticatedUser();
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
        if (($_SERVER['REQUEST_METHOD'] ?? 'GET') !== 'POST') {
            return;
        }

        $token = $_POST['_csrf'] ?? '';
        $sessionToken = $_SESSION['csrf_token'] ?? '';

        if (!is_string($token) || !is_string($sessionToken) || !hash_equals($sessionToken, $token)) {
            Response::setStatus(403);
            exit('CSRF token mismatch');
        }
    }

    public static function generateCsrf(): string
    {
        if (empty($_SESSION['csrf_token'])) {
            $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
        }

        return $_SESSION['csrf_token'];
    }

    private static function refreshAuthenticatedUser(): void
    {
        $user = new \app\Models\User();
        $fresh = $user->find((int)$_SESSION['user']['id']);

        if (!$fresh || (int)($fresh['is_banned'] ?? 0) === 1) {
            $_SESSION = [];
            session_destroy();

            $reason = !$fresh ? 'deleted' : 'banned';
            Response::redirect(BASE_URL . '/login?reason=' . $reason);
            return;
        }

        $_SESSION['user'] = $fresh;
    }
}