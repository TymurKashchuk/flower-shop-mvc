<?php

namespace app\Controllers;

use core\Controller;
use core\Middleware;
use core\Request;
use app\Models\User;

class UserController extends Controller
{
    private User $user;

    public function __construct(Request $request)
    {
        parent::__construct($request);
        $this->user = new User();
    }

    public function adminIndex(): void
    {
        Middleware::admin();
        $this->view('users.admin_index', [
            'title' => 'Користувачі',
            'items' => $this->user->allForAdmin(),
        ], 'admin');
    }

    public function ban(string $id): void
    {
        Middleware::admin();
        $user = $this->user->find((int)$id);

        if (!$user || $user['role'] === 'admin') {
            $_SESSION['error'] = 'Не можна заблокувати адміна.';
            $this->redirect(BASE_URL . '/admin/users');
            return;
        }

        $this->user->ban((int)$id);
        $_SESSION['success'] = 'Користувача заблоковано.';
        $this->redirect(BASE_URL . '/admin/users');
    }

    public function unban(string $id): void
    {
        Middleware::admin();
        $this->user->unban((int)$id);
        $_SESSION['success'] = 'Користувача розблоковано.';
        $this->redirect(BASE_URL . '/admin/users');
    }
}