<?php
require_once 'includes/config.php';
requireLogin();

$user_id = $_SESSION['user_id'];

$stmt = $conn->prepare("SELECT * FROM user WHERE id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$user = $stmt->get_result()->fetch_assoc();

$page_title = 'Profil Saya';
include 'includes/header.php';
?>

<div class="container py-5">
    <div class="card shadow border-0">
        <div class="card-header text-white" style="background:#0f3460;">
            <h4 class="mb-0">👤 Profil Saya</h4>
        </div>

        <div class="card-body text-center">
            <div style="font-size:80px;">👩🏻</div>

            <h3 class="mt-3"><?= clean($user['nama']) ?></h3>

            <span class="badge bg-primary">
                <?= ucfirst($user['role']) ?>
            </span>

            <hr>

            <div class="row text-start">
                <div class="col-md-6 mb-3">
                    <strong>Nama Lengkap</strong>
                    <p><?= clean($user['nama']) ?></p>
                </div>

                <div class="col-md-6 mb-3">
                    <strong>Email</strong>
                    <p><?= clean($user['email']) ?></p>
                </div>

                <div class="col-md-6 mb-3">
                    <strong>Role</strong>
                    <p><?= ucfirst($user['role']) ?></p>
                </div>

                <div class="col-md-6 mb-3">
                    <strong>Bergabung Sejak</strong>
                    <p><?= date('d F Y', strtotime($user['created_at'])) ?></p>
                </div>
            </div>

            <a href="edit_profile.php" class="btn btn-primary">
                ✏️ Edit Profil
            </a>
        </div>
    </div>
</div>

<?php include 'includes/footer.php'; ?>