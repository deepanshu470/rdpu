<?php
// views/products/index.php
$currentCategory = isset($_GET['category']) ? $_GET['category'] : 'all';
$currentSort     = isset($_GET['sort'])     ? $_GET['sort']     : 'default';
$currentSearch   = isset($_GET['search'])   ? $_GET['search']   : '';
$categories = [
    'all'    => ['label' => 'All Pickles', 'emoji' => '🥒'],
    'mango'  => ['label' => 'Mango',       'emoji' => '🥭'],
    'lemon'  => ['label' => 'Lemon',       'emoji' => '🍋'],
    'garlic' => ['label' => 'Garlic',      'emoji' => '🧄'],
    'mixed'  => ['label' => 'Mixed',       'emoji' => '🥗'],
    'chili'  => ['label' => 'Chili',       'emoji' => '🌶️'],
    'carrot' => ['label' => 'Carrot',      'emoji' => '🥕'],
];
?>

<section class="py-4" style="background:linear-gradient(135deg,#fef3c7,#dcfce7);">
    <div class="container">
        <div class="d-flex align-items-center gap-2 mb-2">
            <a href="/?page=home" style="color:#166534;text-decoration:none;font-size:0.85rem;"><i class="fas fa-home me-1"></i>Home</a>
            <span style="color:#78716c;font-size:0.85rem;">/</span>
            <span style="color:#d97706;font-size:0.85rem;font-weight:600;">All Pickles</span>
        </div>
        <h1 style="font-size:2rem;font-weight:800;color:#1c1917;">Our Pickles 🥒</h1>
        <p style="color:#57534e;">Discover <?php echo count($products); ?> authentic homemade pickle<?php echo count($products) !== 1 ? 's' : ''; ?></p>

        <!-- Search bar -->
        <form method="GET" action="/" class="d-flex gap-2 mt-3" style="max-width:500px;">
            <input type="hidden" name="page" value="products">
            <div class="input-group">
                <span class="input-group-text bg-white border-end-0"><i class="fas fa-search text-muted"></i></span>
                <input type="text" name="search" class="form-control border-start-0" placeholder="Search pickles..." value="<?php echo htmlspecialchars($currentSearch); ?>">
                <button class="btn btn-warning fw-bold" type="submit">Search</button>
            </div>
            <?php if ($currentSearch): ?>
            <a href="/?page=products" class="btn btn-outline-secondary">Clear</a>
            <?php endif; ?>
        </form>
    </div>
</section>

<section class="py-4">
    <div class="container">
        <div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center gap-3 mb-4">
            <!-- Category Tabs -->
            <div class="d-flex flex-wrap gap-2">
                <?php foreach ($categories as $slug => $cat): ?>
                <a href="/?page=products&category=<?php echo $slug; ?><?php echo $currentSort !== 'default' ? '&sort=' . $currentSort : ''; ?>"
                   class="btn btn-sm <?php echo $currentCategory === $slug ? 'btn-warning fw-bold' : 'btn-outline-secondary'; ?> rounded-pill">
                    <?php echo $cat['emoji']; ?> <?php echo $cat['label']; ?>
                </a>
                <?php endforeach; ?>
            </div>

            <!-- Sort -->
            <form method="GET" action="/" class="d-flex align-items-center gap-2">
                <input type="hidden" name="page" value="products">
                <input type="hidden" name="category" value="<?php echo htmlspecialchars($currentCategory); ?>">
                <?php if ($currentSearch): ?>
                <input type="hidden" name="search" value="<?php echo htmlspecialchars($currentSearch); ?>">
                <?php endif; ?>
                <label class="text-muted" style="font-size:0.875rem;white-space:nowrap;"><i class="fas fa-sort me-1"></i>Sort by:</label>
                <select name="sort" onchange="this.form.submit()" class="form-select form-select-sm" style="width:auto;">
                    <option value="default"    <?php echo $currentSort === 'default'    ? 'selected' : ''; ?>>Default</option>
                    <option value="price_asc"  <?php echo $currentSort === 'price_asc'  ? 'selected' : ''; ?>>Price: Low to High</option>
                    <option value="price_desc" <?php echo $currentSort === 'price_desc' ? 'selected' : ''; ?>>Price: High to Low</option>
                    <option value="rating"     <?php echo $currentSort === 'rating'     ? 'selected' : ''; ?>>Top Rated</option>
                </select>
            </form>
        </div>

        <?php if (empty($products)): ?>
        <div class="text-center py-5">
            <div style="font-size:4rem;">🥒</div>
            <h4 class="fw-bold mt-3" style="color:#1c1917;">No pickles found</h4>
            <p style="color:#78716c;">Try a different search or browse all categories.</p>
            <a href="/?page=products" class="btn btn-warning fw-bold rounded-pill px-4">Browse All Pickles</a>
        </div>
        <?php else: ?>
        <div class="row g-4">
            <?php foreach ($products as $product): ?>
            <div class="col-sm-6 col-lg-4">
                <div class="product-card card border-0 shadow-sm h-100">
                    <a href="/?page=product&id=<?php echo $product['id']; ?>" class="text-decoration-none">
                        <div class="card-img-wrapper">
                            <img src="/public/images/<?php echo htmlspecialchars($product['image']); ?>" 
                                 alt="<?php echo htmlspecialchars($product['name']); ?>">
                        </div>
                    </a>
                    <div class="card-body d-flex flex-column p-3">
                        <div class="d-flex justify-content-between align-items-center mb-1">
                            <span style="background:#fef3c7;color:#d97706;padding:2px 10px;border-radius:12px;font-size:0.72rem;font-weight:600;text-transform:capitalize;">
                                <?php echo htmlspecialchars($product['category']); ?>
                            </span>
                            <span style="font-size:0.75rem;color:<?php echo $product['inStock'] ? '#166534' : '#dc2626'; ?>;font-weight:600;">
                                <?php echo $product['inStock'] ? '✓ In Stock' : '✗ Out of Stock'; ?>
                            </span>
                        </div>
                        <a href="/?page=product&id=<?php echo $product['id']; ?>" class="text-decoration-none">
                            <h6 class="fw-bold mt-1 mb-1" style="color:#1c1917;font-size:1rem;"><?php echo htmlspecialchars($product['name']); ?></h6>
                        </a>
                        <p style="font-size:0.78rem;color:#78716c;line-height:1.5;margin-bottom:8px;flex-grow:1;">
                            <?php echo htmlspecialchars(substr($product['description'], 0, 100)) . (strlen($product['description']) > 100 ? '...' : ''); ?>
                        </p>
                        <div class="d-flex align-items-center gap-1 mb-2">
                            <?php
                            $rating = $product['rating'];
                            for ($i = 1; $i <= 5; $i++):
                                if ($i <= floor($rating)) echo '<i class="fas fa-star stars" style="font-size:0.75rem;"></i>';
                                elseif ($i - $rating < 1 && $i - $rating > 0) echo '<i class="fas fa-star-half-alt stars" style="font-size:0.75rem;"></i>';
                                else echo '<i class="far fa-star stars" style="font-size:0.75rem;color:#d1d5db;"></i>';
                            endfor;
                            ?>
                            <span style="font-size:0.75rem;color:#78716c;margin-left:4px;"><?php echo $product['rating']; ?> (<?php echo $product['reviews']; ?>)</span>
                        </div>
                        <div class="d-flex align-items-center justify-content-between mb-3">
                            <div class="d-flex align-items-center gap-2">
                                <span class="price-current">₹<?php echo $product['price']; ?></span>
                                <span class="price-mrp">₹<?php echo $product['mrp']; ?></span>
                                <?php $disc = round((($product['mrp'] - $product['price']) / $product['mrp']) * 100); ?>
                                <span class="discount-badge"><?php echo $disc; ?>% off</span>
                            </div>
                            <span style="font-size:0.75rem;color:#78716c;"><i class="fas fa-weight me-1"></i><?php echo htmlspecialchars($product['weight']); ?></span>
                        </div>
                        <div class="d-flex gap-2">
                            <a href="/?page=product&id=<?php echo $product['id']; ?>" class="btn btn-outline-secondary btn-sm flex-shrink-0" style="border-radius:8px;">
                                <i class="fas fa-eye"></i>
                            </a>
                            <button class="btn btn-pickle w-100 btn-sm add-to-cart-btn" 
                                    data-id="<?php echo $product['id']; ?>"
                                    <?php echo !$product['inStock'] ? 'disabled' : ''; ?>>
                                <i class="fas fa-cart-plus me-1"></i>
                                <?php echo $product['inStock'] ? 'Add to Cart' : 'Out of Stock'; ?>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
        <?php endif; ?>
    </div>
</section>
