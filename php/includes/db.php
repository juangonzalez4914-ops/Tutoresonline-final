<?php
$pdo = new PDO('mysql:host=localhost;dbname=tutoresonline;charset=utf8', 'root', '');
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

function siguiente_id($pdo, $tabla) {
    return $pdo->query("SELECT COALESCE(MAX(id),0)+1 FROM $tabla")->fetchColumn();
}
