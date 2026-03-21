<?php
$isEdit = isset($product) && $product;
$formAction = $isEdit ? '/?page=admin&section=product_edit&id=' . $product['id'] : '/?page=admin&section=product_new';
$pageTitle = $isEdit ? 'Edit Product' : 'Add New Product';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $pageTitle; ?> — Admin — Achar</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="/public/css/custom.css">
    <style>body { font-family: 'Poppins', sans-serif; background: #f3f4f6; }
    .admin-layout { display: flex; min-height: 100vh; }
    .main-content { flex: 1; overflow-x: auto; }</style>
</head>
<body>
<div class="admin-layout">
    <aside class="admin-sidebar d-flex flex-column py-4">
        <div class="px-4 mb-4 text-center">
            <div style="font-size:1.3rem;font-weight:800;color:#d97706;">Achar</div>
            <div style="font-size:0.6rem;color:#4ade80;letter-spacing:3px;">ADMIN PANEL</div>
        </div>
        <nav class="flex-grow-1">
            <a href="/?page=admin"><i class="fas fa-tachometer-alt"></i> Dashboard</a>
            <a href="/?page=admin&section=products" class="active"><i class="fas fa-jar"></i> Products</a>
            <a href="/?page=admin&section=offers"><i class="fas fa-tags"></i> Offers</a>
            <a href="/?page=admin&section=orders"><i class="fas fa-shopping-bag"></i> Orders</a>
        </nav>
        <div class="px-4 mt-auto pt-4" style="border-top:1px solid #44403c;">
            <a href="/?page=home" style="display:flex;align-items:center;gap:8px;color:#a8a29e;text-decoration:none;font-size:0.85rem;margin-bottom:8px;"><i class="fas fa-store"></i> View Shop</a>
            <a href="/?page=admin&section=logout" style="display:flex;align-items:center;gap:8px;color:#fca5a5;text-decoration:none;font-size:0.85rem;"><i class="fas fa-sign-out-alt"></i> Logout</a>
        </div>
    </aside>

    <div class="main-content p-4">
        <div class="d-flex justify-content-between align-items-center mb-4 p-3 bg-white rounded-3 shadow-sm">
            <h4 style="font-weight:700;color:#1c1917;margin:0;">
                <i class="fas fa-<?php echo $isEdit ? 'edit' : 'plus-circle'; ?> me-2" style="color:#d97706;"></i>
                <?php echo $pageTitle; ?>
            </h4>
            <a href="/?page=admin&section=products" class="btn btn-outline-secondary rounded-pill px-4">
                <i class="fas fa-arrow-left me-2"></i>Cancel
            </a>
        </div>

        <div class="bg-white rounded-3 shadow-sm p-4" style="max-width:800px;">
            <form method="POST" action="<?php echo $formAction; ?>">
                <?php if ($isEdit): ?>
                <input type="hidden" name="id" value="<?php echo $product['id']; ?>">
                <?php endif; ?>

                <div class="row g-3">
                    <div class="col-12">
                        <label class="form-label fw-600" style="font-size:0.875rem;">Product Name <span class="text-danger">*</span></label>
                        <input type="text" name="name" class="form-control" placeholder="e.g. Mango Achar" required value="<?php echo htmlspecialchars($product['name'] ?? ''); ?>">
                    </div>

                    <div class="col-md-6">
                        <label class="form-label fw-600" style="font-size:0.875rem;">Category <span class="text-danger">*</span></label>
                        <select name="category" class="form-select" required>
                            <?php
                            $cats = ['mango' => 'Mango', 'lemon' => 'Lemon', 'garlic' => 'Garlic', 'mixed' => 'Mixed Vegetable', 'chili' => 'Chili', 'carrot' => 'Carrot'];
                            foreach ($cats as $val => $label):
                                $selected = isset($product['category']) && $product['category'] === $val ? 'selected' : '';
                            ?>
                            <option value="<?php echo $val; ?>" <?php echo $selected; ?>><?php echo $label; ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label fw-600" style="font-size:0.875rem;">Weight</label>
                        <input type="text" name="weight" class="form-control" placeholder="e.g. 250g, 500g, 1kg" value="<?php echo htmlspecialchars($product['weight'] ?? '250g'); ?>">
                    </div>

                    <div class="col-md-6">
                        <label class="form-label fw-600" style="font-size:0.875rem;">Price (₹) <span class="text-danger">*</span></label>
                        <div class="input-group">
                            <span class="input-group-text">₹</span>
                            <input type="number" name="price" class="form-control" min="0" required value="<?php echo htmlspecialchars($product['price'] ?? ''); ?>" placeholder="199">
                        </div>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label fw-600" style="font-size:0.875rem;">MRP (₹) <span class="text-danger">*</span></label>
                        <div class="input-group">
                            <span class="input-group-text">₹</span>
                            <input type="number" name="mrp" class="form-control" min="0" required value="<?php echo htmlspecialchars($product['mrp'] ?? ''); ?>" placeholder="249">
                        </div>
                    </div>

                    <div class="col-12">
                        <label class="form-label fw-600" style="font-size:0.875rem;">Description <span class="text-danger">*</span></label>
                        <textarea name="description" class="form-control" rows="3" required placeholder="Describe the pickle..."><?php echo htmlspecialchars($product['description'] ?? ''); ?></textarea>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label fw-600" style="font-size:0.875rem;">Rating (0–5)</label>
                        <input type="number" name="rating" class="form-control" min="0" max="5" step="0.1" value="<?php echo htmlspecialchars($product['rating'] ?? '4.0'); ?>">
                    </div>

                    <div class="col-md-6">
                        <label class="form-label fw-600" style="font-size:0.875rem;">Number of Reviews</label>
                        <input type="number" name="reviews" class="form-control" min="0" value="<?php echo htmlspecialchars($product['reviews'] ?? '0'); ?>">
                    </div>

                    <div class="col-md-6">
                        <div class="form-check form-switch mt-2 p-3 rounded-3" style="background:#f9fafb;">
                            <input class="form-check-input" type="checkbox" name="inStock" id="inStock" style="width:2.5em;height:1.4em;" <?php echo !isset($product) || (isset($product['inStock']) && $product['inStock']) ? 'checked' : ''; ?>>
                            <label class="form-check-label ms-2 fw-600" for="inStock" style="font-size:0.875rem;">In Stock</label>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="form-check form-switch mt-2 p-3 rounded-3" style="background:#f9fafb;">
                            <input class="form-check-input" type="checkbox" name="featured" id="featured" style="width:2.5em;height:1.4em;" <?php echo isset($product['featured']) && $product['featured'] ? 'checked' : ''; ?>>
                            <label class="form-check-label ms-2 fw-600" for="featured" style="font-size:0.875rem;">Featured Product</label>
                        </div>
                    </div>

                    <div class="col-12 d-flex gap-3 pt-2">
                        <button type="submit" class="btn btn-success px-5 py-2 fw-bold rounded-pill">
                            <i class="fas fa-save me-2"></i><?php echo $isEdit ? 'Save Changes' : 'Add Product'; ?>
                        </button>
                        <a href="/?page=admin&section=products" class="btn btn-outline-secondary px-4 py-2 rounded-pill">Cancel</a>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
