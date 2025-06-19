<?= $this->extend('layouts/default') ?> <!-- Assuming a default layout file -->

<?= $this->section('content') ?>
<h2>Reservas List</h2>

<?php if (session()->getFlashdata('success')): ?>
    <div class="alert alert-success">
        <?= session()->getFlashdata('success') ?>
    </div>
<?php endif; ?>

<?php if (session()->getFlashdata('error')): ?>
    <div class="alert alert-danger">
        <?= session()->getFlashdata('error') ?>
    </div>
<?php endif; ?>
<?php
    // Display all errors if they exist (e.g., from with('errors', $array))
    $validation_errors = session()->getFlashdata('errors');
    if (!empty($validation_errors) && is_array($validation_errors)):
?>
    <div class="alert alert-danger">
        <p><strong>Please correct the following errors:</strong></p>
        <ul>
            <?php foreach ($validation_errors as $error): ?>
                <li><?= esc($error) ?></li>
            <?php endforeach; ?>
        </ul>
    </div>
<?php endif; ?>


<p>
    <a href="<?= site_url('reservas/new') ?>" class="btn btn-primary">Create New Reserva</a>
</p>

<table class="table table-bordered table-striped">
    <thead>
        <tr>
            <th>ID</th>
            <th>Laboratorio</th>
            <th>Docente Solicitante</th>
            <th>Tema</th>
            <th>Fecha/Hora Inicio</th>
            <th>Fecha/Hora Fin</th>
            <th>Estado</th>
            <th>Actions</th>
        </tr>
    </thead>
    <tbody>
        <?php if (!empty($reservas) && is_array($reservas)): ?>
            <?php foreach ($reservas as $reserva): ?>
                <tr>
                    <td><?= esc($reserva['id_res']) ?></td>
                    <td><?= esc($reserva['nombre_lab'] ?? $reserva['fk_id_lab']) ?></td> <!-- Display name if joined -->
                    <td>
                        <?php
                            $docente_nombre = trim(esc($reserva['nombre_doc'] ?? '') . ' ' . esc($reserva['primer_apellido_doc'] ?? ''));
                            echo $docente_nombre ?: esc($reserva['fk_id_doc']);
                        ?>
                    </td>
                    <td><?= esc($reserva['tema_res']) ?></td>
                    <td><?= esc(CodeIgniter\I18n\Time::parse($reserva['fecha_hora_res'])->toLocalizedString('yyyy-MM-dd HH:mm')) ?></td>
                    <td><?= esc(CodeIgniter\I18n\Time::parse($reserva['fecha_hora_fin_res'])->toLocalizedString('yyyy-MM-dd HH:mm')) ?></td>
                    <td><span class="badge bg-info text-dark"><?= esc($reserva['estado_res']) ?></span></td>
                    <td>
                        <a href="<?= site_url('reservas/show/' . $reserva['id_res']) ?>" class="btn btn-sm btn-info">View</a>
                        <a href="<?= site_url('reservas/edit/' . $reserva['id_res']) ?>" class="btn btn-sm btn-warning">Edit</a>
                        <a href="<?= site_url('reservas/delete/' . $reserva['id_res']) ?>" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure you want to delete this reserva?');">Delete</a>
                    </td>
                </tr>
            <?php endforeach; ?>
        <?php else: ?>
            <tr>
                <td colspan="8" class="text-center">No reservas found.</td>
            </tr>
        <?php endif; ?>
    </tbody>
</table>

<?= $this->endSection() ?>
