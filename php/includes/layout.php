<?php
function cabecera($titulo, $activo = '') {
    $nav = ['explorar' => ['Explorar', 'explorar.php'], 'aprendizaje' => ['Mi aprendizaje', 'mi_aprendizaje.php'], 'portal' => ['Portal del tutor', 'portal.php'], 'cuenta' => ['Mi cuenta', 'mi_cuenta.php']];
    $tipo = $_SESSION['tipo'] ?? '';
    echo '<!DOCTYPE html><html lang="es"><head><meta charset="UTF-8"><meta name="viewport" content="width=device-width, initial-scale=1"><title>' . htmlspecialchars($titulo) . ' - Aprende</title><link rel="stylesheet" href="css/style.css"></head><body><div class="app"><aside class="sidebar"><div class="brand"><div class="brand-mark"></div><div class="brand-name">Aprende</div></div><nav class="nav">';
    foreach ($nav as $k => $v) {
        if ($k === 'portal' && $tipo !== 'tutor') continue;
        if ($k === 'aprendizaje' && $tipo !== 'estudiante') continue;
        echo '<a class="nav-item' . ($k === $activo ? ' active' : '') . '" href="' . $v[1] . '">' . $v[0] . '</a>';
    }
    echo '</nav><div class="sidebar-spacer"></div><div class="sidebar-user"><div class="who"><span class="name">' . htmlspecialchars($_SESSION['nombre'] ?? '') . '</span><span class="role">' . htmlspecialchars($tipo) . ' · <a href="logout.php">Salir</a></span></div></div></aside><main class="main">';
}

function pie() {
    echo '</main></div></body></html>';
}

function inicial($nombre) {
    return htmlspecialchars(strtoupper(substr($nombre, 0, 1)));
}
