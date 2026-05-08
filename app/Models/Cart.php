<?php

namespace app\Models;

use core\Model;

class Cart extends Model
{
    protected string $table = 'cart';

    private function getOwnerCondition(?int $userId, string $sessionId): array
    {
        if ($userId) {
            return ['user_id = ?', [$userId]];
        }
        return ['session_id = ?', [$sessionId]];
    }

    public function getBySession(string $sessionId, ?int $userId = null): array
    {
        [$condition, $params] = $this->getOwnerCondition($userId, $sessionId);

        $stmt = $this->db->prepare("
            SELECT c.*, p.name, p.price, p.image, p.slug, p.stock
            FROM {$this->table} c
            JOIN products p ON c.product_id = p.id
            WHERE c.{$condition}
            ORDER BY c.created_at ASC
        ");
        $stmt->execute($params);
        return $stmt->fetchAll();
    }

    public function getProductQty(int $productId, string $sessionId, ?int $userId = null): int
    {
        [$condition, $params] = $this->getOwnerCondition($userId, $sessionId);

        $stmt = $this->db->prepare("
        SELECT quantity FROM {$this->table}
        WHERE {$condition} AND product_id = ?
    ");
        $stmt->execute(array_merge($params, [$productId]));
        return (int)($stmt->fetchColumn() ?: 0);
    }

    public function addItem(string $sessionId, int $productId, int $quantity = 1, ?int $userId = null): bool
    {
        [$condition, $params] = $this->getOwnerCondition($userId, $sessionId);

        $stmt = $this->db->prepare("
            SELECT id, quantity FROM {$this->table}
            WHERE {$condition} AND product_id = ?
        ");
        $stmt->execute(array_merge($params, [$productId]));
        $existing = $stmt->fetch();

        if ($existing) {
            $stmt = $this->db->prepare("
                UPDATE {$this->table} SET quantity = quantity + ?
                WHERE id = ?
            ");
            return $stmt->execute([$quantity, $existing['id']]);
        }

        $stmt = $this->db->prepare("
            INSERT INTO {$this->table} (session_id, user_id, product_id, quantity)
            VALUES (?, ?, ?, ?)
        ");
        return $stmt->execute([$sessionId, $userId, $productId, $quantity]);
    }

    public function updateQuantity(int $itemId, string $sessionId, int $quantity, ?int $userId = null): bool
    {
        [$condition, $params] = $this->getOwnerCondition($userId, $sessionId);

        $stmt = $this->db->prepare("
            UPDATE {$this->table} SET quantity = ?
            WHERE id = ? AND {$condition}
        ");
        return $stmt->execute(array_merge([$quantity, $itemId], $params));
    }

    public function removeItem(int $itemId, string $sessionId, ?int $userId = null): bool
    {
        [$condition, $params] = $this->getOwnerCondition($userId, $sessionId);

        $stmt = $this->db->prepare("
            DELETE FROM {$this->table}
            WHERE id = ? AND {$condition}
        ");
        return $stmt->execute(array_merge([$itemId], $params));
    }

    public function clearSession(string $sessionId, ?int $userId = null): bool
    {
        [$condition, $params] = $this->getOwnerCondition($userId, $sessionId);

        $stmt = $this->db->prepare("
            DELETE FROM {$this->table} WHERE {$condition}
        ");
        return $stmt->execute($params);
    }

    public function clearGuestSession(string $sessionId): void
    {
        $stmt = $this->db->prepare(
            "DELETE FROM {$this->table} WHERE session_id = ? AND user_id IS NULL"
        );
        $stmt->execute([$sessionId]);
    }

    public function countItems(string $sessionId, ?int $userId = null): int
    {
        [$condition, $params] = $this->getOwnerCondition($userId, $sessionId);

        $stmt = $this->db->prepare("
            SELECT COALESCE(SUM(quantity), 0) FROM {$this->table} WHERE {$condition}
        ");
        $stmt->execute($params);
        return (int)$stmt->fetchColumn();
    }

    public function mergeSessionToUser(string $sessionId, int $userId): void
    {
        $stmt = $this->db->prepare("
        SELECT product_id, quantity FROM {$this->table}
        WHERE session_id = ? AND user_id IS NULL
    ");
        $stmt->execute([$sessionId]);
        $sessionItems = $stmt->fetchAll();

        foreach ($sessionItems as $item) {
            $check = $this->db->prepare("
            SELECT id, quantity FROM {$this->table}
            WHERE user_id = ? AND product_id = ?
        ");
            $check->execute([$userId, $item['product_id']]);
            $existing = $check->fetch();

            if ($existing) {
                $stockStmt = $this->db->prepare("SELECT stock FROM products WHERE id = ?");
                $stockStmt->execute([$item['product_id']]);
                $stock = (int)$stockStmt->fetchColumn();
                $newQty = min($existing['quantity'] + $item['quantity'], $stock);

                $upd = $this->db->prepare("UPDATE {$this->table} SET quantity = ? WHERE id = ?");
                $upd->execute([$newQty, $existing['id']]);
            } else {
                $upd = $this->db->prepare("
                UPDATE {$this->table} SET user_id = ? WHERE session_id = ? AND product_id = ? AND user_id IS NULL
            ");
                $upd->execute([$userId, $sessionId, $item['product_id']]);
            }
        }

        $stmt = $this->db->prepare("
        DELETE FROM {$this->table} WHERE session_id = ? AND user_id IS NULL
    ");
        $stmt->execute([$sessionId]);
    }
}