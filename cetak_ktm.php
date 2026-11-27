<?php
// cetak_ktm.php 
declare(strict_types=1);

require_once "koneksi.php";
require_once "helpers.php";
require_once "vendor/autoload.php";

use Dompdf\Dompdf;
use Dompdf\Options;

// 1. Proteksi RBAC 
check_role(['admin', 'mahasiswa']);
// 2. Validasi parameter NIM dari URL 
if (!isset($_GET['nim']) || empty($_GET['nim'])) {
    die("Error: Parameter NIM tidak ditemukan!");
}

$nimTarget = trim($_GET['nim']);

// 3. Ambil data satu mahasiswa menggunakan Prepared Statement 
$stmt = $koneksi->prepare("SELECT nim, nama, prodi, foto, created_at FROM mahasiswa WHERE 
nim = ?");
$stmt->bind_param("s", $nimTarget);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    $stmt->close();
    $koneksi->close();
    die("Error: Data mahasiswa tidak ditemukan!");
}

$mhs = $result->fetch_assoc();
$stmt->close();
$koneksi->close();

// 4. Konversi Foto Profil Lokal menjadi Base64 Data URI agar terbaca 100% oleh Dompdf 
$fotoHtml = '<div class="foto-placeholder">NO PHOTO</div>';
if (!empty($mhs['foto'])) {
    $pathFoto = __DIR__ . '/uploads/' . basename($mhs['foto']);
    if (file_exists($pathFoto) && is_file($pathFoto)) {
        $tipeMime = mime_content_type($pathFoto);
        $dataBiner = file_get_contents($pathFoto);
        $base64 = 'data:' . $tipeMime . ';base64,' . base64_encode($dataBiner);
        $fotoHtml = '<img src="' . $base64 . '" class="foto-mhs" alt="Foto KTM">';
    }
}

// 5. Susun HTML & CSS Kartu Mahasiswa 
$html = ' 
<!DOCTYPE html> 
<html lang="id"> 
<head> 
    <meta charset="UTF-8"> 
    <style> 
        @page { 
            margin: 15px; 
        } 
        body { 
            font-family: Helvetica, Arial, sans-serif; 
            margin: 0; 
            padding: 0; 
        } 
        .kartu-ktm { 
            width: 340px; 
            border: 2px solid #004085; 
            border-radius: 10px; 
            overflow: hidden; 
            background-color: #ffffff; 
        } 
        .header-ktm { 
            background-color: #004085; 
            color: #ffffff; 
            padding: 10px 14px; 
            text-align: center; 
        } 
        .header-ktm h3 { 
            margin: 0; 
            font-size: 13px; 
            letter-spacing: 0.5px; 
        } 
        .header-ktm p { 
            margin: 2px 0 0 0; 
            font-size: 9px; 
            color: #cce5ff; 
        } 
        .body-ktm { 
            padding: 14px; 
        } 
 .layout-table { 
            width: 100%; 
            border-collapse: collapse; 
        } 
        .kolom-foto { 
            vertical-align: top; 
            width: 85px; 
        } 
        .foto-mhs { 
            width: 75px; 
            height: 95px; 
            object-fit: cover; 
            border: 1px solid #999999; 
            border-radius: 4px; 
        } 
        .foto-placeholder { 
            width: 75px; 
            height: 95px; 
            background-color: #e9ecef; 
            border: 1px dashed #adb5bd; 
            color: #6c757d; 
            font-size: 9px; 
            text-align: center; 
            line-height: 95px; 
            border-radius: 4px; 
        } 
        .kolom-info { 
            vertical-align: top; 
            padding-left: 10px; 
        } 
        .label-field { 
            font-size: 8px; 
            color: #666666; 
            text-transform: uppercase; 
            margin-bottom: 1px; 
        } 
        .nilai-field { 
            font-size: 11px; 
            font-weight: bold; 
            color: #111111; 
            margin-bottom: 7px; 
        } 
        .footer-ktm { 
            background-color: #f1f3f5; 
            padding: 6px 14px; 
            font-size: 8px; 
            color: #495057; 
            border-top: 1px solid #dee2e6; 
            text-align: right; 
        } 
    </style> 
</head> 
<body> 
    <div class="kartu-ktm"> 
        <div class="header-ktm"> 
            <h3>KARTU TANDA MAHASISWA</h3> 
            <p>SISTEM INFORMASI AKADEMIK (SIUKDW)</p> 
        </div> 
        <div class="body-ktm"> 
            <table class="layout-table"> 
                <tr> 
                    <td class="kolom-foto">' . $fotoHtml . '</td> 
                    <td class="kolom-info"> 
                        <div class="label-field">Nomor Induk Mahasiswa</div> 
                        <div class="nilai-field">' . htmlspecialchars($mhs['nim']) .
    '</div> 
 
                        <div class="label-field">Nama Lengkap</div> 
                        <div class="nilai-field">' . htmlspecialchars($mhs['nama']) .
    '</div> 
 
                        <div class="label-field">Program Studi</div> 
                        <div class="nilai-field">' . htmlspecialchars($mhs['prodi']) .
    '</div> 
                    </td> 
                </tr> 
            </table> 
<div class="footer-ktm"> 
Berlaku selama tercatat sebagai mahasiswa aktif 
</div> 
</div> 
</body> 
</html>';
// 6. Render PDF dengan ukuran kertas kustom (dalam satuan point: 1 inch = 72 pt) 
$options = new Options();
$options->set('defaultFont', 'Helvetica');
$dompdf = new Dompdf($options);
$dompdf->loadHtml($html);
$dompdf->setPaper([0, 0, 290, 210]); // Ukuran kartu kustom 
$dompdf->render();
$dompdf->stream("KTM_" . $mhs['nim'] . ".pdf", ["Attachment" => false]);
exit();
?> 