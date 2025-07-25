<?php
require_once '../db.php';

$user_id = $_POST['user_id'] ?? 0;
$curso_id = $_POST['curso_id'] ?? 0;
$rol_id = 5; // Estudiante

if (!$user_id || !$curso_id) {
  echo "Datos incompletos.";
  exit;
}

// Buscar instancia de enrolment manual en ese curso
$stmt = $pdo->prepare("SELECT id FROM mdl_enrol WHERE courseid = ? AND enrol = 'manual' LIMIT 1");
$stmt->execute([$curso_id]);
$enrol = $stmt->fetch();

if (!$enrol) {
  echo "No se encontr¨® un m¨¦todo de inscripci¨®n manual en este curso.";
  exit;
}

$enrol_id = $enrol['id'];

// Insertar en mdl_user_enrolments
$stmt = $pdo->prepare("
  INSERT IGNORE INTO mdl_user_enrolments (enrolid, userid, timestart, timecreated, timemodified, status)
  VALUES (?, ?, UNIX_TIMESTAMP(), UNIX_TIMESTAMP(), UNIX_TIMESTAMP(), 0)
");
$stmt->execute([$enrol_id, $user_id]);

// Obtener el contexto del curso
$stmt = $pdo->prepare("SELECT id FROM mdl_context WHERE contextlevel = 50 AND instanceid = ?");
$stmt->execute([$curso_id]);
$context = $stmt->fetch();

if (!$context) {
  echo "No se encontr¨® el contexto del curso.";
  exit;
}

$contextid = $context['id'];

// Insertar en mdl_role_assignments
$stmt = $pdo->prepare("
  INSERT IGNORE INTO mdl_role_assignments (roleid, contextid, userid, timemodified)
  VALUES (?, ?, ?, UNIX_TIMESTAMP())
");
$stmt->execute([$rol_id, $contextid, $user_id]);

echo "Usuario inscrito correctamente al curso.";
