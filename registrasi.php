<?php
// registrasi.php
declare(strict_types=1);
require_once "helpers.php";
init_session();
?>
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Registrasi Pengguna Baru</title>
</head>

<body>
    <h2>Form Pendaftaran Pengguna Baru</h2>
    <p>Silakan isi formulir di bawah ini untuk mendaftar akun</p>

    <?php if (isset($_GET['status']) && $_GET['status'] === 'empty'): ?>
        <p style="color:red;"><strong>Peringatan:</strong> Semua kolom wajib diisi!</p>
        <hr>
    <?php endif; ?>

    <?php if (isset($_GET['status']) && $_GET['status'] === 'exists'): ?>
        <p style="color:red;"><strong>Gagal:</strong> Username sudah digunakan, silakan pilih username lain!</p>
        <hr>
    <?php endif; ?>

    <?php if (isset($_GET['status']) && $_GET['status'] === 'success'): ?>
        <p style="color:green;"><strong>Berhasil:</strong> Akun Anda berhasil dibuat! <a href="login.php">Klik di sini untuk
                Login</a></p>
        <hr>
    <?php endif; 
    
    ?>

    <form action="proses_registrasi.php" method="POST">
        <p>
            <label for="username">Username: </label><br>
            <input type="text" id="username" name="username" placeholder="Masukkan username" required
                autocomplete="off">
        </p>
        <p>
            <label for="nama_lengkap">Nama Lengkap: </label><br>
            <input type="text" id="nama_lengkap" name="nama_lengkap" placeholder="Masukkan nama lengkap" required>
        </p>
        <p>
            <label for="password">Password: </label><br>
            <input type="password" id="password" name="password" placeholder="Masukkan password" required>
        </p>
        <p>
            <label for="role">Peran (Role): </label><br>
            <select id="role" name="role" required>
                <option value="mahasiswa" selected>Mahasiswa</option>
                <option value="admin">Admin</option>
            </select>
        </p>
        <p>
            <button type="submit" name="btn_registrasi">Daftar Akun</button>
        </p>
    </form>
    <p><a href="login.php">Sudah punya akun? Login di sini</a></p>
</body>

</html>