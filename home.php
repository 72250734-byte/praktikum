<?php
require_once "koneksi.php";
require_once "helpers.php";

// Proteksi Halaman: Boleh diakses Admin maupun Mahasiswa
check_role(['admin', 'mahasiswa']);

$sql = "SELECT foto, nim, nama, prodi, hobi, created_at FROM mahasiswa";
$result = $koneksi->query($sql);
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar Mahasiswa - Portal Kampus</title>
    <!-- Bootstrap 5 CSS CDN -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body class="bg-light">

    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-primary shadow-sm mb-4">
        <div class="container">
            <a class="navbar-brand fw-bold" href="#">SIUKDW</a>
            <div class="d-flex align-items-center text-white">
                <span class="me-3">
                    Halo, <strong><?= htmlspecialchars($_SESSION['nama']) ?></strong>
                    <span class="badge bg-light text-primary ms-1"><?= strtoupper($_SESSION['role']) ?></span>
                </span>
                <a href="logout.php" class="btn btn-outline-light btn-sm">Logout</a>
            </div>
        </div>
    </nav>

    <!-- Konten Utama -->
    <div class="container">
        <div class="card shadow-sm border-0">
            <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
                <h5 class="mb-0 fw-bold text-secondary">Daftar Mahasiswa Aktif</h5>

                <!-- HANYA ADMIN YANG DAPAT MELIHAT TOMBOL TAMBAH DATA -->
                <?php if ($_SESSION['role'] === 'admin'): ?>
                    <a href="admin_dashboard.php" class="btn btn-primary btn-sm">
                        < Kembali ke admin_dashboard
                    </a>
                <?php endif; ?>
            </div>

            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover table-bordered align-middle mb-0">
                        <thead class="table-light text-center">
                            <tr>
                                <th>Foto</th>
                                <th>NIM</th>
                                <th>Nama</th>
                                <th>Program Studi</th>
                                <th>Hobi</th>
                                <th>Dibuat</th>

                                <?php if ($_SESSION['role'] === 'admin'): ?>
                                    <th>Aksi</th>
                                <?php endif; ?>
                            </tr>
                        </thead>

                        <tbody>
                            <?php if ($result->num_rows > 0): ?>
                                <?php while ($row = $result->fetch_assoc()): ?>
                                    <tr>
                                        <td class="text-center">
                                            <?php if (!empty($row["foto"])): ?>
                                                <!-- Sesuaikan path folder gambar jika perlu -->
                                                <img src="uploads/<?= htmlspecialchars($row["foto"]) ?>"
                                                    alt="Foto <?= htmlspecialchars($row["nama"]) ?>"
                                                    class="rounded border object-fit-cover" width="60" height="60">
                                            <?php else: ?>
                                                <span class="badge bg-secondary">Tidak ada foto</span>
                                            <?php endif; ?>
                                        </td>
                                        <td class="text-center fw-semibold"><?= htmlspecialchars($row["nim"]) ?></td>
                                        <td><?= htmlspecialchars($row["nama"]) ?></td>
                                        <td>
                                            <span class="badge bg-info text-dark bg-opacity-25 border border-info">
                                                <?= htmlspecialchars($row["prodi"]) ?>
                                            </span>
                                        </td>
                                        <td><?= htmlspecialchars($row["hobi"] ?? "-") ?></td>
                                        <td class="text-muted small text-center"><?= htmlspecialchars($row["created_at"]) ?>
                                        </td>

                                        <?php if ($_SESSION['role'] === 'admin'): ?>
                                            <td class="text-center">
                                                <a href="edit.php?nim=<?= urlencode($row['nim']) ?>"
                                                    class="btn btn-warning btn-sm">Edit</a>
                                                <a href="hapus.php?nim=<?= urlencode($row['nim']) ?>" class="btn btn-danger btn-sm"
                                                    onclick="return confirm('Yakin ingin menghapus data ini?')">Hapus</a>
                                            </td>
                                        <?php endif; ?>
                                    </tr>
                                <?php endwhile; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="<?= ($_SESSION['role'] === 'admin') ? '7' : '6' ?>"
                                        class="text-center text-muted py-4">
                                        Belum ada data mahasiswa.
                                    </td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap 5 JS Bundle CDN -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>