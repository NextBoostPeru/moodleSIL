<?php
require_once '../db.php';

$page = isset($_GET['page']) ? max(1, intval($_GET['page'])) : 1;
$perPage = 5;
$offset = ($page - 1) * $perPage;

if (isset($_GET['pagination'])) {
  // Calcular número total de páginas
  $stmt = $pdo->query("SELECT COUNT(*) AS total FROM mdl_user WHERE deleted = 0");
  $total = $stmt->fetch()['total'];
  $pages = ceil($total / $perPage);

  // Paginación con rango inteligente
  echo '<li class="page-item' . ($page == 1 ? ' disabled' : '') . '">';
  echo '<a class="page-link" href="#" onclick="cargarUsuarios(' . ($page - 1) . ')">Anterior</a></li>';

  if ($pages > 1) {
    if ($page > 3) {
      echo '<li class="page-item"><a class="page-link" href="#" onclick="cargarUsuarios(1)">1</a></li>';
      if ($page > 4) echo '<li class="page-item disabled"><span class="page-link">...</span></li>';
    }

    for ($i = max(1, $page - 2); $i <= min($pages, $page + 2); $i++) {
      echo '<li class="page-item' . ($i == $page ? ' active' : '') . '">';
      echo '<a class="page-link" href="#" onclick="cargarUsuarios(' . $i . ')">' . $i . '</a></li>';
    }

    if ($page < $pages - 2) {
      if ($page < $pages - 3) echo '<li class="page-item disabled"><span class="page-link">...</span></li>';
      echo '<li class="page-item"><a class="page-link" href="#" onclick="cargarUsuarios(' . $pages . ')">' . $pages . '</a></li>';
    }
  }

  echo '<li class="page-item' . ($page == $pages ? ' disabled' : '') . '">';
  echo '<a class="page-link" href="#" onclick="cargarUsuarios(' . ($page + 1) . ')">Siguiente</a></li>';

  exit;
}

// Listado de usuarios recientes
$stmt = $pdo->prepare("
  SELECT id, firstname, lastname, email, timecreated
  FROM mdl_user
  WHERE deleted = 0
  ORDER BY timecreated DESC
  LIMIT :limit OFFSET :offset
");
$stmt->bindValue(':limit', $perPage, PDO::PARAM_INT);
$stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
$stmt->execute();

$usuarios = $stmt->fetchAll();

echo '<ul class="list-group list-group-flush">';
foreach ($usuarios as $u) {
  echo '<li class="list-group-item">';
  echo '<strong>' . htmlspecialchars($u['firstname'] . ' ' . $u['lastname']) . '</strong><br>';
  echo '<small class="text-muted">' . htmlspecialchars($u['email']) . ' | Registro: ' . date('d/m/Y', $u['timecreated']) . '</small>';
  echo '</li>';
}
echo '</ul>';
