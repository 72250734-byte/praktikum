<?php
declare(strict_types=1);
require_once "koneksi.php";
require_once "helpers.php";

check_role(['admin']);

if ($_SERVER["REQUEST_METHOD"] !== "POST" || !isset($_POST['btn_simpan'])) {
    header("Location: index.php");
    exit();
}

$nim = trim($_POST['nim'] ?? '');
$nama = sanitize_input($_POST['nama'] ?? '');
$prodi = sanitize_input($_POST['prodi'] ?? '');
$hobiArr = $_POST['hobi'] ?? [];
$fotoLama = !empty($_POST['foto_lama']) ? $_POST['foto_lama'] : null;

if (empty($nim) || empty($nama) || empty($prodi)) {
    die("Error: NIM, Nama, dan Program Studi wajib diisi!");
}

$hobiString = !empty($hobiArr) ? process_hobi($hobiArr) : null;
$fotoFinal = $fotoLama;
// Cek apakah admin mengunggah foto baru
if (isset($_FILES['foto']) && $_FILES['foto']['error'] !== UPLOAD_ERR_NO_FILE) {
    $hasilUpload = upload_foto_mahasiswa($_FILES['foto'], $nim);
    if (!$hasilUpload['status']) {
        die("Gagal Upload Foto Baru: " . htmlspecialchars($hasilUpload['error']));
    }
    // Gunakan nama foto baru dan hapus foto lama dari folder uploads/
    $fotoFinal = $hasilUpload['filename'];
    hapus_foto_lama($fotoLama);
}

$sql = "UPDATE mahasiswa SET nama = ?, prodi = ?, hobi = ?, foto = ? WHERE nim = ?";
$stmt = $koneksi->prepare($sql);
$stmt->bind_param("sssss", $nama, $prodi, $hobiString, $fotoFinal, $nim);

if ($stmt->execute()) {
    $stmt->close();
    $koneksi->close();
    header("Location: mahasiswa.php?msg=updated");
    exit();
} else {
    $stmt->close();
    $koneksi->close();
    die("Gagal memperbarui data.");
}
?>