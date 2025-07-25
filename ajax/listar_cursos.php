<?php
require_once '../db.php';
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

$categoria = intval($_GET['categoria'] ?? 0);
$orden = $_GET['orden'] ?? 'fullname';


$sql = "
  SELECT c.id, c.fullname, cat.name AS categoria, c.capacity, c.vacancies, c.update_date
  FROM mdl_course c
  JOIN mdl_course_categories cat ON c.category = cat.id
  WHERE (:categoria = 0 OR c.category = :categoria)
  ORDER BY " . ($orden === 'capacity' || $orden === 'vacancies' || $orden === 'update_date' ? "c.$orden" : "c.fullname");

$stmt = $pdo->prepare($sql);
$stmt->bindValue(':categoria', $categoria, PDO::PARAM_INT);
$stmt->execute();
$cursos = $stmt->fetchAll(PDO::FETCH_ASSOC);


echo '<div class="table-responsive"><table class="table table-striped">';
echo '<thead><tr><th>Curso</th><th>Categoría</th><th>Capacidad</th><th>Vacantes</th><th>Actualización</th></tr></thead><tbody>';
foreach ($cursos as $curso) {
  echo '<tr>';
  echo '<td>' . htmlspecialchars($curso['fullname']) . '</td>';
  echo '<td>' . htmlspecialchars($curso['categoria']) . '</td>';
  echo '<td>' . htmlspecialchars($curso['capacity']) . '</td>';
  echo '<td>' . htmlspecialchars($curso['vacancies']) . '</td>';
  echo '<td>' . htmlspecialchars($curso['update_date']) . '</td>';
  echo '<td>
    <button class="btn btn-sm btn-primary" onclick="abrirModalEditar(' . $curso['id'] . ')"><i class="bi bi-pencil"></i></button>
    <button class="btn btn-sm btn-danger" onclick="eliminarCurso(' . $curso['id'] . ')"><i class="bi bi-trash"></i></button>
  </td>';
  echo '</tr>';

}
echo '</tbody></table></div>';
