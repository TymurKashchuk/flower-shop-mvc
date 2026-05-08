<?php

namespace app\Models;

use core\Model;

class Product extends Model
{
    protected string $table = 'products';

    public function getAllActive(): array
    {
        $stmt = $this->db->query("
            SELECT p.*, c.name AS category_name, c.slug AS category_slug
            FROM {$this->table} p
            LEFT JOIN categories c ON p.category_id = c.id
            WHERE p.is_active = 1
            ORDER BY p.created_at DESC
        ");
        return $stmt->fetchAll();
    }

    public function getByCategory(int $categoryId): array
    {
        $stmt = $this->db->prepare("
            SELECT p.*, c.name AS category_name, c.slug AS category_slug
            FROM {$this->table} p
            LEFT JOIN categories c ON p.category_id = c.id
            WHERE p.is_active = 1 AND p.category_id = ?
            ORDER BY p.created_at DESC
        ");

        $stmt->execute([$categoryId]);
        return $stmt->fetchAll();
    }

    public function findBySlug(string $slug): ?array
    {
        $stmt = $this->db->prepare("
            SELECT p.*, c.name AS category_name, c.slug AS category_slug
            FROM {$this->table} p
            LEFT JOIN categories c ON p.category_id = c.id
            WHERE p.slug = ? AND p.is_active = 1
        ");
        $stmt->execute([$slug]);
        return $stmt->fetch() ?: null;
    }

    public function search(string $query): array
    {
        $stmt = $this->db->prepare("
            SELECT p.*, c.name AS category_name
            FROM {$this->table} p
            LEFT JOIN categories c ON p.category_id = c.id
            WHERE p.is_active = 1
            AND p.name LIKE ?
            ORDER BY p.name ASC
            LIMIT 10
        ");
        $stmt->execute(['%' . $query . '%']);
        return $stmt->fetchAll();
    }

    public function filter(?int $categoryId = null, ?float $priceMin = null, ?float $priceMax = null): array
    {
        $sql = "SELECT p.*, c.name AS category_name FROM {$this->table} p
                   LEFT JOIN categories c ON p.category_id = c.id
                   WHERE p.is_active = 1";
        $params = [];

        if ($categoryId) {
            $sql .= " AND p.category_id = ?";
            $params[] = $categoryId;
        }

        if ($priceMin !== null) {
            $sql .= " AND p.price >= ?";
            $params[] = $priceMin;
        }

        if ($priceMax !== null) {
            $sql .= " AND p.price <= ?";
            $params[] = $priceMax;
        }

        $sql .= " ORDER BY p.created_at DESC";

        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll();
    }

    public function create(array $data): bool
    {
        $stmt = $this->db->prepare("
            INSERT INTO {$this->table}
            (category_id, name, slug, price, description, image, stock, is_active)
            VALUES (?, ?, ?, ?, ?, ?, ?, ?)
        ");
        return $stmt->execute([
            $data['category_id'],
            $data['name'],
            $data['slug'],
            $data['price'],
            $data['description'],
            $data['image'] ?? null,
            $data['stock'] ?? 0,
            $data['is_active'] ?? 1,
        ]);
    }

    public function update(int $id, array $data): bool
    {
        $stmt = $this->db->prepare("
            UPDATE {$this->table}
            SET category_id = ?, name = ?, slug = ?, price = ?,
                description = ?, image = ?, stock = ?, is_active = ?
            WHERE id = ?
        ");
        return $stmt->execute([
            $data['category_id'],
            $data['name'],
            $data['slug'],
            $data['price'],
            $data['description'],
            $data['image'] ?? null,
            $data['stock'] ?? 0,
            $data['is_active'] ?? 1,
            $id,
        ]);
    }

    public function getPaginated(int $page = 1, int $perPage = 9): array
    {
        $offset = ($page - 1) * $perPage;
        $stmt = $this->db->prepare("
        SELECT p.*, c.name AS category_name, c.slug AS category_slug
        FROM {$this->table} p
        LEFT JOIN categories c ON p.category_id = c.id
        WHERE p.is_active = 1
        ORDER BY p.created_at DESC
        LIMIT ? OFFSET ?
    ");
        $stmt->execute([$perPage, $offset]);
        return $stmt->fetchAll();
    }

    public function countActive(): int
    {
        $stmt = $this->db->query("SELECT COUNT(*) FROM {$this->table} WHERE is_active = 1");
        return (int)$stmt->fetchColumn();
    }

    public function getFeatured(int $limit = 8): array
    {
        $stmt = $this->db->prepare("
        SELECT p.*, c.name AS category_name, c.slug AS category_slug
        FROM {$this->table} p
        LEFT JOIN categories c ON p.category_id = c.id
        WHERE p.is_active = 1
        ORDER BY p.created_at DESC
        LIMIT ?
        ");
        $stmt->execute([$limit]);
        return $stmt->fetchAll();
    }

    public function getAllForAdmin(): array
    {
        $stmt = $this->db->query("
        SELECT p.*, c.name AS category_name
        FROM {$this->table} p
        LEFT JOIN categories c ON p.category_id = c.id
        ORDER BY p.created_at DESC
    ");
        return $stmt->fetchAll();
    }
}