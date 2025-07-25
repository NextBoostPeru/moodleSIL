<?php
session_start();

header('Content-Type: text/html; charset=utf-8');

$error = $_SESSION['login_error'] ?? '';
unset($_SESSION['login_error']);
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Iniciar sesión</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        html, body { height: 100%; margin: 0; }
        .login-container {
            display: flex;
            height: 100vh;
        }
        .form-section {
            flex: 1;
            background: #fff;
            display: flex;
            justify-content: center;
            align-items: center;
            padding: 40px;
        }
        .image-section {
            flex: 1;
            background: #f8f9fa;
            background-image: url(//sanignaciodeloyolaperu.com/aulavirtual/pluginfile.php/1/theme_moove/loginbgimg/1741481751/portada%20aula.png);
    background-size: cover; /
            background-size: cover;
            background-position: center;
        }
        @media (max-width: 768px) {
            .image-section { display: none; }
            .form-section { flex: 1; }
        }
    </style>
</head>
<body>
<div class="login-container">
    <div class="form-section">
        <div class="w-100" style="max-width: 400px;">
            <h3 class="mb-4 text-center">Iniciar Sesión</h3>
            <form action="procesar_login.php" method="POST">
                <div class="mb-3">
                    <label class="form-label">Usuario</label>
                    <input type="text" name="username" class="form-control" required autofocus>
                </div>
                <div class="mb-3">
    <label class="form-label">Contraseña</label>
    <div class="input-group">
        <input type="password" name="password" class="form-control" id="inputPassword" required>
        <button class="btn btn-outline-secondary" type="button" id="togglePassword">
            <i class="bi bi-eye-slash" id="iconToggle"></i>
        </button>
    </div>
</div>

                <button type="submit" class="btn btn-primary w-100">Ingresar</button>
            </form>
        </div>
    </div>
    <div class="image-section"></div>
</div>

<?php if ($error): ?>
<!-- Toast de Bootstrap -->
<div class="position-fixed bottom-0 end-0 p-3" style="z-index: 11">
  <div id="toastError" class="toast align-items-center text-white bg-danger border-0 show" role="alert">
    <div class="d-flex">
      <div class="toast-body"><?= htmlspecialchars($error) ?></div>
      <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"></button>
    </div>
  </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

<?php endif; ?>
<!-- Bootstrap Icons -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
<script>
document.getElementById('togglePassword').addEventListener('click', function () {
    const passwordInput = document.getElementById('inputPassword');
    const icon = document.getElementById('iconToggle');
    const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
    passwordInput.setAttribute('type', type);
    icon.classList.toggle('bi-eye');
    icon.classList.toggle('bi-eye-slash');
});
</script>

</body>
</html>
