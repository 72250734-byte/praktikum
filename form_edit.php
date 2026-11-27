<?php
require_once "koneksi.php";
require_once "helpers.php";

if (isset($_GET['nim']) && !empty($_GET['nim'])) {
   
} else {
    die("error");
}


    // SANITASI TEKS
    $nim = sanitize_input($_GET['nim']);

    if (!validate_nim($nim)) {
        die('ERROR : Nim tidak valid! Harus 8 DIGITTTT');
    }

    // $nama = sanitize_input($_GET['nama']);
    // $prodi = sanitize_input($_GET['prodi']);
    // $hobi = sanitize_input($_GET['hobi']);


$sql = $koneksi->prepare("SELECT nim, nama, prodi, hobi FROM mahasiswa WHERE nim = ?");
$sql->bind_param("s", $_GET['nim']);
$sql->execute();
$result = $sql->get_result();
if ($result->num_rows === 0) {
    die("ERROR Data tidak ditemukan");
}
$row = $result->fetch_assoc();
$sql->close();
$array_hobi = !empty($row["hobi"]) ? explode(", ", $row["hobi"]) : [];
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Form Edit Mahasiswa</title>
</head>

<body>
    <form action="proses_edit.php" method="POST">
        <table>
            <tr>
                <td>NIM</td>
                <td><input type="number" name="nim" value="<?= $row['nim'] ?>" readonly></td>
            </tr>
            <tr>
                <td>Nama</td>
                <td><input type="text" name="nama" value="<?= $row['nama'] ?>"></td>
            </tr>
            <tr>
                <td>Prodi</td>
                <td>
                    <select name="prodi">
                        <option value="0">---Pilih Prodi---</option>
                        <option value="Sistem Informasi" <?= ($row['prodi'] == 'Sistem Informasi') ? 'selected' : '' ?>>
                            Sistem Informasi</option>
                        <option value="Informatika" <?= ($row['prodi'] == 'Informatika') ? 'selected' : '' ?>>
                            Informatika</option>
                        <option value="Manajemen" <?= ($row['prodi'] == 'Manajemen') ? 'selected' : '' ?>>Manajemen
                        </option>
                    </select>
                </td>
            </tr>
            <tr>
                <td>Hobi</td>
                <td>
                    <input type="checkbox" name="hobi[]" value="Koding"
                        <?= in_array('Koding', $array_hobi) ? 'checked' : '' ?>>Koding
                    <input type="checkbox" name="hobi[]" value="UI/UX"
                        <?= in_array('UI/UX', $array_hobi) ? 'checked' : '' ?>>UI/UX
                    <input type="checkbox" name="hobi[]" value="Organisasi"
                        <?= in_array('Organisasi', $array_hobi) ? 'checked' : '' ?>>Organisasi
                </td>
            </tr>
            <tr>
                <td colspan="2"><button type="submit" name="submit">Submit</button></td>
            </tr>
            <tr>
                <td colspan="2">
                    <p><a href="mahasiswa.php">Kembali ke daftar mahasiswa</a></p>
                </td>
            </tr>
        </table>
    </form>
</body>

</html>
```