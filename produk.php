<?php
// ============================================
// HALAMAN PRODUK (User)
// File: produk.php
// Pertemuan 13: Tampilan Katalog Produk
// ============================================
require_once 'includes/config.php';
$page_title = 'Produk';
include 'includes/header.php';

$keyword    = clean($_GET['q'] ?? '');
$kat_id     = (int)($_GET['kategori'] ?? 0);
$sort       = clean($_GET['sort'] ?? 'terbaru');

$where = ["1=1"];
if ($keyword) $where[] = "p.nama_produk LIKE '%$keyword%'";
if ($kat_id)  $where[] = "p.kategori_id = $kat_id";
$where_str = implode(' AND ', $where);

$order = match($sort) {
    'termurah' => 'p.harga ASC',
    'termahal'  => 'p.harga DESC',
    'nama'      => 'p.nama_produk ASC',
    default     => 'p.created_at DESC'
};

$produk_list = $conn->query("
    SELECT p.*, k.nama_kategori
    FROM produk p
    LEFT JOIN kategori k ON p.kategori_id = k.id
    WHERE $where_str
    ORDER BY $order
");

$kategori_all = $conn->query("SELECT * FROM kategori ORDER BY nama_kategori");
?>

<div class="row mb-4">
    <div class="col-md-8">
        <h4 class="fw-700"><i class="bi bi-grid me-2"></i>Semua Produk</h4>
    </div>
    <div class="col-md-4">
        <!-- SEARCH -->
        <form method="GET" class="d-flex gap-2">
            <input type="text" name="q" class="form-control" placeholder="Cari produk..." value="<?= clean($keyword) ?>">
            <button type="submit" class="btn btn-primary"><i class="bi bi-search"></i></button>
        </form>
    </div>
</div>

<div class="row g-4">
    <!-- FILTER SIDEBAR -->
    <div class="col-md-3">
        <div class="card mb-3">
            <div class="card-header" style="background:#f8f9fa;"><strong>🏷 Kategori</strong></div>
            <div class="list-group list-group-flush">
                <a href="produk.php" class="list-group-item list-group-item-action <?= !$kat_id ? 'active' : '' ?>">Semua Kategori</a>
                <?php while ($kat = $kategori_all->fetch_assoc()): ?>
                <a href="produk.php?kategori=<?= $kat['id'] ?>" class="list-group-item list-group-item-action <?= $kat_id == $kat['id'] ? 'active' : '' ?>">
                    <?= clean($kat['nama_kategori']) ?>
                </a>
                <?php endwhile; ?>
            </div>
        </div>
        <div class="card">
            <div class="card-header" style="background:#f8f9fa;"><strong>⬆ Urutkan</strong></div>
            <div class="list-group list-group-flush">
                <?php foreach (['terbaru'=>'Terbaru','termurah'=>'Termurah','termahal'=>'Termahal','nama'=>'Nama A-Z'] as $k=>$v): ?>
                <a href="?<?= http_build_query(array_merge($_GET, ['sort'=>$k])) ?>"
                   class="list-group-item list-group-item-action <?= $sort === $k ? 'active' : '' ?>"><?= $v ?></a>
                <?php endforeach; ?>
            </div>
        </div>
    </div>

    <!-- GRID PRODUK -->
    <div class="col-md-9">
        <div class="d-flex justify-content-between mb-3">
            <span class="text-muted"><?= $produk_list->num_rows ?> produk ditemukan</span>
            <?php if ($keyword || $kat_id): ?>
            <a href="produk.php" class="btn btn-sm btn-outline-secondary"><i class="bi bi-x me-1"></i>Reset Filter</a>
            <?php endif; ?>
        </div>
        <div class="row g-3">
            <?php while ($p = $produk_list->fetch_assoc()): ?>
            <div class="col-6 col-md-4">
                <div class="card h-100 product-card">
                    <div style="height:180px;background:linear-gradient(135deg,#667eea22,#764ba222);display:flex;align-items:center;justify-content:center;border-radius:12px 12px 0 0;">
                        <?php if ($p['gambar'] && file_exists('uploads/'.$p['gambar'])): ?>
                            <img src="uploads/<?= $p['gambar'] ?>" class="w-100 product-img" alt="<?= clean($p['nama_produk']) ?>">
                        <?php else: ?>
                            <span style="font-size:3.5rem;">🛍️</span>
                        <?php endif; ?>
                    </div>
                    <div class="card-body d-flex flex-column">
                        <span class="badge bg-light text-secondary mb-1" style="font-size:0.7rem;width:fit-content;"><?= clean($p['nama_kategori'] ?? '') ?></span>
                        <h6 class="fw-600"><?= clean($p['nama_produk']) ?></h6>
                        <div class="mt-auto">
                            <div class="fw-700 text-primary"><?= rupiah($p['harga']) ?></div>
                            <div class="d-flex justify-content-between align-items-center mt-2">
                                <small class="text-muted">Stok: <?= $p['stok'] ?></small>
                                <?php if ($p['stok'] <= 0): ?><span class="badge bg-danger">Habis</span><?php endif; ?>
                            </div>
                            <div class="d-grid gap-1 mt-2">
                                <a href="detail_produk.php?id=<?= $p['id'] ?>" class="btn btn-outline-primary btn-sm">Detail</a>
                                <?php if (isLoggedIn() && $p['stok'] > 0): ?>
                                <a href="keranjang.php?action=tambah&id=<?= $p['id'] ?>" class="btn btn-primary btn-sm">
                                    <i class="bi bi-cart-plus"></i> Beli
                                </a>
                                <?php elseif (!isLoggedIn()): ?>
                                <a href="login.php" class="btn btn-secondary btn-sm">Login dulu</a>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <?php endwhile; ?>
            <?php if ($produk_list->num_rows === 0): ?>
            <div class="col-12 text-center py-5 text-muted">
                <div style="font-size:3rem;">🔍</div>
                <p>Produk tidak ditemukan.</p>
                <a href="produk.php" class="btn btn-primary">Lihat Semua Produk</a>
            </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<?php include 'includes/footer.php'; ?>
