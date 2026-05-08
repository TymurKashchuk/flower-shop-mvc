<?php

namespace app\Models;

use core\Model;

class Review extends Model
{
    protected string $table = 'reviews';

    public function getByProduct(int $productId): array
    {
        $stmt = $this->db->prepare("
            SELECT r.*, u.name AS user_name
            FROM {$this->table} r
            LEFT JOIN users u ON r.user_id = u.id
            WHERE r.product_id = ? AND r.is_active = 1
            ORDER BY r.created_at DESC
        ");
        $stmt->execute([$productId]);
        return $stmt->fetchAll();
    }

    public function hasReviewed(int $userId, int $productId): bool
    {
        $stmt = $this->db->prepare("
            SELECT COUNT(*) FROM {$this->table}
            WHERE user_id = ? AND product_id = ?
        ");
        $stmt->execute([$userId, $productId]);
        return (int)$stmt->fetchColumn() > 0;
    }

    public function create(array $data): bool
    {
        $stmt = $this->db->prepare("
            INSERT INTO {$this->table} (user_id, product_id, rating, text, is_active)
            VALUES (?, ?, ?, ?, 0)
        ");
        return $stmt->execute([
            $data['user_id'],
            $data['product_id'],
            $data['rating'],
            $data['text'],
        ]);
    }

    public function allForAdmin(): array
    {
        return $this->db->query("
            SELECT r.*, u.name AS user_name, p.name AS product_name
            FROM {$this->table} r
            LEFT JOIN users u ON r.user_id = u.id
            LEFT JOIN products p ON r.product_id = p.id
            ORDER BY r.created_at DESC
        ")->fetchAll();
    }

    public function approve(int $id): bool
    {
        $stmt = $this->db->prepare("
            UPDATE {$this->table} SET is_active = 1 WHERE id = ?
        ");
        return $stmt->execute([$id]);
    }

    public function delete(int $id): bool
    {
        $stmt = $this->db->prepare("
            DELETE FROM {$this->table} WHERE id = ?
        ");
        return $stmt->execute([$id]);
    }

    public function reply(int $id, string $text): bool
    {
        $stmt = $this->db->prepare("
        UPDATE {$this->table} SET admin_reply = ? WHERE id = ?
    ");
        return $stmt->execute([$text, $id]);
    }

    public function getAverageRating(int $productId): float
    {
        $stmt = $this->db->prepare("
            SELECT AVG(rating) FROM {$this->table}
            WHERE product_id = ? AND is_active = 1
        ");
        $stmt->execute([$productId]);
        return round((float)$stmt->fetchColumn(), 1);
    }
}