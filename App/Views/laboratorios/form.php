<?= $this->extend('layouts/default') ?> <!-- Assuming a default layout file -->

<?= $this->section('content') ?>

<?php
    $is_edit = isset($laboratorio) && !empty($laboratorio) && isset($laboratorio['id_lab']);
    $form_action = $is_edit ? site_url('laboratorios/update/' . $laboratorio['id_lab']) : site_url('laboratorios/create');

    $errors = $errors ?? session()->getFlashdata('errors') ?? [];
    $old_input = $old_input ?? session()->getFlashdata('old_input') ?? [];

    // Function to get old value or laboratorio value
    function getLabValue($field_name, $lab_data, $old_input_data) {
        if (isset($old_input_data[$field_name])) {
            return esc($old_input_data[$field_name]);
        } elseif (isset($lab_data[$field_name])) {
            return esc($lab_data[$field_name]);
        }
        return '';
    }
?>

<h2><?= $is_edit ? 'Edit Laboratorio' : 'Create New Laboratorio' ?></h2>

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
        <label for="nombre_lab">Nombre del Laboratorio</label>
        <input type="text" name="nombre_lab" id="nombre_lab" class="form-control"
               value="<?= getLabValue('nombre_lab', $laboratorio ?? [], $old_input) ?>" required>
    </div>

    <div class="form-group">
        <label for="descripcion_lab">Descripción</label>
        <textarea name="descripcion_lab" id="descripcion_lab" class="form-control" rows="3"><?= getLabValue('descripcion_lab', $laboratorio ?? [], $old_input) ?></textarea>
    </div>

    <div class="form-group">
        <label for="siglas_lab">Siglas</label>
        <input type="text" name="siglas_lab" id="siglas_lab" class="form-control"
               value="<?= getLabValue('siglas_lab', $laboratorio ?? [], $old_input) ?>">
    </div>

    <div class="form-group">
        <label for="tipo_lab">Tipo de Laboratorio</label>
        <input type="text" name="tipo_lab" id="tipo_lab" class="form-control"
               value="<?= getLabValue('tipo_lab', $laboratorio ?? [], $old_input) ?>">
    </div>

    <div class="form-group">
        <label for="facultad_lab">Facultad</label>
        <input type="text" name="facultad_lab" id="facultad_lab" class="form-control"
               value="<?= getLabValue('facultad_lab', $laboratorio ?? [], $old_input) ?>">
    </div>

    <div class="form-group">
        <label for="ubicacion_lab">Ubicación</label>
        <input type="text" name="ubicacion_lab" id="ubicacion_lab" class="form-control"
               value="<?= getLabValue('ubicacion_lab', $laboratorio ?? [], $old_input) ?>">
    </div>

    <div class="form-group">
        <label for="paralelo_guia">Paralelo Guía</label>
        <input type="text" name="paralelo_guia" id="paralelo_guia" class="form-control"
               value="<?= getLabValue('paralelo_guia', $laboratorio ?? [], $old_input) ?>">
    </div>

    <div class="form-group">
        <label for="color_lab">Color Identificativo</label>
        <input type="color" name="color_lab" id="color_lab" class="form-control" style="height: 40px;"
               value="<?= getLabValue('color_lab', $laboratorio ?? [], $old_input) ?: '#ffffff' ?>">
    </div>

    <div class="form-group">
        <label for="fotografia1_lab">Fotografía 1 (URL/Path)</label>
        <input type="text" name="fotografia1_lab" id="fotografia1_lab" class="form-control"
               value="<?= getLabValue('fotografia1_lab', $laboratorio ?? [], $old_input) ?>">
    </div>

    <div class="form-group">
        <label for="fotografia2_lab">Fotografía 2 (URL/Path)</label>
        <input type="text" name="fotografia2_lab" id="fotografia2_lab" class="form-control"
               value="<?= getLabValue('fotografia2_lab', $laboratorio ?? [], $old_input) ?>">
    </div>

    <div class="form-group">
        <label for="fk_docente_responsable_lab">Docente Responsable</label>
        <select name="fk_docente_responsable_lab" id="fk_docente_responsable_lab" class="form-control" required>
            <option value="">Seleccione un Docente</option>
            <?php if (!empty($docentes)): ?>
                <?php foreach ($docentes as $docente_item): ?>
                    <?php
                        $docente_display = trim(esc($docente_item['nombre_doc'] . ' ' . $docente_item['primer_apellido_doc'] . ' ' . ($docente_item['segundo_apellido_doc'] ?? '')));
                        $docente_display .= ' (C.I: ' . esc($docente_item['cedula_doc']) . ')';
                    ?>
                    <option value="<?= esc($docente_item['id_doc']) ?>"
                        <?= (getLabValue('fk_docente_responsable_lab', $laboratorio ?? [], $old_input) == $docente_item['id_doc']) ? 'selected' : '' ?>>
                        <?= $docente_display ?>
                    </option>
                <?php endforeach; ?>
            <?php endif; ?>
        </select>
    </div>

    <div class="form-group">
        <label for="fk_administrativo_responsable_lab">ID Administrativo Responsable</label>
        <input type="number" name="fk_administrativo_responsable_lab" id="fk_administrativo_responsable_lab" class="form-control"
               value="<?= getLabValue('fk_administrativo_responsable_lab', $laboratorio ?? [], $old_input) ?>" required>
        <!-- Consider fetching admins for a dropdown too, similar to docentes -->
    </div>

    <div class="form-group">
        <label for="fk_administrativo_responsable_secundario_lab">ID Administrativo Responsable Secundario (Opcional)</label>
        <input type="number" name="fk_administrativo_responsable_secundario_lab" id="fk_administrativo_responsable_secundario_lab" class="form-control"
               value="<?= getLabValue('fk_administrativo_responsable_secundario_lab', $laboratorio ?? [], $old_input) ?>">
    </div>

    <button type="submit" class="btn btn-primary"><?= $is_edit ? 'Update' : 'Create' ?> Laboratorio</button>
    <a href="<?= site_url('laboratorios') ?>" class="btn btn-secondary">Cancel</a>

<?= form_close() ?>

<?= $this->endSection() ?>
