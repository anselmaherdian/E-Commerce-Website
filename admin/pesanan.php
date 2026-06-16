<?php
require_once '../includes/config.php';
requireLogin();
requireAdmin();

$page_title = 'Kelola Pesanan';

// UPDATE STATUS
if (isset($_POST['update_status'])) {

    $id = (int)$_POST['id'];
    $status = $_POST['status'];

    $stmt = $conn->prepare("
        UPDATE pesanan
        SET status = ?
        WHERE id = ?
    ");

    $stmt->bind_param("si", $status, $id);
    $stmt->execute();

    header("Location: pesanan.php");
    exit;
}

include '../includes/header.php';

$pesanan = $conn->query("
    SELECT p.*, u.nama
    FROM pesanan p
    JOIN user u ON p.user_id = u.id
    ORDER BY p.created_at DESC
");
?>

<div class="container py-4">

    <h3 class="mb-4">📦 Kelola Pesanan</h3>

    <div class="card">

        <div class="card-header">
            Daftar Pesanan
        </div>

        <div class="table-responsive">

            <table class="table table-hover align-middle mb-0">

                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Pelanggan</th>
                        <th>Total</th>
                        <th>Status</th>
                        <th>Tanggal</th>
                        <th>Aksi</th>
                    </tr>
                </thead>

                <tbody>

                <?php while ($p = $pesanan->fetch_assoc()): ?>

                    <?php
                    $warna = [
                        'pending' => 'warning',
                        'diproses' => 'info',
                        'dikirim' => 'primary',
                        'selesai' => 'success',
                        'dibatalkan' => 'danger'
                    ];

                    $badge = $warna[$p['status']] ?? 'secondary';
                    ?>

                    <tr>

                        <td>#<?= $p['id'] ?></td>

                        <td><?= clean($p['nama']) ?></td>

                        <td><?= rupiah($p['total_harga']) ?></td>

                        <td>
                            <span class="badge bg-<?= $badge ?>">
                                <?= ucfirst($p['status']) ?>
                            </span>
                        </td>

                        <td>
                            <?= date('d M Y H:i', strtotime($p['created_at'])) ?>
                        </td>

                        <td>

                            <button
                                class="btn btn-sm btn-primary"
                                data-bs-toggle="modal"
                                data-bs-target="#status<?= $p['id'] ?>">
                                Ubah Status
                            </button>

                        </td>

                    </tr>

                    <!-- MODAL STATUS -->

                    <div class="modal fade"
                         id="status<?= $p['id'] ?>"
                         tabindex="-1">

                        <div class="modal-dialog">

                            <div class="modal-content">

                                <form method="POST">

                                    <div class="modal-header">
                                        <h5>Update Status Pesanan</h5>
                                        <button
                                            class="btn-close"
                                            data-bs-dismiss="modal">
                                        </button>
                                    </div>

                                    <div class="modal-body">

                                        <input type="hidden"
                                               name="id"
                                               value="<?= $p['id'] ?>">

                                        <label>Status</label>

                                        <select
                                            name="status"
                                            class="form-select">

                                            <option value="pending">Pending</option>

                                            <option value="diproses">Diproses</option>

                                            <option value="dikirim">Dikirim</option>

                                            <option value="selesai">Selesai</option>

                                            <option value="dibatalkan">Dibatalkan</option>

                                        </select>

                                    </div>

                                    <div class="modal-footer">

                                        <button
                                            type="submit"
                                            name="update_status"
                                            class="btn btn-success">
                                            Simpan
                                        </button>

                                    </div>

                                </form>

                            </div>

                        </div>

                    </div>

                <?php endwhile; ?>

                </tbody>

            </table>

        </div>

    </div>

</div>

<?php include '../includes/footer.php'; ?>