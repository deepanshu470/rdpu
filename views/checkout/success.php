<?php
// views/checkout/success.php
?>
<section class="py-5" style="background:linear-gradient(135deg,#f0fdf4,#dcfce7);min-height:70vh;display:flex;align-items:center;">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-7 col-lg-5 text-center">
                <div class="bg-white rounded-4 shadow-lg p-5">
                    <!-- Animated checkmark circle -->
                    <div style="width:100px;height:100px;background:linear-gradient(135deg,#166534,#15803d);border-radius:50%;display:flex;align-items:center;justify-content:center;margin:0 auto 24px;position:relative;" class="success-circle">
                        <div style="position:absolute;width:100px;height:100px;border-radius:50%;background:rgba(22,101,52,0.2);animation:pulse-ring 2s ease-out infinite;"></div>
                        <svg width="50" height="50" viewBox="0 0 50 50" fill="none">
                            <path class="checkmark-path" d="M10 25 L20 35 L40 15" stroke="white" stroke-width="4" stroke-linecap="round" stroke-linejoin="round" fill="none" stroke-dasharray="50" stroke-dashoffset="50" style="animation:checkmark 0.8s ease-out 0.3s forwards;"/>
                        </svg>
                    </div>

                    <h2 style="font-size:1.8rem;font-weight:800;color:#166534;margin-bottom:8px;">Order Placed!</h2>
                    <p style="font-size:1.1rem;color:#57534e;margin-bottom:20px;">
                        🎉 Your delicious pickles are on their way!
                    </p>

                    <?php if ($order && isset($order['id'])): ?>
                    <div class="p-3 rounded-3 mb-4" style="background:#f0fdf4;border:2px dashed #86efac;">
                        <div style="font-size:0.8rem;color:#78716c;margin-bottom:4px;">Order ID</div>
                        <div style="font-size:1.3rem;font-weight:800;color:#166534;font-family:monospace;letter-spacing:2px;"><?php echo htmlspecialchars($order['id']); ?></div>
                    </div>

                    <div class="row g-2 mb-4 text-start">
                        <div class="col-6 p-3 rounded-3" style="background:#f9fafb;">
                            <div style="font-size:0.75rem;color:#78716c;">Customer</div>
                            <div style="font-weight:600;font-size:0.875rem;color:#1c1917;"><?php echo htmlspecialchars($order['customer']['name']); ?></div>
                        </div>
                        <div class="col-6 p-3 rounded-3" style="background:#f9fafb;">
                            <div style="font-size:0.75rem;color:#78716c;">Total Amount</div>
                            <div style="font-weight:700;font-size:1rem;color:#166534;">₹<?php echo number_format($order['total']); ?></div>
                        </div>
                        <div class="col-6 p-3 rounded-3" style="background:#f9fafb;">
                            <div style="font-size:0.75rem;color:#78716c;">Payment</div>
                            <div style="font-weight:600;font-size:0.875rem;color:#1c1917;text-transform:uppercase;"><?php echo htmlspecialchars($order['payment']); ?></div>
                        </div>
                        <div class="col-6 p-3 rounded-3" style="background:#f9fafb;">
                            <div style="font-size:0.75rem;color:#78716c;">Status</div>
                            <div style="font-weight:600;font-size:0.875rem;"><span style="background:#dcfce7;color:#166534;padding:2px 8px;border-radius:12px;">Confirmed ✓</span></div>
                        </div>
                    </div>
                    <?php endif; ?>

                    <div class="p-3 rounded-3 mb-4" style="background:#fef3c7;">
                        <i class="fas fa-truck me-2" style="color:#d97706;"></i>
                        <span style="font-size:0.9rem;color:#92400e;font-weight:600;">Estimated Delivery: 3–5 Business Days</span>
                    </div>

                    <p style="font-size:0.85rem;color:#78716c;margin-bottom:24px;">
                        A confirmation will be sent to your email. Thank you for choosing Achar! 🥒
                    </p>

                    <div class="d-flex flex-column gap-2">
                        <a href="/?page=products" class="btn btn-pickle py-3 fs-6">
                            <i class="fas fa-store me-2"></i>Continue Shopping
                        </a>
                        <a href="/?page=home" class="btn btn-outline-secondary rounded-pill">
                            <i class="fas fa-home me-2"></i>Back to Home
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
