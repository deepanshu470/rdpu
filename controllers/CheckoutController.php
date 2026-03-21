<?php
class CheckoutController {
    private $cartModel;
    private $orderModel;

    public function __construct() {
        $this->cartModel  = new CartModel();
        $this->orderModel = new OrderModel();
    }

    public function index() {
        if ($this->cartModel->isEmpty()) {
            header('Location: ' . BASE_URL . '?page=cart');
            exit;
        }
        $items    = $this->cartModel->getItems();
        $subtotal = $this->cartModel->getSubtotal();
        $shipping = $this->cartModel->getShipping();
        $total    = $this->cartModel->getTotal();
        $cartCount = $this->cartModel->getCount();
        include __DIR__ . '/../views/layouts/header.php';
        include __DIR__ . '/../views/checkout/index.php';
        include __DIR__ . '/../views/layouts/footer.php';
    }

    public function process() {
        if ($this->cartModel->isEmpty()) {
            header('Location: ' . BASE_URL . '?page=cart');
            exit;
        }

        $required = ['name', 'email', 'phone', 'address', 'city', 'state', 'pincode'];
        $errors   = [];
        foreach ($required as $field) {
            if (empty($_POST[$field])) {
                $errors[] = ucfirst($field) . ' is required';
            }
        }

        if (!empty($errors)) {
            $_SESSION['flash'] = ['type' => 'error', 'message' => implode(', ', $errors)];
            header('Location: ' . BASE_URL . '?page=checkout');
            exit;
        }

        $couponDiscount = 0;
        if (!empty($_POST['coupon'])) {
            $offerModel = new OfferModel();
            $offer      = $offerModel->validateCode($_POST['coupon']);
            if ($offer) {
                $subtotal       = $this->cartModel->getSubtotal();
                $couponDiscount = round($subtotal * $offer['discount'] / 100);
            }
        }

        $order = $this->orderModel->create([
            'customer' => [
                'name'    => htmlspecialchars($_POST['name']),
                'email'   => htmlspecialchars($_POST['email']),
                'phone'   => htmlspecialchars($_POST['phone']),
                'address' => htmlspecialchars($_POST['address']),
                'city'    => htmlspecialchars($_POST['city']),
                'state'   => htmlspecialchars($_POST['state']),
                'pincode' => htmlspecialchars($_POST['pincode']),
            ],
            'items'    => $this->cartModel->getItems(),
            'subtotal' => $this->cartModel->getSubtotal(),
            'shipping' => $this->cartModel->getShipping(),
            'discount' => $couponDiscount,
            'total'    => $this->cartModel->getTotal($couponDiscount),
            'payment'  => htmlspecialchars($_POST['payment'] ?? 'cod'),
        ]);

        $this->cartModel->clear();
        $_SESSION['last_order'] = $order;
        header('Location: ' . BASE_URL . '?page=checkout&step=success');
        exit;
    }

    public function success() {
        $order     = $_SESSION['last_order'] ?? null;
        $cartCount = $this->cartModel->getCount();
        include __DIR__ . '/../views/layouts/header.php';
        include __DIR__ . '/../views/checkout/success.php';
        include __DIR__ . '/../views/layouts/footer.php';
    }
}
