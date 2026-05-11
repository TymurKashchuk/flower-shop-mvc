<?php

namespace app\Models;

use core\Model;

class Order extends Model
{
    protected string $table = 'orders';

    public function create(array $data): int
    {
        $stmt = $this->db->prepare("
        INSERT INTO orders (user_id, name, phone, address, delivery_type, total, status)
        VALUES (:user_id, :name, :phone, :address, :delivery_type, :total, 'new')
            ");
        $stmt->execute($data);
        return (int)$this->db->lastInsertId();
    }

    public function allForAdmin(): array
    {
        $stmt = $this->db->query("
            SELECT o.*, u.email 
            FROM orders o
            LEFT JOIN users u ON o.user_id = u.id
            ORDER BY o.created_at DESC
        ");
        return $stmt->fetchAll();
    }

    public function findForAdmin(int $id): ?array
    {
        $stmt = $this->db->prepare("
        SELECT o.*, u.email
        FROM orders o
        LEFT JOIN users u ON o.user_id = u.id
        WHERE o.id = ?
        LIMIT 1
    ");
        $stmt->execute([$id]);

        $order = $stmt->fetch();

        return $order ?: null;
    }

    public function deleteWithItems(int $id): bool
    {
        $this->db->beginTransaction();

        try {
            $stmt = $this->db->prepare("DELETE FROM order_items WHERE order_id = ?");
            $stmt->execute([$id]);

            $stmt = $this->db->prepare("DELETE FROM orders WHERE id = ?");
            $stmt->execute([$id]);

            $this->db->commit();
            return true;
        } catch (\Throwable $e) {
            $this->db->rollBack();
            return false;
        }
    }

    public function updateStatus(int $id, string $status): bool
    {
        $stmt = $this->db->prepare("UPDATE orders SET status = ? WHERE id = ?");
        return $stmt->execute([$status, $id]);
    }

}