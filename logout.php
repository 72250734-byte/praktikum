<?php
// logout.php
declare(strict_types=1);
require_once "helpers.php";

init_session();

// 1. Kosongkan semua variabel array superglobal $_SESSION
$_SESSION = array();

// 2. Hapus Cookie Sesi pada browser client
if (ini_get("session.use_cookies")) {
    $params = session_get_cookie_params();
    setcookie(
        session_name(),
        '',
        time() - 42000,
        $params["path"],
        $params["domain"],
        $params["secure"],
        $params["httponly"]
    );
}

// 3. Hancurkan berkas fisik sesi di server
session_destroy();

// 4. Alihkan pengguna ke halaman login
header("Location: login.php");
exit();
?>