<?php
require_once '../db.php';

$id = intval($_GET['id']);
$stmt = $pdo->prepare("SELECT id, fullname, capacity, vacancies FROM mdl_course WHERE id = ?");
$stmt->execute([$id]);
echo json_encode($stmt->fetch());
