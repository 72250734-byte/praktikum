<?php
// cetak_laporan.php
declare(strict_types=1);

require_once "koneksi.php";
require_once "helpers.php";
require_once "vendor/autoload.php";

use Dompdf\Dompdf;
use Dompdf\Options;

// 1. Proteksi Halaman RBAC (Admin & Mahasiswa diperbolehkan mencetak laporan)
check_role(['admin', 'mahasiswa']);

// 2. Mengambil data mahasiswa dari Data Layer
$sql = "SELECT nim, nama, prodi, hobi, created_at FROM mahasiswa ORDER BY nim ASC";
$result = $koneksi->query($sql);

// 3. Menyusun template HTML & CSS di dalam memori server
// Catatan: Dompdf mendukung CSS 2.1 dan sebagian CSS3 (gunakan tabel untuk layout yangstabil);
$html = '
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <style>
        body {
            font-family: Helvetica, Arial, sans-serif;
            font-size: 11px;
            color: #222222;
        }

        .kop-surat {
            text-align: center;
            border-bottom: 2px solid #004085;
            padding-bottom: 10px;
            margin-bottom: 20px;
        }

        .kop-surat h2 {
            margin: 0;
            color: #004085;
            font-size: 16px;
            text-transform: uppercase;

            .kop-surat p {
                margin: 4px 0 0 0;
                font-size: 10px;
                color: #555555;
            }

            .meta-info {
                margin-bottom: 12px;
                font-size: 10px;
            }

            table.data-tabel {
                width: 100%;
                border-collapse: collapse;
            }

            table.data-tabel th {
                background-color: #004085;
                color: #ffffff;
                font-weight: bold;
                text-align: left;
                padding: 7px;
                border: 1px solid #004085;
            }

            table.data-tabel td {
                padding: 6px 7px;
                border: 1px solid #cccccc;
                vertical-align: top;
            }

            table.data-tabel tr:nth-child(even) {
                background-color: #f8f9fa;
            }

            .footer-ttd {
                margin-top: 30px;
                text-align: right;
                font-size: 10px;
            }
    </style>
</head>

<body>
    <div class="kop-surat">
        <h2>Laporan Rekapitulasi Data Mahasiswa Aktif</h2>
        <p>Sistem Informasi Akademik Terpadu (SIUKDW) - Fakultas Teknologi Informasi</p>
    </div>

    <div class="meta-info">
        <strong>Dicetak oleh:</strong> ' . htmlspecialchars($_SESSION['nama']) . ' (' .
    strtoupper($_SESSION['role']) . ') <br>
        <strong>Tanggal Cetak:</strong> ' . format_tanggal_indo(date('Y-m-d H:i:s')) . '
    </div>

    <table class="data-tabel">
        <thead>
            <tr>
                <th style="width: 5%;">No</th>
                <th style="width: 13%;">NIM</th>
                <th style="width: 25%;">Nama Lengkap</th>
                <th style="width: 18%;">Program Studi</th>
                <th style="width: 24%;">Hobi</th>
                <th style="width: 15%;">Terdaftar</th>
            </tr>
        </thead>
        <tbody>';

$no = 1;
while ($row = $result->fetch_assoc()) {
    $hobiTampil = !empty($row['hobi']) ? htmlspecialchars($row['hobi']) : '-';
    $html .= '
            <tr>
                <td>' . $no++ . '</td>
                <td>' . htmlspecialchars($row['nim']) . '</td>
                <td>' . htmlspecialchars($row['nama']) . '</td>
                <td>' . htmlspecialchars($row['prodi']) . '</td>
                <td>' . $hobiTampil . '</td>
                <td>' . format_tanggal_indo($row['created_at']) . '</td>
            </tr>';
}

$html .= '
        </tbody>
    </table>

    <div class="footer-ttd">
        <p>Yogyakarta, ' . format_tanggal_indo(date('Y-m-d H:i:s')) . '</p>
        <br><br><br>
        <p><strong>Bagian Administrasi Akademik</strong></p>
    </div>
</body>

</html>';

$koneksi->close();

// 4. Konfigurasi dan Inisialisasi Dompdf
$options = new Options();
$options->set('defaultFont', 'Helvetica');

$dompdf = new Dompdf($options);
$dompdf->loadHtml($html);

// 5. Mengatur ukuran kertas A4 dan orientasi Portrait
$dompdf->setPaper('A4', 'portrait');

// 6. Render HTML menjadi PDF
$dompdf->render();

// 7. Kirim output PDF ke browser (Attachment => false untuk pratinjau di browser, trueuntuk langsung unduh)
$dompdf->stream("Laporan_Mahasiswa_SIUKDW.pdf", ["Attachment" => false]);
exit();
?>