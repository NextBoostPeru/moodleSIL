<?php
require_once '../db.php';

$id = intval($_GET['id'] ?? 0);

$stmt = $pdo->prepare("SELECT id, username, firstname, lastname, email FROM mdl_user WHERE id = ?");
$stmt->execute([$id]);
$user = $stmt->fetch();

if (!$user) {
    echo 'Usuario no encontrado';
    exit;
}
?>
<form id="formEditarUsuario">
  <input type="hidden" name="id" value="<?= $user['id'] ?>">
  <div class="mb-3">
    <label class="form-label">Nombre de usuario</label>
    <input type="text" class="form-control" name="username" value="<?= htmlspecialchars($user['username']) ?>" required>
  </div>
  <div class="mb-3">
    <label class="form-label">Nombres</label>
    <input type="text" class="form-control" name="firstname" value="<?= htmlspecialchars($user['firstname']) ?>" required>
  </div>
  <div class="mb-3">
    <label class="form-label">Apellidos</label>
    <input type="text" class="form-control" name="lastname" value="<?= htmlspecialchars($user['lastname']) ?>" required>
  </div>
  <div class="mb-3">
    <label class="form-label">Correo</label>
    <input type="email" class="form-control" name="email" value="<?= htmlspecialchars($user['email']) ?>" required>
  </div>
  <button type="button" class="btn btn-primary" onclick="guardarUsuario()">Guardar</button>
</form>
<script>
function guardarUsuario() {
  const form = document.getElementById('formEditarUsuario');
  fetch('./ajax/actualizar_usuario.php', {
    method: 'POST',
    body: new FormData(form)
  })
    .then(res => res.text())
    .then(resp => {
      if (resp.trim() === 'ok') {
        cargarEstudiantes();
        bootstrap.Modal.getInstance(document.getElementById('modalEditar')).hide();
      }
    });
}
</script>
