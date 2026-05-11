<?php

namespace app\Models;

use core\Model;

class OrderItem extends Model
{
    protected string $table = 'order_items';

    public function createBatch(int $orderId, array $items): void
    {
        $stmt = $this->db->prepare("
            INSERT INTO order_items (order_id, product_id, qty, price)
            VALUES (:order_id, :product_id, :qty, :price)
        ");

        foreach ($items as $item) {
            $stmt->execute([
                'order_id' => $orderId,
                'product_id' => $item['product_id'],
                'qty' => $item['qty'],
                'price' => $item['price'],
            ]);
        }
    }

    public function getByOrder(int $orderId): array
    {
        $stmt = $this->db->prepare("
        SELECT oi.*, p.name AS product_name, p.slug AS product_slug
        FROM order_items oi
        JOIN products p ON p.id = oi.product_id
        WHERE oi.order_id = :order_id
        ORDER BY oi.id ASC
    ");
        $stmt->execute(['order_id' => $orderId]);

        return $stmt->fetchAll();
    }
}