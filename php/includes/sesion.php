<?php
session_start();

function iniciar_sesion($u) {
    session_regenerate_id(true);
    $_SESSION['id'] = $u['id'];
    $_SESSION['nombre'] = $u['nombre'];
    $_SESSION['tipo'] = $u['tipo'];
}

function requiere_login() {
    if (!isset($_SESSION['id'])) {
        header('Location: login.php');
        exit;
    }
}

function requiere_tipo($tipo) {
    requiere_login();
    if ($_SESSION['tipo'] !== $tipo) {
        http_response_code(403);
        exit('Acceso no permitido');
    }
}

function cerrar_sesion() {
    $_SESSION = [];
    session_destroy();
}
