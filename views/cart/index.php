<?php
// views/cart/index.php
?>

<section class="py-5" style="background:#f9fafb;min-height:70vh;">
    <div class="container">
        <div class="d-flex align-items-center gap-2 mb-4">
            <a href="/?page=home" style="color:#166534;text-decoration:none;font-size:0.875rem;"><i class="fas fa-home me-1"></i>Home</a>
            <span style="color:#78716c;">/</span>
            <span style="color:#d97706;font-weight:600;font-size:0.875rem;">Shopping Cart</span>
        </div>

        <h2 style="font-size:1.8rem;font-weight:800;color:#1c1917;margin-bottom:24px;">
            <i class="fas fa-shopping-cart me-2" style="color:#d97706;"></i>
            Shopping Cart
            <span style="font-size:1rem;font-weight:500;color:#78716c;">(<?php echo count($items); ?> item<?php echo count($items) !== 1 ? 's' : ''; ?>)</span>
        </h2>

        <?php if (empty($items)): ?>
        <!-- Empty Cart -->
        <div class="text-center py-5 bg-white rounded-4 shadow-sm">
            <div style="font-size:5rem;margin-bottom:16px;">🛒</div>
            <h4 style="font-weight:700;color:#1c1917;">Your cart is empty!</h4>
            <p style="color:#78716c;margin-bottom:24px;">Looks like you haven't added any pickles yet. Let's fix that!</p>
            <a href="/?page=products" class="btn btn-pickle px-5 py-3 fs-6">
                <i class="fas fa-store me-2"></i>Shop Now
            </a>
        </div>

        <?php else: ?>
        <div class="row g-4">
            <!-- Cart Items -->
            <div class="col-lg-8">
                <form method="POST" action="/?page=cart&action=update" id="cart-form">
                    <div class="bg-white rounded-4 shadow-sm overflow-hidden">
                        <div class="table-responsive">
                            <table class="table mb-0 align-middle">
                                <thead style="background:#fef3c7;">
                                    <tr>
                                        <th style="padding:14px 20px;font-size:0.8rem;color:#57534e;font-weight:700;text-transform:uppercase;letter-spacing:0.5px;border:none;">Product</th>
                                        <th style="padding:14px 12px;font-size:0.8rem;color:#57534e;font-weight:700;text-transform:uppercase;letter-spacing:0.5px;border:none;">Weight</th>
                                        <th style="padding:14px 12px;font-size:0.8rem;color:#57534e;font-weight:700;text-transform:uppercase;letter-spacing:0.5px;border:none;">Unit Price</th>
                                        <th style="padding:14px 12px;font-size:0.8rem;color:#57534e;font-weight:700;text-transform:uppercase;letter-spacing:0.5px;border:none;">Qty</th>
                                        <th style="padding:14px 12px;font-size:0.8rem;color:#57534e;font-weight:700;text-transform:uppercase;letter-spacing:0.5px;border:none;">Subtotal</th>
                                        <th style="padding:14px 12px;font-size:0.8rem;color:#57534e;font-weight:700;text-transform:uppercase;letter-spacing:0.5px;border:none;"></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($items as $id => $item): ?>
                                    <tr style="border-bottom:1px solid #f3f4f6;">
                                        <td style="padding:16px 20px;">
                                            <div class="d-flex align-items-center gap-3">
                                                <div style="width:60px;height:60px;background:linear-gradient(135deg,#fef3c7,#dcfce7);border-radius:10px;display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                                                    <img src="/public/images/<?php echo htmlspecialchars($item['image']); ?>"
                                                         alt="<?php echo htmlspecialchars($item['name']); ?>"
                                                         style="width:50px;height:50px;object-fit:contain;">
                                                </div>
                                                <div>
                                                    <div style="font-weight:600;color:#1c1917;font-size:0.9rem;"><?php echo htmlspecialchars($item['name']); ?></div>
                                                    <a href="/?page=product&id=<?php echo $item['id']; ?>" style="font-size:0.75rem;color:#d97706;text-decoration:none;">View Product</a>
                                                </div>
                                            </div>
                                        </td>
                                        <td style="padding:16px 12px;font-size:0.875rem;color:#78716c;"><?php echo htmlspecialchars($item['weight']); ?></td>
                                        <td style="padding:16px 12px;font-weight:600;color:#166534;">₹<?php echo number_format($item['price']); ?></td>
                                        <td style="padding:16px 12px;">
                                            <div class="d-flex align-items-center gap-1">
                                                <button type="button" class="qty-btn qty-minus">−</button>
                                                <input type="number" 
                                                       name="quantities[<?php echo $id; ?>]" 
                                                       value="<?php echo $item['quantity']; ?>"
                                                       min="1" max="20"
                                                       class="qty-input">
                                                <button type="button" class="qty-btn qty-plus">+</button>
                                            </div>
                                        </td>
                                        <td style="padding:16px 12px;font-weight:700;color:#1c1917;">₹<?php echo number_format($item['price'] * $item['quantity']); ?></td>
                                        <td style="padding:16px 12px;">
                                            <form method="POST" action="/?page=cart&action=remove" style="display:inline;">
                                                <input type="hidden" name="product_id" value="<?php echo $item['id']; ?>">
                                                <button type="submit" class="btn btn-sm" style="background:#fee2e2;color:#dc2626;border:none;border-radius:8px;padding:6px 10px;" title="Remove">
                                                    <i class="fas fa-trash-alt"></i>
                                                </button>
                                            </form>
                                        </td>
                                    </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                        <div class="d-flex justify-content-between align-items-center p-3" style="border-top:1px solid #f3f4f6;">
                            <a href="/?page=products" class="btn btn-outline-secondary rounded-pill px-4">
                                <i class="fas fa-arrow-left me-2"></i>Continue Shopping
                            </a>
                            <button type="submit" class="btn btn-warning fw-bold rounded-pill px-4">
                                <i class="fas fa-sync-alt me-2"></i>Update Cart
                            </button>
                        </div>
                    </div>
                </form>
            </div>

            <!-- Order Summary -->
            <div class="col-lg-4">
                <div class="bg-white rounded-4 shadow-sm p-4 sticky-top" style="top:80px;">
                    <h5 class="fw-bold mb-4" style="color:#1c1917;border-bottom:2px solid #fef3c7;padding-bottom:12px;">
                        <i class="fas fa-receipt me-2" style="color:#d97706;"></i>Order Summary
                    </h5>

                    <div class="d-flex justify-content-between mb-2">
                        <span style="color:#57534e;">Subtotal</span>
                        <span style="font-weight:600;">₹<?php echo number_format($subtotal); ?></span>
                    </div>
                    <div class="d-flex justify-content-between mb-3">
                        <span style="color:#57534e;">Shipping</span>
                        <span style="font-weight:600;color:<?php echo $shipping === 0 ? '#166534' : '#1c1917'; ?>;">
                            <?php echo $shipping === 0 ? '🎉 FREE' : '₹' . $shipping; ?>
                        </span>
                    </div>
                    <?php if ($shipping > 0): ?>
                    <div class="p-2 rounded-3 mb-3" style="background:#fef3c7;font-size:0.8rem;color:#92400e;">
                        <i class="fas fa-truck me-1"></i>
                        Add ₹<?php echo 500 - $subtotal; ?> more for <strong>Free Shipping!</strong>
                    </div>
                    <?php endif; ?>

                    <!-- Coupon -->
                    <div class="mb-3 p-3 rounded-3" style="background:#f9fafb;border:1px solid #e5e7eb;">
                        <label style="font-size:0.85rem;font-weight:600;color:#1c1917;margin-bottom:8px;display:block;">
                            <i class="fas fa-tag me-1" style="color:#d97706;"></i>Coupon Code
                        </label>
                        <div class="input-group input-group-sm">
                            <input type="text" id="coupon-input" class="form-control" placeholder="Enter code (e.g. PICKLE10)" style="text-transform:uppercase;">
                            <button id="apply-coupon" class="btn btn-warning fw-bold">Apply</button>
                        </div>
                        <small style="color:#78716c;font-size:0.72rem;margin-top:4px;display:block;">Try: PICKLE10, SAVE15, FIRST20, FESTIVAL25</small>
                    </div>

                    <hr style="border-color:#fef3c7;">
                    <div class="d-flex justify-content-between mb-4">
                        <span style="font-weight:700;font-size:1.1rem;color:#1c1917;">Total</span>
                        <span style="font-weight:800;font-size:1.3rem;color:#166534;">₹<?php echo number_format($total); ?></span>
                    </div>
                    <a href="/?page=checkout" class="btn btn-pickle w-100 py-3 fs-6">
                        <i class="fas fa-lock me-2"></i>Proceed to Checkout
                    </a>
                    <div class="text-center mt-3" style="font-size:0.75rem;color:#78716c;">
                        <i class="fas fa-shield-alt me-1 text-success"></i>Secure checkout · No hidden charges
                    </div>
                </div>
            </div>
        </div>
        <?php endif; ?>
    </div>
</section>
