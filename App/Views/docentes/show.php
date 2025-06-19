<?= $this->extend('base') ?> <!-- Changed from layouts/default -->

<?= $this->section('title') ?>
    Detalles del Docente - <?= esc($docente['nombre_doc'] ?? '') ?> <?= esc($docente['primer_apellido_doc'] ?? '') ?>
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<h2>Detalles del Docente</h2>

<?php if (!empty($docente)): ?>
    <table class="table table-bordered table-hover">
        <tbody>
            <tr>
                <th>ID</th>
                <td><?= esc($docente['id_doc']) ?></td>
            </tr>
            <tr>
                <th>Cédula</th>
                <td><?= esc($docente['cedula_doc']) ?></td>
            </tr>
            <tr>
                <th>Primer Apellido</th>
                <td><?= esc($docente['primer_apellido_doc']) ?></td>
            </tr>
            <tr>
                <th>Segundo Apellido</th>
                <td><?= esc($docente['segundo_apellido_doc'] ?? 'N/A') ?></td>
            </tr>
            <tr>
                <th>Nombre</th>
                <td><?= esc($docente['nombre_doc']) ?></td>
            </tr>
            <tr>
                <th>Abreviatura Título</th>
                <td><?= esc($docente['abreviatura_titulo_doc'] ?? 'N/A') ?></td>
            </tr>
            <tr>
                <th>Fotografía (URL/Path)</th>
                <td>
                    <?php if (!empty($docente['fotografia_doc'])): ?>
                        <a href="<?= esc($docente['fotografia_doc']) ?>" target="_blank"><?= esc($docente['fotografia_doc']) ?></a>
                        <!-- You could also display an <img src="<?= esc($docente['fotografia_doc']) ?>" alt="Fotografia" style="max-width: 200px; max-height: 200px;"> if it's a direct image URL -->
                    <?php else: ?>
                        N/A
                    <?php endif; ?>
                </td>
            </tr>
            <tr>
                <th>Perfil Profesional</th>
                <td><?= nl2br(esc($docente['perfil_profesional_doc'] ?? 'N/A')) ?></td>
            </tr>
            <tr>
                <th>Teléfono</th>
                <td><?= esc($docente['telefono_doc'] ?? 'N/A') ?></td>
            </tr>
            <tr>
                <th>Email</th>
                <td><a href="mailto:<?= esc($docente['email_doc']) ?>"><?= esc($docente['email_doc']) ?></a></td>
            </tr>
            <tr>
                <th>Oficina</th>
                <td><?= esc($docente['oficina_doc'] ?? 'N/A') ?></td>
            </tr>
            <tr>
                <th>Facebook</th>
                <td>
                    <?php if (!empty($docente['facebook_doc'])): ?>
                        <a href="<?= esc($docente['facebook_doc']) ?>" target="_blank"><?= esc($docente['facebook_doc']) ?></a>
                    <?php else: ?>
                        N/A
                    <?php endif; ?>
                </td>
            </tr>
            <tr>
                <th>Twitter (X)</th>
                <td>
                     <?php if (!empty($docente['twitter_doc'])): ?>
                        <a href="https://twitter.com/<?= esc(str_replace('@', '', $docente['twitter_doc'])) ?>" target="_blank"><?= esc($docente['twitter_doc']) ?></a>
                    <?php else: ?>
                        N/A
                    <?php endif; ?>
                </td>
            </tr>
            <tr>
                <th>LinkedIn</th>
                <td>
                    <?php if (!empty($docente['linkedin_doc'])): ?>
                        <a href="<?= esc($docente['linkedin_doc']) ?>" target="_blank"><?= esc($docente['linkedin_doc']) ?></a>
                    <?php else: ?>
                        N/A
                    <?php endif; ?>
                </td>
            </tr>
            <tr>
                <th>Página Web</th>
                <td>
                    <?php if (!empty($docente['pagina_web_doc'])): ?>
                        <a href="<?= esc($docente['pagina_web_doc']) ?>" target="_blank"><?= esc($docente['pagina_web_doc']) ?></a>
                    <?php else: ?>
                        N/A
                    <?php endif; ?>
                </td>
            </tr>
            <tr>
                <th>Sexo</th>
                <td><?= esc($docente['sexo_doc'] ?? 'N/A') ?></td>
            </tr>
            <tr>
                <th>FK ID Carrera</th>
                <td><?= esc($docente['fk_id_car']) ?></td>
            </tr>
            <tr>
                <th>FK ID Usuario</th>
                <td><?= esc($docente['fk_id_usu']) ?></td>
            </tr>
            <tr>
                <th>Fecha Creación</th>
                <td><?= esc(CodeIgniter\I18n\Time::parse($docente['fecha_creacion_doc'])->toLocalizedString('dd MMMM, yyyy HH:mm:ss')) ?></td>
            </tr>
            <tr>
                <th>Fecha Actualización</th>
                <td><?= esc(CodeIgniter\I18n\Time::parse($docente['fecha_actualizacion_doc'])->toLocalizedString('dd MMMM, yyyy HH:mm:ss')) ?></td>
            </tr>
            <tr>
                <th>Usuario Creación</th>
                <td><?= esc($docente['usuario_creacion_doc']) ?></td>
            </tr>
            <tr>
                <th>Usuario Actualización</th>
                <td><?= esc($docente['usuario_actualizacion_doc']) ?></td>
            </tr>
        </tbody>
    </table>

    <p class="mt-3">
        <a href="<?= site_url('docente/editar/' . $docente['id_doc']) ?>" class="btn btn-warning">Editar Docente</a>
        <a href="<?= site_url('docente') ?>" class="btn btn-secondary">Volver a la Lista</a>
    </p>

<?php else: ?>
    <div class="alert alert-warning" role="alert">
        Docente no encontrado.
    </div>
    <a href="<?= site_url('docente') ?>" class="btn btn-secondary">Volver a la Lista</a>
<?php endif; ?>

<?= $this->endSection() ?>
