<?php
// views/home/index.php
?>

<!-- ===================== HERO ===================== -->
<section style="background:linear-gradient(135deg,#fef3c7 0%,#fed7aa 30%,#d1fae5 70%,#a7f3d0 100%);min-height:90vh;display:flex;align-items:center;overflow:hidden;position:relative;">
    <!-- Decorative blobs -->
    <div style="position:absolute;top:-80px;right:-80px;width:400px;height:400px;background:radial-gradient(circle,rgba(217,119,6,0.15),transparent 70%);border-radius:50%;"></div>
    <div style="position:absolute;bottom:-60px;left:-60px;width:300px;height:300px;background:radial-gradient(circle,rgba(22,101,52,0.12),transparent 70%);border-radius:50%;"></div>

    <div class="container py-5">
        <div class="row align-items-center g-5">
            <div class="col-lg-6 hero-text">
                <span style="background:#166534;color:white;padding:6px 16px;border-radius:20px;font-size:0.8rem;font-weight:600;letter-spacing:1px;">🏆 #1 Homemade Pickle Brand</span>
                <h1 style="font-size:clamp(2.2rem,5vw,3.8rem);font-weight:800;line-height:1.15;color:#1c1917;margin-top:16px;">
                    India's Finest<br>
                    <span style="background:linear-gradient(135deg,#d97706,#ea580c);-webkit-background-clip:text;-webkit-text-fill-color:transparent;background-clip:text;">Homemade</span><br>
                    <span style="color:#166534;">Pickles</span> 🥒
                </h1>
                <p style="font-size:1.1rem;color:#57534e;line-height:1.8;margin:20px 0;">
                    Crafted with love using <strong>pure ingredients</strong>, traditional family recipes, and <strong>no preservatives</strong>. 
                    From Mango Achar to Gongura — taste India's diverse pickle heritage delivered to your doorstep.
                </p>
                <div class="d-flex flex-wrap gap-3 mb-4">
                    <div class="d-flex align-items-center gap-2">
                        <span style="background:#dcfce7;color:#166534;width:32px;height:32px;border-radius:50%;display:flex;align-items:center;justify-content:center;font-size:0.8rem;">✓</span>
                        <span style="font-size:0.9rem;color:#57534e;">No Preservatives</span>
                    </div>
                    <div class="d-flex align-items-center gap-2">
                        <span style="background:#fef3c7;color:#d97706;width:32px;height:32px;border-radius:50%;display:flex;align-items:center;justify-content:center;font-size:0.8rem;">✓</span>
                        <span style="font-size:0.9rem;color:#57534e;">Pure Mustard Oil</span>
                    </div>
                    <div class="d-flex align-items-center gap-2">
                        <span style="background:#fee2e2;color:#dc2626;width:32px;height:32px;border-radius:50%;display:flex;align-items:center;justify-content:center;font-size:0.8rem;">✓</span>
                        <span style="font-size:0.9rem;color:#57534e;">Free Shipping ₹500+</span>
                    </div>
                </div>
                <div class="d-flex flex-wrap gap-3">
                    <a href="/?page=products" class="btn btn-pickle px-4 py-3 fs-6">
                        <i class="fas fa-shopping-bag me-2"></i>Shop Now
                    </a>
                    <a href="#offers" class="btn px-4 py-3 fs-6" style="background:white;border:2px solid #d97706;color:#d97706;border-radius:50px;font-weight:600;transition:all 0.3s;">
                        <i class="fas fa-tag me-2"></i>View Offers
                    </a>
                </div>
                <div class="d-flex align-items-center gap-4 mt-4">
                    <div class="text-center">
                        <div style="font-size:1.5rem;font-weight:800;color:#166534;">10K+</div>
                        <div style="font-size:0.75rem;color:#78716c;">Happy Customers</div>
                    </div>
                    <div style="width:1px;height:40px;background:#e5e7eb;"></div>
                    <div class="text-center">
                        <div style="font-size:1.5rem;font-weight:800;color:#d97706;">12+</div>
                        <div style="font-size:0.75rem;color:#78716c;">Pickle Varieties</div>
                    </div>
                    <div style="width:1px;height:40px;background:#e5e7eb;"></div>
                    <div class="text-center">
                        <div style="font-size:1.5rem;font-weight:800;color:#ea580c;">4.8★</div>
                        <div style="font-size:0.75rem;color:#78716c;">Average Rating</div>
                    </div>
                </div>
            </div>
            <div class="col-lg-6 text-center hero-image position-relative">
                <div style="position:relative;display:inline-block;">
                    <!-- Main jar -->
                    <div class="floating-jar" style="display:inline-block;">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 300 300" width="320" height="320">
                            <defs>
                                <radialGradient id="jarGrad" cx="40%" cy="35%">
                                    <stop offset="0%" stop-color="#1a7a3f"/>
                                    <stop offset="100%" stop-color="#0d4a25"/>
                                </radialGradient>
                            </defs>
                            <rect x="75" y="115" width="150" height="155" rx="18" fill="url(#jarGrad)"/>
                            <rect x="72" y="115" width="8" height="155" rx="4" fill="rgba(255,255,255,0.08)"/>
                            <rect x="68" y="90" width="164" height="32" rx="10" fill="#d97706"/>
                            <rect x="78" y="96" width="144" height="20" rx="7" fill="#fbbf24"/>
                            <line x1="88" y1="104" x2="212" y2="104" stroke="#d97706" stroke-width="2"/>
                            <rect x="90" y="130" width="24" height="58" rx="12" fill="#4ade80" opacity="0.92"/>
                            <rect x="122" y="125" width="22" height="65" rx="11" fill="#22c55e" opacity="0.85"/>
                            <rect x="152" y="132" width="24" height="55" rx="12" fill="#4ade80" opacity="0.75"/>
                            <rect x="182" y="127" width="20" height="62" rx="10" fill="#16a34a" opacity="0.92"/>
                            <circle cx="101" cy="143" r="3" fill="#ef4444" opacity="0.8"/>
                            <circle cx="132" cy="148" r="3" fill="#f97316" opacity="0.8"/>
                            <circle cx="163" cy="145" r="3" fill="#ef4444" opacity="0.8"/>
                            <circle cx="192" cy="140" r="2.5" fill="#fbbf24" opacity="0.9"/>
                            <rect x="86" y="196" width="128" height="58" rx="10" fill="#fef3c7" opacity="0.92"/>
                            <text x="150" y="218" text-anchor="middle" font-family="Georgia,serif" font-size="16" font-weight="bold" fill="#92400e">Achar</text>
                            <text x="150" y="236" text-anchor="middle" font-family="Arial,sans-serif" font-size="10" fill="#166534" letter-spacing="2">HOMEMADE PICKLES</text>
                            <text x="150" y="248" text-anchor="middle" font-family="Arial,sans-serif" font-size="8" fill="#d97706">500g · No Preservatives</text>
                        </svg>
                    </div>
                    <!-- Floating small elements -->
                    <div class="floating-jar-2" style="position:absolute;top:20px;right:-20px;">
                        <span style="font-size:2.5rem;">🌶️</span>
                    </div>
                    <div class="floating-jar-3" style="position:absolute;bottom:40px;left:-30px;">
                        <span style="font-size:2rem;">🥭</span>
                    </div>
                    <div class="floating-jar" style="position:absolute;top:60px;left:-10px;">
                        <span style="font-size:1.8rem;">🧄</span>
                    </div>
                    <div class="floating-jar-2" style="position:absolute;bottom:80px;right:-10px;">
                        <span style="font-size:1.8rem;">🍋</span>
                    </div>
                    <!-- Rating badge -->
                    <div style="position:absolute;top:10px;left:50%;transform:translateX(-50%);background:white;border-radius:20px;padding:6px 14px;box-shadow:0 4px 20px rgba(0,0,0,0.12);white-space:nowrap;">
                        <span style="color:#f59e0b;">★★★★★</span>
                        <span style="font-size:0.8rem;font-weight:600;color:#1c1917;margin-left:4px;">4.8/5.0</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- ===================== MARQUEE TICKER ===================== -->
<div class="marquee-wrapper py-2">
    <div class="marquee-content">
        <?php for ($i = 0; $i < 2; $i++): ?>
            <?php if (!empty($offers)): foreach ($offers as $offer): ?>
                <span class="px-6 text-white font-semibold" style="padding:0 40px;font-weight:600;">
                    🎉 <?php echo htmlspecialchars($offer['title']); ?> — Use code <strong><?php echo htmlspecialchars($offer['code']); ?></strong> for <?php echo $offer['discount']; ?>% OFF
                </span>
                <span style="padding:0 20px;color:rgba(255,255,255,0.5);">•</span>
            <?php endforeach; else: ?>
                <span class="px-6 text-white font-semibold" style="padding:0 40px;font-weight:600;">
                    🎉 Free delivery on orders above ₹500! Use <strong>PICKLE10</strong> for 10% OFF
                </span>
                <span style="padding:0 20px;color:rgba(255,255,255,0.5);">•</span>
                <span class="px-6 text-white font-semibold" style="padding:0 40px;font-weight:600;">
                    🥒 100% Homemade · No Preservatives · Pure Mustard Oil
                </span>
                <span style="padding:0 20px;color:rgba(255,255,255,0.5);">•</span>
            <?php endif; ?>
        <?php endfor; ?>
    </div>
</div>

<!-- ===================== OFFERS ===================== -->
<?php if (!empty($offers)): ?>
<section id="offers" class="py-5" style="background:#f9fafb;">
    <div class="container">
        <div class="text-center mb-4 reveal">
            <span style="background:#fef3c7;color:#d97706;padding:4px 14px;border-radius:20px;font-size:0.8rem;font-weight:600;">🏷️ Special Offers</span>
            <h2 style="font-size:2rem;font-weight:800;color:#1c1917;margin-top:8px;">Today's Best Deals</h2>
            <p style="color:#78716c;">Use these coupon codes at checkout to save big!</p>
        </div>
        <div class="row g-4">
            <?php
            $offerColors = [
                ['from' => '#7c3aed', 'to' => '#db2777'],
                ['from' => '#d97706', 'to' => '#ea580c'],
                ['from' => '#166534', 'to' => '#15803d'],
                ['from' => '#1d4ed8', 'to' => '#7c3aed'],
            ];
            foreach ($offers as $i => $offer):
                $colors = $offerColors[$i % count($offerColors)];
            ?>
            <div class="col-md-6 col-lg-3 reveal">
                <div class="offer-card h-100" style="background:linear-gradient(135deg,<?php echo $colors['from']; ?>,<?php echo $colors['to']; ?>);">
                    <div style="font-size:2.5rem;font-weight:900;opacity:0.25;position:absolute;top:10px;right:15px;line-height:1;">
                        <?php echo $offer['discount']; ?>%
                    </div>
                    <div style="position:relative;z-index:1;">
                        <span style="background:rgba(255,255,255,0.2);padding:3px 10px;border-radius:12px;font-size:0.75rem;font-weight:600;">
                            <?php echo $offer['discount']; ?>% OFF
                        </span>
                        <h5 class="fw-bold mt-2 mb-1" style="font-size:1.1rem;"><?php echo htmlspecialchars($offer['title']); ?></h5>
                        <p style="font-size:0.82rem;opacity:0.9;margin-bottom:12px;"><?php echo htmlspecialchars($offer['description']); ?></p>
                        <div style="background:rgba(255,255,255,0.2);border:1.5px dashed rgba(255,255,255,0.6);border-radius:8px;padding:8px 12px;display:flex;justify-content:space-between;align-items:center;">
                            <span style="font-family:monospace;font-size:1rem;font-weight:700;letter-spacing:2px;"><?php echo htmlspecialchars($offer['code']); ?></span>
                            <button onclick="copyCode('<?php echo htmlspecialchars($offer['code']); ?>')" style="background:white;color:#1c1917;border:none;border-radius:6px;padding:4px 10px;font-size:0.75rem;font-weight:600;cursor:pointer;">
                                Copy
                            </button>
                        </div>
                        <?php if ($offer['minOrder'] > 0): ?>
                        <p style="font-size:0.75rem;opacity:0.75;margin-top:8px;margin-bottom:0;">Min. order: ₹<?php echo $offer['minOrder']; ?></p>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>
<?php endif; ?>

<!-- ===================== FEATURED PRODUCTS ===================== -->
<section class="py-5">
    <div class="container">
        <div class="text-center mb-5 reveal">
            <span style="background:#dcfce7;color:#166534;padding:4px 14px;border-radius:20px;font-size:0.8rem;font-weight:600;">⭐ Best Sellers</span>
            <h2 style="font-size:2rem;font-weight:800;color:#1c1917;margin-top:8px;">Featured Pickles</h2>
            <p style="color:#78716c;">Our most loved pickles, handpicked by thousands of happy customers</p>
        </div>
        <div class="row g-4">
            <?php foreach ($featured as $product): ?>
            <div class="col-sm-6 col-lg-4 reveal">
                <div class="product-card card border-0 shadow-sm h-100">
                    <a href="/?page=product&id=<?php echo $product['id']; ?>" class="text-decoration-none">
                        <div class="card-img-wrapper">
                            <img src="/public/images/<?php echo htmlspecialchars($product['image']); ?>" 
                                 alt="<?php echo htmlspecialchars($product['name']); ?>">
                        </div>
                    </a>
                    <div class="card-body d-flex flex-column p-3">
                        <div class="d-flex justify-content-between align-items-start mb-1">
                            <span style="background:#fef3c7;color:#d97706;padding:2px 10px;border-radius:12px;font-size:0.72rem;font-weight:600;text-transform:capitalize;">
                                <?php echo htmlspecialchars($product['category']); ?>
                            </span>
                            <?php if (!$product['inStock']): ?>
                            <span style="background:#fee2e2;color:#dc2626;padding:2px 8px;border-radius:12px;font-size:0.72rem;font-weight:600;">Out of Stock</span>
                            <?php endif; ?>
                        </div>
                        <a href="/?page=product&id=<?php echo $product['id']; ?>" class="text-decoration-none">
                            <h6 class="fw-bold mt-1 mb-1" style="color:#1c1917;font-size:1rem;"><?php echo htmlspecialchars($product['name']); ?></h6>
                        </a>
                        <div class="d-flex align-items-center gap-1 mb-2">
                            <?php
                            $rating = $product['rating'];
                            for ($i = 1; $i <= 5; $i++):
                                if ($i <= floor($rating)) echo '<i class="fas fa-star stars" style="font-size:0.75rem;"></i>';
                                elseif ($i - $rating < 1 && $i - $rating > 0) echo '<i class="fas fa-star-half-alt stars" style="font-size:0.75rem;"></i>';
                                else echo '<i class="far fa-star stars" style="font-size:0.75rem;color:#d1d5db;"></i>';
                            endfor;
                            ?>
                            <span style="font-size:0.75rem;color:#78716c;margin-left:4px;">(<?php echo $product['reviews']; ?>)</span>
                        </div>
                        <div class="d-flex align-items-center gap-2 mb-2">
                            <span class="price-current">₹<?php echo $product['price']; ?></span>
                            <span class="price-mrp">₹<?php echo $product['mrp']; ?></span>
                            <?php $disc = round((($product['mrp'] - $product['price']) / $product['mrp']) * 100); ?>
                            <span class="discount-badge"><?php echo $disc; ?>% off</span>
                        </div>
                        <p style="font-size:0.78rem;color:#78716c;margin-bottom:8px;">
                            <i class="fas fa-weight me-1"></i><?php echo htmlspecialchars($product['weight']); ?>
                        </p>
                        <button class="btn btn-pickle w-100 mt-auto add-to-cart-btn" 
                                data-id="<?php echo $product['id']; ?>"
                                <?php echo !$product['inStock'] ? 'disabled' : ''; ?>>
                            <i class="fas fa-cart-plus me-2"></i>
                            <?php echo $product['inStock'] ? 'Add to Cart' : 'Out of Stock'; ?>
                        </button>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
        <div class="text-center mt-5">
            <a href="/?page=products" class="btn btn-outline-warning px-5 py-3 rounded-pill fw-bold fs-6">
                <i class="fas fa-th-large me-2"></i>View All Products
            </a>
        </div>
    </div>
</section>

<!-- ===================== CATEGORIES ===================== -->
<section class="py-5" style="background:linear-gradient(135deg,#fef3c7,#dcfce7);">
    <div class="container">
        <div class="text-center mb-5 reveal">
            <span style="background:#d97706;color:white;padding:4px 14px;border-radius:20px;font-size:0.8rem;font-weight:600;">📂 Browse By Category</span>
            <h2 style="font-size:2rem;font-weight:800;color:#1c1917;margin-top:8px;">Shop By Category</h2>
            <p style="color:#78716c;">From tangy mangoes to fiery chilies — find your perfect pickle</p>
        </div>
        <div class="row g-3 justify-content-center">
            <?php
            $categories = [
                ['slug' => 'mango',  'emoji' => '🥭', 'name' => 'Mango',   'sub' => 'Aam ka Achar',  'color' => '#fef3c7', 'text' => '#92400e'],
                ['slug' => 'lemon',  'emoji' => '🍋', 'name' => 'Lemon',   'sub' => 'Nimbu Pickle',   'color' => '#fefce8', 'text' => '#713f12'],
                ['slug' => 'garlic', 'emoji' => '🧄', 'name' => 'Garlic',  'sub' => 'Lehsun Achar',  'color' => '#faf5ff', 'text' => '#4c1d95'],
                ['slug' => 'mixed',  'emoji' => '🥗', 'name' => 'Mixed',   'sub' => 'Sabzi Pickle',   'color' => '#dcfce7', 'text' => '#14532d'],
                ['slug' => 'chili',  'emoji' => '🌶️', 'name' => 'Chili',  'sub' => 'Mirch Achar',   'color' => '#fee2e2', 'text' => '#7f1d1d'],
                ['slug' => 'carrot', 'emoji' => '🥕', 'name' => 'Carrot',  'sub' => 'Gajar Pickle',   'color' => '#fff7ed', 'text' => '#7c2d12'],
            ];
            foreach ($categories as $cat):
            ?>
            <div class="col-4 col-md-4 col-lg-2 reveal">
                <a href="/?page=products&category=<?php echo $cat['slug']; ?>" class="text-decoration-none">
                    <div class="category-card text-center p-3 rounded-4 shadow-sm" style="background:<?php echo $cat['color']; ?>;">
                        <div style="font-size:2.5rem;margin-bottom:8px;"><?php echo $cat['emoji']; ?></div>
                        <h6 class="fw-bold mb-0" style="color:<?php echo $cat['text']; ?>;font-size:0.9rem;"><?php echo $cat['name']; ?></h6>
                        <small style="color:<?php echo $cat['text']; ?>;opacity:0.7;font-size:0.72rem;"><?php echo $cat['sub']; ?></small>
                    </div>
                </a>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<!-- ===================== WHY CHOOSE US ===================== -->
<section class="py-5" id="about">
    <div class="container">
        <div class="text-center mb-5 reveal">
            <span style="background:#dcfce7;color:#166534;padding:4px 14px;border-radius:20px;font-size:0.8rem;font-weight:600;">💚 Why Achar?</span>
            <h2 style="font-size:2rem;font-weight:800;color:#1c1917;margin-top:8px;">Why Choose Us</h2>
            <p style="color:#78716c;">Every jar tells a story of tradition, purity, and passion</p>
        </div>
        <div class="row g-4">
            <?php
            $features = [
                ['icon' => 'fas fa-leaf',        'color' => '#dcfce7', 'icolor' => '#166534', 'title' => 'Pure Ingredients',       'desc' => 'We source only the freshest and finest vegetables, spices, and mustard oil directly from trusted farms across India.'],
                ['icon' => 'fas fa-ban',          'color' => '#fee2e2', 'icolor' => '#dc2626', 'title' => 'No Preservatives',       'desc' => 'All our pickles are naturally preserved using salt, oil, and traditional methods — zero artificial additives.'],
                ['icon' => 'fas fa-book-open',    'color' => '#fef3c7', 'icolor' => '#d97706', 'title' => 'Traditional Recipes',    'desc' => 'Passed down through generations, our recipes honor the authentic flavors of regional Indian pickle traditions.'],
                ['icon' => 'fas fa-shield-alt',   'color' => '#ede9fe', 'icolor' => '#7c3aed', 'title' => 'Hygienic Packaging',     'desc' => 'Packed in sterilized glass jars under strict quality control to ensure freshness and safety on every order.'],
            ];
            foreach ($features as $f):
            ?>
            <div class="col-sm-6 col-lg-3 reveal">
                <div class="text-center p-4 rounded-4 shadow-sm bg-white h-100" style="transition:transform 0.3s;border:1px solid #f3f4f6;" onmouseover="this.style.transform='translateY(-6px)'" onmouseout="this.style.transform='translateY(0)'">
                    <div style="width:70px;height:70px;background:<?php echo $f['color']; ?>;border-radius:20px;display:flex;align-items:center;justify-content:center;margin:0 auto 16px;">
                        <i class="<?php echo $f['icon']; ?>" style="font-size:1.8rem;color:<?php echo $f['icolor']; ?>;"></i>
                    </div>
                    <h5 class="fw-bold mb-2" style="color:#1c1917;"><?php echo $f['title']; ?></h5>
                    <p style="color:#78716c;font-size:0.875rem;line-height:1.7;margin:0;"><?php echo $f['desc']; ?></p>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<!-- ===================== TESTIMONIALS ===================== -->
<section class="py-5" style="background:#f9fafb;">
    <div class="container">
        <div class="text-center mb-5 reveal">
            <span style="background:#fef3c7;color:#d97706;padding:4px 14px;border-radius:20px;font-size:0.8rem;font-weight:600;">💬 Customer Love</span>
            <h2 style="font-size:2rem;font-weight:800;color:#1c1917;margin-top:8px;">What Our Customers Say</h2>
            <p style="color:#78716c;">Real reviews from real pickle lovers across India</p>
        </div>
        <div class="row g-4">
            <?php foreach ($testimonials as $t): ?>
            <div class="col-md-6 col-lg-4 reveal">
                <div class="testimonial-card">
                    <div class="d-flex align-items-center gap-3 mb-3">
                        <div style="width:50px;height:50px;border-radius:50%;background:linear-gradient(135deg,#d97706,#ea580c);display:flex;align-items:center;justify-content:center;color:white;font-weight:700;font-size:0.9rem;flex-shrink:0;">
                            <?php echo htmlspecialchars($t['avatar']); ?>
                        </div>
                        <div>
                            <div style="font-weight:700;color:#1c1917;font-size:0.95rem;"><?php echo htmlspecialchars($t['name']); ?></div>
                            <div style="font-size:0.78rem;color:#78716c;"><i class="fas fa-map-marker-alt me-1"></i><?php echo htmlspecialchars($t['location']); ?></div>
                        </div>
                        <div class="ms-auto">
                            <?php for ($i = 0; $i < $t['rating']; $i++): ?>
                                <i class="fas fa-star stars" style="font-size:0.8rem;"></i>
                            <?php endfor; ?>
                        </div>
                    </div>
                    <p style="color:#57534e;font-size:0.875rem;line-height:1.7;margin:0;font-style:italic;">
                        "<?php echo htmlspecialchars($t['text']); ?>"
                    </p>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<!-- ===================== FAQ ===================== -->
<section class="py-5">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <div class="text-center mb-5 reveal">
                    <span style="background:#ede9fe;color:#7c3aed;padding:4px 14px;border-radius:20px;font-size:0.8rem;font-weight:600;">❓ FAQ</span>
                    <h2 style="font-size:2rem;font-weight:800;color:#1c1917;margin-top:8px;">Frequently Asked Questions</h2>
                </div>
                <?php
                $faqs = [
                    ['q' => 'How long do your pickles last?',                    'a' => 'Our pickles have a shelf life of 6-12 months when stored properly in a cool, dry place. Once opened, refrigerate and consume within 3-4 months. The salt and oil act as natural preservatives.'],
                    ['q' => 'Are the pickles suitable for vegetarians/vegans?',   'a' => 'Yes! All our pickles are 100% vegetarian and vegan-friendly. We use no animal products of any kind in our recipes.'],
                    ['q' => 'Do you use any artificial preservatives?',           'a' => 'Absolutely not. We use only natural preservation methods — high-quality mustard oil, salt, and time-tested pickling techniques passed down through generations.'],
                    ['q' => 'What is your shipping and delivery policy?',         'a' => 'We offer free delivery on orders above ₹500. For orders below that, a nominal shipping fee of ₹60 applies. Most orders are delivered within 3-5 business days across India.'],
                    ['q' => 'Can I return or exchange a product?',               'a' => 'We accept returns if the product is damaged during transit or if the wrong item was delivered. Please contact us within 48 hours of receiving your order with photos.'],
                    ['q' => 'Are your pickles safe for people with health conditions?', 'a' => 'Our pickles are made with natural ingredients, but they do contain oil and salt. If you have specific dietary restrictions or health conditions, please consult your doctor before consuming. We also offer low-oil variants on request.'],
                ];
                foreach ($faqs as $faq):
                ?>
                <div class="reveal mb-3">
                    <button class="faq-question w-100 text-start d-flex justify-content-between align-items-center p-4 rounded-3 border-0"
                            style="background:white;box-shadow:0 2px 15px rgba(0,0,0,0.06);font-weight:600;color:#1c1917;cursor:pointer;">
                        <?php echo htmlspecialchars($faq['q']); ?>
                        <i class="fas fa-chevron-down faq-icon" style="color:#d97706;transition:transform 0.3s;flex-shrink:0;margin-left:12px;"></i>
                    </button>
                    <div class="faq-answer" style="max-height:0;overflow:hidden;transition:max-height 0.4s ease;background:white;border-radius:0 0 12px 12px;padding:0 20px;">
                        <p style="color:#57534e;font-size:0.875rem;line-height:1.7;padding:16px 0;margin:0;">
                            <?php echo htmlspecialchars($faq['a']); ?>
                        </p>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>
</section>

<!-- ===================== CONTACT ===================== -->
<section class="py-5" id="contact" style="background:linear-gradient(135deg,#1c1917,#292524);">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-7">
                <div class="text-center mb-5 reveal">
                    <span style="background:rgba(217,119,6,0.2);color:#fbbf24;padding:4px 14px;border-radius:20px;font-size:0.8rem;font-weight:600;">📩 Get In Touch</span>
                    <h2 style="font-size:2rem;font-weight:800;color:white;margin-top:8px;">Contact Us</h2>
                    <p style="color:#a8a29e;">Have questions? We'd love to hear from you!</p>
                </div>
                <div class="bg-white rounded-4 p-4 p-md-5 shadow-lg reveal">
                    <form onsubmit="handleContactForm(event)">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label fw-600" style="font-size:0.875rem;">Your Name</label>
                                <input type="text" class="form-control" placeholder="Rahul Sharma" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-600" style="font-size:0.875rem;">Email Address</label>
                                <input type="email" class="form-control" placeholder="rahul@example.com" required>
                            </div>
                            <div class="col-12">
                                <label class="form-label fw-600" style="font-size:0.875rem;">Phone Number</label>
                                <input type="tel" class="form-control" placeholder="+91 98765 43210">
                            </div>
                            <div class="col-12">
                                <label class="form-label fw-600" style="font-size:0.875rem;">Message</label>
                                <textarea class="form-control" rows="4" placeholder="Write your message here..." required></textarea>
                            </div>
                            <div class="col-12">
                                <button type="submit" class="btn btn-pickle w-100 py-3 fs-6">
                                    <i class="fas fa-paper-plane me-2"></i>Send Message
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</section>

<script>
function copyCode(code) {
    navigator.clipboard.writeText(code).then(() => {
        if (typeof showToast === 'function') showToast('Code "' + code + '" copied! 🎉');
    }).catch(() => {
        if (typeof showToast === 'function') showToast('Code: ' + code);
    });
}
function handleContactForm(e) {
    e.preventDefault();
    if (typeof showToast === 'function') showToast('Message sent! We\'ll get back to you soon. 📩');
    e.target.reset();
}
</script>
