<?php
class AdminController {

    private function requireAuth() {
        if (empty($_SESSION['admin_logged_in'])) {
            header('Location: ' . BASE_URL . '?page=admin&section=login');
            exit;
        }
    }

    public function login() {
        $error = '';
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $user = $_POST['username'] ?? '';
            $pass = $_POST['password'] ?? '';
            if ($user === ADMIN_USER && $pass === ADMIN_PASS) {
                $_SESSION['admin_logged_in'] = true;
                header('Location: ' . BASE_URL . '?page=admin');
                exit;
            } else {
                $error = 'Invalid credentials.';
            }
        }
        include __DIR__ . '/../views/admin/login.php';
    }

    public function logout() {
        unset($_SESSION['admin_logged_in']);
        header('Location: ' . BASE_URL . '?page=admin&section=login');
        exit;
    }

    public function dashboard() {
        $this->requireAuth();
        $productModel = new ProductModel();
        $orderModel   = new OrderModel();
        $products     = $productModel->getAll();
        $orders       = $orderModel->getAll();
        $revenue      = $orderModel->getTotalRevenue();
        $cartCount    = 0;
        include __DIR__ . '/../views/admin/dashboard.php';
    }

    public function products() {
        $this->requireAuth();
        $productModel = new ProductModel();
        $products     = $productModel->getAll();
        $cartCount    = 0;
        include __DIR__ . '/../views/admin/products.php';
    }

    public function productForm($id = null) {
        $this->requireAuth();
        $productModel = new ProductModel();
        $product      = $id ? $productModel->getById($id) : null;
        $cartCount    = 0;
        include __DIR__ . '/../views/admin/product_form.php';
    }

    public function productSave() {
        $this->requireAuth();
        $productModel = new ProductModel();
        $data = [
            'name'        => htmlspecialchars($_POST['name'] ?? ''),
            'category'    => htmlspecialchars($_POST['category'] ?? 'mixed'),
            'price'       => (int)($_POST['price'] ?? 0),
            'mrp'         => (int)($_POST['mrp'] ?? 0),
            'description' => htmlspecialchars($_POST['description'] ?? ''),
            'weight'      => htmlspecialchars($_POST['weight'] ?? '250g'),
            'image'       => 'product-placeholder.svg',
            'inStock'     => isset($_POST['inStock']),
            'rating'      => (float)($_POST['rating'] ?? 4.0),
            'reviews'     => (int)($_POST['reviews'] ?? 0),
            'featured'    => isset($_POST['featured']),
        ];
        $id = isset($_POST['id']) && $_POST['id'] ? (int)$_POST['id'] : null;
        if ($id) {
            $productModel->update($id, $data);
        } else {
            $productModel->create($data);
        }
        header('Location: ' . BASE_URL . '?page=admin&section=products');
        exit;
    }

    public function productDelete($id) {
        $this->requireAuth();
        $productModel = new ProductModel();
        $productModel->delete($id);
        header('Location: ' . BASE_URL . '?page=admin&section=products');
        exit;
    }

    public function offers() {
        $this->requireAuth();
        $offerModel = new OfferModel();
        $offers     = $offerModel->getAll();

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $action = $_POST['action'] ?? '';
            if ($action === 'create' || $action === 'update') {
                $data = [
                    'title'       => htmlspecialchars($_POST['title'] ?? ''),
                    'description' => htmlspecialchars($_POST['description'] ?? ''),
                    'discount'    => (int)($_POST['discount'] ?? 0),
                    'code'        => strtoupper(htmlspecialchars($_POST['code'] ?? '')),
                    'image'       => 'hero-achar.svg',
                    'active'      => isset($_POST['active']),
                    'minOrder'    => (int)($_POST['minOrder'] ?? 0),
                ];
                if ($action === 'update' && isset($_POST['id'])) {
                    $offerModel->update((int)$_POST['id'], $data);
                } else {
                    $offerModel->create($data);
                }
            } elseif ($action === 'delete' && isset($_POST['id'])) {
                $offerModel->delete((int)$_POST['id']);
            } elseif ($action === 'toggle' && isset($_POST['id'])) {
                $offer = $offerModel->getById((int)$_POST['id']);
                if ($offer) {
                    $offer['active'] = !$offer['active'];
                    $offerModel->update((int)$_POST['id'], $offer);
                }
            }
            header('Location: ' . BASE_URL . '?page=admin&section=offers');
            exit;
        }

        $cartCount = 0;
        include __DIR__ . '/../views/admin/offers.php';
    }

    public function orders() {
        $this->requireAuth();
        $orderModel = new OrderModel();
        $orders     = $orderModel->getAll();
        $cartCount  = 0;
        include __DIR__ . '/../views/admin/orders.php';
    }
}
