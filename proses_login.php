<?php
// proses_login.php
declare(strict_types=1);
require_once "koneksi.php";
require_once "helpers.php";

init_session();

if ($_SERVER["REQUEST_METHOD"] !== "POST" || !isset($_POST['btn_login'])) {
    header("Location: login.php");
    exit();
}

$usernameInput = sanitize_input($_POST['username'] ?? '');
$passwordInput = $_POST['password'] ?? '';

if (empty($usernameInput) || empty($passwordInput)) {
    header("Location: login.php?status=failed");
    exit();
}

// 1. Cari Pengguna Berdasarkan Username
$sql = "SELECT id_pengguna, username, nama_lengkap, password_hash, role FROM pengguna WHERE username = ?";
$stmt = $koneksi->prepare($sql);
$stmt->bind_param("s", $usernameInput);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 1) {
    $user = $result->fetch_assoc();
    // 2. Verifikasi Password Hash
    if (password_verify($passwordInput, $user['password_hash'])) {
        // 3. Set Up Session & Regenerate Sesi ID
        regenerate_user_session($user);
        $stmt->close();
        $koneksi->close();

        // 4. Redirect Berdasarkan Role (RBAC Routing)
        if ($user['role'] === 'admin') {
            header("Location: admin_dashboard.php");
        } else {
            header("Location: mahasiswa.php");
        }
        exit();
    }
}

$stmt->close();
$koneksi->close();
header("Location: login.php?status=failed");
exit();
?>