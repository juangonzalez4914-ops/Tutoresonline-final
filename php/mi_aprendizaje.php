<?php
require 'includes/db.php';
require 'includes/sesion.php';
require 'includes/layout.php';
requiere_tipo('estudiante');

$st = $pdo->prepare('SELECT id, xp FROM estudiantes WHERE usuario_id = ?');
$st->execute([$_SESSION['id']]);
$e = $st->fetch(PDO::FETCH_ASSOC);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $sid = (int)$_POST['sesion_id'];
    $st = $pdo->prepare("SELECT tutor_id FROM sesiones WHERE id = ? AND estudiante_id = ? AND estado = 'aceptada'");
    $st->execute([$sid, $e['id']]);
    $tutor = $st->fetchColumn();
    $st = $pdo->prepare('SELECT COUNT(*) FROM resenas WHERE sesion_id = ?');
    $st->execute([$sid]);
    if ($tutor && $st->fetchColumn() == 0) {
        $cal = min(5, max(1, (int)$_POST['calificacion']));
        $pdo->prepare('INSERT INTO resenas VALUES (?,?,?,?)')->execute([siguiente_id($pdo, 'resenas'), $sid, $cal, $_POST['comentario']]);
        $pdo->prepare('UPDATE tutores SET calificacion_promedio = (SELECT AVG(r.calificacion) FROM resenas r JOIN sesiones s ON s.id = r.sesion_id WHERE s.tutor_id = ?) WHERE id = ?')->execute([$tutor, $tutor]);
    }
    header('Location: mi_aprendizaje.php');
    exit;
}
$st = $pdo->prepare('SELECT s.*, u.nombre AS tutor, m.nombre AS materia, (SELECT COUNT(*) FROM resenas r WHERE r.sesion_id = s.id) AS tiene FROM sesiones s JOIN tutores t ON t.id = s.tutor_id JOIN usuarios u ON u.id = t.usuario_id JOIN materias m ON m.id = s.materia_id WHERE s.estudiante_id = ? ORDER BY s.fecha DESC, s.hora');
$st->execute([$e['id']]);
$sesiones = $st->fetchAll(PDO::FETCH_ASSOC);
cabecera('Mi aprendizaje', 'aprendizaje');
?>
<div class="dash-head"><div><h1>¡Hola de nuevo, <?= htmlspecialchars($_SESSION['nombre']) ?>!</h1><p style="color:var(--muted)">Estas son tus clases.</p></div><span class="xp-badge"><?= $e['xp'] ?> XP</span></div>
<div class="section-title"><h2>Mis sesiones</h2></div>
<div class="card-block">
<?php foreach ($sesiones as $s): ?>
  <div class="fila">
    <div><b><?= htmlspecialchars($s['materia']) ?></b> con <?= htmlspecialchars($s['tutor']) ?><br><span style="color:var(--muted);font-size:13px"><?= $s['fecha'] ?> · <?= $s['hora'] ?> · <?= $s['duracion'] ?> min · <?= $s['modalidad'] ?> · $<?= number_format($s['precio_total'], 0, ',', '.') ?></span></div>
    <?php if ($s['estado'] === 'aceptada' && !$s['tiene']): ?>
      <form method="post"><input type="hidden" name="sesion_id" value="<?= $s['id'] ?>"><select name="calificacion"><option>5</option><option>4</option><option>3</option><option>2</option><option>1</option></select> <input name="comentario" placeholder="Comentario"> <button class="mini">Calificar</button></form>
    <?php else: ?><span class="badge-virtual"><?= $s['tiene'] ? 'calificada' : $s['estado'] ?></span><?php endif; ?>
  </div>
<?php endforeach; ?>
<?php if (!$sesiones) echo '<p>Aún no tienes sesiones.</p>'; ?>
</div>
<?php pie(); ?>
