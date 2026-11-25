<?php
function recordar_correo($correo) {
    setcookie('recordar_correo', $correo, time() + 60*60*24*30, '/', '', false, true);
}

function correo_recordado() {
    return $_COOKIE['recordar_correo'] ?? '';
}

function olvidar_correo() {
    setcookie('recordar_correo', '', time() - 3600, '/');
}

function guardar_filtro($materia) {
    setcookie('ultimo_filtro', $materia, time() + 60*60*24*7, '/');
}
