<?php
// proses_daftar.php 
declare(strict_types=1);
require_once "koneksi.php";
require_once "helpers.php";

check_role(['admin']);

if ($_SERVER["REQUEST_METHOD"] !== "POST" || !isset($_POST['btn_daftar'])) {
    header("Location: daftar.php");
    exit();
}

// 1. Tangkap & sanitasi input teks 
$nimRaw = trim($_POST['nim'] ?? '');
$nama = sanitize_input($_POST['nama'] ?? '');
$prodi = sanitize_input($_POST['prodi'] ?? '');
$hobiArr = $_POST['hobi'] ?? [];
$password = $_POST['password'] ?? '';

if (!validate_nim($nimRaw) || empty($nama) || empty($prodi) || empty($password)) {
    die("Error: Pastikan NIM (8 digit), Nama, Prodi, dan Password terisi dengan 
benar!");
}

$hobiString = !empty($hobiArr) ? process_hobi($hobiArr) : null;
$passHash = password_hash($password, PASSWORD_DEFAULT);

// 2. Proses Upload Foto Profil menggunakan helper 
$hasilUpload = upload_foto_mahasiswa($_FILES['foto'] ?? [], $nimRaw);
if (!$hasilUpload['status']) {
    die("Gagal Upload Foto: " . htmlspecialchars($hasilUpload['error']));
}
$namaFileFoto = $hasilUpload['filename'];

// 3. Simpan ke Database menggunakan Prepared Statement 
$sql = "INSERT INTO mahasiswa (nim, nama, prodi, hobi, foto, password_hash) VALUES 
(?, ?, ?, ?, ?, ?)";
$stmt = $koneksi->prepare($sql);
$stmt->bind_param(
    "ssssss",
    $nimRaw,
    $nama,
    $prodi,
    $hobiString,
    $namaFileFoto,
    $passHash
);

if ($stmt->execute()) {
    $stmt->close();
    $koneksi->close();
    header("Location: index.php?msg=added");
    exit();
} else {
    // Jika gagal simpan ke DB (misal NIM duplikat), hapus foto yang terlanjur  terunggah !

    hapus_foto_lama($namaFileFoto);
    $errorMsg = $stmt->error;
    $stmt->close();
    $koneksi->close();
    die("Gagal menyimpan data ke database: " . htmlspecialchars($errorMsg));
}
?>