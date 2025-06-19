<?= $this->extend('base') ?> <!-- Changed from layouts/default -->

<?= $this->section('title') ?>
    <?php
        $is_edit_form = isset($docente) && !empty($docente) && isset($docente['id_doc']);
        echo $is_edit_form ? 'Editar Docente' : 'Crear Nuevo Docente';
    ?>
<?= $this->endSection() ?>

<?= $this->section('content') ?>

<?php
    $is_edit = isset($docente) && !empty($docente) && isset($docente['id_doc']);
    // Form action now points to docente/guardar for both create and edit
    $form_action = site_url('docente/guardar');

    $current_errors = session()->getFlashdata('errors') ?? $errors ?? []; // errors can be passed directly too
    $old_input_data = session()->getFlashdata('old_input') ?? [];

    // Function to get old value or docente value
    function getFormValue($field_name, $docente_data, $old_data) {
        if (!empty($old_data) && isset($old_data[$field_name])) {
            return esc($old_data[$field_name]);
        } elseif (isset($docente_data[$field_name])) {
            return esc($docente_data[$field_name]);
        }
        return '';
    }
?>

<h2><?= $is_edit ? 'Editar Docente' : 'Crear Nuevo Docente' ?></h2>

<?php if (!empty($current_errors)): ?>
    <div class="alert alert-danger">
        <p><strong>Por favor corrija los siguientes errores:</strong></p>
        <ul>
            <?php foreach ($current_errors as $error): ?>
                <li><?= esc($error) ?></li>
            <?php endforeach; ?>
        </ul>
    </div>
<?php endif; ?>

<?= form_open($form_action) ?>
    <?= csrf_field() ?>

    <?php if ($is_edit): ?>
        <input type="hidden" name="id_doc" value="<?= esc($docente['id_doc']) ?>">
    <?php endif; ?>

    <div class="form-group mb-3">
        <label for="cedula_doc" class="form-label">Cédula</label>
        <input type="text" name="cedula_doc" id="cedula_doc" class="form-control"
               value="<?= getFormValue('cedula_doc', $docente ?? [], $old_input_data) ?>" required>
    </div>

    <div class="form-group mb-3">
        <label for="nombre_doc" class="form-label">Nombres</label>
        <input type="text" name="nombre_doc" id="nombre_doc" class="form-control"
               value="<?= getFormValue('nombre_doc', $docente ?? [], $old_input_data) ?>" required>
    </div>

    <div class="form-group mb-3">
        <label for="primer_apellido_doc" class="form-label">Primer Apellido</label>
        <input type="text" name="primer_apellido_doc" id="primer_apellido_doc" class="form-control"
               value="<?= getFormValue('primer_apellido_doc', $docente ?? [], $old_input_data) ?>" required>
    </div>

    <div class="form-group mb-3">
        <label for="segundo_apellido_doc" class="form-label">Segundo Apellido</label>
        <input type="text" name="segundo_apellido_doc" id="segundo_apellido_doc" class="form-control"
               value="<?= getFormValue('segundo_apellido_doc', $docente ?? [], $old_input_data) ?>">
    </div>

    <div class="form-group mb-3">
        <label for="email_doc" class="form-label">Email</label>
        <input type="email" name="email_doc" id="email_doc" class="form-control"
               value="<?= getFormValue('email_doc', $docente ?? [], $old_input_data) ?>" required>
    </div>

    <div class="form-group mb-3">
        <label for="sexo_doc" class="form-label">Sexo</label>
        <input type="text" name="sexo_doc" id="sexo_doc" class="form-control"
               value="<?= getFormValue('sexo_doc', $docente ?? [], $old_input_data) ?>">
    </div>

    <div class="form-group mb-3">
        <label for="abreviatura_titulo_doc" class="form-label">Abreviatura Título</label>
        <input type="text" name="abreviatura_titulo_doc" id="abreviatura_titulo_doc" class="form-control"
               value="<?= getFormValue('abreviatura_titulo_doc', $docente ?? [], $old_input_data) ?>">
    </div>

    <div class="form-group mb-3">
        <label for="fotografia_doc" class="form-label">Fotografía (URL/Path)</label>
        <input type="text" name="fotografia_doc" id="fotografia_doc" class="form-control"
               value="<?= getFormValue('fotografia_doc', $docente ?? [], $old_input_data) ?>">
    </div>

    <div class="form-group mb-3">
        <label for="perfil_profesional_doc" class="form-label">Perfil Profesional</label>
        <textarea name="perfil_profesional_doc" id="perfil_profesional_doc" class="form-control" rows="3"><?= getFormValue('perfil_profesional_doc', $docente ?? [], $old_input_data) ?></textarea>
    </div>

    <div class="form-group mb-3">
        <label for="telefono_doc" class="form-label">Teléfono</label>
        <input type="text" name="telefono_doc" id="telefono_doc" class="form-control"
               value="<?= getFormValue('telefono_doc', $docente ?? [], $old_input_data) ?>">
    </div>

    <div class="form-group mb-3">
        <label for="oficina_doc" class="form-label">Oficina</label>
        <input type="text" name="oficina_doc" id="oficina_doc" class="form-control"
               value="<?= getFormValue('oficina_doc', $docente ?? [], $old_input_data) ?>">
    </div>

    <div class="form-group mb-3">
        <label for="facebook_doc" class="form-label">Facebook URL</label>
        <input type="url" name="facebook_doc" id="facebook_doc" class="form-control"
               value="<?= getFormValue('facebook_doc', $docente ?? [], $old_input_data) ?>">
    </div>

    <div class="form-group mb-3">
        <label for="twitter_doc" class="form-label">Twitter Handle (X)</label>
        <input type="text" name="twitter_doc" id="twitter_doc" class="form-control"
               value="<?= getFormValue('twitter_doc', $docente ?? [], $old_input_data) ?>">
    </div>

    <div class="form-group mb-3">
        <label for="linkedin_doc" class="form-label">LinkedIn URL</label>
        <input type="url" name="linkedin_doc" id="linkedin_doc" class="form-control"
               value="<?= getFormValue('linkedin_doc', $docente ?? [], $old_input_data) ?>">
    </div>

    <div class="form-group mb-3">
        <label for="pagina_web_doc" class="form-label">Página Web</label>
        <input type="url" name="pagina_web_doc" id="pagina_web_doc" class="form-control"
               value="<?= getFormValue('pagina_web_doc', $docente ?? [], $old_input_data) ?>">
    </div>

    <div class="form-group mb-3">
        <label for="fk_id_car" class="form-label">ID Carrera (FK)</label>
        <input type="number" name="fk_id_car" id="fk_id_car" class="form-control"
               value="<?= getFormValue('fk_id_car', $docente ?? [], $old_input_data) ?>" required>
    </div>

    <div class="form-group mb-3">
        <label for="fk_id_usu" class="form-label">ID Usuario (FK)</label>
        <input type="number" name="fk_id_usu" id="fk_id_usu" class="form-control"
               value="<?= getFormValue('fk_id_usu', $docente ?? [], $old_input_data) ?>" required>
    </div>

    <button type="submit" class="btn btn-primary"><?= $is_edit ? 'Actualizar' : 'Guardar' ?> Docente</button>
    <a href="<?= site_url('docente') ?>" class="btn btn-secondary">Cancelar</a>

<?= form_close() ?>

<?= $this->endSection() ?>
