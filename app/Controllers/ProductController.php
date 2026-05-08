<?php

namespace app\Controllers;

use core\Controller;
use core\Middleware;
use core\Request;
use app\Models\Product;
use app\Models\Category;

class ProductController extends Controller
{
    private Product $product;
    private Category $category;

    public function __construct(Request $request)
    {
        parent::__construct($request);
        $this->product = new Product();
        $this->category = new Category();
    }

    public function index(): void
    {
        Middleware::admin();
        $this->view('admin.products.index', [
            'title' => 'Товари',
            'items' => $this->product->getAllForAdmin(),
            'categories' => $this->category->getAllForMenu(),
        ], 'admin');
    }

    public function create(): void
    {
        Middleware::admin();
        $this->view('admin.products.create', [
            'title' => 'Додати товар',
            'categories' => $this->category->getAllForMenu(),
            'errors' => $this->getErrors(),
            'old' => $this->getOld(),
        ], 'admin');
    }

    public function store(): void
    {
        Middleware::admin();

        $data = $this->collectFormData();
        $errors = $this->validate($data);

        if ($errors) {
            $this->back($errors, $data);
            return;
        }

        $data['slug'] = $this->makeSlug($data['name']);
        $this->product->create($data);
        \core\Buffer::clearCache();
        $_SESSION['success'] = 'Товар додано.';
        $this->redirect(BASE_URL . '/admin/products');
    }

    public function edit(string $id): void
    {
        Middleware::admin();
        $item = $this->product->find((int)$id);
        if (!$item) {
            $this->redirect(BASE_URL . '/admin/products');
            return;
        }

        $this->view('admin.products.edit', [
            'title' => 'Редагувати товар',
            'item' => $item,
            'categories' => $this->category->getAllForMenu(),
            'errors' => $this->getErrors(),
            'old' => $this->getOld(),
        ], 'admin');
    }

    public function update(string $id): void
    {
        Middleware::admin();

        $data = $this->collectFormData();
        $errors = $this->validate($data);

        if ($errors) {
            $this->back($errors, $data);
            return;
        }

        $data['slug'] = $this->makeSlug($data['name']);
        $this->product->update((int)$id, $data);
        \core\Buffer::clearCache();
        $_SESSION['success'] = 'Товар оновлено.';
        $this->redirect(BASE_URL . '/admin/products');
    }

    public function delete(string $id): void
    {
        Middleware::admin();
        $this->product->delete((int)$id);
        \core\Buffer::clearCache();
        $_SESSION['success'] = 'Товар видалено.';
        $this->redirect(BASE_URL . '/admin/products');
    }

    private function collectFormData(): array
    {
        return [
            'category_id' => (int)$this->request->post('category_id'),
            'name' => trim($this->request->post('name', '')),
            'price' => (float)$this->request->post('price', 0),
            'description' => trim($this->request->post('description', '')),
            'image' => trim($this->request->post('image', '')),
            'stock' => (int)$this->request->post('stock', 0),
            'is_active' => (int)$this->request->post('is_active', 1),
        ];
    }

    private function validate(array $data): array
    {
        $errors = [];
        if (empty($data['name'])) $errors['name'] = "Назва обов'язкова";
        if ($data['price'] <= 0) $errors['price'] = 'Ціна має бути більше 0';
        if (empty($data['category_id'])) $errors['category_id'] = 'Оберіть категорію';
        return $errors;
    }

    private function makeSlug(string $name): string
    {
        $map = ['а' => 'a', 'б' => 'b', 'в' => 'v', 'г' => 'h', 'ґ' => 'g', 'д' => 'd', 'е' => 'e', 'є' => 'ie',
            'ж' => 'zh', 'з' => 'z', 'и' => 'y', 'і' => 'i', 'ї' => 'i', 'й' => 'i', 'к' => 'k', 'л' => 'l',
            'м' => 'm', 'н' => 'n', 'о' => 'o', 'п' => 'p', 'р' => 'r', 'с' => 's', 'т' => 't', 'у' => 'u',
            'ф' => 'f', 'х' => 'kh', 'ц' => 'ts', 'ч' => 'ch', 'ш' => 'sh', 'щ' => 'shch', 'ь' => '',
            'ю' => 'iu', 'я' => 'ia', ' ' => '-'];
        $slug = mb_strtolower($name);
        $slug = strtr($slug, $map);
        $slug = preg_replace('/[^a-z0-9\-]/', '', $slug);
        $slug = preg_replace('/-+/', '-', $slug);
        return trim($slug, '-') ?: 'product-' . time();
    }
}