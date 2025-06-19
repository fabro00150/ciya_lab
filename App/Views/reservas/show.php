<?= $this->extend('base') ?> <!-- Changed from layouts/default -->

<?= $this->section('title') ?>
    Detalles de Reserva - ID: <?= esc($reserva['id_res'] ?? 'Desconocido') ?>
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="container mt-4">
    <h2>Detalles de la Reserva</h2>

    <?php if (!empty($reserva)): ?>
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">Reserva ID: <?= esc($reserva['id_res']) ?></h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <p><strong>Laboratorio:</strong> <?= esc($reserva['nombre_lab'] ?? $reserva['fk_id_lab']) ?> (ID: <?= esc($reserva['fk_id_lab']) ?>)</p>
                        <p><strong>Docente Solicitante:</strong>
                            <?php
                                $docente_nombre_completo_show = trim(esc($reserva['nombre_doc'] ?? '') . ' ' . esc($reserva['primer_apellido_doc'] ?? '') . ' ' . esc($reserva['segundo_apellido_doc'] ?? ''));
                                echo $docente_nombre_completo_show ?: 'N/D';
                            ?>
                            (ID: <?= esc($reserva['fk_id_doc']) ?>)
                        </p>
                        <p><strong>Tema de Reserva:</strong> <?= esc($reserva['tema_res']) ?></p>
                        <p><strong>Estado:</strong>
                            <?php
                                $estado_show = esc($reserva['estado_res']);
                                $badge_class_show = 'bg-secondary'; // Default
                                switch ($estado_show) {
                                    case 'Confirmada': $badge_class_show = 'bg-success'; break;
                                    case 'Solicitada': $badge_class_show = 'bg-warning text-dark'; break;
                                    case 'Cancelada': $badge_class_show = 'bg-danger'; break;
                                    case 'Realizada': $badge_class_show = 'bg-primary'; break;
                                    case 'Rechazada': $badge_class_show = 'bg-dark'; break;
                                    case 'En Curso': $badge_class_show = 'bg-info text-dark'; break;
                                }
                            ?>
                            <span class="badge <?= $badge_class_show ?>"><?= $estado_show ?></span>
                        </p>
                        <p><strong>Fecha/Hora Inicio:</strong> <?= esc(CodeIgniter\I18n\Time::parse($reserva['fecha_hora_res'])->toLocalizedString('dd MMMM, yyyy HH:mm a')) ?></p>
                        <p><strong>Duración:</strong> <?= esc($reserva['duracion_res']) ?> minutos</p>
                        <p><strong>Fecha/Hora Fin:</strong> <?= esc(CodeIgniter\I18n\Time::parse($reserva['fecha_hora_fin_res'])->toLocalizedString('dd MMMM, yyyy HH:mm a')) ?></p>
                    </div>
                    <div class="col-md-6">
                        <p><strong>Comentario:</strong> <?= nl2br(esc($reserva['comentario_res'] ?? 'N/D')) ?></p>
                        <p><strong>Número de Participantes:</strong> <?= esc($reserva['numero_participantes_res'] ?? 'N/D') ?></p>
                        <p><strong>Curso:</strong> <?= esc($reserva['curso_res'] ?? 'N/D') ?></p>
                        <p><strong>Materia:</strong> <?= esc($reserva['materia_res'] ?? 'N/D') ?></p>
                        <p><strong>Paralelo:</strong> <?= esc($reserva['paralelo_res'] ?? 'N/D') ?></p>
                        <p><strong>Carrera (FK ID Car-2):</strong> <?= esc($reserva['fk_id_car-2'] ?? 'N/D') ?></p>
                        <p><strong>Usuario (FK ID Usu-2):</strong> <?= esc($reserva['fk_id_usu-2'] ?? 'N/D') ?></p>
                    </div>
                </div>
            </div>
        </div>

        <div class="card mt-3">
            <div class="card-header">
                <h5 class="card-title mb-0">Información Adicional y Técnica</h5>
            </div>
            <div class="card-body">
                 <div class="row">
                    <div class="col-md-6">
                        <p><strong>Tipo Reserva (FK ID Tipres):</strong> <?= esc($reserva['fk_id_tipres'] ?? 'N/D') ?></p>
                        <p><strong>Área (FK ID Area):</strong> <?= esc($reserva['fk_id_area'] ?? 'N/D') ?></p>
                        <p><strong>Guía (FK ID Guia):</strong> <?= esc($reserva['fk_id_guia'] ?? 'N/D') ?></p>
                        <p><strong>Descripción de Participantes:</strong> <?= nl2br(esc($reserva['descripcion_participantes_res'] ?? 'N/D')) ?></p>
                        <p><strong>Materiales Solicitados:</strong> <?= nl2br(esc($reserva['materiales_res'] ?? 'N/D')) ?></p>
                    </div>
                    <div class="col-md-6">
                        <p><strong>Software Requerido:</strong> <?= nl2br(esc($reserva['software_res'] ?? 'N/D')) ?></p>
                        <p><strong>Tipo (Interna/Externa, etc.):</strong> <?= esc($reserva['tipo_res'] ?? 'N/D') ?></p>
                        <p><strong>Pedido por Docente:</strong> <?= isset($reserva['pedidodocente_res']) ? ($reserva['pedidodocente_res'] ? 'Sí' : 'No') : 'N/D' ?></p>
                        <p><strong>Tipo Texto (Info Adicional):</strong> <?= nl2br(esc($reserva['tipo_texto_res'] ?? 'N/D')) ?></p>
                    </div>
                </div>
            </div>
        </div>

        <?php if($reserva['estado_res'] == 'Realizada' || $reserva['estado_res'] == 'Cancelada' || $is_edit ): // Show post-reserva info if relevant or editing ?>
        <div class="card mt-3">
            <div class="card-header">
                <h5 class="card-title mb-0">Información Post-Reserva</h5>
            </div>
            <div class="card-body">
                <p><strong>Observaciones Finales:</strong> <?= nl2br(esc($reserva['observaciones_finales_res'] ?? 'N/D')) ?></p>
                <p><strong>Asistencia Registrada:</strong> <?= esc($reserva['asistencia_res'] ?? 'N/D') ?></p>
                <p><strong>Guía Adjunta (Path/URL):</strong>
                    <?php if(!empty($reserva['guia_adjunta_res'])): ?>
                        <a href="<?= esc($reserva['guia_adjunta_res']) ?>" target="_blank"><?= esc($reserva['guia_adjunta_res']) ?></a>
                    <?php else: ?>
                        N/D
                    <?php endif; ?>
                </p>
            </div>
        </div>
        <?php endif; ?>

        <div class="card mt-3">
            <div class="card-header">
                <h5 class="card-title mb-0">Auditoría</h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <p><strong>Usuario Creación:</strong> <?= esc($reserva['usuario_creacion_res']) ?></p>
                        <p><strong>Fecha Creación:</strong> <?= esc(CodeIgniter\I18n\Time::parse($reserva['fecha_creacion_res'])->toUserFriendlyString()) ?></p>
                    </div>
                    <div class="col-md-6">
                        <p><strong>Usuario Actualización:</strong> <?= esc($reserva['usuario_actualizacion_res']) ?></p>
                        <p><strong>Fecha Actualización:</strong> <?= esc(CodeIgniter\I18n\Time::parse($reserva['fecha_actualizacion_res'])->toUserFriendlyString()) ?></p>
                    </div>
                </div>
            </div>
        </div>

        <p class="mt-4">
            <a href="<?= site_url('reserva/editar/' . $reserva['id_res']) ?>" class="btn btn-warning"><i class="fas fa-edit"></i> Editar Reserva</a>
            <a href="<?= site_url('reserva') ?>" class="btn btn-secondary"><i class="fas fa-arrow-left"></i> Volver a la Lista</a>
        </p>

    <?php else: ?>
        <div class="alert alert-warning" role="alert">
            Reserva no encontrada.
        </div>
        <a href="<?= site_url('reserva') ?>" class="btn btn-secondary"><i class="fas fa-arrow-left"></i> Volver a la Lista</a>
    <?php endif; ?>
</div>
<?= $this->endSection() ?>
