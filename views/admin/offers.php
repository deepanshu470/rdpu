<?php $currentSection = 'offers'; ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Offers — Admin — Achar</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="/public/css/custom.css">
    <style>body { font-family: 'Poppins', sans-serif; background: #f3f4f6; }
    .admin-layout { display: flex; min-height: 100vh; }
    .main-content { flex: 1; overflow-x: auto; }
    #add-offer-form { display: none; transition: all 0.3s; }</style>
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
            <a href="/?page=admin&section=products"><i class="fas fa-jar"></i> Products</a>
            <a href="/?page=admin&section=offers" class="active"><i class="fas fa-tags"></i> Offers</a>
            <a href="/?page=admin&section=orders"><i class="fas fa-shopping-bag"></i> Orders</a>
        </nav>
        <div class="px-4 mt-auto pt-4" style="border-top:1px solid #44403c;">
            <a href="/?page=home" style="display:flex;align-items:center;gap:8px;color:#a8a29e;text-decoration:none;font-size:0.85rem;margin-bottom:8px;"><i class="fas fa-store"></i> View Shop</a>
            <a href="/?page=admin&section=logout" style="display:flex;align-items:center;gap:8px;color:#fca5a5;text-decoration:none;font-size:0.85rem;"><i class="fas fa-sign-out-alt"></i> Logout</a>
        </div>
    </aside>

    <div class="main-content p-4">
        <div class="d-flex justify-content-between align-items-center mb-4 p-3 bg-white rounded-3 shadow-sm">
            <h4 style="font-weight:700;color:#1c1917;margin:0;"><i class="fas fa-tags me-2" style="color:#d97706;"></i>Offers & Coupons</h4>
            <button onclick="toggleAddForm()" class="btn btn-success rounded-pill px-4 fw-bold">
                <i class="fas fa-plus me-2"></i>Add New Offer
            </button>
        </div>

        <!-- Add Offer Form -->
        <div id="add-offer-form" class="bg-white rounded-3 shadow-sm p-4 mb-4">
            <h5 class="fw-bold mb-4" style="color:#1c1917;border-bottom:2px solid #fef3c7;padding-bottom:12px;">
                <i class="fas fa-plus-circle me-2" style="color:#d97706;"></i>Add New Offer
            </h5>
            <form method="POST" action="/?page=admin&section=offers">
                <input type="hidden" name="action" value="create">
                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label fw-600" style="font-size:0.875rem;">Offer Title <span class="text-danger">*</span></label>
                        <input type="text" name="title" class="form-control" placeholder="e.g. Welcome Offer" required>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label fw-600" style="font-size:0.875rem;">Discount % <span class="text-danger">*</span></label>
                        <input type="number" name="discount" class="form-control" min="1" max="99" required placeholder="10">
                    </div>
                    <div class="col-md-3">
                        <label class="form-label fw-600" style="font-size:0.875rem;">Coupon Code <span class="text-danger">*</span></label>
                        <input type="text" name="code" class="form-control" placeholder="PICKLE10" required style="text-transform:uppercase;">
                    </div>
                    <div class="col-12">
                        <label class="form-label fw-600" style="font-size:0.875rem;">Description</label>
                        <input type="text" name="description" class="form-control" placeholder="Offer description for customers">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label fw-600" style="font-size:0.875rem;">Minimum Order (₹)</label>
                        <input type="number" name="minOrder" class="form-control" min="0" value="0" placeholder="0">
                    </div>
                    <div class="col-md-4 d-flex align-items-end">
                        <div class="form-check form-switch p-3 rounded-3 w-100" style="background:#f9fafb;">
                            <input class="form-check-input" type="checkbox" name="active" id="newActive" style="width:2.5em;height:1.4em;" checked>
                            <label class="form-check-label ms-2 fw-600" for="newActive" style="font-size:0.875rem;">Active</label>
                        </div>
                    </div>
                    <div class="col-md-4 d-flex align-items-end">
                        <button type="submit" class="btn btn-success rounded-pill px-4 py-2 fw-bold w-100">
                            <i class="fas fa-save me-2"></i>Save Offer
                        </button>
                    </div>
                </div>
            </form>
        </div>

        <!-- Offers Table -->
        <div class="bg-white rounded-3 shadow-sm overflow-hidden">
            <div class="p-3" style="border-bottom:1px solid #f3f4f6;">
                <h5 class="fw-bold mb-0" style="color:#1c1917;"><i class="fas fa-list me-2" style="color:#d97706;"></i>All Offers (<?php echo count($offers); ?>)</h5>
            </div>
            <?php if (empty($offers)): ?>
            <div class="p-5 text-center" style="color:#78716c;">
                <i class="fas fa-tags fs-1 d-block mb-3"></i>
                <p>No offers created yet.</p>
            </div>
            <?php else: ?>
            <div class="table-responsive">
                <table class="table mb-0 align-middle" style="font-size:0.875rem;">
                    <thead style="background:#fef3c7;">
                        <tr>
                            <th class="px-3 py-3" style="border:none;color:#57534e;font-weight:600;">ID</th>
                            <th class="px-3 py-3" style="border:none;color:#57534e;font-weight:600;">Title</th>
                            <th class="px-3 py-3" style="border:none;color:#57534e;font-weight:600;">Code</th>
                            <th class="px-3 py-3" style="border:none;color:#57534e;font-weight:600;">Discount</th>
                            <th class="px-3 py-3" style="border:none;color:#57534e;font-weight:600;">Min Order</th>
                            <th class="px-3 py-3" style="border:none;color:#57534e;font-weight:600;">Status</th>
                            <th class="px-3 py-3" style="border:none;color:#57534e;font-weight:600;">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($offers as $offer): ?>
                        <tr style="border-bottom:1px solid #f9fafb;">
                            <td class="px-3 py-3" style="color:#78716c;">#<?php echo $offer['id']; ?></td>
                            <td class="px-3 py-3">
                                <div style="font-weight:600;color:#1c1917;"><?php echo htmlspecialchars($offer['title']); ?></div>
                                <?php $desc = $offer['description'] ?? ''; ?>
                <div style="font-size:0.75rem;color:#78716c;"><?php echo htmlspecialchars(strlen($desc) > 50 ? substr($desc, 0, 50) . '...' : $desc); ?></div>
                            </td>
                            <td class="px-3 py-3">
                                <code style="background:#f3f4f6;padding:4px 10px;border-radius:8px;font-size:0.85rem;color:#7c3aed;font-weight:700;letter-spacing:1px;">
                                    <?php echo htmlspecialchars($offer['code']); ?>
                                </code>
                            </td>
                            <td class="px-3 py-3">
                                <span style="background:#dcfce7;color:#166534;padding:3px 10px;border-radius:12px;font-size:0.8rem;font-weight:700;">
                                    <?php echo $offer['discount']; ?>% OFF
                                </span>
                            </td>
                            <td class="px-3 py-3" style="color:#57534e;">₹<?php echo $offer['minOrder'] ?? 0; ?></td>
                            <td class="px-3 py-3">
                                <?php if ($offer['active']): ?>
                                <span style="background:#dcfce7;color:#166534;padding:3px 10px;border-radius:12px;font-size:0.75rem;font-weight:600;">● Active</span>
                                <?php else: ?>
                                <span style="background:#f3f4f6;color:#9ca3af;padding:3px 10px;border-radius:12px;font-size:0.75rem;font-weight:600;">● Inactive</span>
                                <?php endif; ?>
                            </td>
                            <td class="px-3 py-3">
                                <div class="d-flex gap-2 flex-wrap">
                                    <form method="POST" action="/?page=admin&section=offers" style="display:inline;">
                                        <input type="hidden" name="action" value="toggle">
                                        <input type="hidden" name="id" value="<?php echo $offer['id']; ?>">
                                        <button type="submit" class="btn btn-sm rounded-pill px-3" style="background:<?php echo $offer['active'] ? '#fef3c7' : '#dcfce7'; ?>;color:<?php echo $offer['active'] ? '#d97706' : '#166534'; ?>;border:none;font-size:0.78rem;font-weight:600;">
                                            <i class="fas fa-toggle-<?php echo $offer['active'] ? 'on' : 'off'; ?> me-1"></i>
                                            <?php echo $offer['active'] ? 'Deactivate' : 'Activate'; ?>
                                        </button>
                                    </form>
                                    <form method="POST" action="/?page=admin&section=offers" onsubmit="return confirm('Delete this offer?');" style="display:inline;">
                                        <input type="hidden" name="action" value="delete">
                                        <input type="hidden" name="id" value="<?php echo $offer['id']; ?>">
                                        <button type="submit" class="btn btn-sm btn-danger rounded-pill px-3" style="font-size:0.78rem;">
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
<script>
function toggleAddForm() {
    const form = document.getElementById('add-offer-form');
    form.style.display = form.style.display === 'none' || form.style.display === '' ? 'block' : 'none';
}
</script>
</body>
</html>
