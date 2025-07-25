<?php
require_once '../db.php';

$id = intval($_GET['id'] ?? $_POST['id'] ?? 0);
if ($id > 0) {
  $pdo->prepare("DELETE FROM mdl_course WHERE id = ?")->execute([$id]);
  echo "ok";
}
