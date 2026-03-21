<?php
require_once __DIR__ . '/config/config.php';
require_once __DIR__ . '/models/ProductModel.php';
require_once __DIR__ . '/models/CartModel.php';
require_once __DIR__ . '/models/OrderModel.php';
require_once __DIR__ . '/models/OfferModel.php';
require_once __DIR__ . '/controllers/HomeController.php';
require_once __DIR__ . '/controllers/ProductController.php';
require_once __DIR__ . '/controllers/CartController.php';
require_once __DIR__ . '/controllers/CheckoutController.php';
require_once __DIR__ . '/controllers/AdminController.php';

$page    = $_GET['page'] ?? 'home';
$action  = $_GET['action'] ?? '';
$section = $_GET['section'] ?? '';
$id      = isset($_GET['id']) ? (int)$_GET['id'] : null;
$step    = $_GET['step'] ?? '';

switch ($page) {
    case 'home':
    case '':
        $controller = new HomeController();
        $controller->index();
        break;

    case 'products':
        $controller = new ProductController();
        $controller->index();
        break;

    case 'product':
        $controller = new ProductController();
        $controller->detail($id);
        break;

    case 'cart':
        $controller = new CartController();
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && $action === 'add') {
            $controller->add();
        } elseif ($_SERVER['REQUEST_METHOD'] === 'POST' && $action === 'remove') {
            $controller->remove();
        } elseif ($_SERVER['REQUEST_METHOD'] === 'POST' && $action === 'update') {
            $controller->update();
        } else {
            $controller->index();
        }
        break;

    case 'checkout':
        $controller = new CheckoutController();
        if ($step === 'success') {
            $controller->success();
        } elseif ($_SERVER['REQUEST_METHOD'] === 'POST' && $action === 'process') {
            $controller->process();
        } else {
            $controller->index();
        }
        break;

    case 'admin':
        $controller = new AdminController();
        if ($section === 'login') {
            $controller->login();
        } elseif ($section === 'logout') {
            $controller->logout();
        } elseif ($section === 'products') {
            $controller->products();
        } elseif ($section === 'product_new') {
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                $controller->productSave();
            } else {
                $controller->productForm();
            }
        } elseif ($section === 'product_edit') {
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                $controller->productSave();
            } else {
                $controller->productForm($id);
            }
        } elseif ($section === 'product_delete') {
            $controller->productDelete($id);
        } elseif ($section === 'offers') {
            $controller->offers();
        } elseif ($section === 'orders') {
            $controller->orders();
        } else {
            if (empty($_SESSION['admin_logged_in'])) {
                header('Location: ' . BASE_URL . '?page=admin&section=login');
                exit;
            }
            $controller->dashboard();
        }
        break;

    default:
        $controller = new HomeController();
        $controller->index();
        break;
}
