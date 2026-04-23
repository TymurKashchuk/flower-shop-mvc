<?php

namespace core;

class Response
{
    private static int $statusCode = 200;

    private static function setStatus(int $code): void
    {
        self::$statusCode = $code;
        http_response_code($code);
    }

    private static function getStatus(): int
    {
        return self::$statusCode;
    }

    public static function json(array $data, int $status = 200): void
    {
        self::setStatus($status);
        header('Content-Type: application/json; charset=utf-8');
        echo json_encode($data, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
        exit;
    }

    public static function redirect(string $url): void
    {
        header('Location: ' . $url);
        exit;
    }

    public static function notFound(): void
    {
        self::setStatus(404);
    }

    public static function serverError(): void
    {
        self::setStatus(500);
    }
}