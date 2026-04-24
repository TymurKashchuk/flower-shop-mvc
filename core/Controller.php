<?php

namespace core;

abstract class Controller
{
    protected Request $request;

    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    protected function view(string $view, array $data = [], string $layout = 'main'): void
    {
        extract($data);
        $viewFile = APP_PATH . '/Views/' . str_replace('.', '/', $view) . '.php';
        $layoutFile = APP_PATH . '/Views/layouts/' . $layout . '.php';

        if (!file_exists($viewFile)) {
            Response::notFound();
            die("View $view not found");
        }

        ob_start();
        require $viewFile;
        $content = ob_get_clean();

        require $layoutFile;
    }

    protected function redirect(string $url): void
    {
        Response::redirect($url);
    }

    protected function back(array $errors = [], array $old = []): void
    {
        $_SESSION['errors'] = $errors;
        $_SESSION['old'] = $old;
        header('Location: ' . ($_SERVER['HTTP_REFERER'] ?? BASE_URL));
        exit;
    }

    protected function getErrors(): array
    {
        $errors = $_SESSION['errors'] ?? [];
        unset($_SESSION['errors']);
        return $errors;
    }

    protected function getOld(): array
    {
        $old = $_SESSION['old'] ?? [];
        unset($_SESSION['old']);
        return $old;
    }
}