<?php
require 'includes/db.php';
require 'includes/sesion.php';
require 'includes/archivos.php';
require 'includes/layout.php';
requiere_login();

$msg = '';
if (isset($_FILES['foto'])) {
    [$ok, $res] = subir_archivo($_FILES['foto'], 'uploads/fotos', ['jpg', 'png']);
    if ($ok) {
        $pdo->prepare('UPDATE usuarios SET foto_url = ? WHERE id = ?')->execute([$res, $_SESSION['id']]);
    }
    $msg = $ok ? 'Foto actualizada' : $res;
}
if (isset($_FILES['material']) && $_SESSION['tipo'] === 'tutor') {
    [$ok, $res] = subir_archivo($_FILES['material'], 'uploads/materiales/' . $_SESSION['id'], ['pdf', 'docx'], 5242880);
    $msg = $ok ? 'Material subido' : $res;
}
$materiales = listar_archivos('uploads/materiales/' . $_SESSION['id']);
cabecera('Mi cuenta', 'cuenta');
?>
<div class="page-head"><h1>Mi cuenta</h1><p><?= $msg ?></p></div>
<div class="card-block"><h3>Foto de perfil (jpg o png, máximo 2 MB)</h3>
<form method="post" enctype="multipart/form-data"><input type="file" name="foto"> <button class="mini">Cambiar foto</button></form></div>
<?php if ($_SESSION['tipo'] === 'tutor'): ?>
<div class="card-block"><h3>Material de clase (pdf o docx, máximo 5 MB)</h3>
<form method="post" enctype="multipart/form-data"><input type="file" name="material"> <button class="mini">Subir material</button></form>
<ul><?php foreach ($materiales as $m) echo '<li>' . htmlspecialchars($m) . '</li>'; ?></ul></div>
<?php endif; ?>
<?php pie(); ?>
