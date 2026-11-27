<?php
//tambah.php
require_once "koneksi.php";
require_once "helpers.php";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nim = $_POST['nim'] ?? "";
    $nama = $_POST['nama'];
    $prodi = $_POST['prodi'];
    $hobi = $_POST['hobi'] ?? null;
    $hobi_str = !empty($hobi) ? implode(',', $hobi) : null;
    $password = MD5($nim);

    $cek_nim = $koneksi->prepare('SELECT nim FROM mahasiswa WHERE nim = ?');
    $cek_nim->bind_param('s', $nim); // 's' = tipe string
    $cek_nim->execute();
    $cek_nim->store_result(); // simpan hasil agar num_rows bisa dipakai


    // CEK NIM 
    if (!validate_nim($nim)) {
        die('ERROR : Nim tidak valid! Harus 8 DIGITTTT');

    }

    // SANITASI TEKS
    $nim = sanitize_input($nim);
    $nama = sanitize_input($nama);
    $prodi = sanitize_input($prodi);
    $hobi = sanitize_input($hobi_str);



    if ($cek_nim->num_rows() > 0) {
        $pesan = "Data gagal ditambahkan karena NIM sudah terdaftar di database.";
        $tipe_pesan = "danger"; // Warna merah untuk error
    } else {
        $cek_nim->close();
        // UPLOAD FOTO (opsional), memakai fungsi dari helpers.php
        $upload = upload_foto_mahasiswa($_FILES['foto'] ?? [], $nim);
        if (!$upload['status']) {
            die('ERROR : ' . $upload['error']);
        }
        $foto_nama = $upload['filename']; // null jika foto tidak dipilih
        $sql = $koneksi->prepare("INSERT INTO mahasiswa (foto, nim, nama, prodi, hobi, password_hash) VALUES (?, ?, ?, ?, ?, ?)");

        $sql->bind_param('ssssss', $foto_nama, $nim, $nama, $prodi, $hobi_str, $password);

        if ($sql->execute()) {
            $pesan = "Data sudah berhasil ditambahkan :D";
            $tipe_pesan = "success"; // Warna hijau untuk sukses
        } else {
            $pesan = "Data gagal ditambahkan :( " . $sql->error;
            $tipe_pesan = "danger"; // Warna merah untuk error
        }
        $sql->close();
    }
}

?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tambah Mahasiswa</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>

    <nav class="navbar navbar-dark bg-primary">
        <div class="container">
            <a class="navbar-brand" href="#">SIUKDW</a>
            <a href="mahasiswa.php" class="btn btn-light">Kembali</a>
        </div>
    </nav>

    <div class="container mt-4">

        <div class="card">

            <div class="card-header">
                Form Tambah Mahasiswa
            </div>

            <div class="card-body">

                <?php if (isset($pesan)): ?>
                    <div class="alert alert-<?= $tipe_pesan ?>">
                        <?= htmlspecialchars($pesan) ?>
                    </div>
                <?php endif; ?>

                <form action="" method="POST" enctype="multipart/form-data">

                    <div class="mb-3">
                        <label class="form-label">NIM</label>
                        <input type="number" name="nim" class="form-control" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Nama</label>
                        <input type="text" name="nama" class="form-control" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Program Studi</label>
                        <select name="prodi" class="form-select" required>
                            <option value="">Pilih Prodi</option>
                            <option value="Sistem Informasi">Sistem Informasi</option>
                            <option value="Informatika">Informatika</option>
                            <option value="Manajemen">Manajemen</option>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Hobi</label>

                        <div class="form-check">
                            <input type="checkbox" name="hobi[]" value="Koding" class="form-check-input">
                            <label class="form-check-label">Koding</label>
                        </div>

                        <div class="form-check">
                            <input type="checkbox" name="hobi[]" value="UI/UX" class="form-check-input">
                            <label class="form-check-label">UI/UX</label>
                        </div>

                        <div class="form-check">
                            <input type="checkbox" name="hobi[]" value="Organisasi" class="form-check-input">
                            <label class="form-check-label">Organisasi</label>
                        </div>

                        <div class="form-check">
                            <input type="checkbox" name="hobi[]" value="Mancing" class="form-check-input">
                            <label class="form-check-label">Mancing</label>
                        </div>

                        <div class="form-check">
                            <input type="checkbox" name="hobi[]" value="Nobar" class="form-check-input">
                            <label class="form-check-label">Nobar</label>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Foto</label>
                        <input type="file" name="foto" class="form-control"
                            accept="image/jpeg,image/png,image/webp">
                    </div>

                    <button type="submit" class="btn btn-primary">Simpan</button>
                    <a href="mahasiswa.php" class="btn btn-secondary">Batal</a>

                </form>

            </div>

        </div>

    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>
