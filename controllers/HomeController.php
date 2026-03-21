<?php
class HomeController {
    private $productModel;
    private $offerModel;

    public function __construct() {
        $this->productModel = new ProductModel();
        $this->offerModel   = new OfferModel();
    }

    public function index() {
        $featured     = $this->productModel->getFeatured();
        $offers       = $this->offerModel->getActive();
        $testimonials = json_decode(file_get_contents(DATA_DIR . 'testimonials.json'), true) ?: [];
        $cartModel    = new CartModel();
        $cartCount    = $cartModel->getCount();
        include __DIR__ . '/../views/layouts/header.php';
        include __DIR__ . '/../views/home/index.php';
        include __DIR__ . '/../views/layouts/footer.php';
    }
}
