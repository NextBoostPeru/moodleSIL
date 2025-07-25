<?php
require_once '../db.php';

$page = isset($_GET['page']) ? max(1, intval($_GET['page'])) : 1;
$perPage = 5;
$offset = ($page - 1) * $perPage;

if (isset($_GET['pagination'])) {
  // Total de cursos
  $stmt = $pdo->query("SELECT COUNT(*) AS total FROM mdl_course WHERE category != 0");
  $total = $stmt->fetch()['total'];
  $pages = ceil($total / $perPage);

  // Paginación compacta
  echo '<li class="page-item' . ($page == 1 ? ' disabled' : '') . '">';
  echo '<a class="page-link" href="#" onclick="cargarCursos(' . ($page - 1) . ')">Anterior</a></li>';

  if ($pages > 1) {
    if ($page > 3) {
      echo '<li class="page-item"><a class="page-link" href="#" onclick="cargarCursos(1)">1</a></li>';
      if ($page > 4) echo '<li class="page-item disabled"><span class="page-link">...</span></li>';
    }

    for ($i = max(1, $page - 2); $i <= min($pages, $page + 2); $i++) {
      echo '<li class="page-item' . ($i == $page ? ' active' : '') . '">';
      echo '<a class="page-link" href="#" onclick="cargarCursos(' . $i . ')">' . $i . '</a></li>';
    }

    if ($page < $pages - 2) {
      if ($page < $pages - 3) echo '<li class="page-item disabled"><span class="page-link">...</span></li>';
      echo '<li class="page-item"><a class="page-link" href="#" onclick="cargarCursos(' . $pages . ')">' . $pages . '</a></li>';
    }
  }

  echo '<li class="page-item' . ($page == $pages ? ' disabled' : '') . '">';
  echo '<a class="page-link" href="#" onclick="cargarCursos(' . ($page + 1) . ')">Siguiente</a></li>';

  exit;
}

// Listado de cursos con su categoría
$stmt = $pdo->prepare("
  SELECT c.id, c.fullname, c.summary, cat.name AS category_name
  FROM mdl_course c
  JOIN mdl_course_categories cat ON c.category = cat.id
  WHERE c.category != 0
  ORDER BY c.id DESC
  LIMIT :limit OFFSET :offset
");
$stmt->bindValue(':limit', $perPage, PDO::PARAM_INT);
$stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
$stmt->execute();

$cursos = $stmt->fetchAll();

echo '<ul class="list-group list-group-flush">';
foreach ($cursos as $c) {
  echo '<li class="list-group-item">';
  echo '<strong>' . htmlspecialchars($c['fullname']) . '</strong><br>';
  echo '<small class="text-muted">Categoría: ' . htmlspecialchars($c['category_name']) . '</small><br>';
  if (!empty($c['summary'])) {
    echo '<small>' . htmlspecialchars(strip_tags(substr($c['summary'], 0, 100))) . '...</small>';
  }
  echo '</li>';
}
echo '</ul>';
