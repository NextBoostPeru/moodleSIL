<?php
require_once '../db.php';

$categoria = isset($_GET['categoria']) ? intval($_GET['categoria']) : 0;
$orden = $_GET['orden'] ?? 'fullname';

$ordenValido = ['fullname', 'capacity', 'vacancies', 'update_date'];
if (!in_array($orden, $ordenValido)) {
  $orden = 'fullname';
}

$sql = "
  SELECT c.fullname, cat.name AS categoria, c.capacity, c.vacancies, c.update_date
  FROM mdl_course c
  JOIN mdl_course_categories cat ON c.category = cat.id
  WHERE (:categoria = 0 OR c.category = :categoria)
  ORDER BY $orden
";

$stmt = $pdo->prepare($sql);
$stmt->bindValue(':categoria', $categoria, PDO::PARAM_INT);
$stmt->execute();
$cursos = $stmt->fetchAll();

echo '<div class="table-responsive">';
echo '<table class="table table-striped table-bordered align-middle">';
echo '<thead class="table-light">
<tr>
  <th>Curso</th>
  <th>Categoría</th>
  <th>Capacidad</th>
  <th>Vacantes</th>
  <th>Última Actualización</th>
</tr></thead><tbody>';

foreach ($cursos as $c) {
  echo '<tr>';
  echo '<td>' . htmlspecialchars($c['fullname']) . '</td>';
  echo '<td>' . htmlspecialchars($c['categoria']) . '</td>';
  echo '<td>' . $c['capacity'] . '</td>';
  echo '<td>' . $c['vacancies'] . '</td>';
  echo '<td>' . htmlspecialchars($c['update_date']) . '</td>';
  echo '</tr>';
}

echo '</tbody></table></div>';
?>
