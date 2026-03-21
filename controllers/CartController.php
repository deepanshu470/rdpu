<?php
class CartController {
    private $cartModel;
    private $productModel;

    public function __construct() {
        $this->cartModel    = new CartModel();
        $this->productModel = new ProductModel();
    }

    public function index() {
        $items    = $this->cartModel->getItems();
        $subtotal = $this->cartModel->getSubtotal();
        $shipping = $this->cartModel->getShipping();
        $total    = $this->cartModel->getTotal();
        $cartCount = $this->cartModel->getCount();
        include __DIR__ . '/../views/layouts/header.php';
        include __DIR__ . '/../views/cart/index.php';
        include __DIR__ . '/../views/layouts/footer.php';
    }

    public function add() {
        $productId = isset($_POST['product_id']) ? (int)$_POST['product_id'] : 0;
        $quantity  = isset($_POST['quantity']) ? (int)$_POST['quantity'] : 1;
        $product   = $this->productModel->getById($productId);

        if ($product && $product['inStock']) {
            $this->cartModel->add($productId, $quantity, $product);
            $message = 'Product added to cart!';
            $success = true;
        } else {
            $message = 'Product not available.';
            $success = false;
        }

        if (isset($_POST['ajax'])) {
            header('Content-Type: application/json');
            echo json_encode([
                'success'   => $success,
                'message'   => $message,
                'cartCount' => $this->cartModel->getCount(),
            ]);
            exit;
        }

        $_SESSION['flash'] = ['type' => $success ? 'success' : 'error', 'message' => $message];
        header('Location: ' . BASE_URL . '?page=cart');
        exit;
    }

    public function remove() {
        $productId = isset($_POST['product_id']) ? (int)$_POST['product_id'] : 0;
        $this->cartModel->remove($productId);
        header('Location: ' . BASE_URL . '?page=cart');
        exit;
    }

    public function update() {
        if (isset($_POST['quantities']) && is_array($_POST['quantities'])) {
            foreach ($_POST['quantities'] as $id => $qty) {
                $this->cartModel->update((int)$id, (int)$qty);
            }
        }
        header('Location: ' . BASE_URL . '?page=cart');
        exit;
    }
}
