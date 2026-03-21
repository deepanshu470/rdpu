<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login — Achar</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700;800&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Poppins', sans-serif; background: linear-gradient(135deg, #1c1917 0%, #292524 50%, #166534 100%); min-height: 100vh; display: flex; align-items: center; justify-content: center; }
        .login-card { background: white; border-radius: 20px; overflow: hidden; box-shadow: 0 25px 80px rgba(0,0,0,0.4); width: 100%; max-width: 420px; }
        .login-header { background: linear-gradient(135deg, #166534, #15803d); padding: 32px; text-align: center; }
        .login-body { padding: 32px; }
        .form-control:focus { border-color: #d97706; box-shadow: 0 0 0 0.2rem rgba(217,119,6,0.15); }
        .btn-login { background: linear-gradient(135deg, #d97706, #ea580c); color: white; border: none; padding: 12px; border-radius: 10px; font-weight: 700; font-size: 1rem; width: 100%; transition: all 0.3s; }
        .btn-login:hover { transform: translateY(-2px); box-shadow: 0 8px 25px rgba(217,119,6,0.4); color: white; }
    </style>
</head>
<body>
<div class="login-card">
    <div class="login-header">
        <div style="display:flex;align-items:center;justify-content:center;gap:12px;margin-bottom:12px;">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 44 44" width="48" height="48">
                <rect x="8" y="18" width="28" height="22" rx="5" fill="rgba(255,255,255,0.2)"/>
                <rect x="5" y="12" width="34" height="9" rx="4" fill="#d97706"/>
                <rect x="10" y="22" width="6" height="12" rx="3" fill="#4ade80" opacity="0.9"/>
                <rect x="19" y="24" width="5" height="10" rx="2.5" fill="#22c55e" opacity="0.8"/>
                <rect x="27" y="21" width="6" height="14" rx="3" fill="#4ade80" opacity="0.85"/>
            </svg>
            <div class="text-start">
                <div style="font-size:1.5rem;font-weight:800;color:white;">Achar</div>
                <div style="font-size:0.65rem;color:#86efac;letter-spacing:3px;">ADMIN PANEL</div>
            </div>
        </div>
        <p style="color:rgba(255,255,255,0.8);font-size:0.875rem;margin:0;">Welcome back! Please sign in to continue.</p>
    </div>
    <div class="login-body">
        <?php if (!empty($error)): ?>
        <div class="alert alert-danger d-flex align-items-center gap-2 rounded-3 mb-4" role="alert">
            <i class="fas fa-exclamation-triangle"></i>
            <span><?php echo htmlspecialchars($error); ?></span>
        </div>
        <?php endif; ?>

        <form method="POST">
            <div class="mb-3">
                <label class="form-label fw-600" style="font-size:0.875rem;">Username</label>
                <div class="input-group">
                    <span class="input-group-text bg-light border-end-0"><i class="fas fa-user text-muted"></i></span>
                    <input type="text" name="username" class="form-control border-start-0" placeholder="Enter username" required autocomplete="username">
                </div>
            </div>
            <div class="mb-4">
                <label class="form-label fw-600" style="font-size:0.875rem;">Password</label>
                <div class="input-group">
                    <span class="input-group-text bg-light border-end-0"><i class="fas fa-lock text-muted"></i></span>
                    <input type="password" name="password" class="form-control border-start-0" placeholder="Enter password" required autocomplete="current-password">
                </div>
            </div>
            <button type="submit" class="btn-login">
                <i class="fas fa-sign-in-alt me-2"></i>Sign In to Admin Panel
            </button>
        </form>

        <div class="text-center mt-4 pt-3" style="border-top:1px solid #f3f4f6;">
            <p style="font-size:0.78rem;color:#78716c;margin-bottom:6px;">Default credentials: admin / admin123</p>
            <a href="/?page=home" style="color:#166534;text-decoration:none;font-size:0.85rem;font-weight:600;">
                <i class="fas fa-arrow-left me-1"></i>Back to Shop
            </a>
        </div>
    </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
