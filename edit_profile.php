<?php
require_once 'includes/config.php';
requireLogin();

$user_id = $_SESSION['user_id'];

// ambil data user
$stmt = $conn->prepare("SELECT * FROM user WHERE id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$user = $stmt->get_result()->fetch_assoc();

// kalau form disubmit
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $nama  = $_POST['nama'];
    $email = $_POST['email'];

    $update = $conn->prepare("UPDATE user SET nama = ?, email = ? WHERE id = ?");
    $update->bind_param("ssi", $nama, $email, $user_id);
    $update->execute();

    // update session kalau dipakai
    $_SESSION['nama'] = $nama;

    header("Location: profile.php?msg=updated");
    exit;
}

$page_title = 'Edit Profile';
include 'includes/header.php';
?>

<div class="container py-5">
    <div class="card shadow-sm">
        <div class="card-header" style="background:#0f3460;color:white;">
            ✏️ Edit Profile
        </div>

        <div class="card-body">

            <?php if (isset($_GET['msg'])): ?>
                <div class="alert alert-success">
                    Profile berhasil diupdate ✅
                </div>
            <?php endif; ?>

            <form method="POST">

                <div class="mb-3">
                    <label>Nama</label>
                    <input type="text" name="nama" class="form-control"
                        value="<?= clean($user['nama']) ?>" required>
                </div>

                <div class="mb-3">
                    <label>Email</label>
                    <input type="email" name="email" class="form-control"
                        value="<?= clean($user['email']) ?>" required>
                </div>

                <button class="btn btn-primary">
                    💾 Simpan Perubahan
                </button>

                <a href="profile.php" class="btn btn-secondary">
                    Batal
                </a>

            </form>

        </div>
    </div>
</div>

<?php include 'includes/footer.php'; ?>