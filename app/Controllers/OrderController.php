<?php

namespace app\Controllers;

use app\Models\Order;
use app\Models\OrderItem;
use core\Controller;
use core\Middleware;
use core\Request;

class OrderController extends Controller
{
    private Order $order;
    private OrderItem $orderItem;

    public function __construct(Request $request)
    {
        parent::__construct($request);
        $this->order = new Order();
        $this->orderItem = new OrderItem();
    }

    public function adminIndex(): void
    {
        Middleware::admin();

        $this->view('orders.admin_index', [
            'title' => 'Замовлення',
            'items' => $this->order->allForAdmin(),
        ], 'admin');
    }

    public function adminShow(int $id): void
    {
        Middleware::admin();

        $order = $this->order->findForAdmin($id);

        if (!$order) {
            $this->redirect(BASE_URL . '/admin/orders');
            return;
        }

        $this->view('orders.admin_show', [
            'title' => 'Замовлення #' . $id,
            'order' => $order,
            'items' => $this->orderItem->getByOrder($id),
        ], 'admin');
    }

    public function updateStatus(int $id): void
    {
        Middleware::admin();

        $status = $_POST['status'] ?? '';
        $allowed = ['new', 'processing', 'done', 'cancelled'];

        if (in_array($status, $allowed, true)) {
            $this->order->updateStatus($id, $status);
        }

        $this->redirect(BASE_URL . '/admin/orders/' . $id);
    }

    public function delete(int $id): void
    {
        Middleware::admin();

        $order = $this->order->findForAdmin($id);

        if (!$order) {
            $_SESSION['error'] = 'Замовлення не знайдено.';
            $this->redirect(BASE_URL . '/admin/orders');
            return;
        }

        $this->order->deleteWithItems($id);

        $_SESSION['success'] = 'Замовлення видалено!';
        $this->redirect(BASE_URL . '/admin/orders');
    }
}