//proses_upload.php

<?php
if (isset($_POST["upload"])) {
    $fileName = $_FILES["berkas"]["name"];//"name" untuk menulis nama foto
    $file = $_FILES["berkas"]["size"];//"size" untuk menulis ukuran file foto
    $fileTipe = $_FILES["berkas"]["type"];//"type" untuk menulis type foto
    echo "Nama File : " . $fileName; 
    echo "Ukuran File :" . $file;
    echo "Tipe file : " . $fileTipe;
}

?>