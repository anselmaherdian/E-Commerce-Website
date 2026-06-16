<?php
require_once '../includes/config.php';
requireLogin();
requireAdmin();

$page_title = 'Kelola Kategori';

// TAMBAH KATEGORI
if (isset($_POST['tambah'])) {

    $nama = trim($_POST['nama_kategori']);
    $deskripsi = trim($_POST['deskripsi']);

    $stmt = $conn->prepare("
        INSERT INTO kategori (nama_kategori, deskripsi)
        VALUES (?, ?)
    ");

    $stmt->bind_param("ss", $nama, $deskripsi);
    $stmt->execute();

    header("Location: kategori.php");
    exit;
}

// HAPUS KATEGORI
if (isset($_GET['hapus'])) {

    $id = (int)$_GET['hapus'];

    $conn->query("
        DELETE FROM kategori
        WHERE id = $id
    ");

    header("Location: kategori.php");
    exit;
}

// EDIT KATEGORI
if (isset($_POST['update'])) {

    $id = (int)$_POST['id'];
    $nama = trim($_POST['nama_kategori']);
    $deskripsi = trim($_POST['deskripsi']);

    $stmt = $conn->prepare("
        UPDATE kategori
        SET nama_kategori = ?, deskripsi = ?
        WHERE id = ?
    ");

    $stmt->bind_param("ssi", $nama, $deskripsi, $id);
    $stmt->execute();

    header("Location: kategori.php");
    exit;
}

include '../includes/header.php';

$kategori = $conn->query("
    SELECT *
    FROM kategori
    ORDER BY id DESC
");
?>

<div class="container py-4">

    <h3 class="mb-4">🏷️ Kelola Kategori</h3>

    <!-- FORM TAMBAH -->
    <div class="card mb-4">
        <div class="card-header">
            Tambah Kategori
        </div>

        <div class="card-body">

            <form method="POST">

                <div class="mb-3">
                    <label>Nama Kategori</label>
                    <input type="text"
                           name="nama_kategori"
                           class="form-control"
                           required>
                </div>

                <div class="mb-3">
                    <label>Deskripsi</label>
                    <textarea name="deskripsi"
                              class="form-control"
                              rows="3"></textarea>
                </div>

                <button type="submit"
                        name="tambah"
                        class="btn btn-primary">
                    ➕ Tambah
                </button>

            </form>

        </div>
    </div>

    <!-- TABEL -->
    <div class="card">

        <div class="card-header">
            Daftar Kategori
        </div>

        <div class="table-responsive">

            <table class="table table-bordered table-hover mb-0">

                <thead>
                    <tr>
                        <th width="80">ID</th>
                        <th>Nama</th>
                        <th>Deskripsi</th>
                        <th width="180">Aksi</th>
                    </tr>
                </thead>

                <tbody>

                <?php while ($k = $kategori->fetch_assoc()): ?>

                    <tr>

                        <td><?= $k['id'] ?></td>

                        <td><?= clean($k['nama_kategori']) ?></td>

                        <td><?= clean($k['deskripsi']) ?></td>

                        <td>

                            <button
                                class="btn btn-warning btn-sm"
                                data-bs-toggle="modal"
                                data-bs-target="#edit<?= $k['id'] ?>">
                                Edit
                            </button>

                            <a href="?hapus=<?= $k['id'] ?>"
                               class="btn btn-danger btn-sm"
                               onclick="return confirm('Hapus kategori ini?')">
                               Hapus
                            </a>

                        </td>

                    </tr>

                    <!-- MODAL EDIT -->
                    <div class="modal fade"
                         id="edit<?= $k['id'] ?>"
                         tabindex="-1">

                        <div class="modal-dialog">

                            <div class="modal-content">

                                <form method="POST">

                                    <div class="modal-header">
                                        <h5>Edit Kategori</h5>
                                        <button class="btn-close"
                                                data-bs-dismiss="modal"></button>
                                    </div>

                                    <div class="modal-body">

                                        <input type="hidden"
                                               name="id"
                                               value="<?= $k['id'] ?>">

                                        <div class="mb-3">
                                            <label>Nama</label>
                                            <input type="text"
                                                   name="nama_kategori"
                                                   class="form-control"
                                                   value="<?= clean($k['nama_kategori']) ?>"
                                                   required>
                                        </div>

                                        <div class="mb-3">
                                            <label>Deskripsi</label>
                                            <textarea name="deskripsi"
                                                      class="form-control"><?= clean($k['deskripsi']) ?></textarea>
                                        </div>

                                    </div>

                                    <div class="modal-footer">

                                        <button type="submit"
                                                name="update"
                                                class="btn btn-primary">
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