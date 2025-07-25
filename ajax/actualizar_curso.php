<?php
require_once '../db.php';
date_default_timezone_set('America/Lima');

$id = $_POST['id'];
$fullname = trim($_POST['fullname']);
$capacity = intval($_POST['capacity']);
$vacancies = intval($_POST['vacancies']);
$update_date = date('Y-m-d');

$stmt = $pdo->prepare("UPDATE mdl_course SET fullname = ?, capacity = ?, vacancies = ?, update_date = ? WHERE id = ?");
$stmt->execute([$fullname, $capacity, $vacancies, $update_date, $id]);

echo "ok";
