<?php

namespace app\Controllers;

use core\Controller;
use core\Request;
use core\Response;
use app\Models\Cart;
use app\Models\Product;

class CartController extends Controller
{
    private Cart $cart;
    private Product $product;
    private string $sessionId;
    private ?int $userId = null;

    public function __construct(Request $request)
    {
        parent::__construct($request);
        $this->cart = new Cart();
        $this->product = new Product();
        $this->sessionId = session_id();
        $this->userId = $_SESSION['user']['id'] ?? null;
    }

    public function index(): void
    {
        $items = $this->cart->getBySession($this->sessionId, $this->userId);
        $total = $this->calculateTotal($items);

        $this->view('cart.index', [
            'title' => 'Кошик',
            'items' => $items,
            'total' => $total,
        ]);
    }

    public function count(): void
    {
        Response::json([
            'count' => $this->cart->countItems($this->sessionId, $this->userId),
        ]);
    }

    public function add(): void
    {
        $productId = (int)$this->request->post('product_id');
        $quantity = max(1, (int)$this->request->post('quantity', 1));

        $product = $this->product->find($productId);

        if (!$product || !$product['is_active']) {
            $_SESSION['error'] = 'Товар не знайдено.';
            $this->redirect(BASE_URL . '/catalog');
            return;
        }

        if ($product['stock'] <= 0) {
            $_SESSION['error'] = 'На жаль, цього товару немає в наявності.';
            $this->redirect(BASE_URL . '/catalog/' . $product['slug']);
            return;
        }

        $quantity = min($quantity, $product['stock']);

        $this->cart->addItem($this->sessionId, $productId, $quantity, $this->userId);

        if ($this->request->isAjax()) {
            Response::json([
                'success' => true,
                'count' => $this->cart->countItems($this->sessionId, $this->userId),
                'message' => 'Товар додано до кошика',
            ]);
            return;
        }

        $_SESSION['success'] = 'Товар додано до кошика!';
        $this->redirect(BASE_URL . '/cart');
    }

    public function update(): void
    {
        $itemId = (int)$this->request->post('item_id');
        $quantity = (int)$this->request->post('quantity');

        if ($quantity < 1) {
            $this->cart->removeItem($itemId, $this->sessionId, $this->userId);
        } else {
            $cartItems = $this->cart->getBySession($this->sessionId, $this->userId);
            $cartItem = array_values(array_filter($cartItems, fn($i) => $i['id'] === $itemId))[0] ?? null;

            if ($cartItem) {
                $product = $this->product->find($cartItem['product_id']);
                $quantity = min($quantity, $product['stock'] ?? $quantity);
            }

            $this->cart->updateQuantity($itemId, $this->sessionId, $quantity, $this->userId);
        }

        if ($this->request->isAjax()) {
            $items = $this->cart->getBySession($this->sessionId, $this->userId);
            Response::json([
                'success' => true,
                'count' => $this->cart->countItems($this->sessionId, $this->userId),
                'total' => number_format($this->calculateTotal($items), 2, '.', ' '),
            ]);
            return;
        }

        $this->redirect(BASE_URL . '/cart');
    }

    public function remove(): void
    {
        $itemId = (int)$this->request->post('item_id');
        $this->cart->removeItem($itemId, $this->sessionId, $this->userId);

        if ($this->request->isAjax()) {
            $items = $this->cart->getBySession($this->sessionId, $this->userId);
            Response::json([
                'success' => true,
                'count' => $this->cart->countItems($this->sessionId, $this->userId),
                'total' => number_format($this->calculateTotal($items), 2, '.', ' '),
            ]);
            return;
        }

        $_SESSION['success'] = 'Товар видалено з кошика.';
        $this->redirect(BASE_URL . '/cart');
    }

    public function clear(): void
    {
        $this->cart->clearSession($this->sessionId, $this->userId);

        $_SESSION['success'] = 'Кошик очищено.';
        $this->redirect(BASE_URL . '/cart');
    }

    private function calculateTotal(array $items): float
    {
        return array_reduce($items, function (float $sum, array $item): float {
            return $sum + ($item['price'] * $item['quantity']);
        }, 0.0);
    }
}