<?php
class CartModel {
    private function &getCart() {
        if (!isset($_SESSION['cart'])) {
            $_SESSION['cart'] = [];
        }
        return $_SESSION['cart'];
    }

    public function add($productId, $quantity = 1, $productData = []) {
        $cart = &$this->getCart();
        if (isset($cart[$productId])) {
            $cart[$productId]['quantity'] += $quantity;
        } else {
            $cart[$productId] = [
                'id'       => $productId,
                'quantity' => $quantity,
                'name'     => $productData['name'] ?? '',
                'price'    => $productData['price'] ?? 0,
                'image'    => $productData['image'] ?? 'product-placeholder.svg',
                'weight'   => $productData['weight'] ?? '',
            ];
        }
    }

    public function remove($productId) {
        $cart = &$this->getCart();
        unset($cart[$productId]);
    }

    public function update($productId, $quantity) {
        $cart = &$this->getCart();
        if ($quantity <= 0) {
            $this->remove($productId);
        } elseif (isset($cart[$productId])) {
            $cart[$productId]['quantity'] = (int)$quantity;
        }
    }

    public function getItems() {
        return $this->getCart();
    }

    public function getCount() {
        $cart = $this->getCart();
        return array_sum(array_column($cart, 'quantity'));
    }

    public function getSubtotal() {
        $cart = $this->getCart();
        $total = 0;
        foreach ($cart as $item) {
            $total += $item['price'] * $item['quantity'];
        }
        return $total;
    }

    public function getTotal($couponDiscount = 0) {
        $subtotal = $this->getSubtotal();
        $shipping  = $subtotal >= 500 ? 0 : 60;
        return $subtotal + $shipping - $couponDiscount;
    }

    public function getShipping() {
        return $this->getSubtotal() >= 500 ? 0 : 60;
    }

    public function clear() {
        $_SESSION['cart'] = [];
    }

    public function isEmpty() {
        return empty($this->getCart());
    }
}
