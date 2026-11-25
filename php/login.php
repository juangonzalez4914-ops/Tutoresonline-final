<?php
require 'includes/db.php';
require 'includes/sesion.php';
require 'includes/cookies.php';

$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $st = $pdo->prepare('SELECT * FROM usuarios WHERE correo = ?');
    $st->execute([$_POST['correo']]);
    $u = $st->fetch(PDO::FETCH_ASSOC);
    if ($u && password_verify($_POST['clave'], $u['clave'])) {
        iniciar_sesion($u);
        isset($_POST['recordar']) ? recordar_correo($u['correo']) : olvidar_correo();
        header('Location: ' . ($u['tipo'] === 'tutor' ? 'portal.php' : 'explorar.php'));
        exit;
    }
    $error = 'Correo o clave incorrectos';
}
?>
<!DOCTYPE html>
<html lang="es"><head><meta charset="UTF-8"><meta name="viewport" content="width=device-width, initial-scale=1"><title>Entrar - Aprende</title><link rel="stylesheet" href="css/style.css"></head>
<body>
<form method="post" class="login-box">
  <h1>Aprende</h1>
  <input class="campo" name="correo" value="<?= htmlspecialchars(correo_recordado()) ?>" placeholder="Correo">
  <input class="campo" name="clave" type="password" placeholder="Clave">
  <label><input type="checkbox" name="recordar"> Recordarme</label>
  <button class="btn-primary">Entrar</button>
  <p><?= $error ?></p>
</form>
</body></html>
