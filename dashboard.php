<?php
require_once 'includes/auth.php';
require_once './db.php';
require_once 'includes/header.php';
require_once 'includes/sidebar.php';
require_once 'includes/navbar.php';


if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>

<style>
  .dashboard-card {
    border-radius: 12px;
    overflow: hidden;
    transition: box-shadow 0.2s ease;
  }

  .dashboard-card:hover {
    box-shadow: 0 4px 20px rgba(0, 0, 0, 0.07);
  }

  .dashboard-card .card-title {
    font-size: 1.1rem;
    font-weight: 600;
    color: #343a40;
  }

  .dashboard-card .card-body {
    background-color: #ffffff;
  }

  @media (max-width: 768px) {
    .dashboard-card .card-title {
      font-size: 1rem;
    }
  }
</style>

<div class="container-fluid mt-4">
  <div class="row g-4">
    <!-- Usuarios recientes -->
    <div class="col-lg-6 col-md-12">
      <div class="card dashboard-card shadow-sm border-0">
        <div class="card-body">
          <div class="d-flex justify-content-between align-items-center mb-3">
            <h5 class="card-title mb-0"><i class="bi bi-people-fill me-2 text-primary"></i>Usuarios recientes</h5>
          </div>
          <div id="usuariosLista"></div>
          <nav>
            <ul class="pagination justify-content-center mt-3" id="usuariosPaginacion"></ul>
          </nav>
        </div>
      </div>
    </div>

    <!-- Cursos recientes -->
    <div class="col-lg-6 col-md-12">
      <div class="card dashboard-card shadow-sm border-0">
        <div class="card-body">
          <div class="d-flex justify-content-between align-items-center mb-3">
            <h5 class="card-title mb-0"><i class="bi bi-journal-code me-2 text-success"></i>Últimos cursos</h5>
          </div>
          <div id="cursosLista"></div>
          <nav>
            <ul class="pagination justify-content-center mt-3" id="cursosPaginacion"></ul>
          </nav>
        </div>
      </div>
    </div>

    <!-- Categorías de cursos -->
    <div class="col-12">
      <div class="card dashboard-card shadow-sm border-0 mt-2">
        <div class="card-body">
          <h5 class="card-title mb-3"><i class="bi bi-diagram-3-fill me-2 text-warning"></i>Cursos por categoría</h5>
          <div id="categoriasLista"></div>
        </div>
      </div>
    </div>
  </div>
</div>

<script>
function cargarUsuarios(page = 1) {
  fetch(`ajax/usuarios.php?page=${page}`)
    .then(res => res.text())
    .then(html => document.getElementById('usuariosLista').innerHTML = html);

  fetch(`ajax/usuarios.php?page=${page}&pagination=1`)
    .then(res => res.text())
    .then(html => document.getElementById('usuariosPaginacion').innerHTML = html);
}

function cargarCursos(page = 1) {
  fetch(`ajax/cursos.php?page=${page}`)
    .then(res => res.text())
    .then(html => document.getElementById('cursosLista').innerHTML = html);

  fetch(`ajax/cursos.php?page=${page}&pagination=1`)
    .then(res => res.text())
    .then(html => document.getElementById('cursosPaginacion').innerHTML = html);
}

function cargarCategorias() {
  fetch(`ajax/categorias.php`)
    .then(res => res.text())
    .then(html => document.getElementById('categoriasLista').innerHTML = html);
}

// Inicializar
cargarUsuarios();
cargarCursos();
cargarCategorias();
</script>

<?php require_once 'includes/footer.php'; ?>
