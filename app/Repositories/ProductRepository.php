<?php

namespace app\Repositories;

use app\Models\Product;
use app\Models\Category;

class ProductRepository
{
    private Product $product;
    private Category $category;

    public function __construct(Product $product, Category $category)
    {
        $this->product = $product;
        $this->category = $category;
    }

    public function getCatalogProducts(
        ?int   $categoryId = null,
        ?float $priceMin = null,
        ?float $priceMax = null
    ): array
    {
        if ($categoryId !== null || $priceMin !== null || $priceMax !== null) {
            return $this->product->filter($categoryId, $priceMin, $priceMax);
        }
        return $this->product->getAllActive();
    }

    public function getPaginatedProducts(int $page = 1, int $perPage = 9): array
    {
        $total = $this->product->countActive();
        $perPage = $perPage > 0 ? $perPage : 9;
        $totalPages = $total > 0 ? (int)ceil($total / $perPage) : 1;

        return [
            'products' => $this->product->getPaginated($page, $perPage),
            'total' => $total,
            'current_page' => $page,
            'per_page' => $perPage,
            'total_pages' => $totalPages,
        ];
    }

    public function getProductBySlug(string $slug): ?array
    {
        return $this->product->findBySlug($slug);
    }

    public function getProductById(int $id): ?array
    {
        return $this->product->find($id);
    }

    public function searchProducts(string $query): array
    {
        if (strlen(trim($query)) < 2) {
            return [];
        }
        return $this->product->search($query);
    }

    public function getAllCategories(): array
    {
        return $this->category->getAllForMenu();
    }

    public function getCategoryBySlug(string $slug): ?array
    {
        return $this->category->findBySlug($slug);
    }

    public function createProduct(array $data): bool
    {
        return $this->product->create($data);
    }

    public function updateProduct(int $id, array $data): bool
    {
        return $this->product->update($id, $data);
    }

    public function deleteProduct(int $id): bool
    {
        return $this->product->delete($id);
    }

    public function lastId(): string
    {
        return $this->product->lastId();
    }
}