<?php
require_once '../db.php';

$sql = "
  SELECT cc.name, COUNT(c.id) AS total
  FROM mdl_course_categories cc
  LEFT JOIN mdl_course c ON c.category = cc.id
  GROUP BY cc.id
  ORDER BY total DESC
";

$stmt = $pdo->query($sql);
$categorias = $stmt->fetchAll();

echo '<div class="row">';
foreach ($categorias as $row) {
  echo '<div class="col-md-4 mb-3">';
  echo '  <div class="card border-0 shadow-sm h-100">';
  echo '    <div class="card-body">';
  echo '      <h6 class="card-title">' . htmlspecialchars($row['name']) . '</h6>';
  echo '      <p class="card-text"><strong>' . $row['total'] . '</strong> cursos</p>';
  echo '    </div>';
  echo '  </div>';
  echo '</div>';
}
echo '</div>';
