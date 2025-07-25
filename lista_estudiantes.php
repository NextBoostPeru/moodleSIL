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
</script>

<?php require_once 'includes/footer.php'; ?>
