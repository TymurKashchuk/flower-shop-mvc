<?php

namespace app\Controllers;

use core\Controller;
use core\Middleware;
use core\Request;
use core\Response;
use app\Models\Review;
use app\Models\Product;

class ReviewController extends Controller
{
    private Review $review;
    private Product $product;

    public function __construct(Request $request)
    {
        parent::__construct($request);
        $this->review = new Review();
        $this->product = new Product();
    }

    public function store(string $slug): void
    {
        Middleware::auth();

        $product = $this->product->findBySlug($slug);
        if (!$product) {
            Response::setStatus(404);
            $this->view('errors.404');
            return;
        }

        $userId = $_SESSION['user']['id'];

        if ($this->review->hasReviewed($userId, $product['id'])) {
            $_SESSION['error'] = 'Ви вже залишили відгук на цей товар.';
            $this->redirect(BASE_URL . '/catalog/' . $slug);
            return;
        }

        $rating = (int)$this->request->post('rating', 0);
        $text = trim($this->request->post('text', ''));
        $errors = [];

        if ($rating < 1 || $rating > 5) {
            $errors[] = 'Оцінка має бути від 1 до 5.';
        }
        if (mb_strlen($text) < 5) {
            $errors[] = 'Відгук занадто короткий.';
        }

        if ($errors) {
            $_SESSION['error'] = implode(' ', $errors);
            $this->redirect(BASE_URL . '/catalog/' . $slug);
            return;
        }

        $this->review->create([
            'user_id' => $userId,
            'product_id' => $product['id'],
            'rating' => $rating,
            'text' => $text,
        ]);

        $_SESSION['success'] = 'Дякуємо! Відгук відправлено на модерацію.';
        $this->redirect(BASE_URL . '/catalog/' . $slug);
    }

    public function adminIndex(): void
    {
        Middleware::admin();
        $this->view('reviews.admin_index', [
            'title' => 'Управління відгуками',
            'items' => $this->review->allForAdmin(),
        ], 'admin');
    }

    public function approve(string $id): void
    {
        Middleware::admin();
        $this->review->approve((int)$id);
        $_SESSION['success'] = 'Відгук опубліковано!';
        $this->redirect(BASE_URL . '/admin/reviews');
    }

    public function delete(string $id): void
    {
        Middleware::admin();
        $this->review->delete((int)$id);
        $_SESSION['success'] = 'Відгук видалено!';
        $this->redirect(BASE_URL . '/admin/reviews');
    }

    public function reply(string $id): void
    {
        Middleware::admin();
        $text = trim($this->request->post('reply', ''));
        if(mb_strlen($text) < 2) {
            $_SESSION['error'] = 'Відповідь занадто коротка.';
            $this->redirect(BASE_URL . '/admin/reviews');
            return;
        }

        $this->review->reply((int)$id, $text);
        $_SESSION['success'] = 'Відповідь збережено!';
        $this->redirect(BASE_URL . '/admin/reviews');
    }
}