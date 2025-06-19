<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <style>
        .color-box {
            width: 20px;
            height: 20px;
            display: inline-block;
            border: 1px solid #ddd;
        }
    </style>
</head>
<body>
    <div class="container mt-4">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h1><?= $title ?></h1>
            <a href="/laboratorio/crear" class="btn btn-primary">
                <i class="bi bi-plus-circle"></i> Nuevo Laboratorio
            </a>
        </div>
        
        <?php if(session()->has('success')): ?>
            <div class="alert alert-success"><?= session('success') ?></div>
        <?php endif; ?>
        
        <div class="table-responsive">
            <table class="table table-striped table-hover">
                <thead class="table-dark">
                    <tr>
                        <th>ID</th>
                        <th>Nombre</th>
                        <th>Facultad</th>
                        <th>Ubicación</th>
                        <th>Responsable</th>
                        <th>Color</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($laboratorios as $lab): ?>
                    <tr>
                        <td><?= $lab['id_lab'] ?></td>
                        <td><?= esc($lab['nombre_lab']) ?></td>
                        <td><?= esc($lab['facultad_lab']) ?></td>
                        <td><?= esc($lab['ubicacion_lab']) ?></td>
                        <td><?= esc($lab['nombre_responsable'] ?? 'Sin asignar') ?></td>
                        <td><span class="color-box" style="background-color: <?= $lab['color_lab'] ?>"></span></td>
                        <td>
                            <a href="/laboratorio/editar/<?= $lab['id_lab'] ?>" class="btn btn-sm btn-warning">
                                <i class="bi bi-pencil-square"></i>
                            </a>
                            <a href="/laboratorio/eliminar/<?= $lab['id_lab'] ?>" class="btn btn-sm btn-danger" onclick="return confirm('¿Eliminar este laboratorio?')">
                                <i class="bi bi-trash"></i>
                            </a>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>