<?php

namespace app\Models;

use core\Model;

class Cart extends Model
{
    protected string $table = 'cart';

    public function getBySession(string $sessionId): array
    {
        $stmt = $this->db->prepare("
        SELECT c.*, p.name, p.price, p.image, p.slug, p.stock
        FROM {$this->table} c
        JOIN products p ON c.product_id = p.id
        WHERE c.session_id = ?
        ORDER BY c.created_at ASC");
        $stmt->execute([$sessionId]);
        return $stmt->fetchAll();
    }

    public function addItem(string $sessionId, string $productId, int $quantity = 1): bool
    {
        $stmt = $this->db->prepare("
        SELECT id,quantity 
        FROM {$this->table}
        WHERE session_id = ? AND product_id = ?");
        $stmt->execute([$sessionId, $productId]);
        $existing = $stmt->fetch();

        if ($existing) {
            $stmt = $this->db->prepare("
            UPDATE {$this->table}
            SET quantity = quantity + ?
            WHERE id = ?");
            return $stmt->execute([$quantity, $existing['id']]);
        }

        $stmt = $this->db->prepare("     
        INSERT INTO {$this->table} (session_id, product_id, quantity)
        VALUES (?, ?, ?)");
        return $stmt->execute([$sessionId, $productId, $quantity]);
    }

    public function updateQuantity(int $itemId, string $sessionId, int $quantity): bool
    {
        $stmt = $this->db->prepare("
            UPDATE {$this->table}
            SET quantity = ?
            WHERE id = ? AND session_id = ?
        ");
        return $stmt->execute([$quantity, $itemId, $sessionId]);
    }

    public function removeItem(int $itemId, string $sessionId): bool
    {
        $stmt = $this->db->prepare("
        DELETE FROM {$this->table}
        WHERE id = ? AND session_id = ?
        ");
        return $stmt->execute([$itemId, $sessionId]);
    }

    public function clearSession(string $sessionId): bool
    {
        $stmt = $this->db->prepare("
            DELETE FROM {$this->table} WHERE session_id = ?
        ");
        return $stmt->execute([$sessionId]);
    }

    public function countItems(string $sessionId): int
    {
        $stmt = $this->db->prepare("
            SELECT SUM(quantity) FROM {$this->table} WHERE session_id = ?
        ");
        $stmt->execute([$sessionId]);
        return (int) $stmt->fetchColumn();
    }
}