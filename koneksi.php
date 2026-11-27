<?php
// koneksi.php


$host = "localhost";
$user = "root";
$pass = "";
$db = "db_siukdw";

$koneksi = new mysqli($host, $user, $pass, $db);

if ($koneksi->connect_error) {
    die("Koneksi basis data gagal: " . $koneksi->connect_error);
}
?>