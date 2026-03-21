<?php
// Shared admin sidebar snippet — included directly in admin views
$currentSection = $_GET['section'] ?? 'dashboard';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard — Achar</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="/public/css/custom.css">
    <style>
        body { font-family: 'Poppins', sans-serif; background: #f3f4f6; }
        .admin-layout { display: flex; min-height: 100vh; }
        .main-content { flex: 1; overflow-x: auto; }
    </style>
</head>
<body>
<div class="admin-layout">
    <!-- Sidebar -->
    <aside class="admin-sidebar d-flex flex-column py-4">
        <div class="px-4 mb-4 text-center">
            <div style="font-size:1.3rem;font-weight:800;color:#d97706;">Achar</div>
            <div style="font-size:0.6rem;color:#4ade80;letter-spacing:3px;">ADMIN PANEL</div>
        </div>
        <nav class="flex-grow-1">
            <a href="/?page=admin" class="<?php echo !$currentSection || $currentSection === 'dashboard' ? 'active' : ''; ?>">
                <i class="fas fa-tachometer-alt"></i> Dashboard
            </a>
            <a href="/?page=admin&section=products" class="<?php echo $currentSection === 'products' || $currentSection === 'product_new' || $currentSection === 'product_edit' ? 'active' : ''; ?>">
                <i class="fas fa-jar"></i> Products
            </a>
            <a href="/?page=admin&section=offers" class="<?php echo $currentSection === 'offers' ? 'active' : ''; ?>">
                <i class="fas fa-tags"></i> Offers
            </a>
            <a href="/?page=admin&section=orders" class="<?php echo $currentSection === 'orders' ? 'active' : ''; ?>">
                <i class="fas fa-shopping-bag"></i> Orders
            </a>
        </nav>
        <div class="px-4 mt-auto pt-4" style="border-top:1px solid #44403c;">
            <a href="/?page=home" style="display:flex;align-items:center;gap:8px;color:#a8a29e;text-decoration:none;font-size:0.85rem;margin-bottom:8px;">
                <i class="fas fa-store"></i> View Shop
            </a>
            <a href="/?page=admin&section=logout" style="display:flex;align-items:center;gap:8px;color:#fca5a5;text-decoration:none;font-size:0.85rem;">
                <i class="fas fa-sign-out-alt"></i> Logout
            </a>
        </div>
    </aside>

    <!-- Main Content -->
    <div class="main-content p-4">
        <!-- Top bar -->
        <div class="d-flex justify-content-between align-items-center mb-4 p-3 bg-white rounded-3 shadow-sm">
            <h4 style="font-weight:700;color:#1c1917;margin:0;"><i class="fas fa-tachometer-alt me-2" style="color:#d97706;"></i>Dashboard</h4>
            <span style="font-size:0.8rem;color:#78716c;"><i class="fas fa-clock me-1"></i><?php echo date('D, d M Y'); ?></span>
        </div>

        <!-- Stats Cards -->
        <div class="row g-4 mb-4">
            <div class="col-sm-6 col-xl-3">
                <div class="bg-white rounded-3 p-4 shadow-sm d-flex align-items-center gap-3">
                    <div style="width:56px;height:56px;background:#dcfce7;border-radius:14px;display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                        <i class="fas fa-jar" style="font-size:1.4rem;color:#166534;"></i>
                    </div>
                    <div>
                        <div style="font-size:1.8rem;font-weight:800;color:#1c1917;line-height:1;"><?php echo count($products); ?></div>
                        <div style="font-size:0.8rem;color:#78716c;">Total Products</div>
                    </div>
                </div>
            </div>
            <div class="col-sm-6 col-xl-3">
                <div class="bg-white rounded-3 p-4 shadow-sm d-flex align-items-center gap-3">
                    <div style="width:56px;height:56px;background:#fef3c7;border-radius:14px;display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                        <i class="fas fa-shopping-bag" style="font-size:1.4rem;color:#d97706;"></i>
                    </div>
                    <div>
                        <div style="font-size:1.8rem;font-weight:800;color:#1c1917;line-height:1;"><?php echo count($orders); ?></div>
                        <div style="font-size:0.8rem;color:#78716c;">Total Orders</div>
                    </div>
                </div>
            </div>
            <div class="col-sm-6 col-xl-3">
                <div class="bg-white rounded-3 p-4 shadow-sm d-flex align-items-center gap-3">
                    <div style="width:56px;height:56px;background:#ede9fe;border-radius:14px;display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                        <i class="fas fa-rupee-sign" style="font-size:1.4rem;color:#7c3aed;"></i>
                    </div>
                    <div>
                        <div style="font-size:1.8rem;font-weight:800;color:#1c1917;line-height:1;">₹<?php echo number_format($revenue); ?></div>
                        <div style="font-size:0.8rem;color:#78716c;">Total Revenue</div>
                    </div>
                </div>
            </div>
            <div class="col-sm-6 col-xl-3">
                <div class="bg-white rounded-3 p-4 shadow-sm d-flex align-items-center gap-3">
                    <div style="width:56px;height:56px;background:#fee2e2;border-radius:14px;display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                        <i class="fas fa-clock" style="font-size:1.4rem;color:#dc2626;"></i>
                    </div>
                    <div>
                        <?php $pendingOrders = array_filter($orders, fn($o) => $o['status'] === 'pending'); ?>
                        <div style="font-size:1.8rem;font-weight:800;color:#1c1917;line-height:1;"><?php echo count($pendingOrders); ?></div>
                        <div style="font-size:0.8rem;color:#78716c;">Pending Orders</div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Quick Actions -->
        <div class="row g-3 mb-4">
            <div class="col-12">
                <div class="bg-white rounded-3 p-3 shadow-sm d-flex flex-wrap gap-2">
                    <a href="/?page=admin&section=product_new" class="btn btn-sm btn-success rounded-pill px-3"><i class="fas fa-plus me-1"></i>Add Product</a>
                    <a href="/?page=admin&section=products" class="btn btn-sm btn-warning rounded-pill px-3"><i class="fas fa-list me-1"></i>Manage Products</a>
                    <a href="/?page=admin&section=offers" class="btn btn-sm btn-info text-white rounded-pill px-3"><i class="fas fa-tags me-1"></i>Manage Offers</a>
                    <a href="/?page=admin&section=orders" class="btn btn-sm btn-secondary rounded-pill px-3"><i class="fas fa-shopping-bag me-1"></i>View Orders</a>
                    <a href="/?page=home" class="btn btn-sm btn-outline-secondary rounded-pill px-3" target="_blank"><i class="fas fa-external-link-alt me-1"></i>View Shop</a>
                </div>
            </div>
        </div>

        <!-- Recent Orders -->
        <div class="bg-white rounded-3 shadow-sm overflow-hidden">
            <div class="p-3 d-flex justify-content-between align-items-center" style="border-bottom:1px solid #f3f4f6;">
                <h5 class="fw-bold mb-0" style="color:#1c1917;"><i class="fas fa-history me-2" style="color:#d97706;"></i>Recent Orders</h5>
                <a href="/?page=admin&section=orders" class="btn btn-sm btn-outline-warning rounded-pill">View All</a>
            </div>
            <?php $recentOrders = array_slice($orders, 0, 5); ?>
            <?php if (empty($recentOrders)): ?>
            <div class="p-5 text-center" style="color:#78716c;">
                <i class="fas fa-inbox fs-2 d-block mb-2"></i>No orders yet.
            </div>
            <?php else: ?>
            <div class="table-responsive">
                <table class="table mb-0 align-middle" style="font-size:0.875rem;">
                    <thead style="background:#f9fafb;">
                        <tr>
                            <th class="px-3 py-3" style="border:none;color:#57534e;font-weight:600;">Order ID</th>
                            <th class="px-3 py-3" style="border:none;color:#57534e;font-weight:600;">Customer</th>
                            <th class="px-3 py-3" style="border:none;color:#57534e;font-weight:600;">Items</th>
                            <th class="px-3 py-3" style="border:none;color:#57534e;font-weight:600;">Total</th>
                            <th class="px-3 py-3" style="border:none;color:#57534e;font-weight:600;">Payment</th>
                            <th class="px-3 py-3" style="border:none;color:#57534e;font-weight:600;">Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($recentOrders as $order): ?>
                        <tr style="border-bottom:1px solid #f9fafb;">
                            <td class="px-3 py-3"><code style="font-size:0.78rem;color:#7c3aed;"><?php echo htmlspecialchars($order['id']); ?></code></td>
                            <td class="px-3 py-3">
                                <div style="font-weight:600;color:#1c1917;"><?php echo htmlspecialchars($order['customer']['name']); ?></div>
                                <div style="font-size:0.75rem;color:#78716c;"><?php echo htmlspecialchars($order['customer']['city']); ?></div>
                            </td>
                            <td class="px-3 py-3"><?php echo count($order['items'] ?? []); ?> item(s)</td>
                            <td class="px-3 py-3"><strong style="color:#166534;">₹<?php echo number_format($order['total']); ?></strong></td>
                            <td class="px-3 py-3"><span style="text-transform:uppercase;font-size:0.78rem;font-weight:600;"><?php echo htmlspecialchars($order['payment']); ?></span></td>
                            <td class="px-3 py-3">
                                <span style="background:#fef3c7;color:#d97706;padding:3px 10px;border-radius:12px;font-size:0.75rem;font-weight:600;text-transform:capitalize;">
                                    <?php echo htmlspecialchars($order['status']); ?>
                                </span>
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
