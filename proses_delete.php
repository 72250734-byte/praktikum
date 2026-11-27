<?php
// proses_delete.php - menampilkan hasil proses hapus data
require_once "koneksi.php";
require_once "helpers.php";

check_role(['admin']);

// Hanya status yang dikenali yang boleh dipakai
$statusValid = ['success', 'failed', 'invalid'];
$status = $_GET['status'] ?? 'invalid';
if (!in_array($status, $statusValid, true)) {
    $status = 'invalid';
}

$nim = htmlspecialchars($_GET['nim'] ?? '');

// Teks dan warna alert untuk setiap status
$pesan = [
    'success' => ['success', 'Berhasil!', "Data mahasiswa dengan NIM <strong>$nim</strong> berhasil dihapus."],
    'failed' => ['danger', 'Gagal!', "Data mahasiswa dengan NIM <strong>$nim</strong> gagal dihapus. Silakan coba lagi."],
    'invalid' => ['danger', 'Permintaan tidak valid!', "Parameter NIM tidak ditemukan."],
];

[$warna, $judul, $isi] = $pesan[$status];


?>
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hasil Hapus Data</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body class="bg-light">
    <div class="container py-5" style="max-width: 600px;">
        <div class="card shadow-sm">
            <div class="card-body text-center">
                <h1 class="h4 mb-3">Hasil Hapus Data</h1>

                <div class="alert alert-<?= $warna ?>" role="alert">
                    <h2 class="h5 alert-heading">
                        <?= $judul ?>
                    </h2>
                    <p class="mb-0">
                        <?= $isi ?>
                    </p>
                </div>

                <a href="mahasiswa.php" class="btn btn-primary">&larr; Kembali ke Data Mahasiswa</a>
            </div>
        </div>
    </div>
</body>

</html>