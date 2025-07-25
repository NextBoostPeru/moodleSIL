<?php
require_once '../db.php';

$id = intval($_GET['id'] ?? 0);

if ($id) {
  $stmt = $pdo->prepare("UPDATE mdl_user SET deleted = 1 WHERE id = ?");
  $ok = $stmt->execute([$id]);
  echo $ok ? 'ok' : 'Error al eliminar';
} else {
  echo 'ID inválido';
}
