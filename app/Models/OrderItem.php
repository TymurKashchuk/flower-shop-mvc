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
}