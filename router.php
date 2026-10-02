<?php
// Solo para desarrollo con php -S. Una lista cerrada impide servir fotos o fuentes.
$ruta = rawurldecode(parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH) ?? '/');
if ($ruta === '/' || $ruta === '/index.php') {
    $_SERVER['SCRIPT_NAME'] = '/index.php'; require __DIR__ . '/index.php';
} elseif ($ruta === '/procesar.php') {
    $_SERVER['SCRIPT_NAME'] = '/procesar.php'; require __DIR__ . '/procesar.php';
} else {
    http_response_code(403); echo 'Acceso denegado.';
}
