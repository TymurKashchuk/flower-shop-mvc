<?php

namespace app\Controllers;

use core\Controller;
use core\Database;
use core\Middleware;
use core\Request;

class AdminController extends Controller
{
    public function __construct(Request $request)
    {
        parent::__construct($request);
    }

    public function dashboard(): void
    {
        Middleware::admin();

        $db = Database::getInstance();

        // Key metrics
        $stats = [
            'products' => (int)$db->query("SELECT COUNT(*) FROM products")->fetchColumn(),
            'news' => (int)$db->query("SELECT COUNT(*) FROM news")->fetchColumn(),
            'reviews' => (int)$db->query("SELECT COUNT(*) FROM reviews")->fetchColumn(),
            'pending_reviews' => (int)$db->query("SELECT COUNT(*) FROM reviews WHERE is_active = 0")->fetchColumn(),
            'orders' => (int)$db->query("SELECT COUNT(*) FROM orders")->fetchColumn(),
            'new_orders' => (int)$db->query("SELECT COUNT(*) FROM orders WHERE status = 'new'")->fetchColumn(),
            'revenue' => (float)$db->query("SELECT COALESCE(SUM(total), 0) FROM orders WHERE status = 'done'")->fetchColumn(),
            'low_stock' => (int)$db->query("SELECT COUNT(*) FROM products WHERE stock <= 3")->fetchColumn(),
        ];

        // Recent 5 orders for immediate glance
        $recentOrders = $db->query("
            SELECT id, name, phone, total, status, created_at, delivery_type 
            FROM orders 
            ORDER BY id DESC 
            LIMIT 5
        ")->fetchAll();

        // Recent reviews that may require attention
        $recentReviews = $db->query("
            SELECT r.id, COALESCE(u.name, 'Анонім') AS user_name, r.rating, r.text, r.is_active, r.created_at, p.name AS product_name 
            FROM reviews r 
            LEFT JOIN users u ON u.id = r.user_id
            LEFT JOIN products p ON p.id = r.product_id 
            ORDER BY r.is_active ASC, r.id DESC 
            LIMIT 4
        ")->fetchAll();

        $this->view('admin.dashboard', [
            'title' => 'Дашборд',
            'stats' => $stats,
            'recentOrders' => $recentOrders,
            'recentReviews' => $recentReviews,
        ], 'admin');
    }
}