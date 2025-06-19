<?= $this->extend('base') ?> <!-- Changed from layouts/default -->

<?= $this->section('title') ?>
    <?php
        $is_edit_form = isset($reserva) && !empty($reserva) && isset($reserva['id_res']);
        echo $is_edit_form ? 'Editar Reserva' : 'Crear Nueva Reserva';
    ?>
<?= $this->endSection() ?>

<?= $this->section('content') ?>

<?php
    helper('form'); // Ensure form helper is loaded if not globally
    $is_edit = isset($reserva) && !empty($reserva) && isset($reserva['id_res']);
    $form_action = site_url('reserva/guardar');

    // $errors variable is expected to be passed from the controller directly to the view on validation failure
    // $old_input variable is also expected to be passed from the controller
    $current_errors = $errors ?? session()->getFlashdata('errors') ?? []; // Prefer direct pass, fallback to session
    $input_data = $old_input ?? ($is_edit && !empty($reserva) ? $reserva : []);


    // Function to get value: old input > existing reserva data (for edit) > default
    function getReservaFormValue($field_name, $current_reserva_data, $old_input_data, $default_value = '') {
        // Priority to old_input_data if it exists (form submission failed)
        if (!empty($old_input_data) && isset($old_input_data[$field_name])) {
            return esc($old_input_data[$field_name]);
        }
        // Then to current_reserva_data if editing an existing record
        if (!empty($current_reserva_data) && isset($current_reserva_data[$field_name])) {
            return esc($current_reserva_data[$field_name]);
        }
        // Finally, the default value
        return esc($default_value);
    }
?>

<div class="container mt-4">
    <h2><?= $is_edit ? 'Editar Reserva' : 'Crear Nueva Reserva' ?></h2>

    <?php if (!empty($current_errors)): ?>
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <p><strong>Por favor corrija los siguientes errores:</strong></p>
            <ul>
                <?php foreach ($current_errors as $field => $error_message): ?>
                    <li><?= esc($error_message) ?></li>
                <?php endforeach; ?>
            </ul>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endif; ?>

    <?php if (session()->getFlashdata('error')): // General error not from validation ?>
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <?= session()->getFlashdata('error') ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    <?php endif; ?>


    <?= form_open($form_action) ?>
        <?= csrf_field() ?>

        <?php if ($is_edit): ?>
            <input type="hidden" name="id_res" value="<?= esc($reserva['id_res']) ?>">
        <?php endif; ?>

        <div class="row">
            <div class="col-md-6 mb-3">
                <label for="fk_id_lab" class="form-label">Laboratorio (*)</label>
                <select name="fk_id_lab" id="fk_id_lab" class="form-select <?= isset($current_errors['fk_id_lab']) ? 'is-invalid' : '' ?>" required>
                    <option value="">Seleccione un Laboratorio</option>
                    <?php if (!empty($laboratorios)): ?>
                        <?php foreach ($laboratorios as $lab): ?>
                            <option value="<?= esc($lab['id_lab']) ?>"
                                <?= (getReservaFormValue('fk_id_lab', $reserva ?? [], $input_data) == $lab['id_lab']) ? 'selected' : '' ?>>
                                <?= esc($lab['nombre_lab']) ?> (<?= esc($lab['siglas_lab'] ?? $lab['id_lab'])?>)
                            </option>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </select>
                <?php if(isset($current_errors['fk_id_lab'])): ?><div class="invalid-feedback"><?= esc($current_errors['fk_id_lab']) ?></div><?php endif; ?>
            </div>
            <div class="col-md-6 mb-3">
                <label for="fk_id_doc" class="form-label">Docente Solicitante (*)</label>
                <select name="fk_id_doc" id="fk_id_doc" class="form-select <?= isset($current_errors['fk_id_doc']) ? 'is-invalid' : '' ?>" required>
                    <option value="">Seleccione un Docente</option>
                    <?php if (!empty($docentes)): ?>
                        <?php foreach ($docentes as $doc): ?>
                            <option value="<?= esc($doc['id_doc']) ?>"
                                <?= (getReservaFormValue('fk_id_doc', $reserva ?? [], $input_data) == $doc['id_doc']) ? 'selected' : '' ?>>
                                <?= esc($doc['nombre_doc'] . ' ' . $doc['primer_apellido_doc']) ?> (C.I: <?= esc($doc['cedula_doc']) ?>)
                            </option>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </select>
                 <?php if(isset($current_errors['fk_id_doc'])): ?><div class="invalid-feedback"><?= esc($current_errors['fk_id_doc']) ?></div><?php endif; ?>
            </div>
        </div>

        <div class="mb-3">
            <label for="tema_res" class="form-label">Tema de la Reserva (*)</label>
            <input type="text" name="tema_res" id="tema_res" class="form-control <?= isset($current_errors['tema_res']) ? 'is-invalid' : '' ?>"
                   value="<?= getReservaFormValue('tema_res', $reserva ?? [], $input_data) ?>" required>
            <?php if(isset($current_errors['tema_res'])): ?><div class="invalid-feedback"><?= esc($current_errors['tema_res']) ?></div><?php endif; ?>
        </div>

        <div class="row">
            <div class="col-md-4 mb-3">
                <label for="fecha_hora_res" class="form-label">Fecha y Hora de Inicio (*)</label>
                <?php
                    $fecha_hora_res_val = getReservaFormValue('fecha_hora_res', $reserva ?? [], $input_data);
                    // Ensure value is in 'Y-m-d\TH:i' format for datetime-local input
                    $formatted_fecha_hora_res = $fecha_hora_res_val ? CodeIgniter\I18n\Time::parse($fecha_hora_res_val)->format('Y-m-d\TH:i') : '';
                ?>
                <input type="datetime-local" name="fecha_hora_res" id="fecha_hora_res" class="form-control <?= isset($current_errors['fecha_hora_res']) ? 'is-invalid' : '' ?>"
                       value="<?= $formatted_fecha_hora_res ?>" required>
                <?php if(isset($current_errors['fecha_hora_res'])): ?><div class="invalid-feedback"><?= esc($current_errors['fecha_hora_res']) ?></div><?php endif; ?>
            </div>
            <div class="col-md-4 mb-3">
                <label for="duracion_res" class="form-label">Duración (en minutos) (*)</label>
                <input type="number" name="duracion_res" id="duracion_res" class="form-control <?= isset($current_errors['duracion_res']) ? 'is-invalid' : '' ?>"
                       value="<?= getReservaFormValue('duracion_res', $reserva ?? [], $input_data) ?>" required min="1">
                <?php if(isset($current_errors['duracion_res'])): ?><div class="invalid-feedback"><?= esc($current_errors['duracion_res']) ?></div><?php endif; ?>
            </div>
            <div class="col-md-4 mb-3">
                <label for="fecha_hora_fin_res_display" class="form-label">Fecha y Hora de Fin (Calculada)</label>
                <?php
                    $fecha_hora_fin_val = getReservaFormValue('fecha_hora_fin_res', $reserva ?? [], $input_data);
                    $display_fin_val = $fecha_hora_fin_val ? CodeIgniter\I18n\Time::parse($fecha_hora_fin_val)->format('Y-m-d H:i') : 'Se calculará';
                ?>
                <input type="text" id="fecha_hora_fin_res_display" class="form-control"
                       value="<?= $display_fin_val ?>" readonly>
            </div>
        </div>

        <div class="row">
            <div class="col-md-6 mb-3">
                <label for="estado_res" class="form-label">Estado de la Reserva (*)</label>
                <select name="estado_res" id="estado_res" class="form-select <?= isset($current_errors['estado_res']) ? 'is-invalid' : '' ?>" required>
                    <?php foreach ($estados_reserva ?? [] as $key_estado => $value_estado): ?>
                        <option value="<?= esc($key_estado) ?>" <?= (getReservaFormValue('estado_res', $reserva ?? [], $input_data) == $key_estado) ? 'selected' : '' ?>><?= esc($value_estado) ?></option>
                    <?php endforeach; ?>
                </select>
                <?php if(isset($current_errors['estado_res'])): ?><div class="invalid-feedback"><?= esc($current_errors['estado_res']) ?></div><?php endif; ?>
            </div>
            <div class="col-md-6 mb-3">
                <label for="numero_participantes_res" class="form-label">Número de Participantes</label>
                <input type="number" name="numero_participantes_res" id="numero_participantes_res" class="form-control <?= isset($current_errors['numero_participantes_res']) ? 'is-invalid' : '' ?>"
                       value="<?= getReservaFormValue('numero_participantes_res', $reserva ?? [], $input_data, '0') ?>" min="0">
                <?php if(isset($current_errors['numero_participantes_res'])): ?><div class="invalid-feedback"><?= esc($current_errors['numero_participantes_res']) ?></div><?php endif; ?>
            </div>
        </div>

        <div class="mb-3">
            <label for="comentario_res" class="form-label">Comentario Adicional</label>
            <textarea name="comentario_res" id="comentario_res" class="form-control" rows="2"><?= getReservaFormValue('comentario_res', $reserva ?? [], $input_data) ?></textarea>
        </div>

        <hr>
        <h5>Información Académica y Técnica Adicional</h5>

        <div class="row">
            <div class="col-md-4 mb-3">
                <label for="curso_res" class="form-label">Curso</label>
                <input type="text" name="curso_res" id="curso_res" class="form-control" value="<?= getReservaFormValue('curso_res', $reserva ?? [], $input_data) ?>">
            </div>
            <div class="col-md-4 mb-3">
                <label for="materia_res" class="form-label">Materia</label>
                <input type="text" name="materia_res" id="materia_res" class="form-control" value="<?= getReservaFormValue('materia_res', $reserva ?? [], $input_data) ?>">
            </div>
            <div class="col-md-4 mb-3">
                <label for="paralelo_res" class="form-label">Paralelo</label>
                <input type="text" name="paralelo_res" id="paralelo_res" class="form-control" value="<?= getReservaFormValue('paralelo_res', $reserva ?? [], $input_data) ?>">
            </div>
        </div>

        <div class="row">
             <div class="col-md-4 mb-3">
                <label for="fk_id_car-2" class="form-label">ID Carrera (fk_id_car-2) (*)</label>
                <input type="number" name="fk_id_car-2" id="fk_id_car-2" class="form-control <?= isset($current_errors['fk_id_car-2']) ? 'is-invalid' : '' ?>" value="<?= getReservaFormValue('fk_id_car-2', $reserva ?? [], $input_data) ?>" required>
                 <?php if(isset($current_errors['fk_id_car-2'])): ?><div class="invalid-feedback"><?= esc($current_errors['fk_id_car-2']) ?></div><?php endif; ?>
            </div>
            <div class="col-md-4 mb-3">
                <label for="fk_id_usu-2" class="form-label">ID Usuario (fk_id_usu-2) (*)</label>
                <input type="number" name="fk_id_usu-2" id="fk_id_usu-2" class="form-control <?= isset($current_errors['fk_id_usu-2']) ? 'is-invalid' : '' ?>" value="<?= getReservaFormValue('fk_id_usu-2', $reserva ?? [], $input_data) ?>" required>
                <?php if(isset($current_errors['fk_id_usu-2'])): ?><div class="invalid-feedback"><?= esc($current_errors['fk_id_usu-2']) ?></div><?php endif; ?>
            </div>
            <div class="col-md-4 mb-3">
                <label for="fk_id_tipres" class="form-label">ID Tipo Reserva</label>
                <input type="number" name="fk_id_tipres" id="fk_id_tipres" class="form-control" value="<?= getReservaFormValue('fk_id_tipres', $reserva ?? [], $input_data) ?>">
            </div>
        </div>

        <div class="row">
            <div class="col-md-6 mb-3">
                <label for="fk_id_area" class="form-label">ID Área</label>
                <input type="number" name="fk_id_area" id="fk_id_area" class="form-control" value="<?= getReservaFormValue('fk_id_area', $reserva ?? [], $input_data) ?>">
            </div>
            <div class="col-md-6 mb-3">
                <label for="fk_id_guia" class="form-label">ID Guía</label>
                <input type="number" name="fk_id_guia" id="fk_id_guia" class="form-control" value="<?= getReservaFormValue('fk_id_guia', $reserva ?? [], $input_data) ?>">
            </div>
        </div>

        <div class="mb-3">
            <label for="descripcion_participantes_res" class="form-label">Descripción de Participantes</label>
            <textarea name="descripcion_participantes_res" id="descripcion_participantes_res" class="form-control" rows="2"><?= getReservaFormValue('descripcion_participantes_res', $reserva ?? [], $input_data) ?></textarea>
        </div>

        <div class="mb-3">
            <label for="materiales_res" class="form-label">Materiales y Equipos Solicitados</label>
            <textarea name="materiales_res" id="materiales_res" class="form-control" rows="2"><?= getReservaFormValue('materiales_res', $reserva ?? [], $input_data) ?></textarea>
        </div>

        <div class="mb-3">
            <label for="software_res" class="form-label">Software Requerido</label>
            <textarea name="software_res" id="software_res" class="form-control" rows="2"><?= getReservaFormValue('software_res', $reserva ?? [], $input_data) ?></textarea>
        </div>

        <div class="row">
            <div class="col-md-6 mb-3">
                <label for="tipo_res" class="form-label">Tipo (Interna/Externa, etc.)</label>
                <input type="text" name="tipo_res" id="tipo_res" class="form-control" value="<?= getReservaFormValue('tipo_res', $reserva ?? [], $input_data) ?>">
            </div>
            <div class="col-md-6 mb-3">
                <label for="pedidodocente_res" class="form-label">Pedido por Docente</label>
                 <select name="pedidodocente_res" id="pedidodocente_res" class="form-select">
                    <option value="" <?= getReservaFormValue('pedidodocente_res', $reserva ?? [], $input_data, '') === '' ? 'selected' : '' ?>>Seleccione</option>
                    <option value="1" <?= getReservaFormValue('pedidodocente_res', $reserva ?? [], $input_data) == '1' ? 'selected' : '' ?>>Sí</option>
                    <option value="0" <?= getReservaFormValue('pedidodocente_res', $reserva ?? [], $input_data) == '0' && getReservaFormValue('pedidodocente_res', $reserva ?? [], $input_data, '') !== '' ? 'selected' : '' ?>>No</option>
                </select>
            </div>
        </div>

        <div class="mb-3">
            <label for="tipo_texto_res" class="form-label">Información Adicional (Tipo Texto)</label>
            <textarea name="tipo_texto_res" id="tipo_texto_res" class="form-control" rows="2"><?= getReservaFormValue('tipo_texto_res', $reserva ?? [], $input_data) ?></textarea>
        </div>

        <?php if ($is_edit): ?>
        <hr>
        <h5>Información Post-Reserva (Solo Edición)</h5>
        <div class="mb-3">
            <label for="observaciones_finales_res" class="form-label">Observaciones Finales</label>
            <textarea name="observaciones_finales_res" id="observaciones_finales_res" class="form-control" rows="2"><?= getReservaFormValue('observaciones_finales_res', $reserva ?? [], $input_data) ?></textarea>
        </div>
        <div class="mb-3">
            <label for="asistencia_res" class="form-label">Asistencia (ej: Lista, número)</label>
            <input type="text" name="asistencia_res" id="asistencia_res" class="form-control" value="<?= getReservaFormValue('asistencia_res', $reserva ?? [], $input_data) ?>">
        </div>
        <div class="mb-3">
            <label for="guia_adjunta_res" class="form-label">Guía Adjunta (Path/URL)</label>
            <input type="text" name="guia_adjunta_res" id="guia_adjunta_res" class="form-control" value="<?= getReservaFormValue('guia_adjunta_res', $reserva ?? [], $input_data) ?>">
        </div>
        <?php endif; ?>

        <button type="submit" class="btn btn-primary mt-3"><?= $is_edit ? 'Actualizar Reserva' : 'Crear Reserva' ?></button>
        <a href="<?= site_url('reserva') ?>" class="btn btn-secondary mt-3">Cancelar</a>

    <?= form_close() ?>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const startTimeInput = document.getElementById('fecha_hora_res');
    const durationInput = document.getElementById('duracion_res');
    const endTimeDisplay = document.getElementById('fecha_hora_fin_res_display');

    function calculateAndDisplayEndTime() {
        const startTimeValue = startTimeInput.value;
        const durationValue = parseInt(durationInput.value, 10);

        if (startTimeValue && durationValue > 0) {
            try {
                // Create a date object from the datetime-local string
                const startDate = new Date(startTimeValue.replace('T', ' '));
                if (isNaN(startDate.getTime())) { // Check if date is valid
                    endTimeDisplay.value = 'Fecha de inicio inválida';
                    return;
                }
                startDate.setMinutes(startDate.getMinutes() + durationValue);

                const year = startDate.getFullYear();
                const month = ('0' + (startDate.getMonth() + 1)).slice(-2);
                const day = ('0' + startDate.getDate()).slice(-2);
                const hours = ('0' + startDate.getHours()).slice(-2);
                const minutes = ('0' + startDate.getMinutes()).slice(-2);

                endTimeDisplay.value = `${year}-${month}-${day} ${hours}:${minutes}`;
            } catch (e) {
                endTimeDisplay.value = 'Error calculando';
            }
        } else if (startTimeValue && (!durationValue || durationValue <=0) ) {
             endTimeDisplay.value = 'Ingrese duración válida';
        } else {
            // Keep existing value if editing and not changing, or set to default
            let existingEndTime = "<?= getReservaFormValue('fecha_hora_fin_res', $reserva ?? [], $input_data) ?>";
            if (existingEndTime) {
                 existingEndTime = "<?= CodeIgniter\I18n\Time::parse(getReservaFormValue('fecha_hora_fin_res', $reserva ?? [], $input_data))->format('Y-m-d H:i') ?>";
                 endTimeDisplay.value = existingEndTime;
            } else {
                endTimeDisplay.value = 'Se calculará';
            }
        }
    }

    if(startTimeInput && durationInput && endTimeDisplay){
        startTimeInput.addEventListener('input', calculateAndDisplayEndTime);
        durationInput.addEventListener('input', calculateAndDisplayEndTime);

        // Initial calculation or display if values are pre-filled
        calculateAndDisplayEndTime();
    }
});
</script>

<?= $this->endSection() ?>
