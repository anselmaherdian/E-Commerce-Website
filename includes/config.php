<?php
// ============================================
// KONFIGURASI DATABASE
// File: includes/config.php
// ============================================

define('DB_HOST', 'localhost');
define('DB_USER', 'root');        // Sesuaikan dengan user MySQL XAMPP kamu
define('DB_PASS', '');            // Kosong = default XAMPP, isi jika ada password
define('DB_NAME', 'ecommerce');
define('SITE_NAME', 'Muslimah Shop');
define('SITE_URL', 'http://localhost/ecommerce');
define('UPLOAD_DIR', __DIR__ . '/../uploads/');
define('UPLOAD_URL', SITE_URL . '/uploads/');

// Koneksi ke database
$conn = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);

// Cek koneksi
if ($conn->connect_error) {
    die('<div style="padding:20px;background:#fff3f3;color:#c0392b;font-family:sans-serif;">
        <h2>⚠️ Koneksi Database Gagal</h2>
        <p>' . $conn->connect_error . '</p>
        <p>Pastikan XAMPP berjalan dan database <b>ecommerce</b> sudah dibuat.</p>
    </div>');
}

$conn->set_charset("utf8mb4");

// ─── FUNGSI HELPER ─────────────────────────

// Bersihkan input dari serangan XSS
function clean($data) {
    return htmlspecialchars(strip_tags(trim($data)));
}

// Format harga ke Rupiah
function rupiah($angka) {
    return 'Rp ' . number_format($angka, 0, ',', '.');
}

// Redirect ke halaman lain
function redirect($url) {
    header("Location: $url");
    exit();
}

// Tampilkan pesan sukses/error (flash message)
function setFlash($tipe, $pesan) {
    $_SESSION['flash'] = ['tipe' => $tipe, 'pesan' => $pesan];
}

function getFlash() {
    if (isset($_SESSION['flash'])) {
        $flash = $_SESSION['flash'];
        unset($_SESSION['flash']);
        $cls = $flash['tipe'] === 'sukses' ? 'alert-success' : 'alert-danger';
        echo "<div class='alert $cls alert-dismissible fade show' role='alert'>
            {$flash['pesan']}
            <button type='button' class='btn-close' data-bs-dismiss='alert'></button>
        </div>";
    }
}

// Cek apakah user sudah login
function isLoggedIn() {
    return isset($_SESSION['user_id']);
}

// Cek apakah user adalah admin
function isAdmin() {
    return isset($_SESSION['role']) && $_SESSION['role'] === 'admin';
}

// Wajib login, redirect jika belum
function requireLogin() {
    if (!isLoggedIn()) {
        redirect(SITE_URL . '/login.php');
    }
}

// Wajib admin, redirect jika bukan
function requireAdmin() {
    if (!isAdmin()) {
        redirect(SITE_URL . '/index.php');
    }
}

// Hitung jumlah item di keranjang
function countKeranjang($conn) {
    if (!isLoggedIn()) return 0;
    $uid = $_SESSION['user_id'];
    $q = $conn->query("SELECT SUM(jumlah) as total FROM keranjang WHERE user_id = $uid");
    $row = $q->fetch_assoc();
    return $row['total'] ?? 0;
}

session_start();
?>
