<?php

namespace app\Controllers;

use core\Controller;
use core\Request;
use app\Models\User;

class ProfileController extends Controller
{
    private User $user;

    public function __construct(Request $request)
    {
        parent::__construct($request);
        $this->user = new User();
    }

    public function index(): void
    {
        if (empty($_SESSION['user'])) {
            $this->redirect(BASE_URL . '/login');
            return;
        }

        $user = $this->user->find($_SESSION['user']['id']);

        $this->view('profile.index', [
            'title' => 'Мій профіль',
            'user' => $user,
            'errors' => $this->getErrors(),
            'old' => $this->getOld(),
        ]);
    }

    public function update(): void
    {
        if (empty($_SESSION['user'])) {
            $this->redirect(BASE_URL . '/login');
            return;
        }

        $name = trim($this->request->post('name', ''));
        $email = trim($this->request->post('email', ''));
        $password = $this->request->post('password', '');
        $errors = [];

        if (mb_strlen($name) < 2) {
            $errors['name'] = "Ім'я занадто коротке";
        }

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $errors['email'] = 'Невірний формат email';
        }

        $userId = $_SESSION['user']['id'];

        if (!empty($email) && $email !== ($_SESSION['user']['email'] ?? '')
            && $this->user->emailExists($email, $userId)) {
            $errors['email'] = 'Цей email вже використовується';
        }

        if (!empty($password) && mb_strlen($password) < 6) {
            $errors['password'] = 'Мінімум 6 символів';
        }

        if (!empty($errors)) {
            $this->back($errors, ['name' => $name, 'email' => $email]);
            return;
        }

        $data = ['name' => $name, 'email' => $email];

        if (!empty($password)) {
            $data['password_hash'] = password_hash($password, PASSWORD_BCRYPT);
        }

        $this->user->updateProfile($userId, $data);

        $_SESSION['user']['name'] = $name;
        $_SESSION['user']['email'] = $email;
        $_SESSION['success'] = 'Профіль оновлено!';
        $this->redirect(BASE_URL . '/profile');
    }
}