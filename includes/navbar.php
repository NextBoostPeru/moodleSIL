<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Validamos si está logueado y tiene firstname
$firstname = $_SESSION['firstname'] ?? 'Invitado';
?>
<nav class="navbar navbar-expand-lg bg-white shadow-sm border-bottom">
  <div class="container-fluid justify-content-between">
    <span class="navbar-brand fw-bold text-primary ml-2">
      <i class="bi bi-mortarboard-fill me-1"></i> Aula Virtual
    </span>

    <div class="d-flex align-items-center">
      <span class="me-3 text-dark">
        <i class="bi bi-person-circle me-1"></i>
       <?php echo htmlspecialchars($_SESSION['firstname'] ?? 'Invitado'); ?>
      </span>
      <a href="./logout.php" class="btn btn-danger btn-sm">
        <i class="bi bi-box-arrow-right me-1"></i> Cerrar sesi車n
      </a>
    </div>
  </div>
</nav>
