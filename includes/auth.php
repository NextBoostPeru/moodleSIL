<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Ruta relativa inteligente:
$loginPath = dirname($_SERVER['PHP_SELF']) . "/login.php";

if (!isset($_SESSION['user_id'])) {
    header("Location: $loginPath");
    exit();
}
?>
