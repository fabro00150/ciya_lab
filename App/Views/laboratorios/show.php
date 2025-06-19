<?= $this->extend('layouts/default') ?> <!-- Assuming a default layout file -->

<?= $this->section('content') ?>
<h2>Laboratorio Details</h2>

<?php if (!empty($laboratorio)): ?>
    <table class="table table-bordered">
        <tr>
            <th>ID</th>
            <td><?= esc($laboratorio['id_lab']) ?></td>
        </tr>
        <tr>
            <th>Nombre</th>
            <td><?= esc($laboratorio['nombre_lab']) ?></td>
        </tr>
        <tr>
            <th>Descripción</th>
            <td><?= nl2br(esc($laboratorio['descripcion_lab'] ?? 'N/A')) ?></td>
        </tr>
        <tr>
            <th>Fotografía 1 (URL/Path)</th>
            <td><?= esc($laboratorio['fotografia1_lab'] ?? 'N/A') ?></td>
        </tr>
        <tr>
            <th>Fotografía 2 (URL/Path)</th>
            <td><?= esc($laboratorio['fotografia2_lab'] ?? 'N/A') ?></td>
        </tr>
        <tr>
            <th>Docente Responsable (FK)</th>
            <td><?= esc($laboratorio['fk_docente_responsable_lab']) ?>
                (<?= esc($laboratorio['docente_responsable_nombre'] ?? 'Nombre no disponible') ?>)
            </td>
        </tr>
        <tr>
            <th>Administrativo Responsable (FK)</th>
            <td><?= esc($laboratorio['fk_administrativo_responsable_lab']) ?></td>
        </tr>
         <tr>
            <th>Admin. Responsable Secundario (FK)</th>
            <td><?= esc($laboratorio['fk_administrativo_responsable_secundario_lab'] ?? 'N/A') ?></td>
        </tr>
        <tr>
            <th>Tipo</th>
            <td><?= esc($laboratorio['tipo_lab'] ?? 'N/A') ?></td>
        </tr>
        <tr>
            <th>Ubicación</th>
            <td><?= esc($laboratorio['ubicacion_lab'] ?? 'N/A') ?></td>
        </tr>
        <tr>
            <th>Color Identificativo</th>
            <td>
                <?= esc($laboratorio['color_lab'] ?? 'N/A') ?>
                <?php if (!empty($laboratorio['color_lab'])): ?>
                    <div style="width: 20px; height: 20px; background-color: <?= esc($laboratorio['color_lab']) ?>; display: inline-block; border: 1px solid #ccc; margin-left: 10px;"></div>
                <?php endif; ?>
            </td>
        </tr>
        <tr>
            <th>Siglas</th>
            <td><?= esc($laboratorio['siglas_lab'] ?? 'N/A') ?></td>
        </tr>
        <tr>
            <th>Paralelo Guía</th>
            <td><?= esc($laboratorio['paralelo_guia'] ?? 'N/A') ?></td>
        </tr>
         <tr>
            <th>Facultad</th>
            <td><?= esc($laboratorio['facultad_lab'] ?? 'N/A') ?></td>
        </tr>
        <tr>
            <th>Fecha Creación</th>
            <td><?= esc($laboratorio['fecha_creacion_lab']) ?></td>
        </tr>
        <tr>
            <th>Fecha Actualización</th>
            <td><?= esc($laboratorio['fecha_actualizacion_lab']) ?></td>
        </tr>
        <tr>
            <th>Usuario Creación</th>
            <td><?= esc($laboratorio['usuario_creacion_lab']) ?></td>
        </tr>
        <tr>
            <th>Usuario Actualización</th>
            <td><?= esc($laboratorio['usuario_actualizacion_lab']) ?></td>
        </tr>
    </table>

    <p>
        <a href="<?= site_url('laboratorios/edit/' . $laboratorio['id_lab']) ?>" class="btn btn-warning">Edit</a>
        <a href="<?= site_url('laboratorios') ?>" class="btn btn-secondary">Back to List</a>
    </p>

<?php else: ?>
    <p>Laboratorio not found.</p>
    <a href="<?= site_url('laboratorios') ?>" class="btn btn-secondary">Back to List</a>
<?php endif; ?>

<?= $this->endSection() ?>
