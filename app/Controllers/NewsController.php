<?php

namespace app\Controllers;

use core\Controller;
use core\Middleware;
use core\Request;
use core\Response;
use app\Models\News;

class NewsController extends Controller
{
    private News $news;

    public function __construct(Request $request)
    {
        parent::__construct($request);
        $this->news = new News();
    }

    public function index(): void
    {
        $page = max(1, (int)$this->request->get('page', 1));
        $month = $this->request->get('month') ?: null;
        $perPage = 6;

        $total = $this->news->countFiltered($month);
        $items = $this->news->getFiltered($page, $perPage, $month);
        $pages = (int)ceil($total / $perPage);
        $months = $this->news->getAvailableMonths();

        $this->view('news.index', [
            'title' => 'Новини',
            'items' => $items,
            'page' => $page,
            'pages' => $pages,
            'months' => $months,
            'month' => $month,
        ]);
    }

    public function show(string $slug): void
    {
        $item = $this->news->findBySlug($slug);

        if (!$item) {
            Response::setStatus(404);
            $this->view('errors.404');
            return;
        }

        $this->view('news.show', [
            'title' => $item['title'],
            'item' => $item,
        ]);
    }

    public function adminIndex(): void
    {
        Middleware::admin();
        $this->view('news.admin_index', [
            'title' => 'Управління новинами',
            'items' => $this->news->allForAdmin(),
        ], 'admin');
    }

    public function create(): void
    {
        Middleware::admin();
        $this->view('news.admin_form', [
            'title' => 'Додати новину',
            'item' => null,
            'errors' => $this->getErrors(),
            'old' => $this->getOld(),
        ], 'admin');
    }

    public function store(): void
    {
        Middleware::admin();

        $data = $this->getFormData();
        $errors = $this->validate($data);

        if ($errors) {
            $this->back($errors, $data);
            return;
        }

        $this->news->create($data);
        $_SESSION['success'] = 'Новину додано!';
        $this->redirect(BASE_URL . '/admin/news');
    }

    public function edit(string $id): void
    {
        Middleware::admin();
        $item = $this->news->find((int)$id);

        if (!$item) {
            Response::setStatus(404);
            $this->view('errors.404');
            return;
        }

        $this->view('news.admin_form', [
            'title' => 'Редагувати новину',
            'item' => $item,
            'errors' => $this->getErrors(),
            'old' => $this->getOld(),
        ], 'admin');
    }

    public function update(string $id): void
    {
        Middleware::admin();

        $data = $this->getFormData();
        $errors = $this->validate($data);

        if ($errors) {
            $this->back($errors, $data);
            return;
        }

        $this->news->update((int)$id, $data);
        $_SESSION['success'] = 'Новину оновлено!';
        $this->redirect(BASE_URL . '/admin/news');
    }

    public function delete(string $id): void
    {
        Middleware::admin();
        $this->news->delete((int)$id);
        $_SESSION['success'] = 'Новину видалено!';
        $this->redirect(BASE_URL . '/admin/news');
    }

    private function getFormData(): array
    {
        $title = trim($this->request->post('title', ''));
        return [
            'title' => $title,
            'slug' => $this->makeSlug($title),
            'content' => trim($this->request->post('content', '')),
            'image' => trim($this->request->post('image', '')),
            'is_active' => (int)(bool)$this->request->post('is_active'),
        ];
    }

    private function validate(array $data): array
    {
        $errors = [];
        if (mb_strlen($data['title']) < 3) {
            $errors['title'] = 'Заголовок занадто короткий';
        }
        if (mb_strlen($data['content']) < 10) {
            $errors['content'] = 'Текст занадто короткий';
        }
        return $errors;
    }

    private function makeSlug(string $title): string
    {
        $map = ['а' => 'a', 'б' => 'b', 'в' => 'v', 'г' => 'g', 'д' => 'd', 'е' => 'e', 'є' => 'ye',
            'ж' => 'zh', 'з' => 'z', 'и' => 'y', 'і' => 'i', 'ї' => 'yi', 'й' => 'y', 'к' => 'k',
            'л' => 'l', 'м' => 'm', 'н' => 'n', 'о' => 'o', 'п' => 'p', 'р' => 'r', 'с' => 's',
            'т' => 't', 'у' => 'u', 'ф' => 'f', 'х' => 'kh', 'ц' => 'ts', 'ч' => 'ch', 'ш' => 'sh',
            'щ' => 'shch', 'ь' => '', 'ю' => 'yu', 'я' => 'ya'];
        $slug = mb_strtolower($title);
        $slug = strtr($slug, $map);
        $slug = preg_replace('/[^a-z0-9]+/', '-', $slug);
        return trim($slug, '-');
    }
}