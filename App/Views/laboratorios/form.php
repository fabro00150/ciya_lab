<?= $this->extend('base') ?> <!-- Changed from layouts/default -->

<?= $this->section('title') ?>
    <?php
        $is_edit_form = isset($laboratorio) && !empty($laboratorio) && isset($laboratorio['id_lab']);
        echo $is_edit_form ? 'Editar Laboratorio' : 'Crear Nuevo Laboratorio';
    ?>
<?= $this->endSection() ?>

<?= $this->section('content') ?>

<?php
    $is_edit = isset($laboratorio) && !empty($laboratorio) && isset($laboratorio['id_lab']);
    $form_action = site_url('laboratorio/guardar'); // Points to the new guardar method

    $current_errors = session()->getFlashdata('errors') ?? $errors ?? [];
    $old_input_data = session()->getFlashdata('old_input') ?? [];

    // Function to get old value or laboratorio value
    function getLabFormValue($field_name, $lab_data, $old_data, $default = '') {
        if (!empty($old_data) && isset($old_data[$field_name])) {
            return esc($old_data[$field_name]);
        } elseif (isset($lab_data[$field_name])) {
            return esc($lab_data[$field_name]);
        }
        return esc($default);
    }
?>

<h2><?= $is_edit ? 'Editar Laboratorio' : 'Crear Nuevo Laboratorio' ?></h2>

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
        <input type="hidden" name="id_lab" value="<?= esc($laboratorio['id_lab']) ?>">
    <?php endif; ?>

    <div class="form-group mb-3">
        <label for="nombre_lab" class="form-label">Nombre del Laboratorio (*)</label>
        <input type="text" name="nombre_lab" id="nombre_lab" class="form-control"
               value="<?= getLabFormValue('nombre_lab', $laboratorio ?? [], $old_input_data) ?>" required>
    </div>

    <div class="form-group mb-3">
        <label for="descripcion_lab" class="form-label">Descripción</label>
        <textarea name="descripcion_lab" id="descripcion_lab" class="form-control" rows="3"><?= getLabFormValue('descripcion_lab', $laboratorio ?? [], $old_input_data) ?></textarea>
    </div>

    <div class="form-group mb-3">
        <label for="siglas_lab" class="form-label">Siglas</label>
        <input type="text" name="siglas_lab" id="siglas_lab" class="form-control"
               value="<?= getLabFormValue('siglas_lab', $laboratorio ?? [], $old_input_data) ?>">
    </div>

    <div class="form-group mb-3">
        <label for="tipo_lab" class="form-label">Tipo de Laboratorio</label>
        <input type="text" name="tipo_lab" id="tipo_lab" class="form-control"
               value="<?= getLabFormValue('tipo_lab', $laboratorio ?? [], $old_input_data) ?>">
    </div>

    <div class="form-group mb-3">
        <label for="facultad_lab" class="form-label">Facultad</label>
        <input type="text" name="facultad_lab" id="facultad_lab" class="form-control"
               value="<?= getLabFormValue('facultad_lab', $laboratorio ?? [], $old_input_data) ?>">
    </div>

    <div class="form-group mb-3">
        <label for="ubicacion_lab" class="form-label">Ubicación</label>
        <input type="text" name="ubicacion_lab" id="ubicacion_lab" class="form-control"
               value="<?= getLabFormValue('ubicacion_lab', $laboratorio ?? [], $old_input_data) ?>">
    </div>

    <div class="form-group mb-3">
        <label for="paralelo_guia" class="form-label">Paralelo Guía</label>
        <input type="text" name="paralelo_guia" id="paralelo_guia" class="form-control"
               value="<?= getLabFormValue('paralelo_guia', $laboratorio ?? [], $old_input_data) ?>">
    </div>

    <div class="form-group mb-3">
        <label for="color_lab" class="form-label">Color Identificativo</label>
        <input type="color" name="color_lab" id="color_lab" class="form-control form-control-color" <!-- Bootstrap class for color input -->
               value="<?= getLabFormValue('color_lab', $laboratorio ?? [], $old_input_data, '#ffffff') ?>">
    </div>

    <div class="form-group mb-3">
        <label for="fotografia1_lab" class="form-label">Fotografía 1 (URL/Path)</label>
        <input type="text" name="fotografia1_lab" id="fotografia1_lab" class="form-control"
               value="<?= getLabFormValue('fotografia1_lab', $laboratorio ?? [], $old_input_data) ?>">
    </div>

    <div class="form-group mb-3">
        <label for="fotografia2_lab" class="form-label">Fotografía 2 (URL/Path)</label>
        <input type="text" name="fotografia2_lab" id="fotografia2_lab" class="form-control"
               value="<?= getLabFormValue('fotografia2_lab', $laboratorio ?? [], $old_input_data) ?>">
    </div>

    <div class="form-group mb-3">
        <label for="fk_docente_responsable_lab" class="form-label">Docente Responsable (*)</label>
        <select name="fk_docente_responsable_lab" id="fk_docente_responsable_lab" class="form-control" required>
            <option value="">Seleccione un Docente</option>
            <?php if (!empty($docentes)): ?>
                <?php foreach ($docentes as $docente_item): ?>
                    <?php
                        $docente_display = trim(esc($docente_item['nombre_doc'] . ' ' . $docente_item['primer_apellido_doc'] . ' ' . ($docente_item['segundo_apellido_doc'] ?? '')));
                        $docente_display .= ' (C.I: ' . esc($docente_item['cedula_doc']) . ')';
                    ?>
                    <option value="<?= esc($docente_item['id_doc']) ?>"
                        <?= (getLabFormValue('fk_docente_responsable_lab', $laboratorio ?? [], $old_input_data) == $docente_item['id_doc']) ? 'selected' : '' ?>>
                        <?= $docente_display ?>
                    </option>
                <?php endforeach; ?>
            <?php endif; ?>
        </select>
    </div>

    <div class="form-group mb-3">
        <label for="fk_administrativo_responsable_lab" class="form-label">ID Administrativo Responsable (*)</label>
        <input type="number" name="fk_administrativo_responsable_lab" id="fk_administrativo_responsable_lab" class="form-control"
               value="<?= getLabFormValue('fk_administrativo_responsable_lab', $laboratorio ?? [], $old_input_data) ?>" required>
    </div>

    <div class="form-group mb-3">
        <label for="fk_administrativo_responsable_secundario_lab" class="form-label">ID Administrativo Responsable Secundario (Opcional)</label>
        <input type="number" name="fk_administrativo_responsable_secundario_lab" id="fk_administrativo_responsable_secundario_lab" class="form-control"
               value="<?= getLabFormValue('fk_administrativo_responsable_secundario_lab', $laboratorio ?? [], $old_input_data) ?>">
    </div>

    <button type="submit" class="btn btn-primary"><?= $is_edit ? 'Actualizar' : 'Guardar' ?> Laboratorio</button>
    <a href="<?= site_url('laboratorio') ?>" class="btn btn-secondary">Cancelar</a>

<?= form_close() ?>

<?= $this->endSection() ?>
