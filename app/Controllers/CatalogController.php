<?php

namespace app\Controllers;

use core\Controller;
use core\Request;
use core\Response;
use app\Repositories\ProductRepository;
use app\Models\Product;
use app\Models\Category;

class CatalogController extends Controller
{
    private ProductRepository $repo;

    public function __construct(Request $request)
    {
        parent::__construct($request);
        $this->repo = new ProductRepository(new Product(), new Category());
    }

    public function index(): void
    {
        $categorySlug = $this->request->get('category');
        $priceMin = $this->request->get('price_min') ? (float)$this->request->get('price_min') : null;
        $priceMax = $this->request->get('price_max') ? (float)$this->request->get('price_max') : null;
        $page = max(1, (int)$this->request->get('page', 1));

        $categoryId = null;
        if ($categorySlug) {
            $category = $this->repo->getCategoryBySlug($categorySlug);
            $categoryId = $category['id'] ?? null;
        }

        if ($categoryId || $priceMin || $priceMax) {
            $products = $this->repo->getCatalogProducts($categoryId, $priceMin, $priceMax);
            $pagination = null;
        } else {
            $paginated = $this->repo->getPaginatedProducts($page);
            $products = $paginated['products'];
            $pagination = $paginated;
        }

        $categories = $this->repo->getAllCategories();

        $this->view('catalog.index', [
            'title' => 'Каталог квітів',
            'products' => $products,
            'categories' => $categories,
            'pagination' => $pagination,
            'categorySlug' => $categorySlug,
            'priceMin' => $priceMin,
            'priceMax' => $priceMax,
        ]);
    }

    public function show(string $slug): void
    {
        $product = $this->repo->getProductBySlug($slug);

        if (!$product) {
            Response::notFound();
            $this->view('errors.404');
            return;
        }

        $categories = $this->repo->getAllCategories();

        $this->view('catalog.show', [
            'title' => $product['name'],
            'product' => $product,
            'categories' => $categories,
        ]);
    }

    public function search(): void
    {
        $query = trim($this->request->get('q', ''));
        $products = $this->repo->searchProducts($query);

        $result = array_map(fn($p) => [
            'id' => $p['id'],
            'name' => $p['name'],
            'slug' => $p['slug'],
            'price' => $p['price'],
            'image' => $p['image'],
        ], $products);

        Response::json([
            'success' => true,
            'products' => $result,
            'count' => count($result),
        ]);
    }
}