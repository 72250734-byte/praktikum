<?php
// daftar.php
//declare(strict_types=1);
require_once "helpers.php";

// Proteksi Ketat: Hanya Admin yang boleh membuka halaman tambah data
check_role(['admin']);
?>
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tambah Mahasiswa Baru - SIUKDW</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body class="bg-light">
    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-12 col-md-8 col-lg-6">
                <div class="card border-0 shadow-sm rounded-4">
                    <div class="card-header bg-primary text-white py-3 rounded-top4">
                        <h4 class="mb-0 fw-bold">Form Tambah Data Mahasiswa</h4>
                    </div>
                    <div class="card-body p-4">
                        <!-- WAJIB enctype="multipart/form-data" untuk upload file -->
                        <form action="proses_daftar.php" method="POST" enctype="multipart/form-data">
                            <div class="mb-3">
                                <label for="nim" class="form-label fw-semibold">NIM (8 Digit Angka)</label>
                                <input type="text" class="form-control" id="nim" name="nim" pattern="[0-9]{8}"
                                    maxlength="8" required>
                            </div>

                            <div class="mb-3">
                                <label for="nama" class="form-label fw-semibold">Nama Lengkap</label>
                                <input type="text" class="form-control" id="nama" name="nama" maxlength="100" required>
                            </div>

                            <div class="mb-3">
                                <label for="prodi" class="form-label fw-semibold">Program Studi</label>
                                <select class="form-select" id="prodi" name="prodi" required>
                                    <option value="">-- Pilih Program Studi --</option>
                                    <option value="Sistem Informasi">Sistem Informasi</option>
                                    <option value="Informatika">Informatika</option>
                                </select>
                            </div>

                            <div class="mb-3">
                                <label class="form-label fw-semibold d-block">Hobi (Boleh lebih dari satu)</label>
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="hobi[]" value="Coding PHP"
                                        id="hobi1">
                                    <label class="form-check-label" for="hobi1">Coding PHP</label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="hobi[]" value="Membaca Buku"
                                        id="hobi2">
                                    <label class="form-check-label" for="hobi2">Membaca Buku</label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="hobi[]"
                                        value="Ngobrol Dengan AI" id="hobi3">
                                    <label class="form-check-label" for="hobi3">Ngobrol Dengan AI</label>
                                </div>
                            </div>

                            <div class="mb-3">
                                <label for="foto" class="form-label fw-semibold">Foto Profil (Opsional, JPG/PNG/WEBP,
                                    Maks 2MB)</label>
                                <input type="file" class="form-control" id="foto" name="foto"
                                    accept="image/jpeg,image/png,image/webp">
                            </div>

                            <div class="mb-4">
                                <label for="password" class="form-label fw-semibold">Password Akun</label>
                                <input type="password" class="form-control" id="password" name="password" required>
                            </div>

                            <div class="d-flex justify-content-between">
                                <a href="index.php" class="btn btn-secondary">Kembali</a>
                                <button type="submit" class="btn btn-primary">Simpan Data</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>

</html>