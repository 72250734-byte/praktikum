<?php
// proses_registrasi.php
declare(strict_types=1);
require_once "koneksi.php";
require_once "helpers.php";

if ($_SERVER["REQUEST_METHOD"] !== "POST" || !isset($_POST['btn_registrasi'])) { 
    header("Location: registrasi.php");
    exit();
}

$username = sanitize_input($_POST['username'] ?? '');
$namaLengkap = sanitize_input($_POST['nama_lengkap'] ?? '');
$password = $_POST['password'] ?? '';
$role = $_POST['role'] ?? 'mahasiswa';

if (empty($username) || empty($namaLengkap) || empty($password)) {
    header("Location: registrasi.php?status=empty");
    exit();
}

if (!in_array($role, ['admin', 'mahasiswa'], true)) {
    $role = 'mahasiswa';
}

// Memeriksa duplikasi username (Prepared Statement)
$sqlCheck = "SELECT id_pengguna FROM pengguna WHERE username = ?";
$stmtCheck = $koneksi->prepare($sqlCheck);
$stmtCheck->bind_param("s", $username);
$stmtCheck->execute();
$resultCheck = $stmtCheck->get_result();

if ($resultCheck->num_rows > 0) {
    $stmtCheck->close();
    $koneksi->close();
    header("Location: registrasi.php?status=exists");
    exit();
}
$stmtCheck->close();

// Hashing kata sandi di PHP
$passwordHash = password_hash($password, PASSWORD_DEFAULT);

// Menyimpan pengguna baru
$sqlInsert = "INSERT INTO pengguna (username, nama_lengkap, password_hash, role) VALUES (?, ?, ?, ?)";
$stmtInsert = $koneksi->prepare($sqlInsert);
$stmtInsert->bind_param("ssss", $username, $namaLengkap, $passwordHash, $role);

if ($stmtInsert->execute()) {
    $stmtInsert->close();
    $koneksi->close();
    header("Location: registrasi.php?status=success");
    exit();
} else {
    $stmtInsert->close();
    $koneksi->close();
    header("Location: registrasi.php?status=empty");
    exit();
}
?>