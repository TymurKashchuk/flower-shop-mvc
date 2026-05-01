<?php

namespace core;

class Request
{
    public string $method;
    public string $uri;

    public function __construct()
    {
        $this->method = strtoupper($_SERVER['REQUEST_METHOD']);
        $this->uri = trim(parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH), "/");
    }

    public function isAjax(): bool
    {
        return isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest'
            || (isset($_SERVER['CONTENT_TYPE']) && str_contains($_SERVER['CONTENT_TYPE'], 'application/json'));
    }

    public function getJson(): array{
        $raw = file_get_contents('php://input');
        return json_decode($raw, true) ?? [];
    }

    public function get(string $key, mixed $default = null) :mixed{
        return $_GET[$key] ?? $default;
    }

    public function post(string $key, mixed $default = null) :mixed
    {
        return $_POST[$key] ?? $default;
    }
    public function file(string $key) :?array{
        return $_FILES[$key] ?? null;
    }

    public function isPost(): bool
    {
        return $this->method === 'POST';
    }

    public function isGet(): bool{
        return $this->method === 'GET';
    }
}