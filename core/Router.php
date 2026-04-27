<?php

namespace core;

class Router
{
    private array $routes = [];
    private Request $request;

    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    public function get(string $path, array $action, array $middleware = []): void
    {
        $this->routes[] = [
            'method' => 'GET',
            'path' => $path,
            'controller' => $action[0],
            'action' => $action[1],
            'middleware' => $middleware,
        ];
    }

    public function post(string $path, array $action, array $middleware = []): void
    {
        $this->routes[] = [
            'method' => 'POST',
            'path' => $path,
            'controller' => $action[0],
            'action' => $action[1],
            'middleware' => $middleware,
        ];
    }

    public function run(): void
    {
        $uri = $this->getUri();
        $method = $this->request->method;

        foreach ($this->routes as $route) {
            $pattern = $this->buildPattern($route['path']);

            if ($route['method'] === $method && preg_match($pattern, $uri, $matches)) {
                foreach ($route['middleware'] as $mw) {
                    Middleware::$mw();
                }

                array_shift($matches);
                $params = array_values($matches);

                $controller = new $route['controller']($this->request);
                call_user_func_array([$controller, $route['action']], $params);
                return;
            }
        }

        $this->handle404();
    }

    private function buildPattern(string $path): string
    {
        $pattern = preg_replace('/\{[a-zA-Z]+\}/', '([^/]+)', $path);
        return '#^' . trim($pattern, '/') . '$#u';
    }

    private function getUri(): string
    {
        $uri = $_GET['url'] ?? '';
        return trim($uri, '/');
    }

    private function handle404(): void
    {
        Response::notFound();
        $controller = new \app\Controllers\ErrorController($this->request);
        $controller->notFound();
    }
}