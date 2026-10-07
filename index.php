<?php
require_once __DIR__ . '/includes/funciones.php';
$titulo = 'Registro de aspirantes';
$errores = $_SESSION['errores'] ?? [];
$datos = $_SESSION['datos'] ?? [];
unset($_SESSION['errores'], $_SESSION['datos']);
include __DIR__ . '/includes/header.php';
?>
<main class="container py-5" style="max-width: 820px;">
<section aria-labelledby="titulo">
    <h1 id="titulo" class="h2">Registro de aspirantes</h1>
    <p class="text-secondary">Completa tus datos y adjunta una fotografía. Todos los campos son obligatorios. Edad admitida: de 18 a 70 años.</p>
    <?php if ($errores): ?>
    <div class="alert alert-danger" role="alert"><h2 class="h5">Revisa los siguientes datos</h2><ul class="mb-0">
    <?php foreach ($errores as $error): ?><li><?= e($error) ?></li><?php endforeach; ?>
    </ul></div>
    <?php endif; ?>
    <?php include __DIR__ . '/includes/formulario.php'; ?>
</section>
</main>
<?php include __DIR__ . '/includes/footer.php'; ?>
