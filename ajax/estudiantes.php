<?php
require_once '../db.php';

$page = isset($_GET['page']) ? max(1, intval($_GET['page'])) : 1;
$perPage = 25;
$offset = ($page - 1) * $perPage;
$search = isset($_GET['search']) ? trim($_GET['search']) : '';

$baseQuery = "
  SELECT id, username, firstname, lastname, email, FROM_UNIXTIME(timecreated, '%d/%m/%Y') AS fecha
  FROM mdl_user
  WHERE deleted = 0
";

$params = [];
if ($search !== '') {
  $baseQuery .= " AND (username LIKE :search OR firstname LIKE :search OR lastname LIKE :search)";
  $params[':search'] = "%$search%";
}

if (isset($_GET['pagination'])) {
  $countQuery = "
    SELECT COUNT(*) as total
    FROM mdl_user
    WHERE deleted = 0
  ";
  if ($search !== '') {
    $countQuery .= " AND (username LIKE :search OR firstname LIKE :search OR lastname LIKE :search)";
  }

  $stmt = $pdo->prepare($countQuery);
  if ($search !== '') {
    $stmt->bindValue(':search', "%$search%", PDO::PARAM_STR);
  }
  $stmt->execute();
  $total = $stmt->fetch()['total'];
  $pages = ceil($total / $perPage);

  echo '<li class="page-item' . ($page == 1 ? ' disabled' : '') . '">';
  echo '<a class="page-link" href="#" onclick="cargarEstudiantes(' . ($page - 1) . ', document.getElementById(\'buscador\').value)">Anterior</a></li>';

  if ($pages > 1) {
    if ($page > 3) {
      echo '<li class="page-item"><a class="page-link" href="#" onclick="cargarEstudiantes(1, document.getElementById(\'buscador\').value)">1</a></li>';
      if ($page > 4) echo '<li class="page-item disabled"><span class="page-link">...</span></li>';
    }

    for ($i = max(1, $page - 2); $i <= min($pages, $page + 2); $i++) {
      echo '<li class="page-item' . ($i == $page ? ' active' : '') . '">';
      echo '<a class="page-link" href="#" onclick="cargarEstudiantes(' . $i . ', document.getElementById(\'buscador\').value)">' . $i . '</a></li>';
    }

    if ($page < $pages - 2) {
      if ($page < $pages - 3) echo '<li class="page-item disabled"><span class="page-link">...</span></li>';
      echo '<li class="page-item"><a class="page-link" href="#" onclick="cargarEstudiantes(' . $pages . ', document.getElementById(\'buscador\').value)">' . $pages . '</a></li>';
    }
  }

  echo '<li class="page-item' . ($page == $pages ? ' disabled' : '') . '">';
  echo '<a class="page-link" href="#" onclick="cargarEstudiantes(' . ($page + 1) . ', document.getElementById(\'buscador\').value)">Siguiente</a></li>';

  exit;
}

$finalQuery = $baseQuery . " ORDER BY timecreated DESC LIMIT :limit OFFSET :offset";
$stmt = $pdo->prepare($finalQuery);

foreach ($params as $key => $value) {
  $stmt->bindValue($key, $value, PDO::PARAM_STR);
}
$stmt->bindValue(':limit', $perPage, PDO::PARAM_INT);
$stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
$stmt->execute();

$usuarios = $stmt->fetchAll();

echo '<div class="table-responsive">';
echo '<table class="table table-striped table-bordered">';
echo '<thead class="table-light"><tr>
  <th>Nombre de usuario</th>
  <th>Nombres</th>
  <th>Apellidos</th>
  <th>Correo</th>
  <th>Fecha de registro</th>
  <th>Acciones</th>
</tr></thead><tbody>';

foreach ($usuarios as $u) {
  echo '<tr>';
  echo '<td>' . htmlspecialchars($u['username']) . '</td>';
  echo '<td>' . htmlspecialchars($u['firstname']) . '</td>';
  echo '<td>' . htmlspecialchars($u['lastname']) . '</td>';
  echo '<td>' . htmlspecialchars($u['email']) . '</td>';
  echo '<td>' . $u['fecha'] . '</td>';
  echo '<td>';
  echo '<button class="btn btn-sm btn-primary me-1" onclick="abrirModalEditar(' . $u['id'] . ')"><i class="bi bi-pencil"></i></button>';
  echo '<button class="btn btn-sm btn-danger" onclick="abrirModalEliminar(' . $u['id'] . ')"><i class="bi bi-trash"></i></button>';
  echo '</td>';
  echo '</tr>';
}

echo '</tbody></table></div>';

// Modales
?>

<!-- Modal Editar -->
<div class="modal fade" id="modalEditar" tabindex="-1">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Editar Usuario</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body" id="formEditar"></div>
    </div>
  </div>
</div>

<!-- Modal Eliminar -->
<div class="modal fade" id="modalEliminar" tabindex="-1">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Eliminar Usuario</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body">
        <p>¿Estás seguro de que deseas eliminar este usuario?</p>
        <input type="hidden" id="idEliminar">
        <button class="btn btn-danger" onclick="confirmarEliminar()">Sí, eliminar</button>
      </div>
    </div>
  </div>
</div>

<script>
function abrirModalEditar(id) {
  fetch('./ajax/editar_usuario_form.php?id=' + id)
    .then(res => res.text())
    .then(html => {
      document.getElementById('formEditar').innerHTML = html;
      new bootstrap.Modal(document.getElementById('modalEditar')).show();
    });
}

function abrirModalEliminar(id) {
  document.getElementById('idEliminar').value = id;
  new bootstrap.Modal(document.getElementById('modalEliminar')).show();
}

function confirmarEliminar() {
  const id = document.getElementById('idEliminar').value;
  fetch('./ajax/eliminar_usuario.php?id=' + id, { method: 'POST' })
    .then(res => res.text())
    .then(resp => {
      if (resp.trim() === 'ok') {
        cargarEstudiantes();
        bootstrap.Modal.getInstance(document.getElementById('modalEliminar')).hide();
      }
    });
}
</script>
