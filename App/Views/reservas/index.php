<?= $this->extend('base') ?> <!-- Changed from layouts/default -->

<?= $this->section('title') ?>
    Lista de Reservas
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="container mt-4">
    <h2>Lista de Reservas</h2>

    <?php if (session()->getFlashdata('success')): ?>
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <?= session()->getFlashdata('success') ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endif; ?>

    <?php
        $session_errors = session()->getFlashdata('errors');
        if (!empty($session_errors) && is_array($session_errors)):
    ?>
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <p><strong>Por favor corrija los siguientes errores:</strong></p>
            <ul>
                <?php foreach ($session_errors as $error): ?>
                    <li><?= esc($error) ?></li>
                <?php endforeach; ?>
            </ul>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php elseif(session()->getFlashdata('error')): ?>
         <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <?= session()->getFlashdata('error') ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endif; ?>

    <p class="my-3">
        <a href="<?= site_url('reserva/crear') ?>" class="btn btn-primary"><i class="fas fa-plus-circle"></i> Crear Nueva Reserva</a>
    </p>

    <div class="table-responsive">
        <table class="table table-bordered table-striped table-hover">
            <thead class="table-light">
                <tr>
                    <th>ID</th>
                    <th>Laboratorio</th>
                    <th>Docente Solicitante</th>
                    <th>Tema</th>
                    <th>Fecha/Hora Inicio</th>
                    <th>Fecha/Hora Fin</th>
                    <th>Estado</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($reservas) && is_array($reservas)): ?>
                    <?php foreach ($reservas as $reserva_item): ?>
                        <tr>
                            <td><?= esc($reserva_item['id_res']) ?></td>
                            <td><?= esc($reserva_item['nombre_lab'] ?? $reserva_item['fk_id_lab']) ?></td>
                            <td>
                                <?php
                                    $docente_nombre_completo = trim(esc($reserva_item['nombre_doc'] ?? '') . ' ' . esc($reserva_item['primer_apellido_doc'] ?? ''));
                                    echo $docente_nombre_completo ?: esc($reserva_item['fk_id_doc']);
                                ?>
                            </td>
                            <td><?= esc($reserva_item['tema_res']) ?></td>
                            <td><?= esc(CodeIgniter\I18n\Time::parse($reserva_item['fecha_hora_res'])->toLocalizedString('dd MMM, yyyy HH:mm')) ?></td>
                            <td><?= esc(CodeIgniter\I18n\Time::parse($reserva_item['fecha_hora_fin_res'])->toLocalizedString('dd MMM, yyyy HH:mm')) ?></td>
                            <td>
                                <?php
                                    $estado = esc($reserva_item['estado_res']);
                                    $badge_class = 'bg-secondary'; // Default
                                    switch ($estado) {
                                        case 'Confirmada': $badge_class = 'bg-success'; break;
                                        case 'Solicitada': $badge_class = 'bg-warning text-dark'; break;
                                        case 'Cancelada': $badge_class = 'bg-danger'; break;
                                        case 'Realizada': $badge_class = 'bg-primary'; break;
                                        case 'Rechazada': $badge_class = 'bg-dark'; break;
                                        case 'En Curso': $badge_class = 'bg-info text-dark'; break;
                                    }
                                ?>
                                <span class="badge <?= $badge_class ?>"><?= $estado ?></span>
                            </td>
                            <td>
                                <a href="<?= site_url('reserva/show/' . $reserva_item['id_res']) ?>" class="btn btn-sm btn-info" title="Ver Detalles"><i class="fas fa-eye"></i></a>
                                <a href="<?= site_url('reserva/editar/' . $reserva_item['id_res']) ?>" class="btn btn-sm btn-warning" title="Editar"><i class="fas fa-edit"></i></a>
                                <a href="<?= site_url('reserva/eliminar/' . $reserva_item['id_res']) ?>" class="btn btn-sm btn-danger" title="Eliminar" onclick="return confirm('¿Está seguro de que desea eliminar esta reserva? Esta acción no se puede deshacer.');"><i class="fas fa-trash-alt"></i></a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="8" class="text-center">No se encontraron reservas.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>
<?= $this->endSection() ?>
