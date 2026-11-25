<?php
function subir_archivo($archivo, $carpeta, $permitidos, $maxBytes = 2097152) {
    if ($archivo['error'] !== UPLOAD_ERR_OK) return [false, 'Error al subir'];
    if ($archivo['size'] > $maxBytes) return [false, 'Archivo muy grande'];
    $ext = strtolower(pathinfo($archivo['name'], PATHINFO_EXTENSION));
    if (!in_array($ext, $permitidos)) return [false, 'Tipo no permitido'];
    if (!is_dir($carpeta)) mkdir($carpeta, 0755, true);
    $nombre = uniqid() . '.' . $ext;
    if (!move_uploaded_file($archivo['tmp_name'], "$carpeta/$nombre")) return [false, 'No se pudo guardar'];
    return [true, "$carpeta/$nombre"];
}

function listar_archivos($carpeta) {
    return is_dir($carpeta) ? array_values(array_diff(scandir($carpeta), ['.', '..'])) : [];
}

function eliminar_archivo($ruta) {
    return is_file($ruta) && unlink($ruta);
}
