<?php

namespace app\Controllers;

use core\Controller;
use core\Request;
use app\Models\User;

class AuthController extends Controller
{
    private User $user;

    public function __construct(Request $request)
    {
        parent::__construct($request);
        $this->user = new User();
    }

    public function loginForm(): void
    {
        if (!empty($_SESSION['user'])) {
            $this->redirect(BASE_URL . '/');
            return;
        }

        $this->view('auth.login', [
            'title' => "Вхід",
            'errors' => $this->getErrors(),
            'old' => $this->getOld(),
        ]);
    }

    public function login(): void
    {
        $email = trim($this->request->post('email', ''));
        $password = $this->request->post('password', '');

        $errors = [];

        if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $errors['email'] = 'Введіть коректний email';
        }

        if (empty($password)) {
            $errors['password'] = 'Введіть пароль';
        }

        if ($errors) {
            $this->back($errors, ['email' => $email]);
            return;
        }

        $user = $this->user->findByEmail($email);

        if (!$user || !password_verify($password, $user['password'])) {
            $this->back(['email' => 'Невірний email або пароль'], ['email' => $email]);
            return;
        }

        $_SESSION['user'] = [
            'id' => $user['id'],
            'name' => $user['name'],
            'role' => $user['role'],
        ];

        $this->redirect($user['role'] === 'admin' ? BASE_URL . '/admin' : BASE_URL . '/');
    }

    public function registerForm(): void
    {
        if (!empty($_SESSION['user'])) {
            $this->redirect(BASE_URL . '/');
            return;
        }

        $this->view('auth.register', [
            'title' => 'Реєстрація',
            'errors' => $this->getErrors(),
            'old' => $this->getOld(),
        ]);
    }

    public function register(): void
    {
        $name = trim($this->request->post('name', ''));
        $email = trim($this->request->post('email', ''));
        $password = $this->request->post('password', '');
        $confirm = $this->request->post('password_confirm', '');

        $errors = [];

        if (empty($name) || mb_strlen($name) < 2) {
            $errors['name'] = "Ім'я має бути не менше 2 символів";
        }

        if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $errors['email'] = 'Введіть коректний email';
        } elseif ($this->user->emailExists($email)) {
            $errors['email'] = 'Цей email вже зареєстрований';
        }

        if (empty($password) || mb_strlen($password) < 6) {
            $errors['password'] = 'Пароль має бути не менше 6 символів';
        } elseif ($password !== $confirm) {
            $errors['password_confirm'] = 'Паролі не збігаються';
        }

        if ($errors) {
            $this->back($errors, ['name' => $name, 'email' => $email]);
            return;
        }

        $this->user->create(['name' => $name, 'email' => $email, 'password' => $password]);

        $user = $this->user->findByEmail($email);
        $_SESSION['user'] = [
            'id' => $user['id'],
            'name' => $user['name'],
            'role' => $user['role'],
        ];

        $_SESSION['success'] = 'Ласкаво просимо, ' . $name . '!';
        $this->redirect(BASE_URL . '/');
    }

    public function logout(): void
    {
        $_SESSION = [];
        session_destroy();
        $this->redirect(BASE_URL . '/login');
    }
}