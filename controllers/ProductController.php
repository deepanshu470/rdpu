<?php
class ProductController {
    private $productModel;

    public function __construct() {
        $this->productModel = new ProductModel();
    }

    public function index() {
        $category = isset($_GET['category']) ? $_GET['category'] : 'all';
        $search   = isset($_GET['search']) ? trim($_GET['search']) : '';
        $sort     = isset($_GET['sort']) ? $_GET['sort'] : 'default';

        if ($search) {
            $products = $this->productModel->search($search);
        } else {
            $products = $this->productModel->getByCategory($category);
        }

        if ($sort === 'price_asc') {
            usort($products, fn($a, $b) => $a['price'] - $b['price']);
        } elseif ($sort === 'price_desc') {
            usort($products, fn($a, $b) => $b['price'] - $a['price']);
        } elseif ($sort === 'rating') {
            usort($products, fn($a, $b) => $b['rating'] <=> $a['rating']);
        }

        $cartModel = new CartModel();
        $cartCount = $cartModel->getCount();
        include __DIR__ . '/../views/layouts/header.php';
        include __DIR__ . '/../views/products/index.php';
        include __DIR__ . '/../views/layouts/footer.php';
    }

    public function detail($id) {
        $product = $this->productModel->getById($id);
        if (!$product) {
            header('Location: ' . BASE_URL . '?page=products');
            exit;
        }
        $related = array_slice(
            array_values(array_filter(
                $this->productModel->getByCategory($product['category']),
                fn($p) => $p['id'] != $id
            )),
            0, 4
        );
        $cartModel = new CartModel();
        $cartCount = $cartModel->getCount();
        include __DIR__ . '/../views/layouts/header.php';
        include __DIR__ . '/../views/products/detail.php';
        include __DIR__ . '/../views/layouts/footer.php';
    }
}
