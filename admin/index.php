<?php
// ============================================
// ADMIN DASHBOARD
// File: admin/index.php
// ============================================
require_once '../includes/config.php';
requireLogin();
requireAdmin();
$page_title = 'Admin Dashboard';
include '../includes/header.php';

$total_produk   = $conn->query("SELECT COUNT(*) c FROM produk")->fetch_assoc()['c'];
$total_user     = $conn->query("SELECT COUNT(*) c FROM user WHERE role='user'")->fetch_assoc()['c'];
$total_pesanan  = $conn->query("SELECT COUNT(*) c FROM pesanan")->fetch_assoc()['c'];
$total_kategori = $conn->query("SELECT COUNT(*) c FROM kategori")->fetch_assoc()['c'];
$total_omzet    = $conn->query("SELECT SUM(total_harga) s FROM pesanan WHERE status='selesai'")->fetch_assoc()['s'] ?? 0;

$pesanan_terbaru = $conn->query("
    SELECT ps.*, u.nama FROM pesanan ps
    JOIN user u ON ps.user_id = u.id
    ORDER BY ps.created_at DESC LIMIT 5
");

$stok_menipis = $conn->query("SELECT * FROM produk WHERE stok < 5 ORDER BY stok ASC LIMIT 5");
?>

<div class="row">
    <!-- SIDEBAR -->
    <div class="col-md-3 mb-4">
        <div class="card">
            <div class="card-header" style="background:#0f3460;color:white;"><i class="bi bi-gear-fill me-2"></i><strong>Admin Panel</strong></div>
            <div class="card-body p-3">
                <a href="index.php" class="sidebar-link active"><i class="bi bi-speedometer2"></i> Dashboard</a>
                <a href="produk.php" class="sidebar-link"><i class="bi bi-box-seam"></i> Kelola Produk</a>
                <a href="kategori.php" class="sidebar-link"><i class="bi bi-tags"></i> Kelola Kategori</a>
                <a href="pesanan.php" class="sidebar-link"><i class="bi bi-receipt"></i> Kelola Pesanan</a>
                <a href="user.php" class="sidebar-link"><i class="bi bi-people"></i> Kelola User</a>
                <hr>
                <a href="<?= SITE_URL ?>/index.php" class="sidebar-link"><i class="bi bi-house"></i> Ke Toko</a>
                <a href="<?= SITE_URL ?>/logout.php" class="sidebar-link text-danger"><i class="bi bi-box-arrow-right"></i> Logout</a>
            </div>
        </div>
    </div>

    <div class="col-md-9">
        <h4 class="fw-700 mb-4">📊 Dashboard Admin</h4>

        <!-- STATISTIK CARDS -->
        <div class="row g-3 mb-4">
            <div class="col-6 col-md-3">
                <div class="card text-center p-3" style="border-left:4px solid #0f3460;">
                    <div style="font-size:2rem;">📦</div>
                    <div class="fw-700 fs-3 text-primary"><?= $total_produk ?></div>
                    <div class="text-muted small">Total Produk</div>
                </div>
            </div>
            <div class="col-6 col-md-3">
                <div class="card text-center p-3" style="border-left:4px solid #1a7a63;">
                    <div style="font-size:2rem;">👥</div>
                    <div class="fw-700 fs-3" style="color:#1a7a63;"><?= $total_user ?></div>
                    <div class="text-muted small">Pelanggan</div>
                </div>
            </div>
            <div class="col-6 col-md-3">
                <div class="card text-center p-3" style="border-left:4px solid #e94560;">
                    <div style="font-size:2rem;">🧾</div>
                    <div class="fw-700 fs-3" style="color:#e94560;"><?= $total_pesanan ?></div>
                    <div class="text-muted small">Total Pesanan</div>
                </div>
            </div>
            <div class="col-6 col-md-3">
                <div class="card text-center p-3" style="border-left:4px solid #f9c74f;">
                    <div style="font-size:2rem;">💰</div>
                    <div class="fw-700" style="font-size:1rem;color:#b8860b;"><?= rupiah($total_omzet) ?></div>
                    <div class="text-muted small">Omzet Selesai</div>
                </div>
            </div>
        </div>

        <!-- TABEL PESANAN TERBARU -->
        <div class="card mb-4">
            <div class="card-header d-flex justify-content-between" style="background:#f8f9fa;">
                <strong><i class="bi bi-clock-history me-2"></i>Pesanan Terbaru</strong>
                <a href="pesanan.php" class="btn btn-sm btn-outline-secondary">Lihat Semua</a>
            </div>
            <div class="table-responsive">
                <table class="table table-hover mb-0 align-middle">
                    <thead><tr><th>#ID</th><th>Pelanggan</th><th>Total</th><th>Status</th><th>Tanggal</th></tr></thead>
                    <tbody>
                    <?php while ($ps = $pesanan_terbaru->fetch_assoc()):
                        $status_color = ['pending'=>'warning','diproses'=>'info','dikirim'=>'primary','selesai'=>'success','dibatalkan'=>'danger'];
                        $c = $status_color[$ps['status']] ?? 'secondary';
                    ?>
                    <tr>
                        <td>#<?= $ps['id'] ?></td>
                        <td><?= clean($ps['nama']) ?></td>
                        <td class="fw-600"><?= rupiah($ps['total_harga']) ?></td>
                        <td><span class="badge bg-<?= $c ?>"><?= ucfirst($ps['status']) ?></span></td>
                        <td class="text-muted small"><?= date('d M Y', strtotime($ps['created_at'])) ?></td>
                    </tr>
                    <?php endwhile; ?>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- STOK MENIPIS -->
        <?php if ($stok_menipis->num_rows > 0): ?>
        <div class="card border-warning">
            <div class="card-header bg-warning bg-opacity-10">
                <strong>⚠️ Stok Menipis (< 5 item)</strong>
            </div>
            <div class="table-responsive">
                <table class="table mb-0">
                    <thead><tr><th>Produk</th><th>Stok</th><th>Aksi</th></tr></thead>
                    <tbody>
                    <?php while ($p = $stok_menipis->fetch_assoc()): ?>
                    <tr>
                        <td><?= clean($p['nama_produk']) ?></td>
                        <td><span class="badge bg-<?= $p['stok'] == 0 ? 'danger' : 'warning text-dark' ?>"><?= $p['stok'] ?></span></td>
                        <td><a href="produk.php?aksi=edit&id=<?= $p['id'] ?>" class="btn btn-sm btn-outline-primary">Update Stok</a></td>
                    </tr>
                    <?php endwhile; ?>
                    </tbody>
                </table>
            </div>
        </div>
        <?php endif; ?>
    </div>
</div>

<?php include '../includes/footer.php'; ?>
