<?= $this->extend('base') ?> <!-- Changed from layouts/default -->

<?= $this->section('title') ?>
    Detalles del Laboratorio - <?= esc($laboratorio['nombre_lab'] ?? 'Laboratorio') ?>
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="container mt-4">
    <h2>Detalles del Laboratorio: <?= esc($laboratorio['nombre_lab'] ?? '') ?></h2>

    <?php if (!empty($laboratorio)): ?>
        <div class="card">
            <div class="card-header">
                Información Principal
            </div>
            <div class="card-body">
                <table class="table table-bordered table-hover">
                    <tbody>
                        <tr>
                            <th style="width: 25%;">ID Laboratorio</th>
                            <td><?= esc($laboratorio['id_lab']) ?></td>
                        </tr>
                        <tr>
                            <th>Nombre</th>
                            <td><?= esc($laboratorio['nombre_lab']) ?></td>
                        </tr>
                        <tr>
                            <th>Siglas</th>
                            <td><?= esc($laboratorio['siglas_lab'] ?? 'N/D') ?></td>
                        </tr>
                        <tr>
                            <th>Descripción</th>
                            <td><?= nl2br(esc($laboratorio['descripcion_lab'] ?? 'N/D')) ?></td>
                        </tr>
                        <tr>
                            <th>Tipo</th>
                            <td><?= esc($laboratorio['tipo_lab'] ?? 'N/D') ?></td>
                        </tr>
                        <tr>
                            <th>Facultad</th>
                            <td><?= esc($laboratorio['facultad_lab'] ?? 'N/D') ?></td>
                        </tr>
                        <tr>
                            <th>Ubicación</th>
                            <td><?= esc($laboratorio['ubicacion_lab'] ?? 'N/D') ?></td>
                        </tr>
                         <tr>
                            <th>Paralelo Guía</th>
                            <td><?= esc($laboratorio['paralelo_guia'] ?? 'N/D') ?></td>
                        </tr>
                        <tr>
                            <th>Color Identificativo</th>
                            <td>
                                <div style="display: flex; align-items: center;">
                                    <span style="width: 25px; height: 25px; background-color: <?= esc($laboratorio['color_lab'] ?? '#ffffff') ?>; border: 1px solid #ccc; margin-right: 10px; border-radius: 4px;"></span>
                                    <?= esc($laboratorio['color_lab'] ?? 'No definido') ?>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <th>Fotografía 1 (URL/Path)</th>
                            <td>
                                <?php if (!empty($laboratorio['fotografia1_lab'])): ?>
                                    <a href="<?= esc($laboratorio['fotografia1_lab']) ?>" target="_blank"><?= esc($laboratorio['fotografia1_lab']) ?></a>
                                <?php else: ?>
                                    N/D
                                <?php endif; ?>
                            </td>
                        </tr>
                        <tr>
                            <th>Fotografía 2 (URL/Path)</th>
                            <td>
                                <?php if (!empty($laboratorio['fotografia2_lab'])): ?>
                                    <a href="<?= esc($laboratorio['fotografia2_lab']) ?>" target="_blank"><?= esc($laboratorio['fotografia2_lab']) ?></a>
                                <?php else: ?>
                                    N/D
                                <?php endif; ?>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

        <div class="card mt-4">
            <div class="card-header">
                Responsables
            </div>
            <div class="card-body">
                <table class="table table-bordered table-hover">
                    <tbody>
                        <tr>
                            <th style="width: 25%;">Docente Responsable</th>
                            <td>
                                <?= esc($laboratorio['docente_responsable_nombre'] ?? 'No asignado') ?>
                                (ID: <?= esc($laboratorio['fk_docente_responsable_lab']) ?>)
                            </td>
                        </tr>
                        <tr>
                            <th>Administrativo Responsable</th>
                            <td>ID: <?= esc($laboratorio['fk_administrativo_responsable_lab']) ?> (Nombre no disponible aquí)</td>
                        </tr>
                        <tr>
                            <th>Admin. Responsable Secundario</th>
                            <td>ID: <?= esc($laboratorio['fk_administrativo_responsable_secundario_lab'] ?? 'N/D') ?> (Nombre no disponible aquí)</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

        <div class="card mt-4">
            <div class="card-header">
                Auditoría
            </div>
            <div class="card-body">
                 <table class="table table-bordered table-hover">
                    <tbody>
                        <tr>
                            <th style="width: 25%;">Usuario Creación</th>
                            <td><?= esc($laboratorio['usuario_creacion_lab']) ?></td>
                        </tr>
                        <tr>
                            <th>Fecha Creación</th>
                            <td><?= esc(CodeIgniter\I18n\Time::parse($laboratorio['fecha_creacion_lab'])->toLocalizedString('dd MMMM, yyyy HH:mm:ss')) ?></td>
                        </tr>
                        <tr>
                            <th>Usuario Actualización</th>
                            <td><?= esc($laboratorio['usuario_actualizacion_lab']) ?></td>
                        </tr>
                        <tr>
                            <th>Fecha Actualización</th>
                            <td><?= esc(CodeIgniter\I18n\Time::parse($laboratorio['fecha_actualizacion_lab'])->toLocalizedString('dd MMMM, yyyy HH:mm:ss')) ?></td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

        <p class="mt-4">
            <a href="<?= site_url('laboratorio/editar/' . $laboratorio['id_lab']) ?>" class="btn btn-warning"><i class="fas fa-edit"></i> Editar Laboratorio</a>
            <a href="<?= site_url('laboratorio') ?>" class="btn btn-secondary"><i class="fas fa-arrow-left"></i> Volver a la Lista</a>
        </p>

    <?php else: ?>
        <div class="alert alert-warning" role="alert">
            Laboratorio no encontrado.
        </div>
        <a href="<?= site_url('laboratorio') ?>" class="btn btn-secondary"><i class="fas fa-arrow-left"></i> Volver a la Lista</a>
    <?php endif; ?>
</div>
<?= $this->endSection() ?>
