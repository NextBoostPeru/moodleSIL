<?php
require_once '../db.php'; // Asegúrate de que este archivo tenga la conexión a la base de datos de Moodle

header('Content-Type: application/json');

if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    echo json_encode([]);
    exit;
}

$categoria_id = (int)$_GET['id'];

try {
    $stmt = $pdo->prepare("SELECT id, fullname FROM mdl_course WHERE category = :categoria_id ORDER BY fullname");
    $stmt->execute(['categoria_id' => $categoria_id]);
    $cursos = $stmt->fetchAll(PDO::FETCH_ASSOC);

    echo json_encode($cursos);
} catch (Exception $e) {
    echo json_encode(['error' => 'Error al obtener cursos']);
}
