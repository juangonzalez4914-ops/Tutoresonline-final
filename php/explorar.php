<?php
require 'includes/db.php';
require 'includes/sesion.php';
require 'includes/cookies.php';
require 'includes/layout.php';
requiere_login();

if (isset($_GET['materia'])) {
    guardar_filtro($_GET['materia']);
    $f = $_GET['materia'];
} else {
    $f = $_COOKIE['ultimo_filtro'] ?? '';
}
$sql = 'SELECT t.id, u.nombre, t.bio, t.tarifa_hora, t.calificacion_promedio FROM tutores t JOIN usuarios u ON u.id = t.usuario_id';
$par = [];
if ($f !== '') {
    $sql .= ' WHERE t.id IN (SELECT tm.tutor_id FROM tutor_materia tm JOIN materias m ON m.id = tm.materia_id WHERE m.nombre = ?)';
    $par[] = $f;
}
$st = $pdo->prepare($sql);
$st->execute($par);
$tutores = $st->fetchAll(PDO::FETCH_ASSOC);
$materias = $pdo->query('SELECT nombre FROM materias')->fetchAll(PDO::FETCH_COLUMN);
cabecera('Explorar', 'explorar');
?>
<div class="page-head"><h1>¿Qué te gustaría aprender hoy?</h1><p>Encuentra tutores y agenda una sesión en minutos.</p></div>
<div class="filters">
  <a class="chip<?= $f === '' ? ' active' : '' ?>" href="?materia=">Todas</a>
  <?php foreach ($materias as $m): ?>
  <a class="chip<?= $f === $m ? ' active' : '' ?>" href="?materia=<?= urlencode($m) ?>"><?= htmlspecialchars($m) ?></a>
  <?php endforeach; ?>
</div>
<div class="section-title"><h2>Tutores</h2><span><?= count($tutores) ?> resultados</span></div>
<div class="tutor-grid">
<?php foreach ($tutores as $t): ?>
  <a class="tutor-card" href="perfil.php?id=<?= $t['id'] ?>">
    <div class="tutor-top"><div class="ini"><?= inicial($t['nombre']) ?></div><div><div class="tname"><?= htmlspecialchars($t['nombre']) ?></div></div></div>
    <p class="tutor-blurb"><?= htmlspecialchars($t['bio']) ?></p>
    <div class="tutor-meta"><span class="rating">★ <?= $t['calificacion_promedio'] ?></span><span class="price">$<?= number_format($t['tarifa_hora'], 0, ',', '.') ?>/h</span></div>
  </a>
<?php endforeach; ?>
</div>
<?php pie(); ?>
