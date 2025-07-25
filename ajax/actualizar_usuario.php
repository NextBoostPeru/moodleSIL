<?php
require_once '../db.php';

$id = intval($_POST['id'] ?? 0);
$username = trim($_POST['username'] ?? '');
$firstname = trim($_POST['firstname'] ?? '');
$lastname = trim($_POST['lastname'] ?? '');
$email = trim($_POST['email'] ?? '');

if ($id && $username && $firstname && $lastname && $email) {
  $stmt = $pdo->prepare("UPDATE mdl_user SET username = ?, firstname = ?, lastname = ?, email = ? WHERE id = ?");
  $ok = $stmt->execute([$username, $firstname, $lastname, $email, $id]);

  echo $ok ? 'ok' : 'Error al actualizar';
} else {
  echo 'Datos incompletos';
}
