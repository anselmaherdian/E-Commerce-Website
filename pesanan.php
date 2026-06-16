<?php
require_once 'includes/config.php';
requireLogin();

$user_id = $_SESSION['user_id'];

$page_title = 'Pesanan Saya';
include 'includes/header.php';

// ambil semua pesanan user
$pesanan = $conn->query("
    SELECT *
    FROM pesanan
    WHERE user_id = $user_id
    ORDER BY created_at DESC
");
?>

<div class="container py-4">
    <h4 class="fw-700 mb-4">📦 Pesanan Saya</h4>

    <?php if ($pesanan->num_rows == 0): ?>
        <div class="alert alert-info">
            Kamu belum punya pesanan 😢
        </div>
    <?php else: ?>

        <?php while ($p = $pesanan->fetch_assoc()): ?>

            <?php
            // ambil detail produk di setiap pesanan
            $id_pesanan = $p['id'];

            $detail = $conn->query("
                SELECT dp.*, pr.nama_produk
                FROM detail_pesanan dp
                JOIN produk pr ON dp.produk_id = pr.id
                WHERE dp.pesanan_id = $id_pesanan
            ");

            $status_color = [
                'pending' => 'warning',
                'diproses' => 'info',
                'dikirim' => 'primary',
                'selesai' => 'success',
                'dibatalkan' => 'danger'
            ];

            $c = $status_color[$p['status']] ?? 'secondary';
            ?>

            <div class="card mb-3 shadow-sm">
                <div class="card-header d-flex justify-content-between">
                    <div>
                        <strong>Order #<?= $p['id'] ?></strong>
                        <span class="text-muted ms-2">
                            <?= date('d M Y', strtotime($p['created_at'])) ?>
                        </span>
                    </div>

                    <span class="badge bg-<?= $c ?>">
                        <?= ucfirst($p['status']) ?>
                    </span>
                </div>

                <div class="card-body">

                    <p><strong>Total:</strong> <?= rupiah($p['total_harga']) ?></p>
                    <p><strong>Alamat:</strong> <?= clean($p['alamat_kirim']) ?></p>

                    <hr>

                    <h6>Produk:</h6>

                    <ul class="list-group">
                        <?php while ($d = $detail->fetch_assoc()): ?>
                            <li class="list-group-item d-flex justify-content-between">
                                <span><?= clean($d['nama_produk']) ?> (x<?= $d['jumlah'] ?>)</span>
                                <span><?= rupiah($d['harga_satuan']) ?></span>
                            </li>
                        <?php endwhile; ?>
                    </ul>

                </div>
            </div>

        <?php endwhile; ?>

    <?php endif; ?>
</div>

<?php include 'includes/footer.php'; ?>