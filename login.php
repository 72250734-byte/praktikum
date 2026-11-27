<?php
// login.php
declare(strict_types=1);
require_once "helpers.php";
init_session(); // menginisiasi session 

// Jika sudah login, alihkan otomatis sesuai role
if (isset($_SESSION['is_logged_in']) && $_SESSION['is_logged_in'] === true) {
    if ($_SESSION['role'] === 'admin') {
        header("Location: admin_dashboard.php");
    } else {
        header("Location: mahasiswa.php");
    }
    exit();
}
?>
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Portal Login</title>
</head>

<body>
    <h2>Portal Login</h2>
    <p>Masukkan kredensial Anda untuk melanjutkan</p>

    <?php if (isset($_GET['status']) && $_GET['status'] === 'failed'): ?>
        <p style="color:red;"><strong>Gagal Login:</strong> Username atau password yang Anda masukkan salah!</p>
        <hr>
    <?php endif; ?>

    <?php if (isset($_GET['status']) && $_GET['status'] === 'unauthorized'): ?>
        <p style="color:red;"><strong>Akses Ditolak:</strong> Anda harus login terlebih dahulu!</p>
        <hr>
    <?php endif; ?>

    <form action="proses_login.php" method="POST">
        <p>
            <label for="username">Username: </label><br>
            <input type="text" id="username" name="username" placeholder="Masukkan username" required
                autocomplete="off">
        </p>
        <p>
            <label for="password">Password: </label><br>
            <input type="password" id="password" name="password" placeholder="Masukkan password" required>
        </p>
        <p>
            <button type="submit" name="btn_login">Masuk Sistem</button>
        </p>
    </form>
    <p><a href="registrasi.php">Belum punya akun? Daftar di sini</a></p>
</body>

</html>