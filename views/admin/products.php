<?php
$currentSection = 'products';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Products — Admin — Achar</title>
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
            <h4 style="font-weight:700;color:#1c1917;margin:0;"><i class="fas fa-jar me-2" style="color:#d97706;"></i>Products Management</h4>
            <a href="/?page=admin&section=product_new" class="btn btn-success rounded-pill px-4 fw-bold">
                <i class="fas fa-plus me-2"></i>Add New Product
            </a>
        </div>

        <div class="bg-white rounded-3 shadow-sm overflow-hidden">
            <?php if (empty($products)): ?>
            <div class="p-5 text-center" style="color:#78716c;">
                <i class="fas fa-jar fs-1 d-block mb-3"></i>
                <p class="mb-0">No products found.</p>
                <a href="/?page=admin&section=product_new" class="btn btn-success mt-3 rounded-pill">Add First Product</a>
            </div>
            <?php else: ?>
            <div class="table-responsive">
                <table class="table mb-0 align-middle" style="font-size:0.875rem;">
                    <thead style="background:#fef3c7;">
                        <tr>
                            <th class="px-3 py-3" style="border:none;color:#57534e;font-weight:600;">ID</th>
                            <th class="px-3 py-3" style="border:none;color:#57534e;font-weight:600;">Name</th>
                            <th class="px-3 py-3" style="border:none;color:#57534e;font-weight:600;">Category</th>
                            <th class="px-3 py-3" style="border:none;color:#57534e;font-weight:600;">Price</th>
                            <th class="px-3 py-3" style="border:none;color:#57534e;font-weight:600;">Weight</th>
                            <th class="px-3 py-3" style="border:none;color:#57534e;font-weight:600;">Stock</th>
                            <th class="px-3 py-3" style="border:none;color:#57534e;font-weight:600;">Rating</th>
                            <th class="px-3 py-3" style="border:none;color:#57534e;font-weight:600;">Featured</th>
                            <th class="px-3 py-3" style="border:none;color:#57534e;font-weight:600;">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($products as $p): ?>
                        <tr style="border-bottom:1px solid #f9fafb;">
                            <td class="px-3 py-3" style="color:#78716c;">#<?php echo $p['id']; ?></td>
                            <td class="px-3 py-3">
                                <div style="font-weight:600;color:#1c1917;"><?php echo htmlspecialchars($p['name']); ?></div>
                            </td>
                            <td class="px-3 py-3">
                                <span style="background:#fef3c7;color:#d97706;padding:2px 10px;border-radius:12px;font-size:0.75rem;font-weight:600;text-transform:capitalize;">
                                    <?php echo htmlspecialchars($p['category']); ?>
                                </span>
                            </td>
                            <td class="px-3 py-3">
                                <span style="font-weight:700;color:#166534;">₹<?php echo $p['price']; ?></span>
                                <span style="font-size:0.75rem;color:#9ca3af;text-decoration:line-through;margin-left:4px;">₹<?php echo $p['mrp']; ?></span>
                            </td>
                            <td class="px-3 py-3" style="color:#57534e;"><?php echo htmlspecialchars($p['weight']); ?></td>
                            <td class="px-3 py-3">
                                <?php if ($p['inStock']): ?>
                                <span style="background:#dcfce7;color:#166534;padding:3px 10px;border-radius:12px;font-size:0.75rem;font-weight:600;">✓ In Stock</span>
                                <?php else: ?>
                                <span style="background:#fee2e2;color:#dc2626;padding:3px 10px;border-radius:12px;font-size:0.75rem;font-weight:600;">✗ Out of Stock</span>
                                <?php endif; ?>
                            </td>
                            <td class="px-3 py-3">
                                <span style="color:#f59e0b;">★</span> <span style="font-weight:600;"><?php echo $p['rating']; ?></span>
                                <span style="font-size:0.75rem;color:#78716c;">(<?php echo $p['reviews']; ?>)</span>
                            </td>
                            <td class="px-3 py-3">
                                <?php if (!empty($p['featured'])): ?>
                                <span style="background:#ede9fe;color:#7c3aed;padding:3px 10px;border-radius:12px;font-size:0.75rem;font-weight:600;">Featured</span>
                                <?php else: ?>
                                <span style="color:#9ca3af;font-size:0.8rem;">—</span>
                                <?php endif; ?>
                            </td>
                            <td class="px-3 py-3">
                                <div class="d-flex gap-2">
                                    <a href="/?page=admin&section=product_edit&id=<?php echo $p['id']; ?>" class="btn btn-sm btn-warning rounded-pill px-3">
                                        <i class="fas fa-edit me-1"></i>Edit
                                    </a>
                                    <form method="POST" action="/?page=admin&section=product_delete&id=<?php echo $p['id']; ?>" onsubmit="return confirm('Delete this product?');" style="display:inline;">
                                        <button type="submit" class="btn btn-sm btn-danger rounded-pill px-3">
                                            <i class="fas fa-trash-alt me-1"></i>Delete
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
            <?php endif; ?>
        </div>
    </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
