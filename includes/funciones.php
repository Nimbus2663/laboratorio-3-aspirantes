<?php
// Utilidades compartidas. Todo texto se escapa al mostrarlo en HTML.
declare(strict_types=1);
date_default_timezone_set('America/Panama');
if (session_status() !== PHP_SESSION_ACTIVE) {
    session_start(['cookie_httponly' => true, 'cookie_samesite' => 'Lax',
        'cookie_secure' => !empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off']);
}
header('Cache-Control: no-store');
header('X-Content-Type-Options: nosniff');
if (empty($_SESSION['csrf'])) { $_SESSION['csrf'] = bin2hex(random_bytes(32)); }
function e(string $valor): string {
    return htmlspecialchars($valor, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
}
function campo(string $nombre): string {
    $valor = $_POST[$nombre] ?? '';
    return is_string($valor) ? trim(strip_tags($valor)) : '';
}
function nombreTitulo(string $valor): string {
    // Equivalente Unicode de ucwords(strtolower(...)): conserva ñ y tildes.
    return mb_convert_case($valor, MB_CASE_TITLE, 'UTF-8');
}
function edadDesdeFecha(string $valor, ?DateTimeImmutable $hoy = null): ?int {
    $fecha = DateTimeImmutable::createFromFormat('!Y-m-d', $valor);
    $hoy = $hoy ?? new DateTimeImmutable('today');
    if (!$fecha || $fecha->format('Y-m-d') !== $valor || $fecha > $hoy) { return null; }
    return $fecha->diff($hoy)->y;
}
