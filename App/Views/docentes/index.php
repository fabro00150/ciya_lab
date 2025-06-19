<?= $this->extend('layouts/default') ?> <!-- Assuming a default layout file -->

<?= $this->section('content') ?>
<h2>Docentes List</h2>

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
    <a href="<?= site_url('docentes/new') ?>" class="btn btn-primary">Create New Docente</a>
</p>

<table class="table table-bordered">
    <thead>
        <tr>
            <th>ID</th>
            <th>Cédula</th>
            <th>Nombre Completo</th>
            <th>Email</th>
            <th>Teléfono</th>
            <th>Actions</th>
        </tr>
    </thead>
    <tbody>
        <?php if (!empty($docentes) && is_array($docentes)): ?>
            <?php foreach ($docentes as $docente): ?>
                <tr>
                    <td><?= esc($docente['id_doc']) ?></td>
                    <td><?= esc($docente['cedula_doc']) ?></td>
                    <td><?= esc($docente['nombre_doc']) ?> <?= esc($docente['primer_apellido_doc']) ?> <?= esc($docente['segundo_apellido_doc'] ?? '') ?></td>
                    <td><?= esc($docente['email_doc']) ?></td>
                    <td><?= esc($docente['telefono_doc'] ?? 'N/A') ?></td>
                    <td>
                        <a href="<?= site_url('docentes/show/' . $docente['id_doc']) ?>" class="btn btn-sm btn-info">View</a>
                        <a href="<?= site_url('docentes/edit/' . $docente['id_doc']) ?>" class="btn btn-sm btn-warning">Edit</a>
                        <!-- For simplicity, using GET for delete. In a real app, use POST/DELETE via a form. -->
                        <a href="<?= site_url('docentes/delete/' . $docente['id_doc']) ?>" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure you want to delete this item?');">Delete</a>
                    </td>
                </tr>
            <?php endforeach; ?>
        <?php else: ?>
            <tr>
                <td colspan="6" class="text-center">No docentes found.</td>
            </tr>
        <?php endif; ?>
    </tbody>
</table>

<?= $this->endSection() ?>
