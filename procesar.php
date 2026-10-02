<?php
require_once __DIR__ . '/includes/funciones.php';
// GET muestra únicamente el resultado guardado en la sesión tras un POST válido.
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $errores = [];
    $datos = [];
    foreach (['nombre', 'apellido', 'identificacion', 'nacimiento', 'sexo'] as $campo) {
        $datos[$campo] = campo($campo);
        if ($datos[$campo] === '') { $errores[] = 'El campo ' . $campo . ' es obligatorio.'; }
    }
    $token = $_POST['csrf'] ?? null;
    if (!is_string($token) || !hash_equals($_SESSION['csrf'], $token)) {
        $errores[] = 'La sesión del formulario no es válida. Intenta nuevamente.';
    }
    foreach (['nombre', 'apellido'] as $campo) {
        if (mb_strlen($datos[$campo], 'UTF-8') > 80 ||
            ($datos[$campo] !== '' && !preg_match("/^[\p{L}\p{M}][\p{L}\p{M} '\x{2019}-]*$/u", $datos[$campo]))) {
            $errores[] = 'Revisa ' . $campo . ': usa letras, espacios, guiones o apóstrofos, hasta 80 caracteres.';
        }
        $datos[$campo] = nombreTitulo($datos[$campo]);
    }
    $datos['identificacion'] = strtoupper($datos['identificacion']);
    if ($datos['identificacion'] !== '' && !preg_match('/^[A-Z0-9-]{3,30}$/D', $datos['identificacion'])) {
        $errores[] = 'La identificación debe tener de 3 a 30 caracteres: letras, números o guiones.';
    }
    $edad = edadDesdeFecha($datos['nacimiento']);
    if ($edad === null || $edad < 18 || $edad > 70) {
        $errores[] = 'Ingresa una fecha de nacimiento válida. La edad debe estar entre 18 y 70 años.';
    }
    if (!in_array($datos['sexo'], ['Hombre', 'Mujer'], true)) { $errores[] = 'Selecciona un sexo válido.'; }

    // Se verifica la extensión y el contenido real, no el MIME enviado por el navegador.
    $foto = $_FILES['foto'] ?? null;
    $extension = '';
    if (!is_array($foto) || !isset($foto['error']) || !is_int($foto['error']) || $foto['error'] !== UPLOAD_ERR_OK) {
        $errores[] = 'Adjunta una foto válida de hasta 2 MiB. La carga pudo superar el límite del servidor.';
    } elseif (!is_string($foto['name'] ?? null) || !is_string($foto['tmp_name'] ?? null) ||
              !is_uploaded_file($foto['tmp_name'])) {
        $errores[] = 'La carga de la fotografía no es válida.';
    } else {
        $extension = strtolower(pathinfo(basename($foto['name']), PATHINFO_EXTENSION));
        $permitidos = ['jpg' => 'image/jpeg', 'jpeg' => 'image/jpeg', 'png' => 'image/png',
                      'gif' => 'image/gif', 'webp' => 'image/webp'];
        $tamano = filesize($foto['tmp_name']);
        if ($tamano === false || $tamano < 1 || $tamano > 2 * 1024 * 1024) {
            $errores[] = 'La foto debe pesar entre 1 byte y 2 MiB.';
        } else {
            $mime = (new finfo(FILEINFO_MIME_TYPE))->file($foto['tmp_name']);
            $imagen = @getimagesize($foto['tmp_name']);
            if (!isset($permitidos[$extension]) || $permitidos[$extension] !== $mime ||
                $imagen === false || ($imagen['mime'] ?? '') !== $mime) {
                $errores[] = 'El archivo debe ser una imagen JPG, JPEG, PNG, GIF o WEBP auténtica.';
            } elseif ($imagen[0] > 8000 || $imagen[1] > 8000) {
                $errores[] = 'La foto no puede superar 8000 píxeles por lado.';
            }
        }
    }
    if (!$errores) {
        // Un nombre aleatorio evita colisiones y no revela el nombre original.
        $nombreFoto = bin2hex(random_bytes(16)) . '.' . $extension;
        $destino = __DIR__ . '/uploaded_files/' . $nombreFoto;
        if (!is_writable(__DIR__ . '/uploaded_files') || !move_uploaded_file($foto['tmp_name'], $destino)) {
            $errores[] = 'No se pudo guardar la foto. Verifica los permisos de uploaded_files.';
        }
    }
    if ($errores) {
        $_SESSION['errores'] = $errores;
        $_SESSION['datos'] = $datos;
        header('Location: index.php', true, 303);
        exit;
    }
    $datos['edad'] = $edad;
    $_SESSION['resultado'] = $datos;
    $_SESSION['csrf'] = bin2hex(random_bytes(32));
    // Redirección POST/Redirect/GET: actualizar la página no vuelve a subir la foto.
    header('Location: procesar.php', true, 303);
    exit;
}
if ($_SERVER['REQUEST_METHOD'] !== 'GET') {
    header('Allow: GET, POST'); http_response_code(405); exit('Método no permitido.');
}
$resultado = $_SESSION['resultado'] ?? null;
if (!$resultado) { header('Location: index.php', true, 303); exit; }
$titulo = 'Resultado del registro';
include __DIR__ . '/includes/header.php';
?>
<main class="container py-5" style="max-width: 820px;">
<section aria-labelledby="titulo">
    <h1 id="titulo" class="h2">Registro procesado</h1>
    <p class="alert alert-success">Los datos son válidos y la fotografía se guardó correctamente.</p>
    <dl class="row bg-white border rounded p-4">
    <?php foreach (['nombre'=>'Nombre', 'apellido'=>'Apellido', 'identificacion'=>'Identificación', 'nacimiento'=>'Fecha de nacimiento', 'sexo'=>'Sexo', 'edad'=>'Edad'] as $clave=>$etiqueta): ?>
        <dt class="col-sm-5"><?= e($etiqueta) ?></dt><dd class="col-sm-7"><?= e((string)$resultado[$clave]) ?><?= $clave === 'edad' ? ' años' : '' ?></dd>
    <?php endforeach; ?>
    </dl>
    <p class="text-secondary">La fotografía está protegida contra el acceso directo desde el navegador. Los datos de texto se conservan solo durante la sesión.</p>
    <a href="index.php" class="btn btn-primary">Registrar otro aspirante</a>
</section>
</main>
<?php include __DIR__ . '/includes/footer.php'; ?>
