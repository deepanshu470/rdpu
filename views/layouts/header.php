<?php
$flash = $_SESSION['flash'] ?? null;
if (isset($_SESSION['flash'])) unset($_SESSION['flash']);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo APP_NAME; ?></title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="<?php echo BASE_URL; ?>/public/css/custom.css">
    <style>
        body { font-family: 'Poppins', sans-serif; }
        .tailwind-compat { all: revert; font-family: 'Poppins', sans-serif; }
    </style>
</head>
<body class="bg-gray-50">

<!-- Announcement Bar -->
<div class="announcement-bar text-white text-center py-2 px-4" style="font-size:0.85rem;font-weight:500;">
    <i class="fas fa-truck me-2"></i>
    🎉 Free delivery on orders above ₹500! Use code <strong>PICKLE10</strong> for 10% off
    &nbsp;|&nbsp;
    <i class="fas fa-star text-yellow-300 me-1"></i> 4.8★ Rated by 10,000+ happy customers
</div>

<!-- Navbar -->
<nav class="navbar navbar-expand-lg sticky-top bg-white shadow-sm" style="z-index:1030;">
    <div class="container">
        <a class="navbar-brand d-flex align-items-center gap-2 text-decoration-none" href="<?php echo BASE_URL; ?>/?page=home">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 44 44" width="44" height="44">
                <rect x="8" y="18" width="28" height="22" rx="5" fill="#166534"/>
                <rect x="5" y="12" width="34" height="9" rx="4" fill="#d97706"/>
                <rect x="10" y="22" width="6" height="12" rx="3" fill="#4ade80" opacity="0.85"/>
                <rect x="19" y="24" width="5" height="10" rx="2.5" fill="#22c55e" opacity="0.7"/>
                <rect x="27" y="21" width="6" height="14" rx="3" fill="#4ade80" opacity="0.9"/>
            </svg>
            <div>
                <span style="font-size:1.4rem;font-weight:800;color:#d97706;line-height:1;">Achar</span><br>
                <span style="font-size:0.6rem;color:#166534;letter-spacing:3px;font-weight:600;">PICKLES</span>
            </div>
        </a>

        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav mx-auto gap-1">
                <li class="nav-item">
                    <a class="nav-link px-3 fw-500 <?php echo ($page ?? 'home') === 'home' ? 'text-warning fw-bold' : ''; ?>" href="<?php echo BASE_URL; ?>/?page=home">
                        <i class="fas fa-home me-1"></i>Home
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link px-3 <?php echo ($page ?? '') === 'products' ? 'text-warning fw-bold' : ''; ?>" href="<?php echo BASE_URL; ?>/?page=products">
                        <i class="fas fa-store me-1"></i>Products
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link px-3" href="#about">
                        <i class="fas fa-info-circle me-1"></i>About
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link px-3" href="#contact">
                        <i class="fas fa-envelope me-1"></i>Contact
                    </a>
                </li>
            </ul>

            <div class="d-flex align-items-center gap-3">
                <a href="<?php echo BASE_URL; ?>/?page=cart" class="btn btn-outline-warning position-relative rounded-pill px-3">
                    <i class="fas fa-shopping-cart"></i>
                    <span class="cart-badge position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger"
                          style="font-size:0.65rem;<?php echo ($cartCount ?? 0) > 0 ? '' : 'display:none;'; ?>">
                        <?php echo $cartCount ?? 0; ?>
                    </span>
                    <span class="ms-1 d-none d-lg-inline">Cart</span>
                </a>
                <a href="<?php echo BASE_URL; ?>/?page=admin" class="btn btn-sm" style="background:#166534;color:white;border-radius:20px;font-size:0.8rem;">
                    <i class="fas fa-user-shield me-1"></i>Admin
                </a>
            </div>
        </div>
    </div>
</nav>

<?php if ($flash): ?>
<div class="container mt-3">
    <div class="alert alert-<?php echo $flash['type'] === 'success' ? 'success' : 'danger'; ?> alert-dismissible fade show rounded-3 shadow-sm" role="alert">
        <i class="fas fa-<?php echo $flash['type'] === 'success' ? 'check-circle' : 'exclamation-triangle'; ?> me-2"></i>
        <?php echo htmlspecialchars($flash['message']); ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
</div>
<?php endif; ?>

<main>
