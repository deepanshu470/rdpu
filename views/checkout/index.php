<?php
// views/checkout/index.php
$indianStates = ['Andhra Pradesh','Arunachal Pradesh','Assam','Bihar','Chhattisgarh','Goa','Gujarat','Haryana','Himachal Pradesh','Jharkhand','Karnataka','Kerala','Madhya Pradesh','Maharashtra','Manipur','Meghalaya','Mizoram','Nagaland','Odisha','Punjab','Rajasthan','Sikkim','Tamil Nadu','Telangana','Tripura','Uttar Pradesh','Uttarakhand','West Bengal','Andaman & Nicobar Islands','Chandigarh','Dadra & Nagar Haveli','Daman & Diu','Delhi','Jammu & Kashmir','Ladakh','Lakshadweep','Puducherry'];
?>

<section class="py-5" style="background:#f9fafb;min-height:70vh;">
    <div class="container">
        <div class="d-flex align-items-center gap-2 mb-4" style="font-size:0.875rem;">
            <a href="/?page=home" style="color:#166534;text-decoration:none;"><i class="fas fa-home me-1"></i>Home</a>
            <span style="color:#78716c;">/</span>
            <a href="/?page=cart" style="color:#166534;text-decoration:none;">Cart</a>
            <span style="color:#78716c;">/</span>
            <span style="color:#d97706;font-weight:600;">Checkout</span>
        </div>
        <h2 style="font-size:1.8rem;font-weight:800;color:#1c1917;margin-bottom:8px;">
            <i class="fas fa-lock me-2" style="color:#d97706;"></i>Secure Checkout
        </h2>
        <p style="color:#78716c;margin-bottom:28px;">Complete your order in just a few steps</p>

        <form method="POST" action="/?page=checkout&action=process">
            <div class="row g-4">
                <!-- Left: Customer Details -->
                <div class="col-lg-7">
                    <!-- Shipping Info -->
                    <div class="bg-white rounded-4 shadow-sm p-4 mb-4">
                        <h5 class="fw-bold mb-4" style="color:#1c1917;">
                            <i class="fas fa-map-marker-alt me-2" style="color:#d97706;"></i>Delivery Information
                        </h5>
                        <div class="row g-3">
                            <div class="col-12">
                                <label class="form-label fw-600" style="font-size:0.875rem;">Full Name <span class="text-danger">*</span></label>
                                <input type="text" name="name" class="form-control" placeholder="Rahul Sharma" required value="<?php echo htmlspecialchars($_POST['name'] ?? ''); ?>">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-600" style="font-size:0.875rem;">Email Address <span class="text-danger">*</span></label>
                                <input type="email" name="email" class="form-control" placeholder="rahul@example.com" required value="<?php echo htmlspecialchars($_POST['email'] ?? ''); ?>">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-600" style="font-size:0.875rem;">Phone Number <span class="text-danger">*</span></label>
                                <input type="tel" name="phone" class="form-control" placeholder="+91 98765 43210" required value="<?php echo htmlspecialchars($_POST['phone'] ?? ''); ?>">
                            </div>
                            <div class="col-12">
                                <label class="form-label fw-600" style="font-size:0.875rem;">Full Address <span class="text-danger">*</span></label>
                                <input type="text" name="address" class="form-control" placeholder="House No., Street, Area" required value="<?php echo htmlspecialchars($_POST['address'] ?? ''); ?>">
                            </div>
                            <div class="col-md-5">
                                <label class="form-label fw-600" style="font-size:0.875rem;">City <span class="text-danger">*</span></label>
                                <input type="text" name="city" class="form-control" placeholder="Mumbai" required value="<?php echo htmlspecialchars($_POST['city'] ?? ''); ?>">
                            </div>
                            <div class="col-md-4">
                                <label class="form-label fw-600" style="font-size:0.875rem;">State <span class="text-danger">*</span></label>
                                <select name="state" class="form-select" required>
                                    <option value="">Select State</option>
                                    <?php foreach ($indianStates as $state): ?>
                                    <option value="<?php echo htmlspecialchars($state); ?>" <?php echo (isset($_POST['state']) && $_POST['state'] === $state) ? 'selected' : ''; ?>>
                                        <?php echo htmlspecialchars($state); ?>
                                    </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <div class="col-md-3">
                                <label class="form-label fw-600" style="font-size:0.875rem;">PIN Code <span class="text-danger">*</span></label>
                                <input type="text" name="pincode" class="form-control" placeholder="400001" maxlength="6" pattern="[0-9]{6}" required value="<?php echo htmlspecialchars($_POST['pincode'] ?? ''); ?>">
                            </div>
                        </div>
                    </div>

                    <!-- Payment Method -->
                    <div class="bg-white rounded-4 shadow-sm p-4 mb-4">
                        <h5 class="fw-bold mb-4" style="color:#1c1917;">
                            <i class="fas fa-credit-card me-2" style="color:#d97706;"></i>Payment Method
                        </h5>
                        <div class="d-flex flex-column gap-3">
                            <label class="d-flex align-items-center gap-3 p-3 rounded-3 cursor-pointer" style="border:2px solid #e5e7eb;cursor:pointer;transition:border-color 0.2s;" onclick="this.style.borderColor='#d97706'">
                                <input type="radio" name="payment" value="cod" checked class="form-check-input mt-0" style="width:20px;height:20px;">
                                <div>
                                    <div style="font-weight:600;color:#1c1917;font-size:0.9rem;"><i class="fas fa-money-bill-wave me-2" style="color:#166534;"></i>Cash on Delivery (COD)</div>
                                    <div style="font-size:0.78rem;color:#78716c;">Pay when your order arrives at your doorstep</div>
                                </div>
                            </label>
                            <label class="d-flex align-items-center gap-3 p-3 rounded-3 cursor-pointer" style="border:2px solid #e5e7eb;cursor:pointer;transition:border-color 0.2s;" onclick="this.style.borderColor='#d97706'">
                                <input type="radio" name="payment" value="upi" class="form-check-input mt-0" style="width:20px;height:20px;">
                                <div>
                                    <div style="font-weight:600;color:#1c1917;font-size:0.9rem;"><i class="fas fa-mobile-alt me-2" style="color:#7c3aed;"></i>UPI Payment</div>
                                    <div style="font-size:0.78rem;color:#78716c;">Pay via Google Pay, PhonePe, Paytm, or any UPI app</div>
                                </div>
                            </label>
                            <label class="d-flex align-items-center gap-3 p-3 rounded-3 cursor-pointer" style="border:2px solid #e5e7eb;cursor:pointer;transition:border-color 0.2s;" onclick="this.style.borderColor='#d97706'">
                                <input type="radio" name="payment" value="card" class="form-check-input mt-0" style="width:20px;height:20px;">
                                <div>
                                    <div style="font-weight:600;color:#1c1917;font-size:0.9rem;"><i class="fas fa-credit-card me-2" style="color:#1d4ed8;"></i>Credit / Debit Card</div>
                                    <div style="font-size:0.78rem;color:#78716c;">All major cards accepted — Visa, Mastercard, RuPay</div>
                                </div>
                            </label>
                        </div>
                    </div>

                    <!-- Coupon -->
                    <div class="bg-white rounded-4 shadow-sm p-4">
                        <h5 class="fw-bold mb-3" style="color:#1c1917;">
                            <i class="fas fa-tag me-2" style="color:#d97706;"></i>Coupon Code
                        </h5>
                        <div class="input-group">
                            <input type="text" name="coupon" id="coupon-input" class="form-control" placeholder="Enter coupon code (e.g. PICKLE10)" style="text-transform:uppercase;">
                            <button type="button" id="apply-coupon" class="btn btn-warning fw-bold">Apply</button>
                        </div>
                        <small style="color:#78716c;font-size:0.75rem;margin-top:6px;display:block;">
                            Available codes: PICKLE10, SAVE15, FIRST20, FESTIVAL25
                        </small>
                    </div>
                </div>

                <!-- Right: Order Summary -->
                <div class="col-lg-5">
                    <div class="bg-white rounded-4 shadow-sm p-4 sticky-top" style="top:80px;">
                        <h5 class="fw-bold mb-4" style="color:#1c1917;border-bottom:2px solid #fef3c7;padding-bottom:12px;">
                            <i class="fas fa-receipt me-2" style="color:#d97706;"></i>Order Summary
                        </h5>
                        <div style="max-height:260px;overflow-y:auto;">
                            <?php foreach ($items as $item): ?>
                            <div class="d-flex align-items-center gap-3 mb-3 p-2 rounded-3" style="background:#f9fafb;">
                                <div style="width:48px;height:48px;background:linear-gradient(135deg,#fef3c7,#dcfce7);border-radius:8px;display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                                    <img src="/public/images/<?php echo htmlspecialchars($item['image']); ?>"
                                         alt="<?php echo htmlspecialchars($item['name']); ?>"
                                         style="width:40px;height:40px;object-fit:contain;">
                                </div>
                                <div class="flex-grow-1" style="min-width:0;">
                                    <div style="font-weight:600;font-size:0.85rem;color:#1c1917;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;"><?php echo htmlspecialchars($item['name']); ?></div>
                                    <div style="font-size:0.75rem;color:#78716c;"><?php echo htmlspecialchars($item['weight']); ?> × <?php echo $item['quantity']; ?></div>
                                </div>
                                <div style="font-weight:700;color:#166534;white-space:nowrap;">₹<?php echo number_format($item['price'] * $item['quantity']); ?></div>
                            </div>
                            <?php endforeach; ?>
                        </div>
                        <hr style="border-color:#f3f4f6;margin:16px 0;">
                        <div class="d-flex justify-content-between mb-2">
                            <span style="color:#57534e;font-size:0.9rem;">Subtotal</span>
                            <span style="font-weight:600;">₹<?php echo number_format($subtotal); ?></span>
                        </div>
                        <div class="d-flex justify-content-between mb-3">
                            <span style="color:#57534e;font-size:0.9rem;">Shipping</span>
                            <span style="font-weight:600;color:<?php echo $shipping === 0 ? '#166534' : '#1c1917'; ?>;">
                                <?php echo $shipping === 0 ? 'FREE 🎉' : '₹' . $shipping; ?>
                            </span>
                        </div>
                        <hr style="border-color:#fef3c7;">
                        <div class="d-flex justify-content-between mb-4">
                            <span style="font-weight:700;font-size:1.1rem;color:#1c1917;">Total</span>
                            <span style="font-weight:800;font-size:1.4rem;color:#166534;">₹<?php echo number_format($total); ?></span>
                        </div>
                        <button type="submit" class="btn btn-pickle w-100 py-3 fs-6">
                            <i class="fas fa-check-circle me-2"></i>Place Order
                        </button>
                        <div class="text-center mt-3" style="font-size:0.75rem;color:#78716c;">
                            <i class="fas fa-shield-alt me-1 text-success"></i>
                            Your information is 100% secure and encrypted
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
</section>
