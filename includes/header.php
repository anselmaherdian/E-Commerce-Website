<?php
// ============================================
// HEADER TEMPLATE
// File: includes/header.php
// ============================================
$keranjang_count = countKeranjang($conn);
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= isset($page_title) ? $page_title . ' | ' : '' ?><?= SITE_NAME ?></title>
    <!-- Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css" rel="stylesheet">
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        :root {
            --primary: #2c5364;
            --secondary: #0f3460;
            --accent: #e94560;
            --light: #f8f9fa;
        }
        body { font-family: 'Poppins', sans-serif; background: #f4f6f9; }
        .navbar { background: linear-gradient(135deg, var(--secondary), var(--primary)); }
        .navbar-brand { font-weight: 700; font-size: 1.4rem; color: white !important; }
        .navbar-brand span { color: #f9c74f; }
        .nav-link { color: rgba(255,255,255,0.85) !important; font-weight: 500; }
        .nav-link:hover { color: white !important; }
        .btn-accent { background: var(--accent); border-color: var(--accent); color: white; }
        .btn-accent:hover { background: #c73652; color: white; }
        .badge-cart { background: var(--accent); }
        .card { border: none; border-radius: 12px; box-shadow: 0 2px 12px rgba(0,0,0,0.08); }
        .card-header { border-radius: 12px 12px 0 0 !important; }
        footer { background: var(--secondary); color: rgba(255,255,255,0.7); }
        .product-card:hover { transform: translateY(-4px); transition: transform 0.2s; }
        .product-img { height: 220px; object-fit: cover; border-radius: 8px 8px 0 0; }
        .table th { background: #f0f4f8; font-weight: 600; }
        .sidebar-link { color: #444; text-decoration: none; padding: 10px 15px; display: block; border-radius: 8px; margin-bottom: 4px; }
        .sidebar-link:hover, .sidebar-link.active { background: var(--primary); color: white; }
        .sidebar-link i { width: 20px; }
    </style>
</head>
<body>

<!-- NAVBAR -->
<nav class="navbar navbar-expand-lg navbar-dark sticky-top">
    <div class="container">
        <a class="navbar-brand" href="<?= SITE_URL ?>/index.php">
            🌙 <span>Muslimah</span> Shop
        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navMenu">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navMenu">
            <ul class="navbar-nav me-auto">
                <li class="nav-item">
                    <a class="nav-link" href="<?= SITE_URL ?>/index.php"><i class="bi bi-house"></i> Beranda</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="<?= SITE_URL ?>/produk.php"><i class="bi bi-grid"></i> Produk</a>
                </li>
            </ul>
            <ul class="navbar-nav">
                <?php if (isLoggedIn()): ?>
                    <li class="nav-item">
                        <a class="nav-link" href="<?= SITE_URL ?>/keranjang.php">
                            <i class="bi bi-cart3"></i> Keranjang
                            <?php if ($keranjang_count > 0): ?>
                                <span class="badge badge-cart rounded-pill"><?= $keranjang_count ?></span>
                            <?php endif; ?>
                        </a>
                    </li>
                    <?php if (isAdmin()): ?>
                    <li class="nav-item">
                        <a class="nav-link" href="<?= SITE_URL ?>/admin/index.php"><i class="bi bi-gear"></i> Admin</a>
                    </li>
                    <?php endif; ?>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" data-bs-toggle="dropdown">
                            <i class="bi bi-person-circle"></i> <?= clean($_SESSION['nama']) ?>
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end">
                            <li><a class="dropdown-item" href="<?= SITE_URL ?>/profil.php"><i class="bi bi-person me-2"></i>Profil</a></li>
                            <li><a class="dropdown-item" href="<?= SITE_URL ?>/pesanan.php"><i class="bi bi-box-seam me-2"></i>Pesanan Saya</a></li>
                            <li><hr class="dropdown-divider"></li>
                            <li><a class="dropdown-item text-danger" href="<?= SITE_URL ?>/logout.php"><i class="bi bi-box-arrow-right me-2"></i>Logout</a></li>
                        </ul>
                    </li>
                <?php else: ?>
                    <li class="nav-item">
                        <a class="nav-link" href="<?= SITE_URL ?>/login.php"><i class="bi bi-box-arrow-in-right"></i> Login</a>
                    </li>
                    <li class="nav-item">
                        <a class="btn btn-accent btn-sm ms-2" href="<?= SITE_URL ?>/register.php">Daftar</a>
                    </li>
                <?php endif; ?>
            </ul>
        </div>
    </div>
</nav>
<div class="container mt-4">
<?php getFlash(); ?>
