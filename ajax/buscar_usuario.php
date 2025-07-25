<?php
require_once '../db.php';
header('Content-Type: application/json');

$username = $_GET['username'] ?? '';
$stmt = $pdo->prepare("SELECT id, firstname, lastname FROM mdl_user WHERE username = ?");
$stmt->execute([$username]);
$user = $stmt->fetch();

if ($user) {
  echo json_encode($user);
} else {
  echo json_encode(['error' => 'Usuario no encontrado.']);
}
