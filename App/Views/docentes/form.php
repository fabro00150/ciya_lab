<?= $this->extend('layouts/default') ?> <!-- Assuming a default layout file -->

<?= $this->section('content') ?>

<?php
    $is_edit = isset($docente) && !empty($docente) && isset($docente['id_doc']);
    $form_action = $is_edit ? site_url('docentes/update/' . $docente['id_doc']) : site_url('docentes/create');

    // Retrieve errors and old input from session flashdata if available
    // This is useful when redirecting back with validation errors
    $errors = $errors ?? session()->getFlashdata('errors') ?? [];
    $old_input = $old_input ?? session()->getFlashdata('old_input') ?? [];

    // Function to get old value or docente value
    // Priority: old input (if validation failed), then existing docente data (for edit), then empty
    function getValue($field_name, $docente_data, $old_input_data) {
        if (isset($old_input_data[$field_name])) {
            return esc($old_input_data[$field_name]);
        } elseif (isset($docente_data[$field_name])) {
            return esc($docente_data[$field_name]);
        }
        return '';
    }
?>

<h2><?= $is_edit ? 'Edit Docente' : 'Create New Docente' ?></h2>

<?php if (!empty($errors)): ?>
    <div class="alert alert-danger">
        <p><strong>Please correct the following errors:</strong></p>
        <ul>
            <?php foreach ($errors as $error): ?>
                <li><?= esc($error) ?></li>
            <?php endforeach; ?>
        </ul>
    </div>
<?php endif; ?>

<?= form_open($form_action) ?>
    <?= csrf_field() ?>

    <div class="form-group">
        <label for="cedula_doc">Cédula</label>
        <input type="text" name="cedula_doc" id="cedula_doc" class="form-control"
               value="<?= getValue('cedula_doc', $docente ?? [], $old_input) ?>" required>
    </div>

    <div class="form-group">
        <label for="nombre_doc">Nombres</label>
        <input type="text" name="nombre_doc" id="nombre_doc" class="form-control"
               value="<?= getValue('nombre_doc', $docente ?? [], $old_input) ?>" required>
    </div>

    <div class="form-group">
        <label for="primer_apellido_doc">Primer Apellido</label>
        <input type="text" name="primer_apellido_doc" id="primer_apellido_doc" class="form-control"
               value="<?= getValue('primer_apellido_doc', $docente ?? [], $old_input) ?>" required>
    </div>

    <div class="form-group">
        <label for="segundo_apellido_doc">Segundo Apellido</label>
        <input type="text" name="segundo_apellido_doc" id="segundo_apellido_doc" class="form-control"
               value="<?= getValue('segundo_apellido_doc', $docente ?? [], $old_input) ?>">
    </div>

    <div class="form-group">
        <label for="email_doc">Email</label>
        <input type="email" name="email_doc" id="email_doc" class="form-control"
               value="<?= getValue('email_doc', $docente ?? [], $old_input) ?>" required>
    </div>

    <div class="form-group">
        <label for="sexo_doc">Sexo</label>
        <input type="text" name="sexo_doc" id="sexo_doc" class="form-control"
               value="<?= getValue('sexo_doc', $docente ?? [], $old_input) ?>">
        <!-- Consider using a select dropdown for predefined values e.g., 'Masculino', 'Femenino' -->
    </div>

    <div class="form-group">
        <label for="abreviatura_titulo_doc">Abreviatura Título</label>
        <input type="text" name="abreviatura_titulo_doc" id="abreviatura_titulo_doc" class="form-control"
               value="<?= getValue('abreviatura_titulo_doc', $docente ?? [], $old_input) ?>">
    </div>

    <div class="form-group">
        <label for="fotografia_doc">Fotografía (URL/Path)</label>
        <input type="text" name="fotografia_doc" id="fotografia_doc" class="form-control"
               value="<?= getValue('fotografia_doc', $docente ?? [], $old_input) ?>">
        <!-- For actual file upload, use type="file" and form_open_multipart() -->
    </div>

    <div class="form-group">
        <label for="perfil_profesional_doc">Perfil Profesional</label>
        <textarea name="perfil_profesional_doc" id="perfil_profesional_doc" class="form-control" rows="3"><?= getValue('perfil_profesional_doc', $docente ?? [], $old_input) ?></textarea>
    </div>

    <div class="form-group">
        <label for="telefono_doc">Teléfono</label>
        <input type="text" name="telefono_doc" id="telefono_doc" class="form-control"
               value="<?= getValue('telefono_doc', $docente ?? [], $old_input) ?>">
    </div>

    <div class="form-group">
        <label for="oficina_doc">Oficina</label>
        <input type="text" name="oficina_doc" id="oficina_doc" class="form-control"
               value="<?= getValue('oficina_doc', $docente ?? [], $old_input) ?>">
    </div>

    <div class="form-group">
        <label for="facebook_doc">Facebook URL</label>
        <input type="url" name="facebook_doc" id="facebook_doc" class="form-control"
               value="<?= getValue('facebook_doc', $docente ?? [], $old_input) ?>">
    </div>

    <div class="form-group">
        <label for="twitter_doc">Twitter Handle (X)</label>
        <input type="text" name="twitter_doc" id="twitter_doc" class="form-control"
               value="<?= getValue('twitter_doc', $docente ?? [], $old_input) ?>">
    </div>

    <div class="form-group">
        <label for="linkedin_doc">LinkedIn URL</label>
        <input type="url" name="linkedin_doc" id="linkedin_doc" class="form-control"
               value="<?= getValue('linkedin_doc', $docente ?? [], $old_input) ?>">
    </div>

    <div class="form-group">
        <label for="pagina_web_doc">Página Web</label>
        <input type="url" name="pagina_web_doc" id="pagina_web_doc" class="form-control"
               value="<?= getValue('pagina_web_doc', $docente ?? [], $old_input) ?>">
    </div>

    <div class="form-group">
        <label for="fk_id_car">ID Carrera (FK)</label>
        <input type="number" name="fk_id_car" id="fk_id_car" class="form-control"
               value="<?= getValue('fk_id_car', $docente ?? [], $old_input) ?>" required>
    </div>

    <div class="form-group">
        <label for="fk_id_usu">ID Usuario (FK)</label>
        <input type="number" name="fk_id_usu" id="fk_id_usu" class="form-control"
               value="<?= getValue('fk_id_usu', $docente ?? [], $old_input) ?>" required>
    </div>

    <!--
        Audit fields (usuario_creacion_doc, usuario_actualizacion_doc) are typically set in the controller
        based on the logged-in user and should not be directly editable by the user in the form.
        If they were to be shown (e.g., for admin debugging), they would be read-only.
        For this form, we omit them as per standard practice.
    -->

    <button type="submit" class="btn btn-primary"><?= $is_edit ? 'Update' : 'Create' ?> Docente</button>
    <a href="<?= site_url('docentes') ?>" class="btn btn-secondary">Cancel</a>

<?= form_close() ?>

<?= $this->endSection() ?>
