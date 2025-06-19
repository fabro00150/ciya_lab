<?= $this->extend('layouts/default') ?>

<?= $this->section('content') ?>
<h2>Reserva Details</h2>

<?php if (!empty($reserva)): ?>
    <table class="table table-bordered">
        <tr><th>ID Reserva</th><td><?= esc($reserva['id_res']) ?></td></tr>
        <tr><th>Laboratorio</th><td><?= esc($reserva['nombre_lab'] ?? $reserva['fk_id_lab']) ?> (ID: <?= esc($reserva['fk_id_lab']) ?>)</td></tr>
        <tr>
            <th>Docente Solicitante</th>
            <td>
                <?php
                    $docente_nombre = trim(esc($reserva['nombre_doc'] ?? '') . ' ' . esc($reserva['primer_apellido_doc'] ?? '') . ' ' . esc($reserva['segundo_apellido_doc'] ?? ''));
                    echo $docente_nombre ?: 'N/A';
                ?>
                (ID: <?= esc($reserva['fk_id_doc']) ?>)
            </td>
        </tr>
        <tr><th>Tema de Reserva</th><td><?= esc($reserva['tema_res']) ?></td></tr>
        <tr><th>Comentario</th><td><?= nl2br(esc($reserva['comentario_res'] ?? 'N/A')) ?></td></tr>
        <tr><th>Estado</th><td><span class="badge bg-primary"><?= esc($reserva['estado_res']) ?></span></td></tr>
        <tr><th>Fecha/Hora Inicio</th><td><?= esc(CodeIgniter\I18n\Time::parse($reserva['fecha_hora_res'])->toLocalizedString('dd MMMM, yyyy HH:mm a')) ?></td></tr>
        <tr><th>Duración</th><td><?= esc($reserva['duracion_res']) ?> minutos</td></tr>
        <tr><th>Fecha/Hora Fin</th><td><?= esc(CodeIgniter\I18n\Time::parse($reserva['fecha_hora_fin_res'])->toLocalizedString('dd MMMM, yyyy HH:mm a')) ?></td></tr>

        <tr><th>Tipo Reserva (FK ID Tipres)</th><td><?= esc($reserva['fk_id_tipres'] ?? 'N/A') ?></td></tr>
        <tr><th>Área (FK ID Area)</th><td><?= esc($reserva['fk_id_area'] ?? 'N/A') ?></td></tr>
        <tr><th>Guía (FK ID Guia)</th><td><?= esc($reserva['fk_id_guia'] ?? 'N/A') ?></td></tr>

        <tr><th>Número de Participantes</th><td><?= esc($reserva['numero_participantes_res'] ?? 'N/A') ?></td></tr>
        <tr><th>Descripción de Participantes</th><td><?= nl2br(esc($reserva['descripcion_participantes_res'] ?? 'N/A')) ?></td></tr>
        <tr><th>Materiales Solicitados</th><td><?= nl2br(esc($reserva['materiales_res'] ?? 'N/A')) ?></td></tr>

        <tr><th>Observaciones Finales (Post-Reserva)</th><td><?= nl2br(esc($reserva['observaciones_finales_res'] ?? 'N/A')) ?></td></tr>
        <tr><th>Asistencia Registrada</th><td><?= esc($reserva['asistencia_res'] ?? 'N/A') ?></td></tr>
        <tr><th>Guía Adjunta (Path/URL)</th><td><?= esc($reserva['guia_adjunta_res'] ?? 'N/A') ?></td></tr>

        <tr><th>Curso</th><td><?= esc($reserva['curso_res'] ?? 'N/A') ?></td></tr>
        <tr><th>Materia</th><td><?= esc($reserva['materia_res'] ?? 'N/A') ?></td></tr>
        <tr><th>Carrera (FK ID Car-2)</th><td><?= esc($reserva['fk_id_car-2'] ?? 'N/A') ?></td></tr>
        <tr><th>Paralelo</th><td><?= esc($reserva['paralelo_res'] ?? 'N/A') ?></td></tr>
        <tr><th>Tipo Texto (Info Adicional)</th><td><?= nl2br(esc($reserva['tipo_texto_res'] ?? 'N/A')) ?></td></tr>
        <tr><th>Usuario (FK ID Usu-2)</th><td><?= esc($reserva['fk_id_usu-2'] ?? 'N/A') ?></td></tr>
        <tr><th>Software Requerido</th><td><?= nl2br(esc($reserva['software_res'] ?? 'N/A')) ?></td></tr>
        <tr><th>Tipo (Interna/Externa, etc.)</th><td><?= esc($reserva['tipo_res'] ?? 'N/A') ?></td></tr>
        <tr><th>Pedido Docente (Booleano)</th><td><?= isset($reserva['pedidodocente_res']) ? ($reserva['pedidodocente_res'] ? 'Sí' : 'No') : 'N/A' ?></td></tr>

        <tr><th>Fecha Creación</th><td><?= esc(CodeIgniter\I18n\Time::parse($reserva['fecha_creacion_res'])->toUserFriendlyString()) ?></td></tr>
        <tr><th>Usuario Creación</th><td><?= esc($reserva['usuario_creacion_res']) ?></td></tr>
        <tr><th>Fecha Actualización</th><td><?= esc(CodeIgniter\I18n\Time::parse($reserva['fecha_actualizacion_res'])->toUserFriendlyString()) ?></td></tr>
        <tr><th>Usuario Actualización</th><td><?= esc($reserva['usuario_actualizacion_res']) ?></td></tr>
    </table>

    <p>
        <a href="<?= site_url('reservas/edit/' . $reserva['id_res']) ?>" class="btn btn-warning">Edit</a>
        <a href="<?= site_url('reservas') ?>" class="btn btn-secondary">Back to List</a>
    </p>

<?php else: ?>
    <p>Reserva not found.</p>
    <a href="<?= site_url('reservas') ?>" class="btn btn-secondary">Back to List</a>
<?php endif; ?>

<?= $this->endSection() ?>
