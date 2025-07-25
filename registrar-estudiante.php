<?php
require_once 'includes/auth.php';
require_once './db.php';
require_once 'includes/header.php';
require_once 'includes/sidebar.php';
require_once 'includes/navbar.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $username = trim($_POST['username']);
  $passwordText = trim($_POST['password']) ?: 'Aensil2024'; // Usa Aensil2024 si está vacío
  $password = password_hash($passwordText, PASSWORD_DEFAULT);

  $firstname = trim($_POST['firstname']);
  $lastname = trim($_POST['lastname']);
  $email = trim($_POST['email']);
  $lang = 'es';
  $timecreated = time();

  try {
    $checkStmt = $pdo->prepare("SELECT id FROM mdl_user WHERE username = ?");
    $checkStmt->execute([$username]);

    if ($checkStmt->fetch()) {
      $error = "El nombre de usuario ya existe. Por favor, elige otro.";
    } else {
      $stmt = $pdo->prepare("INSERT INTO mdl_user (auth, confirmed, mnethostid, username, password, firstname, lastname, email, lang, timecreated)
        VALUES ('manual', 1, 1, ?, ?, ?, ?, ?, ?, ?)");
      $stmt->execute([$username, $password, $firstname, $lastname, $email, $lang, $timecreated]);

      $userid = $pdo->lastInsertId();

      // Asignar rol de estudiante
      $roleid = 5;
      $context = $pdo->query("SELECT id FROM mdl_context WHERE contextlevel = 10 LIMIT 1")->fetch();

      if ($context) {
        $stmt = $pdo->prepare("INSERT INTO mdl_role_assignments (roleid, contextid, userid, timemodified)
          VALUES (?, ?, ?, ?)");
        $stmt->execute([$roleid, $context['id'], $userid, $timecreated]);
      }

      $success = "✅ Estudiante registrado correctamente.";
    }
  } catch (Exception $e) {
    $error = "❌ Error al registrar estudiante: " . $e->getMessage();
  }
}
?>

<div class="container mt-5">
  <div class="row justify-content-center">
    <div class="col-md-8">
      <div class="card shadow-sm">
        <div class="card-body">
          <h5 class="card-title mb-4"><i class="bi bi-person-fill-add me-2"></i>Registrar Estudiante</h5>

          <?php if (isset($success)): ?>
            <div class="alert alert-success"><?= $success ?></div>
          <?php endif; ?>
          <?php if (isset($error)): ?>
            <div class="alert alert-danger"><?= $error ?></div>
          <?php endif; ?>

          <form method="POST">
            <div class="mb-3">
              <label for="username" class="form-label">Nombre de usuario</label>
              <input type="text" class="form-control" name="username" id="username" required>
            </div>

            <div class="mb-3">
              <label for="password" class="form-label">Contraseña</label>
              <div class="input-group">
                <input type="password" class="form-control" name="password" id="password" value="Aensil2024" required>
                <button type="button" class="btn btn-outline-secondary" onclick="togglePassword()">
                  <i class="bi bi-eye" id="toggleIcon"></i>
                </button>
              </div>
              <div class="form-text">Si no la modificas, se usará la contraseña por defecto: <strong>Aensil2024</strong></div>
            </div>

            <div class="mb-3">
              <label for="firstname" class="form-label">Nombres</label>
              <input type="text" class="form-control" name="firstname" id="firstname" required>
            </div>

            <div class="mb-3">
              <label for="lastname" class="form-label">Apellidos</label>
              <input type="text" class="form-control" name="lastname" id="lastname" required>
            </div>

            <div class="mb-3">
  <label for="email" class="form-label">Correo electrónico</label>
  <div class="input-group">
    <input type="text" class="form-control" name="email" id="email" placeholder="ejemplo@gmail.com" required>
    <button class="btn btn-outline-secondary" type="button" onclick="completarCorreo('@gmail.com')">@gmail.com</button>
    <button class="btn btn-outline-secondary" type="button" onclick="completarCorreo('@outlook.com')">@outlook.com</button>
    <button class="btn btn-outline-secondary" type="button" onclick="completarCorreo('@yahoo.com')">@yahoo.com</button>
  </div>
  <div class="form-text">Haz clic para completar el dominio automáticamente.</div>
</div>


            <button type="submit" class="btn btn-primary">Registrar</button>
          </form>
        </div>
      </div>
    </div>
  </div>
</div>

<script>
function togglePassword() {
  const passwordInput = document.getElementById("password");
  const toggleIcon = document.getElementById("toggleIcon");

  if (passwordInput.type === "password") {
    passwordInput.type = "text";
    toggleIcon.classList.remove("bi-eye");
    toggleIcon.classList.add("bi-eye-slash");
  } else {
    passwordInput.type = "password";
    toggleIcon.classList.remove("bi-eye-slash");
    toggleIcon.classList.add("bi-eye");
  }
}

function completarCorreo(dominio) {
  const emailInput = document.getElementById('email');
  let valor = emailInput.value.trim();

  if (valor.includes('@')) {
    valor = valor.split('@')[0]; // Elimina el dominio actual
  }

  emailInput.value = valor + dominio;
}
</script>

<?php require_once 'includes/footer.php'; ?>
