<?php
// ============================================
// HALAMAN BERANDA
// File: index.php
// Pertemuan 13: Tampilan Produk & Katalog
// ============================================
require_once 'includes/config.php';
$page_title = 'Beranda';
include 'includes/header.php';

// Ambil produk terbaru (6 produk)
$produk_baru = $conn->query("
    SELECT p.*, k.nama_kategori
    FROM produk p
    LEFT JOIN kategori k ON p.kategori_id = k.id
    ORDER BY p.created_at DESC
    LIMIT 6
");

// Ambil semua kategori
$kategori_all = $conn->query("SELECT *, (SELECT COUNT(*) FROM produk WHERE kategori_id = kategori.id) as jumlah_produk FROM kategori");

// Statistik untuk hero section
$total_produk  = $conn->query("SELECT COUNT(*) as c FROM produk")->fetch_assoc()['c'];
$total_user    = $conn->query("SELECT COUNT(*) as c FROM user WHERE role='user'")->fetch_assoc()['c'];
$total_pesanan = $conn->query("SELECT COUNT(*) as c FROM pesanan")->fetch_assoc()['c'];
?>

<!-- HERO SECTION -->
<div class="card mb-5" style="background: linear-gradient(135deg, #0f3460, #2c5364); color:white; border-radius:20px; overflow-y: auto;
    <div class="card-body p-5">
        <div class="row align-items-center">
            <div class="col-md-7">
                <span class="badge bg-warning text-dark mb-3">🌟 Koleksi Terbaru 2024</span>
                <h1 class="display-5 fw-700 mb-3">Busana Muslimah <br><span style="color:#f9c74f;">Elegan & Syari</span></h1>
                <p class="mb-4" style="opacity:0.9;">Temukan koleksi hijab, gamis, dan aksesoris muslimah pilihan. Kualitas premium, harga bersahabat.</p>
                <div class="d-flex gap-3">
                    <a href="produk.php" class="btn btn-warning fw-600 px-4"><i class="bi bi-grid me-2"></i>Lihat Produk</a>
                    <?php if (!isLoggedIn()): ?>
                    <a href="register.php" class="btn btn-outline-light fw-600 px-4">Daftar Gratis</a>
                    <?php endif; ?>
                </div>
            </div>
            <div class="col-md-5 text-center mt-4 mt-md-0">
                <div class="row g-3">
                    <div class="col-4">
                        <div class="p-3 rounded-3" style="background:rgba(255,255,255,0.1);">
                            <div class="fw-700 fs-3" style="color:#f9c74f;"><?= $total_produk ?>+</div>
                            <div style="font-size:0.75rem;opacity:0.8;">Produk</div>
                        </div>
                    </div>
                    <div class="col-4">
                        <div class="p-3 rounded-3" style="background:rgba(255,255,255,0.1);">
                            <div class="fw-700 fs-3" style="color:#f9c74f;"><?= $total_user ?>+</div>
                            <div style="font-size:0.75rem;opacity:0.8;">Member</div>
                        </div>
                    </div>
                    <div class="col-4">
                        <div class="p-3 rounded-3" style="background:rgba(255,255,255,0.1);">
                            <div class="fw-700 fs-3" style="color:#f9c74f;">4.9⭐</div>
                            <div style="font-size:0.75rem;opacity:0.8;">Rating</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- KATEGORI -->
<h4 class="fw-700 mb-3"><i class="bi bi-tags me-2"></i>Kategori Produk</h4>
<div class="row g-3 mb-5">
    <?php
    $cat_icons = ['Hijab'=>'🧕','Gamis'=>'👗','Aksesoris'=>'💎','Tas'=>'👜'];
    $cat_colors = ['Hijab'=>'#e8f4fd','Gamis'=>'#fdf0e8','Aksesoris'=>'#f0e8fd','Tas'=>'#e8fdf0'];
    while ($kat = $kategori_all->fetch_assoc()):
        $icon  = $cat_icons[$kat['nama_kategori']] ?? '🛍️';
        $color = $cat_colors[$kat['nama_kategori']] ?? '#f8f9fa';
    ?>
    <div class="col-6 col-md-3">
        <a href="produk.php?kategori=<?= $kat['id'] ?>" class="text-decoration-none">
            <div class="card h-100 text-center p-3" style="background:<?= $color ?>; transition: transform 0.2s;" onmouseover="this.style.transform='translateY(-5px)'" onmouseout="this.style.transform='translateY(0)'">
                <div style="font-size:2.5rem;"><?= $icon ?></div>
                <div class="fw-600 mt-2"><?= clean($kat['nama_kategori']) ?></div>
                <div class="text-muted" style="font-size:0.8rem;"><?= $kat['jumlah_produk'] ?> produk</div>
            </div>
        </a>
    </div>
    <?php endwhile; ?>
</div>

<!-- PRODUK TERBARU -->
<div class="d-flex justify-content-between align-items-center mb-3">
    <h4 class="fw-700 mb-0"><i class="bi bi-star me-2"></i>Produk Terbaru</h4>
    <a href="produk.php" class="btn btn-sm btn-outline-secondary">Lihat Semua →</a>
</div>
<div class="row g-4 mb-5">
    <?php while ($p = $produk_baru->fetch_assoc()): ?>
    <div class="col-6 col-md-4">
        <div class="card h-100 product-card">
            <!-- Gambar produk (placeholder jika belum ada) -->
            <div style="height:200px; background: linear-gradient(135deg, #667eea22, #764ba222); display:flex; align-items:center; justify-content:center; border-radius:12px 12px 0 0;">
                <?php if ($p['gambar'] && file_exists('uploads/' . $p['gambar'])): ?>
                    <img src="uploads/<?= $p['gambar'] ?>" class="product-img w-100" alt="<?= clean($p['nama_produk']) ?>">
                <?php else: ?>
                    <span style="font-size:4rem;">🛍️</span>
                <?php endif; ?>
            </div>
            <div class="card-body d-flex flex-column">
                <span class="badge bg-light text-secondary mb-2" style="font-size:0.7rem; width:fit-content;"><?= clean($p['nama_kategori'] ?? 'Produk') ?></span>
                <h6 class="card-title fw-600"><?= clean($p['nama_produk']) ?></h6>
                <p class="text-muted small flex-grow-1"><?= clean(substr($p['deskripsi'], 0, 60)) ?>...</p>
                <div class="d-flex justify-content-between align-items-center mt-auto">
                    <span class="fw-700 text-primary fs-5"><?= rupiah($p['harga']) ?></span>
                    <span class="badge <?= $p['stok'] > 0 ? 'bg-success' : 'bg-danger' ?>">
                        <?= $p['stok'] > 0 ? 'Stok: ' . $p['stok'] : 'Habis' ?>
                    </span>
                </div>
                <div class="mt-3 d-grid gap-2">
                    <a href="detail_produk.php?id=<?= $p['id'] ?>" class="btn btn-outline-primary btn-sm">
                        <i class="bi bi-eye me-1"></i>Detail
                    </a>
                    <?php if (isLoggedIn() && $p['stok'] > 0): ?>
                    <a href="keranjang.php?action=tambah&id=<?= $p['id'] ?>" class="btn btn-primary btn-sm">
                        <i class="bi bi-cart-plus me-1"></i>Tambah ke Keranjang
                    </a>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
    <?php endwhile; ?>
</div>

<?php include 'includes/footer.php'; ?>
