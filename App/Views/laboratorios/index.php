<?= $this->extend('base') ?> <!-- Changed from layouts/default -->

<?= $this->section('title') ?>
    Lista de Laboratorios
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<h2>Lista de Laboratorios</h2>

<?php if (session()->getFlashdata('success')): ?>
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        <?= session()->getFlashdata('success') ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
<?php endif; ?>

<?php if (session()->getFlashdata('error')): ?>
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <?= session()->getFlashdata('error') ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
<?php endif; ?>

<p class="my-3">
    <a href="<?= site_url('laboratorio/crear') ?>" class="btn btn-primary">Crear Nuevo Laboratorio</a>
</p>

<div class="table-responsive">
    <table class="table table-bordered table-striped table-hover">
        <thead class="table-light">
            <tr>
                <th>ID</th>
                <th>Nombre</th>
                <th>Tipo</th>
                <th>Ubicación</th>
                <th>Siglas</th>
                <th>Facultad</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            <?php if (!empty($laboratorios) && is_array($laboratorios)): ?>
                <?php foreach ($laboratorios as $laboratorio_item): ?>
                    <tr>
                        <td><?= esc($laboratorio_item['id_lab']) ?></td>
                        <td><?= esc($laboratorio_item['nombre_lab']) ?></td>
                        <td><?= esc($laboratorio_item['tipo_lab'] ?? 'N/D') ?></td>
                        <td><?= esc($laboratorio_item['ubicacion_lab'] ?? 'N/D') ?></td>
                        <td><?= esc($laboratorio_item['siglas_lab'] ?? 'N/D') ?></td>
                        <td><?= esc($laboratorio_item['facultad_lab'] ?? 'N/D') ?></td>
                        <td>
                            <a href="<?= site_url('laboratorio/show/' . $laboratorio_item['id_lab']) ?>" class="btn btn-sm btn-info" title="Ver Detalles"><i class="fas fa-eye"></i> Ver</a>
                            <a href="<?= site_url('laboratorio/editar/' . $laboratorio_item['id_lab']) ?>" class="btn btn-sm btn-warning" title="Editar"><i class="fas fa-edit"></i> Editar</a>
                            <a href="<?= site_url('laboratorio/eliminar/' . $laboratorio_item['id_lab']) ?>" class="btn btn-sm btn-danger" title="Eliminar" onclick="return confirm('¿Está seguro de que desea eliminar este laboratorio? Podría afectar reservas existentes.');"><i class="fas fa-trash-alt"></i> Eliminar</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr>
                    <td colspan="7" class="text-center">No se encontraron laboratorios.</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>

<?= $this->endSection() ?>
