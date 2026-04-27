<?php

namespace app\Controllers;

use core\Controller;
use core\Request;

class ErrorController extends Controller
{
    public function __construct(Request $request)
    {
        parent::__construct($request);
    }

    public function notFound(): void
    {
        $this->view('errors.404', [
            'title' => '404 — Сторінку не знайдено',
        ]);
    }

    public function serverError(): void
    {
        $this->view('errors.500', [
            'title' => '500 — Помилка сервера',
        ]);
    }
}