<?php
require_once 'includes/auth.php';
require_once './db.php';
require_once 'includes/header.php';
require_once 'includes/sidebar.php';
require_once 'includes/navbar.php';

// Obtener categorías para el filtro
$categorias = $pdo->query("SELECT id, name FROM mdl_course_categories ORDER BY name")->fetchAll();
?>

<div class="container mt-4">
  <h4>Cursos por categoría</h4>

  <div class="row g-3 mb-3">
    <div class="col-md-4">
      <select class="form-select" id="filtroCategoria">
        <option value="0">Todas las categorías</option>
        <?php
        $cats = $pdo->query("SELECT id, name FROM mdl_course_categories ORDER BY name");
        while ($cat = $cats->fetch()) {
          echo "<option value='{$cat['id']}'>" . htmlspecialchars($cat['name']) . "</option>";
        }
        ?>
      </select>
    </div>
    <div class="col-md-4">
      <select class="form-select" id="filtroOrden">
        <option value="fullname">Nombre del curso</option>
        <option value="capacity">Capacidad</option>
        <option value="vacancies">Vacantes</option>
        <option value="update_date">Última actualización</option>
      </select>
    </div>
    <div class="col-md-4">
      <button class="btn btn-primary w-100" onclick="cargarCursos()">Filtrar</button>
    </div>
  </div>

  <div id="listaCursos"></div>
</div>

<!-- Modal Editar -->
<div class="modal fade" id="modalEditarCurso" tabindex="-1">
  <div class="modal-dialog">
    <div class="modal-content">
      <form id="formEditarCurso">
        <div class="modal-header">
          <h5 class="modal-title">Editar Curso</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        </div>
        <div class="modal-body">
          <input type="hidden" name="id" id="editCursoId">
          <div class="mb-3">
            <label class="form-label">Nombre del curso</label>
            <input type="text" name="fullname" id="editFullname" class="form-control" required>
          </div>
          <div class="mb-3">
            <label class="form-label">Capacidad</label>
            <input type="number" name="capacity" id="editCapacity" class="form-control" required>
          </div>
          <div class="mb-3">
            <label class="form-label">Vacantes</label>
            <input type="number" name="vacancies" id="editVacancies" class="form-control" required>
          </div>
        </div>
        <div class="modal-footer">
          <button class="btn btn-primary" type="submit">Guardar cambios</button>
        </div>
      </form>
    </div>
  </div>
</div>


<!-- Modal Eliminar -->
<div class="modal fade" id="modalEliminarCurso" tabindex="-1">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Eliminar Curso</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body">
        ¿Estás seguro de que deseas eliminar este curso?
        <input type="hidden" id="eliminarCursoId">
      </div>
      <div class="modal-footer">
        <button class="btn btn-danger" onclick="confirmarEliminarCurso()">Eliminar</button>
      </div>
    </div>
  </div>
</div>


<script>
function cargarCursos() {
  const categoria = document.getElementById('filtroCategoria').value;
  const orden = document.getElementById('filtroOrden').value;

  fetch(`ajax/listar_cursos.php?categoria=${categoria}&orden=${orden}`)
    .then(res => res.text())
    .then(html => document.getElementById('listaCursos').innerHTML = html);
}

// Cargar inicialmente
document.addEventListener('DOMContentLoaded', cargarCursos);


function abrirModalEditar(id) {
  fetch('ajax/obtener_curso.php?id=' + id)
    .then(res => res.json())
    .then(data => {
      document.getElementById('editCursoId').value = data.id;
      document.getElementById('editFullname').value = data.fullname;
      document.getElementById('editCapacity').value = data.capacity;
      document.getElementById('editVacancies').value = data.vacancies;
      new bootstrap.Modal(document.getElementById('modalEditarCurso')).show();
    });
}

document.getElementById('formEditarCurso').addEventListener('submit', function (e) {
  e.preventDefault();
  const formData = new FormData(this);

  fetch('ajax/actualizar_curso.php', {
    method: 'POST',
    body: formData
  }).then(res => res.text())
    .then(resp => {
      bootstrap.Modal.getInstance(document.getElementById('modalEditarCurso')).hide();
      cargarCursos(); // recargar lista
    });
});

function abrirModalEliminar(id) {
  document.getElementById('eliminarCursoId').value = id;
  new bootstrap.Modal(document.getElementById('modalEliminarCurso')).show();
}

function confirmarEliminarCurso() {
  const id = document.getElementById('eliminarCursoId').value;
  fetch('ajax/eliminar_curso.php?id=' + id, { method: 'POST' })
    .then(res => res.text())
    .then(resp => {
      bootstrap.Modal.getInstance(document.getElementById('modalEliminarCurso')).hide();
      listarCursos(); // recargar
    });
}
</script>


<?php require_once 'includes/footer.php'; ?>
