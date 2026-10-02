<?php
$paginaActual = basename($_SERVER['SCRIPT_NAME']);
?>
<!doctype html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Formulario académico de registro de aspirantes">
    <meta name="author" content="Ricardo Caballero">
    <meta name="robots" content="noindex, nofollow">
    <meta name="theme-color" content="#212529">
    <title><?= e($titulo ?? 'Registro de aspirantes') ?> | Portal UTP</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light d-flex flex-column min-vh-100">
<header>
    <nav class="navbar navbar-dark bg-dark" aria-label="Navegación principal">
        <div class="container"><a class="navbar-brand fw-bold" href="index.php">Portal de Aspirantes</a>
        <a class="text-white" href="index.php">Registro</a></div>
    </nav>
    <nav class="bg-white border-bottom py-3" aria-label="Migas de pan">
        <ol class="breadcrumb container mb-0">
            <li class="breadcrumb-item"><a href="index.php">Inicio</a></li>
            <?php if ($paginaActual === 'procesar.php'): ?>
            <li class="breadcrumb-item"><a href="index.php">Registro</a></li>
            <li class="breadcrumb-item active" aria-current="page">Resultado</li>
            <?php else: ?>
            <li class="breadcrumb-item active" aria-current="page">Registro de aspirante</li>
            <?php endif; ?>
        </ol>
    </nav>
</header>
