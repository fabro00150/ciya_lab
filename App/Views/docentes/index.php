<?= $this->extend('base') ?> <!-- Changed from layouts/default -->

<?= $this->section('title') ?>
    Lista de Docentes
<?= $this->endSection() ?>

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
    <a href="<?= site_url('docente/crear') ?>" class="btn btn-primary">Crear Nuevo Docente</a>
</p>

<table class="table table-bordered table-striped">
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
            <?php foreach ($docentes as $docente_item): ?>
                <tr>
                    <td><?= esc($docente_item['id_doc']) ?></td>
                    <td><?= esc($docente_item['cedula_doc']) ?></td>
                    <td><?= esc($docente_item['nombre_doc']) ?> <?= esc($docente_item['primer_apellido_doc']) ?> <?= esc($docente_item['segundo_apellido_doc'] ?? '') ?></td>
                    <td><?= esc($docente_item['email_doc']) ?></td>
                    <td><?= esc($docente_item['telefono_doc'] ?? 'N/A') ?></td>
                    <td>
                        <a href="<?= site_url('docente/show/' . $docente_item['id_doc']) ?>" class="btn btn-sm btn-info">Ver</a>
                        <a href="<?= site_url('docente/editar/' . $docente_item['id_doc']) ?>" class="btn btn-sm btn-warning">Editar</a>
                        <a href="<?= site_url('docente/eliminar/' . $docente_item['id_doc']) ?>" class="btn btn-sm btn-danger" onclick="return confirm('¿Está seguro de que desea eliminar este docente?');">Eliminar</a>
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
