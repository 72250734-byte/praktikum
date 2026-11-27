<?php

// delete.php 
declare(strict_types=1);
require_once "koneksi.php";
require_once "helpers.php";

check_role(['admin']);

if (!isset($_GET['nim']) || empty($_GET['nim'])) {
    die("Error: Parameter NIM tidak ditemukan!");
}

$nimTarget = $_GET['nim'];

// 1. Ambil nama file foto sebelum record dihapus dari database 
$stmtSelect = $koneksi->prepare("SELECT foto FROM mahasiswa WHERE nim = ?");
$stmtSelect->bind_param("s", $nimTarget);
$stmtSelect->execute();
$res = $stmtSelect->get_result();
$status = "failed";
if ($res->num_rows === 0) {
    $stmtSelect->close();
    $koneksi->close();
    header("Location: mahasiswa.php");
    exit();
}

$row = $res->fetch_assoc();
$fotoTarget = $row['foto'];
$stmtSelect->close();

// 2. Hapus record dari database 
$stmtDelete = $koneksi->prepare("DELETE FROM mahasiswa WHERE nim = ?");
$stmtDelete->bind_param("s", $nimTarget);

if ($stmtDelete->execute() && $stmtDelete->affected_rows > 0) {
    // 3. Hapus berkas fisik foto dari server 
    hapus_foto_lama($fotoTarget);
    $status = "success";
}


$koneksi->close();
$stmtDelete->close();


// 4. Arahkan ke halaman keterangan hasil
header("Location: proses_delete.php?status=" . $status . "&nim=" . urlencode($nimTarget));
exit();
?>




// 4. Arahkan ke halaman keterangan hasil
// header("Location: proses_delete.php?status=" . $status . "&nim=" . urlencode($nimTarget));
// exit();
?>