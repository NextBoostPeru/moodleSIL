
<style>
  #sidebarMenu {
    width: 250px;
    transform: translateX(-100%);
    transition: transform 0.3s ease;
    z-index: 1040;
    background-color: #212529;
    color: #fff;
    border-right: 1px solid #343a40;
  }

  #sidebarMenu.show {
    transform: translateX(0);
  }

  #sidebarToggleBtn {
    z-index: 1050;
    top: 20px;
    left: 20px;
    transition: opacity 0.3s;
  }

  #sidebarToggleBtn.hide {
    opacity: 0;
    pointer-events: none;
  }

  #sidebarMenu .nav-link {
    color: #adb5bd;
    padding: 10px 15px;
    border-radius: 6px;
    transition: background-color 0.2s, color 0.2s;
    font-weight: 500;
  }

  #sidebarMenu .nav-link:hover,
  #sidebarMenu .nav-link.active {
    background-color: #343a40;
    color: #fff;
  }

  #sidebarMenu .nav-link i {
    font-size: 1.1rem;
    width: 1.5rem;
  }

  #sidebarMenu h5 {
    font-size: 1.25rem;
    font-weight: bold;
    color: #ffffff;
  }

  #sidebarCloseBtn {
    color: #000;
    font-size: 0.8rem;
  }

  @media (max-width: 768px) {
    #sidebarToggleBtn {
      top: 10px;
      left: 10px;
    }
  }
</style>

<!-- Bot√≥n toggle -->
<button id="sidebarToggleBtn" class="btn btn-dark position-fixed">
  <i class="bi bi-list"></i>
</button>

<!-- Sidebar -->
<nav id="sidebarMenu" class="position-fixed vh-100 p-3 shadow">
  <div class="d-flex justify-content-between align-items-center mb-4">
    <h5 class="mb-0">Men®≤ Principal</h5>
    <button id="sidebarCloseBtn" class="btn btn-sm btn-light">
      <i class="bi bi-x-lg"></i>
    </button>
  </div>
  <ul class="nav flex-column">
    <li class="nav-item">
      <a class="nav-link" href="./dashboard.php">
        <i class="bi bi-speedometer2"></i> Dashboard
      </a>
    </li>
    <li class="nav-item">
      <a class="nav-link" href="./registrar-estudiante.php">
        <i class="bi bi-person-plus-fill"></i> Registrar Estudiante
      </a>
    </li>
    <li class="nav-item">
      <a class="nav-link" href="./lista_estudiantes.php">
        <i class="bi bi-people-fill"></i> Usuarios
      </a>
    </li>
    <li class="nav-item">
      <a class="nav-link text-white" href="./agregar-usuario-curso.php">
        <i class="bi bi-journal-plus me-2"></i> Agregar a Curso
      </a>
    </li>
       <li class="nav-item">
      <a class="nav-link text-white" href="./lista-cursos.php">
        <i class="bi bi-journal-plus me-2"></i> Lista de Cursos
      </a>
    </li>
    <li class="nav-item mt-3">
      <a class="nav-link" href="./logout.php">
        <i class="bi bi-box-arrow-right"></i> Cerrar sesi√≥n
      </a>
    </li>
  </ul>
</nav>

<script>
  const sidebar = document.getElementById('sidebarMenu');
  const toggleBtn = document.getElementById('sidebarToggleBtn');
  const closeBtn = document.getElementById('sidebarCloseBtn');

  toggleBtn.addEventListener('click', () => {
    sidebar.classList.add('show');
    toggleBtn.classList.add('hide');
  });

  closeBtn.addEventListener('click', () => {
    sidebar.classList.remove('show');
    toggleBtn.classList.remove('hide');
  });
</script>
