<?php
// admin_dashboard.php
require_once "helpers.php";

// Proteksi Ketat: Khusus Role Admin
check_role(['admin']);
init_session();

?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Admin Executive Dashboard - SIUKDW</title>

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

            <div class="d-flex align-items-center gap-3">

                <span class="text-white">
                    <i class="bi bi-person-circle me-1"></i>
                    <?= htmlspecialchars($_SESSION['nama']) ?>
                </span>

                <span class="badge bg-warning text-dark">
                    ADMIN
                </span>

                <a href="logout.php" class="btn btn-outline-light btn-sm">
                    <i class="bi bi-box-arrow-right me-1"></i>
                    Logout
                </a>

            </div>

        </div>

    </nav>


    <!-- KONTEN UTAMA -->
    <div class="container py-5">

        <!-- HEADER DASHBOARD -->
        <div class="mb-4">

            <h3 class="fw-bold text-dark">
                <i class="bi bi-speedometer2 text-primary me-2"></i>
                Panel Kontrol Administrator
            </h3>

            <p class="text-secondary mb-0">
                Selamat datang kembali, Master Administrator:
                <strong><?= htmlspecialchars($_SESSION['nama']) ?></strong>
            </p>

        </div>


        <!-- KARTU SELAMAT DATANG -->
        <div class="card border-0 shadow-sm mb-4">

            <div class="card-body p-4">

                <div class="row align-items-center">

                    <div class="col-md-8">

                        <h4 class="fw-bold text-primary">
                            Selamat Datang di Dashboard Admin!
                        </h4>

                        <p class="text-secondary mb-0">
                            Kelola data mahasiswa dan akses sistem melalui
                            panel administrator SIUKDW.
                        </p>

                    </div>

                    <div class="col-md-4 text-md-end mt-3 mt-md-0">

                        <i class="bi bi-person-workspace text-primary" style="font-size: 75px;"></i>

                    </div>

                </div>

            </div>

        </div>


        <!-- INFORMASI AKSES -->
        <div class="row g-3 mb-4">

            <div class="col-md-6">

                <div class="card border-0 shadow-sm h-100">

                    <div class="card-body">

                        <div class="d-flex align-items-center">

                            <div class="bg-primary bg-opacity-10 text-primary rounded-3 p-3 me-3">

                                <i class="bi bi-shield-lock-fill fs-3"></i>

                            </div>

                            <div>

                                <h6 class="fw-bold mb-1">
                                    Proteksi Administrator
                                </h6>

                                <span class="badge bg-success">
                                    Aktif
                                </span>

                            </div>

                        </div>

                        <p class="text-secondary small mt-3 mb-0">
                            Halaman ini hanya dapat diakses oleh pengguna
                            dengan role Administrator.
                        </p>

                    </div>

                </div>

            </div>


            <div class="col-md-6">

                <div class="card border-0 shadow-sm h-100">

                    <div class="card-body">

                        <div class="d-flex align-items-center">

                            <div class="bg-success bg-opacity-10 text-success rounded-3 p-3 me-3">

                                <i class="bi bi-person-check-fill fs-3"></i>

                            </div>

                            <div>

                                <h6 class="fw-bold mb-1">
                                    Status Pengguna
                                </h6>

                                <span class="badge bg-success">
                                    Administrator
                                </span>

                            </div>

                        </div>

                        <p class="text-secondary small mt-3 mb-0">
                            Anda memiliki hak akses administrator
                            untuk mengelola sistem.
                        </p>

                    </div>

                </div>

            </div>

        </div>


        <!-- MENU PENGELOLAAN -->
        <div class="card border-0 shadow-sm">

            <div class="card-header bg-white py-3">

                <h5 class="fw-bold mb-0">
                    <i class="bi bi-grid-fill text-primary me-2"></i>
                    Menu Pengelolaan Sistem
                </h5>

            </div>


            <div class="card-body">

                <div class="row g-3">

                    <!-- KELOLA MAHASISWA -->
                    <div class="col-md-6">

                        <div class="border rounded p-3 h-100">

                            <div class="d-flex align-items-center mb-3">

                                <i class="bi bi-people-fill fs-2 text-primary me-3"></i>

                                <div>

                                    <h6 class="fw-bold mb-1">
                                        Kelola Data Mahasiswa
                                    </h6>

                                    <small class="text-secondary">
                                        Pengelolaan informasi mahasiswa
                                    </small>

                                </div>

                            </div>

                            <a href="mahasiswa.php" class="btn btn-primary w-100">
                                <i class="bi bi-arrow-right-circle me-1"></i>
                                Kelola Mahasiswa
                            </a>

                        </div>

                    </div>


                    <!-- DAFTAR MAHASISWA -->
                    <div class="col-md-6">

                        <div class="border rounded p-3 h-100">

                            <div class="d-flex align-items-center mb-3">

                                <i class="bi bi-card-list fs-2 text-success me-3"></i>

                                <div>

                                    <h6 class="fw-bold mb-1">
                                        Daftar Mahasiswa
                                    </h6>

                                    <small class="text-secondary">
                                        Melihat daftar mahasiswa terdaftar
                                    </small>

                                </div>

                            </div>

                            <a href="home.php" class="btn btn-success w-100">
                                <i class="bi bi-arrow-right-circle me-1"></i>
                                Masuk Daftar
                            </a>

                        </div>

                    </div>

                </div>

            </div>

        </div>


        <!-- INFORMASI KEAMANAN -->
        <div class="alert alert-info border-0 shadow-sm mt-4">

            <div class="d-flex">

                <i class="bi bi-info-circle-fill fs-4 me-3"></i>

                <div>

                    <h6 class="fw-bold">
                        Informasi Keamanan Sistem
                    </h6>

                    <p class="mb-0 small">
                        Halaman ini dilindungi oleh fungsi
                        <code>check_role(['admin'])</code>.
                        Pengguna dengan role Mahasiswa yang mencoba
                        mengakses URL halaman ini secara langsung
                        akan diblokir otomatis oleh middleware sistem.
                    </p>

                </div>

            </div>

        </div>


        <!-- FOOTER -->
        <div class="text-center text-secondary mt-4">

            <small>
                SIUKDW &copy; <?= date('Y') ?> |
                Admin Dashboard
            </small>

        </div>

    </div>


    <!-- BOOTSTRAP 5 JAVASCRIPT -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

</body>

</html>