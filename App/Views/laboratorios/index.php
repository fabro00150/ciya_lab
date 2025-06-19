<?= $this->extend('layouts/default') ?> <!-- Assuming a default layout file -->

<?= $this->section('content') ?>
<h2>Laboratorios List</h2>

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

<p>
    <a href="<?= site_url('laboratorios/new') ?>" class="btn btn-primary">Create New Laboratorio</a>
</p>

<table class="table table-bordered">
    <thead>
        <tr>
            <th>ID</th>
            <th>Nombre</th>
            <th>Tipo</th>
            <th>Ubicación</th>
            <th>Siglas</th>
            <th>Facultad</th>
            <th>Actions</th>
        </tr>
    </thead>
    <tbody>
        <?php if (!empty($laboratorios) && is_array($laboratorios)): ?>
            <?php foreach ($laboratorios as $laboratorio): ?>
                <tr>
                    <td><?= esc($laboratorio['id_lab']) ?></td>
                    <td><?= esc($laboratorio['nombre_lab']) ?></td>
                    <td><?= esc($laboratorio['tipo_lab'] ?? 'N/A') ?></td>
                    <td><?= esc($laboratorio['ubicacion_lab'] ?? 'N/A') ?></td>
                    <td><?= esc($laboratorio['siglas_lab'] ?? 'N/A') ?></td>
                    <td><?= esc($laboratorio['facultad_lab'] ?? 'N/A') ?></td>
                    <td>
                        <a href="<?= site_url('laboratorios/show/' . $laboratorio['id_lab']) ?>" class="btn btn-sm btn-info">View</a>
                        <a href="<?= site_url('laboratorios/edit/' . $laboratorio['id_lab']) ?>" class="btn btn-sm btn-warning">Edit</a>
                        <a href="<?= site_url('laboratorios/delete/' . $laboratorio['id_lab']) ?>" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure you want to delete this laboratorio?');">Delete</a>
                    </td>
                </tr>
            <?php endforeach; ?>
        <?php else: ?>
            <tr>
                <td colspan="7" class="text-center">No laboratorios found.</td>
            </tr>
        <?php endif; ?>
    </tbody>
</table>

<?= $this->endSection() ?>
