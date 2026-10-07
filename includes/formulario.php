    <form action="procesar.php" method="post" enctype="multipart/form-data" class="bg-white border rounded p-4">
        <input type="hidden" name="csrf" value="<?= e($_SESSION['csrf']) ?>">
        <input type="hidden" name="MAX_FILE_SIZE" value="2097152">
        <div class="row g-3">
        <div class="col-md-6"><label class="form-label" for="nombre">Nombre</label>
        <input class="form-control" type="text" id="nombre" name="nombre" placeholder="Ej.: Sofía" maxlength="80" autocomplete="given-name" value="<?= e($datos['nombre'] ?? '') ?>" required></div>
        <div class="col-md-6"><label class="form-label" for="apellido">Apellido</label>
        <input class="form-control" type="text" id="apellido" name="apellido" placeholder="Ej.: Pérez" maxlength="80" autocomplete="family-name" value="<?= e($datos['apellido'] ?? '') ?>" required></div>
        <div class="col-md-6"><label class="form-label" for="identificacion">Identificación</label>
        <input class="form-control" type="text" id="identificacion" name="identificacion" placeholder="Ej.: 8-123-456" maxlength="30" value="<?= e($datos['identificacion'] ?? '') ?>" required></div>
        <div class="col-md-6"><label class="form-label" for="nacimiento">Fecha de nacimiento</label>
        <input class="form-control" type="date" id="nacimiento" name="nacimiento" aria-describedby="fecha-ayuda" value="<?= e($datos['nacimiento'] ?? '') ?>" required>
        <div id="fecha-ayuda" class="form-text">Selecciona día, mes y año.</div></div>
        <fieldset class="col-12"><legend class="fs-6">Sexo</legend>
        <?php foreach (['Hombre', 'Mujer'] as $sexo): ?>
        <div class="form-check form-check-inline"><input class="form-check-input" type="radio" name="sexo" id="<?= e($sexo) ?>" value="<?= e($sexo) ?>" <?= ($datos['sexo'] ?? '') === $sexo ? 'checked' : '' ?> required><label class="form-check-label" for="<?= e($sexo) ?>"><?= e($sexo) ?></label></div>
        <?php endforeach; ?></fieldset>
        <div class="col-12"><label class="form-label" for="foto">Fotografía del aspirante</label>
        <input class="form-control" type="file" id="foto" name="foto" accept=".jpg,.jpeg,.png,.gif,.webp" aria-describedby="foto-ayuda" required>
        <div class="form-text" id="foto-ayuda">JPG, JPEG, PNG, GIF o WEBP. Máximo 2 MiB. Si hay errores, selecciona nuevamente la foto.</div></div>
        <div class="col-12"><button class="btn btn-primary" type="submit">Registrar aspirante</button></div>
        </div>
    </form>
