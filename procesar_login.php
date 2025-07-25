<?php
session_start();
require_once 'db.php';


$username = $_POST['username'] ?? '';
$password = $_POST['password'] ?? '';

if ($username && $password) {
    $stmt = $pdo->prepare("SELECT * FROM mdl_user WHERE username = ?");
    $stmt->execute([$username]);
    $user = $stmt->fetch();
   

  if ($user) {
    $_SESSION['user_id'] = $user['id'];
    $_SESSION['username'] = $user['username'];
    $_SESSION['firstname'] = $user['firstname'];
    $_SESSION['lastname'] = $user['lastname'];
    $_SESSION['fullname'] = $user['firstname'] . ' ' . $user['lastname'];

    // Obtener rol principal
    $stmtRol = $pdo->prepare("
        SELECT r.shortname AS rol
        FROM mdl_role_assignments ra
        JOIN mdl_context c ON ra.contextid = c.id
        JOIN mdl_role r ON ra.roleid = r.id
        WHERE ra.userid = ? AND c.contextlevel = 10
        LIMIT 1
    ");
    $stmtRol->execute([$user['id']]);
    $rolData = $stmtRol->fetch();

    $_SESSION['rol'] = $rolData ? $rolData['rol'] : 'sin_rol';

    header("Location: dashboard.php");
    exit();
} else {
    $_SESSION['login_error'] = "Credenciales incorrectas.";
    header("Location: login.php");
    exit;
}

} else {
    $_SESSION['login_error'] = "Completa todos los campos.";
    header("Location: login.php");
    exit;
}
?>
