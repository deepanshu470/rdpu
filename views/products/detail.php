<?php
// views/products/detail.php
$discountPct = $product['mrp'] > 0 ? round((($product['mrp'] - $product['price']) / $product['mrp']) * 100) : 0;
?>

<section class="py-4" style="background:#f9fafb;">
    <div class="container">
        <!-- Breadcrumb -->
        <nav aria-label="breadcrumb" class="mb-4">
            <ol class="breadcrumb" style="font-size:0.875rem;">
                <li class="breadcrumb-item"><a href="/?page=home" style="color:#166534;text-decoration:none;"><i class="fas fa-home me-1"></i>Home</a></li>
                <li class="breadcrumb-item"><a href="/?page=products" style="color:#166534;text-decoration:none;">Products</a></li>
                <li class="breadcrumb-item active" style="color:#d97706;"><?php echo htmlspecialchars($product['name']); ?></li>
            </ol>
        </nav>

        <div class="row g-5">
            <!-- Product Image -->
            <div class="col-lg-5">
                <div class="rounded-4 overflow-hidden shadow-sm p-4" style="background:linear-gradient(135deg,#fef3c7,#dcfce7);min-height:360px;display:flex;align-items:center;justify-content:center;position:relative;">
                    <?php if ($discountPct > 0): ?>
                    <div style="position:absolute;top:16px;left:16px;background:#dc2626;color:white;padding:4px 12px;border-radius:20px;font-size:0.8rem;font-weight:700;">
                        <?php echo $discountPct; ?>% OFF
                    </div>
                    <?php endif; ?>
                    <?php if (!$product['inStock']): ?>
                    <div style="position:absolute;top:16px;right:16px;background:#6b7280;color:white;padding:4px 12px;border-radius:20px;font-size:0.8rem;font-weight:600;">
                        Out of Stock
                    </div>
                    <?php endif; ?>
                    <img src="/public/images/<?php echo htmlspecialchars($product['image']); ?>" 
                         alt="<?php echo htmlspecialchars($product['name']); ?>"
                         style="max-width:280px;width:100%;height:auto;object-fit:contain;">
                </div>
                <!-- Trust badges -->
                <div class="row g-2 mt-3 text-center" style="font-size:0.75rem;color:#57534e;">
                    <div class="col-4 p-2 rounded-3 bg-white shadow-sm">
                        <i class="fas fa-leaf text-success fs-5 d-block mb-1"></i>Natural
                    </div>
                    <div class="col-4 p-2 rounded-3 bg-white shadow-sm">
                        <i class="fas fa-shield-alt text-warning fs-5 d-block mb-1"></i>Pure
                    </div>
                    <div class="col-4 p-2 rounded-3 bg-white shadow-sm">
                        <i class="fas fa-truck text-primary fs-5 d-block mb-1"></i>Fast Ship
                    </div>
                </div>
            </div>

            <!-- Product Details -->
            <div class="col-lg-7">
                <span style="background:#fef3c7;color:#d97706;padding:4px 12px;border-radius:12px;font-size:0.8rem;font-weight:600;text-transform:capitalize;">
                    <?php echo htmlspecialchars($product['category']); ?> Pickle
                </span>

                <h1 style="font-size:1.8rem;font-weight:800;color:#1c1917;margin-top:10px;margin-bottom:8px;">
                    <?php echo htmlspecialchars($product['name']); ?>
                </h1>

                <!-- Rating -->
                <div class="d-flex align-items-center gap-2 mb-3">
                    <div class="stars">
                        <?php
                        $rating = $product['rating'];
                        for ($i = 1; $i <= 5; $i++):
                            if ($i <= floor($rating)) echo '<i class="fas fa-star"></i>';
                            elseif ($i - $rating < 1 && $i - $rating > 0) echo '<i class="fas fa-star-half-alt"></i>';
                            else echo '<i class="far fa-star" style="color:#d1d5db;"></i>';
                        endfor;
                        ?>
                    </div>
                    <span style="font-weight:700;color:#1c1917;"><?php echo $product['rating']; ?></span>
                    <span style="color:#78716c;font-size:0.875rem;">(<?php echo $product['reviews']; ?> reviews)</span>
                </div>

                <!-- Price -->
                <div class="d-flex align-items-center gap-3 mb-3 p-3 rounded-3" style="background:#fef3c7;">
                    <span class="price-current" style="font-size:1.8rem;">₹<?php echo $product['price']; ?></span>
                    <div>
                        <span class="price-mrp d-block">MRP: ₹<?php echo $product['mrp']; ?></span>
                        <?php if ($discountPct > 0): ?>
                        <span class="discount-badge"><?php echo $discountPct; ?>% off — You save ₹<?php echo ($product['mrp'] - $product['price']); ?></span>
                        <?php endif; ?>
                    </div>
                </div>

                <!-- Meta -->
                <div class="d-flex flex-wrap gap-3 mb-3">
                    <div class="d-flex align-items-center gap-2 p-2 rounded-3 bg-white shadow-sm">
                        <i class="fas fa-weight" style="color:#d97706;"></i>
                        <span style="font-size:0.875rem;font-weight:600;">Weight: <?php echo htmlspecialchars($product['weight']); ?></span>
                    </div>
                    <div class="d-flex align-items-center gap-2 p-2 rounded-3 bg-white shadow-sm">
                        <?php if ($product['inStock']): ?>
                        <i class="fas fa-check-circle" style="color:#166534;"></i>
                        <span style="font-size:0.875rem;font-weight:600;color:#166534;">In Stock</span>
                        <?php else: ?>
                        <i class="fas fa-times-circle" style="color:#dc2626;"></i>
                        <span style="font-size:0.875rem;font-weight:600;color:#dc2626;">Out of Stock</span>
                        <?php endif; ?>
                    </div>
                </div>

                <!-- Description -->
                <p style="color:#57534e;line-height:1.8;font-size:0.95rem;margin-bottom:20px;">
                    <?php echo htmlspecialchars($product['description']); ?>
                </p>

                <!-- Add to Cart Form -->
                <?php if ($product['inStock']): ?>
                <form id="detail-cart-form" class="d-flex flex-wrap gap-3 align-items-center mb-4">
                    <div class="d-flex align-items-center gap-2">
                        <label class="fw-600 me-2" style="font-size:0.875rem;">Quantity:</label>
                        <button type="button" class="qty-btn qty-minus">−</button>
                        <input type="number" id="qty-<?php echo $product['id']; ?>" class="qty-input" value="1" min="1" max="20">
                        <button type="button" class="qty-btn qty-plus">+</button>
                    </div>
                    <button type="button" class="btn btn-pickle px-4 py-2 add-to-cart-btn" data-id="<?php echo $product['id']; ?>">
                        <i class="fas fa-cart-plus me-2"></i>Add to Cart
                    </button>
                    <a href="/?page=cart" class="btn btn-outline-warning px-4 py-2 rounded-pill fw-bold">
                        <i class="fas fa-shopping-cart me-2"></i>View Cart
                    </a>
                </form>
                <?php else: ?>
                <div class="alert alert-warning rounded-3">
                    <i class="fas fa-exclamation-triangle me-2"></i>
                    This product is currently out of stock. <a href="/?page=products" class="fw-bold" style="color:#d97706;">Browse other pickles</a>
                </div>
                <?php endif; ?>

                <!-- Free shipping notice -->
                <div class="p-3 rounded-3 d-flex align-items-center gap-3" style="background:#dcfce7;border:1px solid #86efac;">
                    <i class="fas fa-truck" style="color:#166534;font-size:1.2rem;"></i>
                    <div>
                        <div style="font-weight:600;color:#166534;font-size:0.875rem;">Free Delivery on orders above ₹500</div>
                        <div style="color:#15803d;font-size:0.8rem;">Or ₹60 flat shipping on smaller orders</div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Related Products -->
        <?php if (!empty($related)): ?>
        <div class="mt-5">
            <h3 style="font-size:1.5rem;font-weight:800;color:#1c1917;margin-bottom:24px;">
                <i class="fas fa-heart me-2" style="color:#d97706;"></i>You Might Also Like
            </h3>
            <div class="row g-4">
                <?php foreach ($related as $rp): ?>
                <div class="col-sm-6 col-lg-3">
                    <div class="product-card card border-0 shadow-sm h-100">
                        <a href="/?page=product&id=<?php echo $rp['id']; ?>" class="text-decoration-none">
                            <div class="card-img-wrapper">
                                <img src="/public/images/<?php echo htmlspecialchars($rp['image']); ?>" 
                                     alt="<?php echo htmlspecialchars($rp['name']); ?>">
                            </div>
                        </a>
                        <div class="card-body p-3">
                            <a href="/?page=product&id=<?php echo $rp['id']; ?>" class="text-decoration-none">
                                <h6 class="fw-bold mb-1" style="color:#1c1917;"><?php echo htmlspecialchars($rp['name']); ?></h6>
                            </a>
                            <div class="d-flex align-items-center gap-2 mb-2">
                                <span class="price-current" style="font-size:1.1rem;">₹<?php echo $rp['price']; ?></span>
                                <span class="price-mrp">₹<?php echo $rp['mrp']; ?></span>
                            </div>
                            <button class="btn btn-pickle w-100 btn-sm add-to-cart-btn" data-id="<?php echo $rp['id']; ?>" <?php echo !$rp['inStock'] ? 'disabled' : ''; ?>>
                                <i class="fas fa-cart-plus me-1"></i><?php echo $rp['inStock'] ? 'Add to Cart' : 'Out of Stock'; ?>
                            </button>
                        </div>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
        </div>
        <?php endif; ?>
    </div>
</section>
