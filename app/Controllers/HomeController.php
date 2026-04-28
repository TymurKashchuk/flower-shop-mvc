<?php

namespace app\Controllers;

use core\Controller;
use core\Request;
use app\Repositories\ProductRepository;
use app\Models\Product;
use app\Models\Category;

class HomeController extends Controller
{
    private ProductRepository $repo;

    public function __construct(Request $request)
    {
        parent::__construct($request);
        $this->repo = new ProductRepository(new Product(), new Category());
    }

    public function index(): void
    {
        $featured = $this->repo->getFeaturedProducts(8);
        $categories = $this->repo->getAllCategories();

        $this->view('home.index', [
            'title' => 'Квітковий салон — свіжі квіти з доставкою',
            'featured' => $featured,
            'categories' => $categories,
        ]);
    }
}