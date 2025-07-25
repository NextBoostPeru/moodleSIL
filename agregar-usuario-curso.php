<?php
require_once 'includes/auth.php';
require_once './db.php';
require_once 'includes/header.php';
require_once 'includes/sidebar.php';
require_once 'includes/navbar.php';
?>

<div class="container mt-4">
  <h4 class="mb-4"><i class="bi bi-person-plus-fill me-2"></i>Agregar usuario a curso</h4>

  <div class="card shadow-sm">
    <div class="card-body">
      <form id="formInscripcion">
        <div class="mb-3">
          <label class="form-label">Nombre de usuario</label>
          <input type="text" name="username" id="username" class="form-control" required>
          <div id="userInfo" class="form-text text-success mt-2"></div>
        </div>

        <div class="mb-3">
          <label class="form-label">Categoría</label>
          <select class="form-select" name="categoria_id" id="categoria_id" required>
            <option value="">Seleccione una categoría</option>
            <?php
            $stmt = $pdo->query("SELECT id, name FROM mdl_course_categories ORDER BY name");
            while ($cat = $stmt->fetch()) {
              echo "<option value='{$cat['id']}'>" . htmlspecialchars($cat['name']) . "</option>";
            }
            ?>
          </select>
        </div>

        <div class="mb-3">
          <label class="form-label">Curso</label>
          <select class="form-select" name="curso_id" id="curso_id" required>
            <option value="">Seleccione un curso</option>
          </select>
        </div>

        <input type="hidden" name="user_id" id="user_id">

        <button type="submit" class="btn btn-primary">Inscribir al curso</button>
      </form>

      <div id="mensaje" class="mt-3"></div>
    </div>
  </div>
</div>

<script>
// Buscar usuario al escribir
document.getElementById('username').addEventListener('blur', () => {
  const username = document.getElementById('username').value;
  if (username.trim() === '') return;

  fetch('ajax/buscar_usuario.php?username=' + encodeURIComponent(username))
    .then(res => res.json())
    .then(data => {
      const info = document.getElementById('userInfo');
      if (data.error) {
        info.innerHTML = data.error;
        info.classList.replace('text-success', 'text-danger');
        document.getElementById('user_id').value = '';
      } else {
        info.innerHTML = `${data.firstname} ${data.lastname}`;
        info.classList.replace('text-danger', 'text-success');
        document.getElementById('user_id').value = data.id;
      }
    });
});

// Cargar cursos según categoría
document.getElementById('categoria_id').addEventListener('change', () => {
  const categoriaId = document.getElementById('categoria_id').value;

  fetch('ajax/cursos_por_categoria.php?id=' + categoriaId)
    .then(res => res.json())
    .then(data => {
      const select = document.getElementById('curso_id');
      select.innerHTML = '<option value="">Seleccione un curso</option>';
      data.forEach(curso => {
        select.innerHTML += `<option value="${curso.id}">${curso.fullname}</option>`;
      });
    });
});

// Enviar formulario
document.getElementById('formInscripcion').addEventListener('submit', function (e) {
  e.preventDefault();
  const form = new FormData(this);

  fetch('ajax/inscribir_usuario.php', {
    method: 'POST',
    body: form
  })
    .then(res => res.text())
    .then(resp => {
      document.getElementById('mensaje').innerHTML = `<div class="alert alert-success">${resp}</div>`;
      this.reset();
      document.getElementById('userInfo').textContent = '';
      document.getElementById('curso_id').innerHTML = '<option value="">Seleccione un curso</option>';
    });
});
</script>

<?php require_once 'includes/footer.php'; ?>
