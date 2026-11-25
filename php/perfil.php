<?php
require 'includes/db.php';
require 'includes/sesion.php';
require 'includes/layout.php';
requiere_login();

$id = (int)($_GET['id'] ?? 0);
$st = $pdo->prepare('SELECT t.*, u.nombre FROM tutores t JOIN usuarios u ON u.id = t.usuario_id WHERE t.id = ?');
$st->execute([$id]);
$t = $st->fetch(PDO::FETCH_ASSOC);
if (!$t) {
    http_response_code(404);
    exit('Tutor no encontrado');
}
$st = $pdo->prepare('SELECT m.id, m.nombre FROM materias m JOIN tutor_materia tm ON tm.materia_id = m.id WHERE tm.tutor_id = ?');
$st->execute([$id]);
$materias = $st->fetchAll(PDO::FETCH_ASSOC);

$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if ($_SESSION['tipo'] !== 'estudiante') {
        $error = 'Solo los estudiantes pueden reservar';
    } else {
        $st = $pdo->prepare('SELECT id FROM estudiantes WHERE usuario_id = ?');
        $st->execute([$_SESSION['id']]);
        $e = $st->fetchColumn();
        $dur = in_array((int)$_POST['duracion'], [30, 60, 90]) ? (int)$_POST['duracion'] : 60;
        $mod = $_POST['modalidad'] === 'presencial' ? 'presencial' : 'virtual';
        $precio = intdiv($t['tarifa_hora'] * $dur, 60) + 2000;
        $pdo->prepare('INSERT INTO sesiones VALUES (?,?,?,?,?,?,?,?,?,?)')->execute([siguiente_id($pdo, 'sesiones'), $e, $id, (int)$_POST['materia_id'], $_POST['fecha'], $_POST['hora'], $dur, $mod, 'pendiente', $precio]);
        header('Location: mi_aprendizaje.php');
        exit;
    }
}
cabecera('Perfil', 'explorar');
?>
<a class="back-link" href="explorar.php">← Volver a explorar</a>
<div class="profile-layout">
  <div>
    <div class="profile-hero"><div class="ini"><?= inicial($t['nombre']) ?></div>
      <div><h1><?= htmlspecialchars($t['nombre']) ?></h1><div class="subline">★ <?= $t['calificacion_promedio'] ?> · $<?= number_format($t['tarifa_hora'], 0, ',', '.') ?> / hora</div></div></div>
    <div class="card-block"><h3>Sobre mí</h3><p><?= htmlspecialchars($t['bio']) ?></p></div>
    <div class="card-block"><h3>Especialidades</h3><div class="pill-row"><?php foreach ($materias as $m) echo '<span class="pill">' . htmlspecialchars($m['nombre']) . '</span>'; ?></div></div>
  </div>
  <form method="post" class="booking-panel">
    <h3>Reserva tu sesión</h3>
    <div class="field-label">Materia</div>
    <select class="campo" name="materia_id"><?php foreach ($materias as $m) echo '<option value="' . $m['id'] . '">' . htmlspecialchars($m['nombre']) . '</option>'; ?></select>
    <div class="field-label">Fecha</div>
    <select class="campo" name="fecha"><?php for ($i = 1; $i <= 5; $i++) { $d = date('Y-m-d', strtotime("+$i day")); echo "<option>$d</option>"; } ?></select>
    <div class="field-label">Hora</div>
    <select class="campo" name="hora"><?php foreach (['09:00', '10:30', '14:00', '15:30', '17:00', '18:30'] as $h) echo "<option>$h</option>"; ?></select>
    <div class="field-label">Duración</div>
    <select class="campo" name="duracion"><option value="30">30 min</option><option value="60" selected>60 min</option><option value="90">90 min</option></select>
    <div class="field-label">Modalidad</div>
    <select class="campo" name="modalidad"><option value="virtual">Virtual</option><option value="presencial">Presencial</option></select>
    <p style="font-size:13px;color:var(--muted)">El total incluye $2.000 de cargo de servicio.</p>
    <button class="btn-primary">Confirmar reserva</button>
    <p><?= $error ?></p>
  </form>
</div>
<?php pie(); ?>
