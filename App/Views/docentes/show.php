<?= $this->extend('layouts/default') ?> <!-- Assuming a default layout file -->

<?= $this->section('content') ?>
<h2>Docente Details</h2>

<?php if (!empty($docente)): ?>
    <table class="table table-bordered">
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
            <td><?= esc($docente['fotografia_doc'] ?? 'N/A') ?></td>
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
            <td><?= esc($docente['email_doc']) ?></td>
        </tr>
        <tr>
            <th>Oficina</th>
            <td><?= esc($docente['oficina_doc'] ?? 'N/A') ?></td>
        </tr>
        <tr>
            <th>Facebook</th>
            <td><?= esc($docente['facebook_doc'] ?? 'N/A') ?></td>
        </tr>
        <tr>
            <th>Twitter</th>
            <td><?= esc($docente['twitter_doc'] ?? 'N/A') ?></td>
        </tr>
        <tr>
            <th>Página Web</th>
            <td><?= esc($docente['pagina_web_doc'] ?? 'N/A') ?></td>
        </tr>
        <tr>
            <th>LinkedIn</th>
            <td><?= esc($docente['linkedin_doc'] ?? 'N/A') ?></td>
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
            <td><?= esc($docente['fecha_creacion_doc']) ?></td>
        </tr>
        <tr>
            <th>Fecha Actualización</th>
            <td><?= esc($docente['fecha_actualizacion_doc']) ?></td>
        </tr>
        <tr>
            <th>Usuario Creación</th>
            <td><?= esc($docente['usuario_creacion_doc']) ?></td>
        </tr>
        <tr>
            <th>Usuario Actualización</th>
            <td><?= esc($docente['usuario_actualizacion_doc']) ?></td>
        </tr>
    </table>

    <p>
        <a href="<?= site_url('docentes/edit/' . $docente['id_doc']) ?>" class="btn btn-warning">Edit</a>
        <a href="<?= site_url('docentes') ?>" class="btn btn-secondary">Back to List</a>
    </p>

<?php else: ?>
    <p>Docente not found.</p>
    <a href="<?= site_url('docentes') ?>" class="btn btn-secondary">Back to List</a>
<?php endif; ?>

<?= $this->endSection() ?>
