<?php

namespace app\Controllers;

use core\Controller;
use core\Database;
use core\Middleware;
use core\Request;
use app\Models\News;
use app\Models\Review;

class AdminController extends Controller
{
    public function __construct(Request $request)
    {
        parent::__construct($request);
    }

    public function dashboard(): void
    {
        Middleware::admin();
        $news = new News();
        $review = new Review();

        $db = Database::getInstance();

        $this->view('admin.dashboard', [
            'title' => 'Дашборд',
            'stats' => [
                'products' => $db->query("SELECT COUNT(*) FROM products")->fetchColumn(),
                'news' => $db->query("SELECT COUNT(*) FROM news")->fetchColumn(),
                'reviews' => $db->query("SELECT COUNT(*) FROM reviews")->fetchColumn(),
                'pending_reviews' => $db->query("SELECT COUNT(*) FROM reviews WHERE is_active = 0")->fetchColumn(),
                'orders' => $db->query("SELECT COUNT(*) FROM orders")->fetchColumn(),
                'new_orders' => $db->query("SELECT COUNT(*) FROM orders WHERE status = 'new'")->fetchColumn(),
            ],
        ], 'admin');
    }
}