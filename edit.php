<?php
// edit.php 
declare(strict_types=1);
require_once "koneksi.php";
require_once "helpers.php";

check_role(['admin']);

if (!isset($_GET['nim']) || empty($_GET['nim'])) {
    die("Error: Parameter NIM tidak ditemukan!");
}

$nimTarget = $_GET['nim'];
$stmt = $koneksi->prepare("SELECT nim, nama, prodi, hobi, foto FROM mahasiswa 
WHERE nim = ?");
$stmt->bind_param("s", $nimTarget);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    die("Error: Data mahasiswa tidak ditemukan!");
}

$data = $result->fetch_assoc();
$arrayHobi = !empty($data['hobi']) ? explode(", ", $data['hobi']) : [];
?>
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Mahasiswa - SIUKDW</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body class="bg-light">
    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-12 col-md-8 col-lg-6">
                <div class="card border-0 shadow-sm rounded-4">
                    <div class="card-header bg-warning text-dark py-3 rounded-top-4">
                        <h4 class="mb-0 fw-bold">Edit Data Mahasiswa</h4>
                    </div>
                    <div class="card-body p-4">
                        <form action="proses_edit.php" method="POST" enctype="multipart/form-data">
                            <!-- Menyimpan nama file foto lama secara tersembunyi -->
                            <input type="hidden" name="foto_lama" value="<?=
                                htmlspecialchars($data['foto'] ?? '') ?>">
                            <div class="mb-3">
                                <label class="form-label fw-semibold">NIM</label>
                                <input type="text" class="form-control bg-light" name="nim"
                                    value="<?= htmlspecialchars($data['nim']) ?>" readonly>
                            </div>
                            <div class="mb-3">
                                <label class="form-label fw-semibold">Nama
                                    Lengkap</label>
                                <input type="text" class="form-control" name="nama"
                                    value="<?= htmlspecialchars($data['nama']) ?>" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label fw-semibold">Program
                                    Studi</label>
                                <select class="form-select" name="prodi" required>
                                    <option value="Sistem Informasi" <?=
                                        ($data['prodi'] === 'Sistem Informasi') ? 'selected' : '' ?>>Sistem
                                        Informasi</option>
                                    <option value="Informatika" <?= ($data['prodi']
                                        === 'Informatika') ? 'selected' : '' ?>>Informatika</option>
                                </select>
                            </div>
                            <div class="mb-3">
                                <label class="form-label fw-semibold d
block">Hobi</label>
                                <?php
                                $opsiHobi = [
                                    'Coding PHP',
                                    'Membaca Buku',
                                    'Ngobrol 
                                    Dengan AI',
                                    'Mancing'
                                ];
                                foreach ($opsiHobi as $idx => $item):
                                    ?>
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="checkbox" id="hobi<?= $idx ?>" name="hobi[]"
                                            value="<?= $item ?>" <?= in_array($item, $arrayHobi) ? 'checked' : '' ?>>
                                        <label class="form-check-label" for="hobi<?=
                                            $idx ?>"><?= $item ?></label>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                            <div class="mb-4">
                                <label class="form-label fw-semibold d-block">Foto
                                    Profil Saat Ini</label>
                                <?php if (
                                    !empty($data['foto']) &&
                                    file_exists('uploads/' . $data['foto'])
                                ): ?>
                                    <img src="uploads/<?=
                                        htmlspecialchars($data['foto']) ?>" alt="Foto Mahasiswa"
                                        class="img-thumbnail mb-2" style="width: 100px; height: 100px; object-fit: cover;">
                                <?php else: ?>
                                    <p class="text-muted small fst-italic mb-2">Belum
                                        ada foto profil.</p>
                                <?php endif; ?>
                                <input class="form-control" type="file" name="foto"
                                    accept="image/jpeg, image/png, image/webp">
                                <div class="form-text">Kosongkan jika tidak ingin
                                    mengganti foto.</div>
                            </div>
                            <div class="d-flex justify-content-between">
                                <a href="index.php" class="btn btn-outline
                                secondary">Batal</a>
                                <button type="submit" name="btn_simpan" class="btn 
                                btn-warning fw-bold px-4">Simpan Perubahan</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>

</html>