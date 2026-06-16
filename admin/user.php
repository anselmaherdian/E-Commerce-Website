<?php
require_once '../includes/config.php';
requireLogin();
requireAdmin();

$page_title = 'Kelola User';

// Hapus user
if (isset($_GET['hapus'])) {

    $id = (int)$_GET['hapus'];

    $conn->query("
        DELETE FROM user
        WHERE id = $id
        AND role = 'user'
    ");

    header("Location: user.php");
    exit;
}

include '../includes/header.php';

$user = $conn->query("
    SELECT *
    FROM user
    ORDER BY created_at DESC
");
?>

<div class="container py-4">

    <h3 class="mb-4">👥 Kelola User</h3>

    <div class="card">

        <div class="card-header">
            Daftar User
        </div>

        <div class="table-responsive">

            <table class="table table-hover align-middle mb-0">

                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Nama</th>
                        <th>Email</th>
                        <th>Role</th>
                        <th>Bergabung</th>
                        <th>Aksi</th>
                    </tr>
                </thead>

                <tbody>

                <?php while ($u = $user->fetch_assoc()): ?>

                    <tr>

                        <td><?= $u['id'] ?></td>

                        <td><?= clean($u['nama']) ?></td>

                        <td><?= clean($u['email']) ?></td>

                        <td>
                            <span class="badge bg-<?= $u['role'] == 'admin' ? 'danger' : 'primary' ?>">
                                <?= ucfirst($u['role']) ?>
                            </span>
                        </td>

                        <td>
                            <?= date('d M Y', strtotime($u['created_at'])) ?>
                        </td>

                        <td>

                            <?php if ($u['role'] != 'admin'): ?>

                                <a href="?hapus=<?= $u['id'] ?>"
                                   class="btn btn-sm btn-danger"
                                   onclick="return confirm('Hapus user ini?')">
                                   Hapus
                                </a>

                            <?php else: ?>

                                <span class="text-muted">
                                    Super Admin
                                </span>

                            <?php endif; ?>

                        </td>

                    </tr>

                <?php endwhile; ?>

                </tbody>

            </table>

        </div>

    </div>

</div>

<?php include '../includes/footer.php'; ?>