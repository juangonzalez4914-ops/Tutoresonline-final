<?php
require 'includes/db.php';
require 'includes/sesion.php';
require 'includes/layout.php';
requiere_tipo('tutor');

$st = $pdo->prepare('SELECT id, calificacion_promedio FROM tutores WHERE usuario_id = ?');
$st->execute([$_SESSION['id']]);
$t = $st->fetch(PDO::FETCH_ASSOC);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $estado = $_POST['accion'] === 'aceptar' ? 'aceptada' : 'rechazada';
    $pdo->prepare('UPDATE sesiones SET estado = ? WHERE id = ? AND tutor_id = ?')->execute([$estado, (int)$_POST['sesion_id'], $t['id']]);
    header('Location: portal.php');
    exit;
}
$st = $pdo->prepare('SELECT COALESCE(SUM(precio_total), 0) FROM sesiones WHERE tutor_id = ? AND estado = ?');
$st->execute([$t['id'], 'aceptada']);
$ingresos = $st->fetchColumn();
$st = $pdo->prepare('SELECT s.*, u.nombre AS estudiante, m.nombre AS materia FROM sesiones s JOIN estudiantes e ON e.id = s.estudiante_id JOIN usuarios u ON u.id = e.usuario_id JOIN materias m ON m.id = s.materia_id WHERE s.tutor_id = ? ORDER BY s.fecha, s.hora');
$st->execute([$t['id']]);
$sesiones = $st->fetchAll(PDO::FETCH_ASSOC);
cabecera('Portal del tutor', 'portal');
?>
<div class="dash-head"><div><h1><?= htmlspecialchars($_SESSION['nombre']) ?></h1><p style="color:var(--muted)">Portal del tutor</p></div><span class="xp-badge">★ <?= $t['calificacion_promedio'] ?> / 5.0</span></div>
<div class="stat-row"><div class="stat-card"><div class="lbl">Ingresos confirmados</div><div class="val">$<?= number_format($ingresos, 0, ',', '.') ?></div></div><div class="stat-card" style="background:linear-gradient(135deg,var(--coral),var(--coral-dark))"><div class="lbl">Sesiones</div><div class="val"><?= count($sesiones) ?></div></div></div>
<div class="section-title"><h2>Solicitudes y turnos</h2></div>
<div class="card-block">
<?php foreach ($sesiones as $s): ?>
  <div class="fila">
    <div><b><?= htmlspecialchars($s['estudiante']) ?></b> · <?= htmlspecialchars($s['materia']) ?><br><span style="color:var(--muted);font-size:13px"><?= $s['fecha'] ?> · <?= $s['hora'] ?> · <?= $s['duracion'] ?> min · $<?= number_format($s['precio_total'], 0, ',', '.') ?></span></div>
    <?php if ($s['estado'] === 'pendiente'): ?>
      <form method="post"><input type="hidden" name="sesion_id" value="<?= $s['id'] ?>"><button class="mini" name="accion" value="aceptar">Aceptar</button> <button class="mini no" name="accion" value="rechazar">Rechazar</button></form>
    <?php else: ?><span class="badge-virtual"><?= $s['estado'] ?></span><?php endif; ?>
  </div>
<?php endforeach; ?>
<?php if (!$sesiones) echo '<p>No hay solicitudes.</p>'; ?>
</div>
<?php pie(); ?>
