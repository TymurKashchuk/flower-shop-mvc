<?php

namespace app\Controllers;

use core\Controller;
use core\Middleware;
use core\Request;
use app\Models\Cart;
use app\Models\Order;
use app\Models\OrderItem;

class CheckoutController extends Controller
{
    private Cart $cart;
    private Order $order;
    private OrderItem $orderItem;
    private string $sessionId;
    private ?int $userId;

    public function __construct(Request $request)
    {
        parent::__construct($request);
        Middleware::auth();

        $this->cart = new Cart();
        $this->order = new Order();
        $this->orderItem = new OrderItem();
        $this->sessionId = session_id();
        $this->userId = $_SESSION['user']['id'] ?? null;
    }

    public function index(): void
    {
        $items = $this->cart->getBySession($this->sessionId, $this->userId);

        if (empty($items)) {
            $this->redirect(BASE_URL . '/cart');
            return;
        }

        $total = $this->calculateTotal($items);

        $this->view('checkout.index', [
            'title' => 'Оформлення замовлення',
            'items' => $items,
            'total' => $total,
        ]);
    }

    public function store(): void
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect(BASE_URL . '/checkout');
            return;
        }

        $name = trim($_POST['name'] ?? '');
        $phone = trim($_POST['phone'] ?? '');
        $address = trim($_POST['address'] ?? '');
        $deliveryType = in_array($_POST['delivery_type'] ?? '', ['courier', 'pickup'], true)
            ? $_POST['delivery_type']
            : 'courier';

        if ($name === '' || $phone === '') {
            $_SESSION['error'] = 'Заповніть усі поля.';
            $this->redirect(BASE_URL . '/checkout');
            return;
        }

        if ($deliveryType === 'courier' && $address === '') {
            $_SESSION['error'] = 'Вкажіть адресу доставки.';
            $this->redirect(BASE_URL . '/checkout');
            return;
        }

        if ($deliveryType === 'pickup') {
            $address = 'Самовивіз';
        }

        $items = $this->cart->getBySession($this->sessionId, $this->userId);

        if (empty($items)) {
            $this->redirect(BASE_URL . '/cart');
            return;
        }

        $total = $this->calculateTotal($items);

        $orderId = $this->order->create([
            'user_id' => $this->userId,
            'name' => $name,
            'phone' => $phone,
            'address' => $address,
            'delivery_type' => $deliveryType,
            'total' => $total,
        ]);

        $orderItems = array_map(fn($item) => [
            'product_id' => $item['product_id'],
            'qty' => $item['quantity'],
            'price' => $item['price'],
        ], $items);

        $this->orderItem->createBatch($orderId, $orderItems);
        //очищаємо кошик
        $this->cart->clearSession($this->sessionId, $this->userId);

        $this->redirect(BASE_URL . '/checkout/success?order=' . $orderId);
    }

    public function success(): void
    {
        $orderId = (int)($_GET['order'] ?? 0);

        $this->view('checkout.success', [
            'title' => 'Замовлення прийнято',
            'orderId' => $orderId,
        ]);
    }

    private function calculateTotal(array $items): float
    {
        return array_reduce($items, fn(float $sum, array $item): float => $sum + ($item['price'] * $item['quantity']), 0.0);
    }
}