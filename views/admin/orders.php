<?php $currentSection = 'orders'; ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Orders — Admin — Achar</title>
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
            <a href="/?page=admin&section=products"><i class="fas fa-jar"></i> Products</a>
            <a href="/?page=admin&section=offers"><i class="fas fa-tags"></i> Offers</a>
            <a href="/?page=admin&section=orders" class="active"><i class="fas fa-shopping-bag"></i> Orders</a>
        </nav>
        <div class="px-4 mt-auto pt-4" style="border-top:1px solid #44403c;">
            <a href="/?page=home" style="display:flex;align-items:center;gap:8px;color:#a8a29e;text-decoration:none;font-size:0.85rem;margin-bottom:8px;"><i class="fas fa-store"></i> View Shop</a>
            <a href="/?page=admin&section=logout" style="display:flex;align-items:center;gap:8px;color:#fca5a5;text-decoration:none;font-size:0.85rem;"><i class="fas fa-sign-out-alt"></i> Logout</a>
        </div>
    </aside>

    <div class="main-content p-4">
        <div class="d-flex justify-content-between align-items-center mb-4 p-3 bg-white rounded-3 shadow-sm">
            <h4 style="font-weight:700;color:#1c1917;margin:0;">
                <i class="fas fa-shopping-bag me-2" style="color:#d97706;"></i>Orders Management
                <span style="font-size:0.9rem;font-weight:500;color:#78716c;">(<?php echo count($orders); ?> total)</span>
            </h4>
        </div>

        <div class="bg-white rounded-3 shadow-sm overflow-hidden">
            <?php if (empty($orders)): ?>
            <div class="p-5 text-center">
                <div style="font-size:4rem;margin-bottom:16px;">📦</div>
                <h5 style="font-weight:700;color:#1c1917;">No orders yet</h5>
                <p style="color:#78716c;margin-bottom:24px;">When customers place orders, they will appear here.</p>
                <a href="/?page=home" class="btn btn-warning rounded-pill px-4 fw-bold">View Shop</a>
            </div>
            <?php else: ?>
            <div class="table-responsive">
                <table class="table mb-0 align-middle" style="font-size:0.875rem;">
                    <thead style="background:#fef3c7;">
                        <tr>
                            <th class="px-3 py-3" style="border:none;color:#57534e;font-weight:600;">Order ID</th>
                            <th class="px-3 py-3" style="border:none;color:#57534e;font-weight:600;">Customer</th>
                            <th class="px-3 py-3" style="border:none;color:#57534e;font-weight:600;">Contact</th>
                            <th class="px-3 py-3" style="border:none;color:#57534e;font-weight:600;">Items</th>
                            <th class="px-3 py-3" style="border:none;color:#57534e;font-weight:600;">Total</th>
                            <th class="px-3 py-3" style="border:none;color:#57534e;font-weight:600;">Payment</th>
                            <th class="px-3 py-3" style="border:none;color:#57534e;font-weight:600;">Date</th>
                            <th class="px-3 py-3" style="border:none;color:#57534e;font-weight:600;">Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($orders as $order): ?>
                        <tr style="border-bottom:1px solid #f9fafb;">
                            <td class="px-3 py-3">
                                <code style="font-size:0.78rem;color:#7c3aed;font-weight:600;"><?php echo htmlspecialchars($order['id']); ?></code>
                            </td>
                            <td class="px-3 py-3">
                                <div style="font-weight:600;color:#1c1917;"><?php echo htmlspecialchars($order['customer']['name']); ?></div>
                                <div style="font-size:0.75rem;color:#78716c;"><?php echo htmlspecialchars($order['customer']['city']); ?>, <?php echo htmlspecialchars($order['customer']['state']); ?></div>
                            </td>
                            <td class="px-3 py-3">
                                <div style="font-size:0.8rem;color:#57534e;"><?php echo htmlspecialchars($order['customer']['phone']); ?></div>
                                <div style="font-size:0.75rem;color:#78716c;"><?php echo htmlspecialchars($order['customer']['email']); ?></div>
                            </td>
                            <td class="px-3 py-3">
                                <div style="font-weight:600;"><?php echo count($order['items'] ?? []); ?> item(s)</div>
                                <?php
                                $itemNames = [];
                                foreach (array_slice(array_values($order['items'] ?? []), 0, 2) as $itm) {
                                    if (isset($itm['name'])) $itemNames[] = $itm['name'];
                                }
                                echo '<div style="font-size:0.72rem;color:#78716c;">' . htmlspecialchars(implode(', ', $itemNames)) . (count($order['items'] ?? []) > 2 ? '...' : '') . '</div>';
                                ?>
                            </td>
                            <td class="px-3 py-3">
                                <div style="font-weight:700;color:#166534;font-size:1rem;">₹<?php echo number_format($order['total']); ?></div>
                                <?php if (!empty($order['discount']) && $order['discount'] > 0): ?>
                                <div style="font-size:0.72rem;color:#dc2626;">Saved ₹<?php echo number_format($order['discount']); ?></div>
                                <?php endif; ?>
                            </td>
                            <td class="px-3 py-3">
                                <span style="background:#f3f4f6;padding:3px 10px;border-radius:10px;font-size:0.78rem;font-weight:600;text-transform:uppercase;color:#374151;">
                                    <?php echo htmlspecialchars($order['payment']); ?>
                                </span>
                            </td>
                            <td class="px-3 py-3" style="font-size:0.78rem;color:#78716c;white-space:nowrap;">
                                <?php echo date('d M Y', strtotime($order['createdAt'])); ?><br>
                                <span style="font-size:0.7rem;"><?php echo date('H:i', strtotime($order['createdAt'])); ?></span>
                            </td>
                            <td class="px-3 py-3">
                                <?php
                                $statusColors = [
                                    'pending'   => ['bg' => '#fef3c7', 'color' => '#d97706'],
                                    'confirmed' => ['bg' => '#dcfce7', 'color' => '#166534'],
                                    'shipped'   => ['bg' => '#dbeafe', 'color' => '#1d4ed8'],
                                    'delivered' => ['bg' => '#dcfce7', 'color' => '#166534'],
                                    'cancelled' => ['bg' => '#fee2e2', 'color' => '#dc2626'],
                                ];
                                $sc = $statusColors[$order['status']] ?? ['bg' => '#f3f4f6', 'color' => '#374151'];
                                ?>
                                <span style="background:<?php echo $sc['bg']; ?>;color:<?php echo $sc['color']; ?>;padding:4px 12px;border-radius:12px;font-size:0.75rem;font-weight:600;text-transform:capitalize;">
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
