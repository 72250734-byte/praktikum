<?php
// helpers.php
declare(strict_types=1);

/** 
 * Memformat timestamp database menjadi format tanggal Indonesia 
 */
function format_tanggal_indo(?string $datetime): string
{
    if (empty($datetime))
        return '-';
    return date('d-m-Y H:i', strtotime($datetime));
}

// 1. Definisi init_session()
function init_session()
{
    if (session_status() === PHP_SESSION_NONE) {
        // Pengaturan keamanan cookie session
        session_set_cookie_params([
            'lifetime' => 3600,
            'path' => '/',
            'domain' => '',
            'secure' => true,    // Hanya kirim via HTTPS
            'httponly' => true,  // Tidak bisa diakses via JavaScript (cegah XSS)
            'samesite' => 'Strict' // Cegah CSRF
        ]);
        session_start();
    }
}

/**
 * Mencegah serangan Session Fixation dengan meregenerasi ID Sesi saat login
 */
function regenerate_user_session(array $userData): void
{
    init_session();
    session_regenerate_id(true);
    $_SESSION['is_logged_in'] = true;
    $_SESSION['user_id'] = $userData['id_pengguna'];
    $_SESSION['username'] = $userData['username'];
    $_SESSION['nama'] = $userData['nama_lengkap'];
    $_SESSION['role'] = $userData['role'];
    $_SESSION['last_login'] = time();
}

/**
 * Membersihkan input teks dari karakter berbahaya (Pencegahan XSS & Whitespace)
 */
function sanitize_input(string $data): string
{
    $data = trim($data); // Hapus spasi di awal/akhir // contoh " budi " -> "budi"
    $data = stripslashes($data); // Hapus backslash // contoh "O\'Neil" -> "O'Neil"
    return htmlspecialchars($data, ENT_QUOTES, 'UTF-8'); // Ubah karakter HTML jadi aman
}

/**
 * Memastikan pengguna sudah login
 */
function check_auth(): void
{
    init_session(); // Sesi harus hidup dulu supaya $_SESSION bisa dibaca
    if (!isset($_SESSION['is_logged_in']) || $_SESSION['is_logged_in'] !== true) { // Belum login jika bendera tidak ada, atau isinya bukan true
        header("Location: login.php?status=unauthorized"); // Mengalihkan ke login.php dengan pesan status=unauthorized
        exit(); // Wajib. Menghentikan skrip. Tanpa ini, sisa halaman tetap dieksekusi dan datanya bisa bocor.
    }
}

/**
 * Memastikan pengguna memiliki peran tertentu yang diizinkan (Middleware RBAC)
 */
function check_role(array $allowedRoles): void
{
    check_auth();
    if (!in_array($_SESSION['role'], $allowedRoles, true)) {
        http_response_code(403);
        echo "<h1 style='color:red; text-align: center; margin-top: 50px;'>403 Akses Ditolak!</h1>";
        echo "<p style='text-align:center;'>Anda tidak memiliki izin untuk mengakses halaman ini.</p>";
        echo "<p style='text-align:center;'><a href='index.php'>Kembali ke Beranda</a></p>";
        exit();
    }
}

/**
 * Mengunggah foto profil mahasiswa dengan validasi ukuran dan MIME type asli
 */
function upload_foto_mahasiswa(array $fileInput, string $nim, string $targetDir = 'uploads/'): array
{
    // 1. Jika tidak ada file yang diunggah (opsional), tetap kembalikan status true
    if (!isset($fileInput['error']) || $fileInput['error'] === UPLOAD_ERR_NO_FILE) {
        return ['status' => true, 'filename' => null, 'error' => ''];
    }

    // 2. Periksa error bawaan proses upload HTTP
    if ($fileInput['error'] !== UPLOAD_ERR_OK) {
        return [
            'status' => false,//Menandai proses gagal
            'filename' => null,
            'error' => 'Terjadi kesalahan saat mengunggah berkas.'//Pesan yang nanti ditampilkan ke pengguna
        ];
    }

    // 3. Validasi ukuran maksimal (2 MB)
    $maxSize = 2 * 1024 * 1024;
    //2 * 1024 * 1024	
    // 2 MB dalam byte = 2.097.152 byte (1 KB = 1024 byte, 1 MB = 1024 KB)
    if ($fileInput['size'] > $maxSize) {
        return [
            'status' => false,//Menandai proses gagal
            'filename' => null,
            'error' => 'Ukuran foto maksimal 2 MB!'
        ];
    }

    // 4. Validasi tipe MIME asli menggunakan Fileinfo (bukan dari $_FILES['type'])
    //Membuka "pembaca" Fileinfo yang diatur agar mengembalikan tipe MIME (contoh: image/png)
    $finfo = finfo_open(FILEINFO_MIME_TYPE);
    //Membaca isi berkas di lokasi sementara (tmp_name) dan mengembalikan tipe MIME aslinya
    $mimeType = finfo_file($finfo, $fileInput['tmp_name']);
    $allowedMimes = [
        'image/jpeg' => 'jpg',
        'image/png' => 'png',
        'image/webp' => 'webp'
    ];
    if (!array_key_exists($mimeType, $allowedMimes)) {
        return [
            'status' => false,
            'filename' => null,
            'error' => 'Format berkas tidak valid! Hanya diperbolehkan JPG, PNG, atau WEBP.'
        ];
    }

    // 5. Pastikan folder tujuan tersedia dan diakhiri garis miring
    $targetDir = rtrim($targetDir, '/\\') . '/';
    if (!is_dir($targetDir)) { // mengecek apakah foldernya tersedia
        mkdir($targetDir, 0755, true);//Membuat folder. 0755 = hak akses; true = boleh membuat folder bertingkat sekaligus
    }

    // 6. Generate nama file acak yang aman
    //Mengambil ekstensi dari daftar putih.
    //Contoh: image/png menghasilkan png. 
    //Ekstensi tidak diambil dari nama berkas pengguna
    $ext = $allowedMimes[$mimeType];
    //random_bytes(6)Menghasilkan 6 byte acak yang aman secara kriptografi
    //bin2hex(6) Mengubah byte menjadi teks heksadesimal, jadi 6 byte menjadi 12 karakter
    $newFileName = 'mhs_' . $nim . '_' . bin2hex(random_bytes(6)) . '.' . $ext;
    //Jalur lengkap tujuan, contoh uploads/mhs_72250734_a3f91c0b7d2e.png
    $destination = $targetDir . $newFileName;

    // 7. Pindahkan berkas dari direktori sementara ke folder uploads
    //move_uploaded_file(asal, tujuan)	
    // Memindahkan berkas dari direktori sementara ke direktori permanen. 
    // Mengembalikan true jika berhasil
    if (move_uploaded_file($fileInput['tmp_name'], $destination)) {
        //filename' => $newFileName	Yang dikembalikan hanya nama berkas, bukan jalur lengkap
        return ['status' => true, 'filename' => $newFileName, 'error' => ''];
    }
    return [
        'status' => false,
        'filename' => null,
        'error' => 'Gagal menyimpan berkas ke direktori server.'
    ];
}

/** 
 * Menghapus berkas fisik di server jika ada (mencegah orphan files) 
 */
function hapus_foto_lama(?string $fileName, string $targetDir = 'uploads/'): void
{
    if (!empty($fileName)) {
        $filePath = $targetDir . basename($fileName);
        if (file_exists($filePath) && is_file($filePath)) {
            unlink($filePath);
        }
    }
}

// --- TAMBAHAN FUNGSI MODUL 11 --- 

/** 
 * Validasi format NIM (harus 8 digit angka) 
 */
function validate_nim(string $nim): bool
{
    return preg_match('/^[0-9]{8}$/', $nim) === 1;
}
function process_hobi(array $hobiArr): string
{
    $bersih = array_map('sanitize_input', $hobiArr);
    return implode(', ', $bersih);
}
?>