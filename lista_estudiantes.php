<?php
require_once 'includes/auth.php';
require_once './db.php';
require_once 'includes/header.php';
require_once 'includes/sidebar.php';
require_once 'includes/navbar.php';
?>

<div class="container-fluid mt-4">
  <h4 class="mb-4">Lista de Estudiantes</h4>

  <div class="row mb-3">
    <div class="col-md-6">
      <input type="text" id="buscador" class="form-control" placeholder="Buscar por nombre, apellido o usuario...">
    </div>
  </div>

  <div id="tablaEstudiantes"></div>
  <nav>
    <ul class="pagination justify-content-center mt-3" id="paginacionEstudiantes"></ul>
  </nav>
</div>

<!-- Modales -->
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

<div class="modal fade" id="modalArchivo" tabindex="-1">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Archivo del usuario</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body" id="previewArchivo" style="text-align:center;"></div>
      <div class="modal-footer">
        <a id="linkDescargar" class="btn btn-primary" href="#" download>Descargar</a>
      </div>
    </div>
  </div>
</div>

<script>
function cargarEstudiantes(page = 1, search = '') {
  fetch(`ajax/estudiantes.php?page=${page}&search=${encodeURIComponent(search)}`)
    .then(res => res.text())
    .then(html => document.getElementById('tablaEstudiantes').innerHTML = html);

  fetch(`ajax/estudiantes.php?page=${page}&search=${encodeURIComponent(search)}&pagination=1`)
    .then(res => res.text())
    .then(html => document.getElementById('paginacionEstudiantes').innerHTML = html);
}

// Cargar al inicio
cargarEstudiantes();

// Buscar en vivo
const buscador = document.getElementById('buscador');
buscador.addEventListener('input', () => cargarEstudiantes(1, buscador.value));

function abrirModalEditar(id) {
  fetch('ajax/editar_usuario_form.php?id=' + id)
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

function abrirModalArchivo(path) {
  const preview = document.getElementById('previewArchivo');
  preview.innerHTML = '';
  const ext = path.split('.').pop().toLowerCase();
  if (['jpg', 'jpeg', 'png', 'gif'].includes(ext)) {
    preview.innerHTML = `<img src="${path}" class="img-fluid">`;
  } else if (ext === 'pdf') {
    preview.innerHTML = `<embed src="${path}" type="application/pdf" width="100%" height="500px">`;
  } else {
    preview.textContent = 'Vista previa no disponible';
  }
  document.getElementById('linkDescargar').href = path;
  new bootstrap.Modal(document.getElementById('modalArchivo')).show();
}

function confirmarEliminar() {
  const id = document.getElementById('idEliminar').value;
  fetch('ajax/eliminar_usuario.php?id=' + id, { method: 'POST' })
    .then(res => res.text())
    .then(resp => {
      if (resp.trim() === 'ok') {
        cargarEstudiantes();
        bootstrap.Modal.getInstance(document.getElementById('modalEliminar')).hide();
      }
    });
}
</script>

<?php require_once 'includes/footer.php'; ?>
