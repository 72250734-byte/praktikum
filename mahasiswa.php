<?php
require_once "koneksi.php";
require_once "helpers.php";

// Proteksi Halaman
check_role(['admin', 'mahasiswa']);

$sql = "SELECT foto, nim, nama, prodi, hobi, created_at FROM mahasiswa";
$result = $koneksi->query($sql);

$isAdmin = ($_SESSION['role'] === 'admin');
$colCount = $isAdmin ? 8 : 7;
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar Mahasiswa - SIUKDW</title>

    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
</head>

<body class="bg-light">

    <!-- NAVBAR -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-primary shadow-sm">
        <div class="container">
            <a class="navbar-brand fw-bold" href="#">
                <i class="bi bi-mortarboard-fill me-2"></i>
                SIUKDW
            </a>

            <div class="d-flex flex-wrap align-items-center gap-2">
                <span class="text-white small">
                    <i class="bi bi-person-circle me-1"></i>
                    <?= htmlspecialchars($_SESSION['nama']) ?>
                </span>

                <span class="badge bg-warning text-dark">
                    <?= htmlspecialchars(strtoupper($_SESSION['role'])) ?>
                </span>

                <a href="logout.php" class="btn btn-outline-light btn-sm">
                    <i class="bi bi-box-arrow-right me-1"></i>
                    Logout
                </a>
            </div>
        </div>
    </nav>

    <!-- KONTEN UTAMA -->
    <main class="container py-5">

        <!-- JUDUL -->
        <div class="mb-4">
            <h3 class="fw-bold text-dark mb-1">
                <i class="bi bi-people-fill text-primary me-2"></i>
                Daftar Mahasiswa
            </h3>
            <p class="text-secondary mb-0">
                Sistem Informasi Pengelolaan Data Mahasiswa
            </p>
        </div>

        <!-- CARD DATA MAHASISWA -->
        <div class="card border-0 shadow-sm">

            <!-- HEADER CARD -->
            <div class="card-header bg-white py-3">
                <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3">
                    <div>
                        <h5 class="fw-bold mb-1">Data Mahasiswa Aktif</h5>
                        <small class="text-secondary">
                            Informasi seluruh mahasiswa yang terdaftar
                        </small>
                    </div>

                    <div class="d-flex flex-wrap gap-2">
                        <!-- Dapat diakses oleh Admin maupun Mahasiswa -->
                        <a href="cetak_laporan.php" target="_blank" class="btn btn-outline-danger btn-sm fw-semibold">
                            <i class="bi bi-file-earmark-pdf me-1"></i>
                            Cetak Laporan PDF
                        </a>

                        <!-- Tombol Tambah hanya tampil untuk Admin -->
                        <?php if ($isAdmin): ?>
                            <a href="tambah.php" class="btn btn-success btn-sm fw-semibold">
                                <i class="bi bi-plus-circle me-1"></i>
                                Tambah Mahasiswa Baru
                            </a>
                        <?php endif; ?>
                    </div>
                </div>
            </div>

            <!-- BODY CARD -->
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover table-bordered align-middle mb-0">
                        <thead class="table-primary">
                            <tr>
                                <th class="text-center" style="width: 80px;">Gambar</th>
                                <th>NIM</th>
                                <th>Nama</th>
                                <th>Program Studi</th>
                                <th>Hobi</th>
                                <th class="text-center">KTM</th>
                                <th>Dibuat</th>
                                <?php if ($isAdmin): ?>
                                    <th class="text-center" style="width: 170px;">Aksi</th>
                                <?php endif; ?>
                            </tr>
                        </thead>

                        <tbody>
                            <?php if ($result && $result->num_rows > 0): ?>
                                <?php while ($row = $result->fetch_assoc()): ?>
                                    <tr>
                                        <!-- GAMBAR -->
                                        <td class="text-center">
                                            <?php if (!empty($row['foto'])): ?>
                                                <img src="uploads/<?= htmlspecialchars($row['foto']) ?>"
                                                    class="rounded object-fit-cover border" width="56" height="56"
                                                    alt="Foto <?= htmlspecialchars($row['nama']) ?>">
                                            <?php else: ?>
                                                <i class="bi bi-person-circle fs-2 text-secondary"></i>
                                            <?php endif; ?>
                                        </td>

                                        <!-- DATA MAHASISWA -->
                                        <td class="fw-semibold">
                                            <?= htmlspecialchars($row['nim']) ?>
                                        </td>

                                        <td>
                                            <?= htmlspecialchars($row['nama']) ?>
                                        </td>

                                        <td>
                                            <span class="badge bg-info text-dark">
                                                <?= htmlspecialchars($row['prodi']) ?>
                                            </span>
                                        </td>

                                        <td>
                                            <?= htmlspecialchars($row['hobi'] ?? '-') ?>
                                        </td>

                                        <td class="text-center">
                                            <a href="cetak_ktm.php?nim=<?= urlencode($row['nim']) ?>" target="_blank"
                                                class="btn btn-outline-info btn-sm text-dark fw-semibold">
                                                <i class="bi bi-card-heading me-1"></i>
                                                PDF KTM
                                            </a>
                                        </td>

                                        <td>
                                            <small class="text-secondary">
                                                <?= htmlspecialchars($row['created_at']) ?>
                                            </small>
                                        </td>

                                        <!-- AKSI KHUSUS ADMIN -->
                                        <?php if ($isAdmin): ?>
                                            <td class="text-center">
                                                <div class="d-inline-flex gap-1">
                                                    <a href="edit.php?nim=<?= urlencode($row['nim']) ?>"
                                                        class="btn btn-warning btn-sm" title="Edit Data">
                                                        <i class="bi bi-pencil-square"></i>
                                                        Edit
                                                    </a>

                                                    <a href="delete.php?nim=<?= urlencode($row['nim']) ?>"
                                                        class="btn btn-danger btn-sm"
                                                        onclick="return confirm('Yakin ingin menghapus data ini?')"
                                                        title="Hapus Data">
                                                        <i class="bi bi-trash"></i>
                                                        Hapus
                                                    </a>
                                                </div>
                                            </td>
                                        <?php endif; ?>
                                    </tr>
                                <?php endwhile; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="<?= $colCount ?>" class="text-center py-5 text-secondary">
                                        <i class="bi bi-inbox fs-1 d-block mb-2"></i>
                                        Belum ada data mahasiswa.
                                    </td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- FOOTER CARD -->
            <div class="card-footer bg-white text-secondary py-3">
                <small>
                    <i class="bi bi-info-circle me-1"></i>
                    Portal Kampus SIUKDW &copy;
                    <?= date('Y') ?>
                </small>
            </div>

        </div>

    </main>

    <!-- BOOTSTRAP 5 JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>