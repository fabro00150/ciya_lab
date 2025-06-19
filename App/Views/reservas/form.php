<?= $this->extend('layouts/default') ?>

<?= $this->section('content') ?>

<?php
    $is_edit = isset($reserva) && !empty($reserva) && isset($reserva['id_res']);
    $form_action = $is_edit ? site_url('reservas/update/' . $reserva['id_res']) : site_url('reservas/create');

    $errors_session = session()->getFlashdata('errors') ?? [];
    $old_input = session()->getFlashdata('old_input') ?? ($is_edit ? $reserva : []); // If editing, use $reserva as base for old_input if no specific old_input

    // Function to get value: old input > existing reserva data (for edit) > default
    function getReservaValue($field_name, $reserva_data, $old_input_data, $default = '') {
        if (isset($old_input_data[$field_name])) {
            return esc($old_input_data[$field_name]);
        } elseif (isset($reserva_data[$field_name])) {
            return esc($reserva_data[$field_name]);
        }
        return esc($default);
    }
?>

<h2><?= $is_edit ? 'Edit Reserva' : 'Create New Reserva' ?></h2>

<?php if (!empty($errors_session)): ?>
    <div class="alert alert-danger">
        <p><strong>Please correct the following errors:</strong></p>
        <ul>
            <?php foreach ($errors_session as $error): ?>
                <li><?= esc($error) ?></li>
            <?php endforeach; ?>
        </ul>
    </div>
<?php endif; ?>
<?php if (session()->getFlashdata('error')): // General error not from validation ?>
    <div class="alert alert-danger">
        <?= session()->getFlashdata('error') ?>
    </div>
<?php endif; ?>


<?= form_open($form_action) ?>
    <?= csrf_field() ?>

    <div class="row">
        <div class="col-md-6">
            <div class="form-group">
                <label for="fk_id_lab">Laboratorio (*)</label>
                <select name="fk_id_lab" id="fk_id_lab" class="form-control" required>
                    <option value="">Seleccione un Laboratorio</option>
                    <?php if (!empty($laboratorios)): ?>
                        <?php foreach ($laboratorios as $lab): ?>
                            <option value="<?= esc($lab['id_lab']) ?>"
                                <?= (getReservaValue('fk_id_lab', $reserva ?? [], $old_input) == $lab['id_lab']) ? 'selected' : '' ?>>
                                <?= esc($lab['nombre_lab']) ?> (<?= esc($lab['siglas_lab'] ?? $lab['id_lab'])?>)
                            </option>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </select>
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-group">
                <label for="fk_id_doc">Docente Solicitante (*)</label>
                <select name="fk_id_doc" id="fk_id_doc" class="form-control" required>
                    <option value="">Seleccione un Docente</option>
                    <?php if (!empty($docentes)): ?>
                        <?php foreach ($docentes as $doc): ?>
                            <option value="<?= esc($doc['id_doc']) ?>"
                                <?= (getReservaValue('fk_id_doc', $reserva ?? [], $old_input) == $doc['id_doc']) ? 'selected' : '' ?>>
                                <?= esc($doc['nombre_doc'] . ' ' . $doc['primer_apellido_doc']) ?> (C.I: <?= esc($doc['cedula_doc']) ?>)
                            </option>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </select>
            </div>
        </div>
    </div>

    <div class="form-group">
        <label for="tema_res">Tema de la Reserva (*)</label>
        <input type="text" name="tema_res" id="tema_res" class="form-control"
               value="<?= getReservaValue('tema_res', $reserva ?? [], $old_input) ?>" required>
    </div>

    <div class="row">
        <div class="col-md-4">
            <div class="form-group">
                <label for="fecha_hora_res">Fecha y Hora de Inicio (*)</label>
                <input type="datetime-local" name="fecha_hora_res" id="fecha_hora_res" class="form-control"
                       value="<?= getReservaValue('fecha_hora_res', $reserva ?? [], $old_input) ? Time::parse(getReservaValue('fecha_hora_res', $reserva ?? [], $old_input))->toDateTimeLocalString() : '' ?>" required>
            </div>
        </div>
        <div class="col-md-4">
            <div class="form-group">
                <label for="duracion_res">Duración (en minutos) (*)</label>
                <input type="number" name="duracion_res" id="duracion_res" class="form-control"
                       value="<?= getReservaValue('duracion_res', $reserva ?? [], $old_input) ?>" required min="1">
            </div>
        </div>
        <div class="col-md-4">
            <div class="form-group">
                <label for="fecha_hora_fin_res">Fecha y Hora de Fin (Calculada)</label>
                <input type="text" name="fecha_hora_fin_res_display" id="fecha_hora_fin_res_display" class="form-control"
                       value="<?= getReservaValue('fecha_hora_fin_res', $reserva ?? [], $old_input) ? Time::parse(getReservaValue('fecha_hora_fin_res', $reserva ?? [], $old_input))->toLocalizedString('yyyy-MM-dd HH:mm') : 'Se calculará automáticamente' ?>" readonly>
                <!-- Actual fecha_hora_fin_res is submitted if needed, or calculated server-side -->
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-6">
            <div class="form-group">
                <label for="estado_res">Estado de la Reserva (*)</label>
                <select name="estado_res" id="estado_res" class="form-control" required>
                    <?php foreach ($estados_reserva ?? [] as $key => $value): ?>
                        <option value="<?= esc($key) ?>" <?= (getReservaValue('estado_res', $reserva ?? [], $old_input) == $key) ? 'selected' : '' ?>><?= esc($value) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-group">
                <label for="numero_participantes_res">Número de Participantes</label>
                <input type="number" name="numero_participantes_res" id="numero_participantes_res" class="form-control"
                       value="<?= getReservaValue('numero_participantes_res', $reserva ?? [], $old_input, '0') ?>" min="0">
            </div>
        </div>
    </div>

    <div class="form-group">
        <label for="comentario_res">Comentario Adicional</label>
        <textarea name="comentario_res" id="comentario_res" class="form-control" rows="2"><?= getReservaValue('comentario_res', $reserva ?? [], $old_input) ?></textarea>
    </div>

    <hr>
    <h5>Información Académica y Técnica</h5>

    <div class="row">
        <div class="col-md-4">
            <div class="form-group">
                <label for="curso_res">Curso</label>
                <input type="text" name="curso_res" id="curso_res" class="form-control" value="<?= getReservaValue('curso_res', $reserva ?? [], $old_input) ?>">
            </div>
        </div>
        <div class="col-md-4">
            <div class="form-group">
                <label for="materia_res">Materia</label>
                <input type="text" name="materia_res" id="materia_res" class="form-control" value="<?= getReservaValue('materia_res', $reserva ?? [], $old_input) ?>">
            </div>
        </div>
        <div class="col-md-4">
            <div class="form-group">
                <label for="paralelo_res">Paralelo</label>
                <input type="text" name="paralelo_res" id="paralelo_res" class="form-control" value="<?= getReservaValue('paralelo_res', $reserva ?? [], $old_input) ?>">
            </div>
        </div>
    </div>

    <div class="row">
         <div class="col-md-4">
            <div class="form-group">
                <label for="fk_id_car-2">ID Carrera (fk_id_car-2) (*)</label>
                <input type="number" name="fk_id_car-2" id="fk_id_car-2" class="form-control" value="<?= getReservaValue('fk_id_car-2', $reserva ?? [], $old_input) ?>" required>
            </div>
        </div>
        <div class="col-md-4">
            <div class="form-group">
                <label for="fk_id_usu-2">ID Usuario (fk_id_usu-2) (*)</label>
                <input type="number" name="fk_id_usu-2" id="fk_id_usu-2" class="form-control" value="<?= getReservaValue('fk_id_usu-2', $reserva ?? [], $old_input) ?>" required>
            </div>
        </div>
        <div class="col-md-4">
            <div class="form-group">
                <label for="fk_id_tipres">ID Tipo Reserva</label>
                <input type="number" name="fk_id_tipres" id="fk_id_tipres" class="form-control" value="<?= getReservaValue('fk_id_tipres', $reserva ?? [], $old_input) ?>">
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-6">
            <div class="form-group">
                <label for="fk_id_area">ID Área</label>
                <input type="number" name="fk_id_area" id="fk_id_area" class="form-control" value="<?= getReservaValue('fk_id_area', $reserva ?? [], $old_input) ?>">
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-group">
                <label for="fk_id_guia">ID Guía</label>
                <input type="number" name="fk_id_guia" id="fk_id_guia" class="form-control" value="<?= getReservaValue('fk_id_guia', $reserva ?? [], $old_input) ?>">
            </div>
        </div>
    </div>

    <div class="form-group">
        <label for="descripcion_participantes_res">Descripción de Participantes</label>
        <textarea name="descripcion_participantes_res" id="descripcion_participantes_res" class="form-control" rows="2"><?= getReservaValue('descripcion_participantes_res', $reserva ?? [], $old_input) ?></textarea>
    </div>

    <div class="form-group">
        <label for="materiales_res">Materiales y Equipos Solicitados</label>
        <textarea name="materiales_res" id="materiales_res" class="form-control" rows="2"><?= getReservaValue('materiales_res', $reserva ?? [], $old_input) ?></textarea>
    </div>

    <div class="form-group">
        <label for="software_res">Software Requerido</label>
        <textarea name="software_res" id="software_res" class="form-control" rows="2"><?= getReservaValue('software_res', $reserva ?? [], $old_input) ?></textarea>
    </div>

    <div class="row">
        <div class="col-md-6">
            <div class="form-group">
                <label for="tipo_res">Tipo (Interna/Externa, etc.)</label>
                <input type="text" name="tipo_res" id="tipo_res" class="form-control" value="<?= getReservaValue('tipo_res', $reserva ?? [], $old_input) ?>">
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-group">
                <label for="pedidodocente_res">Pedido por Docente (Sí/No)</label>
                 <select name="pedidodocente_res" id="pedidodocente_res" class="form-control">
                    <option value="" <?= getReservaValue('pedidodocente_res', $reserva ?? [], $old_input, '') == '' ? 'selected' : '' ?>>Seleccione</option>
                    <option value="1" <?= getReservaValue('pedidodocente_res', $reserva ?? [], $old_input) == '1' ? 'selected' : '' ?>>Sí</option>
                    <option value="0" <?= getReservaValue('pedidodocente_res', $reserva ?? [], $old_input) == '0' ? 'selected' : '' ?>>No</option>
                </select>
            </div>
        </div>
    </div>

    <div class="form-group">
        <label for="tipo_texto_res">Información Adicional (Tipo Texto)</label>
        <textarea name="tipo_texto_res" id="tipo_texto_res" class="form-control" rows="2"><?= getReservaValue('tipo_texto_res', $reserva ?? [], $old_input) ?></textarea>
    </div>

    <?php if ($is_edit): // Fields typically only relevant after reservation is used ?>
    <hr>
    <h5>Información Post-Reserva (Solo Edición)</h5>
    <div class="form-group">
        <label for="observaciones_finales_res">Observaciones Finales</label>
        <textarea name="observaciones_finales_res" id="observaciones_finales_res" class="form-control" rows="2"><?= getReservaValue('observaciones_finales_res', $reserva ?? [], $old_input) ?></textarea>
    </div>
    <div class="form-group">
        <label for="asistencia_res">Asistencia (ej: Lista, número)</label>
        <input type="text" name="asistencia_res" id="asistencia_res" class="form-control" value="<?= getReservaValue('asistencia_res', $reserva ?? [], $old_input) ?>">
    </div>
    <div class="form-group">
        <label for="guia_adjunta_res">Guía Adjunta (Path/URL)</label>
        <input type="text" name="guia_adjunta_res" id="guia_adjunta_res" class="form-control" value="<?= getReservaValue('guia_adjunta_res', $reserva ?? [], $old_input) ?>">
    </div>
    <?php endif; ?>

    <button type="submit" class="btn btn-primary mt-3"><?= $is_edit ? 'Update Reserva' : 'Create Reserva' ?></button>
    <a href="<?= site_url('reservas') ?>" class="btn btn-secondary mt-3">Cancel</a>

<?= form_close() ?>

<script>
// Basic JS to update displayed end time - more robust calculation is server-side.
document.addEventListener('DOMContentLoaded', function () {
    const startTimeInput = document.getElementById('fecha_hora_res');
    const durationInput = document.getElementById('duracion_res');
    const endTimeDisplay = document.getElementById('fecha_hora_fin_res_display');

    function calculateAndDisplayEndTime() {
        const startTimeValue = startTimeInput.value;
        const durationValue = parseInt(durationInput.value, 10);

        if (startTimeValue && durationValue > 0) {
            try {
                const startDate = new Date(startTimeValue);
                startDate.setMinutes(startDate.getMinutes() + durationValue);

                // Format to YYYY-MM-DD HH:MM (manually)
                const year = startDate.getFullYear();
                const month = ('0' + (startDate.getMonth() + 1)).slice(-2);
                const day = ('0' + startDate.getDate()).slice(-2);
                const hours = ('0' + startDate.getHours()).slice(-2);
                const minutes = ('0' + startDate.getMinutes()).slice(-2);

                endTimeDisplay.value = `${year}-${month}-${day} ${hours}:${minutes}`;
            } catch (e) {
                endTimeDisplay.value = 'Error calculando';
            }
        } else if (startTimeValue && !durationValue) {
             endTimeDisplay.value = 'Ingrese duración';
        }
         else {
            endTimeDisplay.value = 'Se calculará automáticamente';
        }
    }

    if(startTimeInput && durationInput && endTimeDisplay){
        startTimeInput.addEventListener('change', calculateAndDisplayEndTime);
        durationInput.addEventListener('input', calculateAndDisplayEndTime);
        // Initial calculation if values are pre-filled (e.g. edit or validation error)
        if (startTimeInput.value && durationInput.value){
             // Only if fecha_hora_fin_res is NOT set by server (i.e. old_input didn't have it)
             if (endTimeDisplay.value === 'Se calculará automáticamente' || endTimeDisplay.value === '') {
                calculateAndDisplayEndTime();
             }
        }
    }
});
</script>

<?= $this->endSection() ?>
