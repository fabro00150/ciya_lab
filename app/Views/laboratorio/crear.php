<div class="container mt-4">
    <h1><?= $title ?></h1>
    
    <form method="post" action="/laboratorio/guardar" enctype="multipart/form-data">
        <div class="row">
            <div class="col-md-6">
                <div class="mb-3">
                    <label class="form-label">Nombre del Laboratorio *</label>
                    <input type="text" name="nombre_lab" class="form-control" required>
                </div>
                
                <div class="mb-3">
                    <label class="form-label">Facultad *</label>
                    <select name="facultad_lab" class="form-select" required>
                        <option value="">Seleccione una facultad</option>
                        <?php foreach ($facultades as $facultad): ?>
                            <option value="<?= esc($facultad['facultad_lab']) ?>"><?= esc($facultad['facultad_lab']) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                
                <div class="mb-3">
                    <label class="form-label">Descripción</label>
                    <textarea name="descripcion_lab" class="form-control" rows="3"></textarea>
                </div>
                
                <div class="mb-3">
                    <label class="form-label">Tipo de Laboratorio</label>
                    <input type="text" name="tipo_lab" class="form-control">
                </div>
                
                <div class="mb-3">
                    <label class="form-label">Ubicación *</label>
                    <input type="text" name="ubicacion_lab" class="form-control" required>
                </div>
            </div>
            
            <div class="col-md-6">
                <div class="mb-3">
                    <label class="form-label">Docente Responsable</label>
                    <select name="fk_docente_responsable_lab" class="form-select">
                        <option value="">Seleccione un docente</option>
                        <?php foreach ($docentes as $docente): ?>
                            <option value="<?= $docente['id_doc'] ?>">
                                <?= esc($docente['nombre_doc'] . ' ' . $docente['primer_apellido_doc']) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                
                <div class="mb-3">
                    <label class="form-label">Administrativo Responsable</label>
                    <select name="fk_administrativo_responsable_lab" class="form-select">
                        <option value="">Seleccione un administrativo</option>
                        <?php foreach ($administrativos as $admin): ?>
                            <option value="<?= $admin['id_admin'] ?>">
                                <?= esc($admin['nombre_admin'] . ' ' . $admin['primer_apellido_admin']) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                
                <div class="mb-3">
                    <label class="form-label">Color identificador</label>
                    <input type="color" name="color_lab" class="form-control form-control-color">
                </div>
                
                <div class="mb-3">
                    <label class="form-label">Fotografía 1</label>
                    <input type="file" name="fotografia1_lab" class="form-control">
                </div>
                
                <div class="mb-3">
                    <label class="form-label">Fotografía 2</label>
                    <input type="file" name="fotografia2_lab" class="form-control">
                </div>
            </div>
        </div>
        
        <div class="row">
            <div class="col-md-6">
                <div class="mb-3">
                    <label class="form-label">Siglas</label>
                    <input type="text" name="siglas_lab" class="form-control">
                </div>
            </div>
            <div class="col-md-6">
                <div class="mb-3">
                    <label class="form-label">Paralelo Guía</label>
                    <input type="text" name="paralelo_guia" class="form-control">
                </div>
            </div>
        </div>
        
        <button type="submit" class="btn btn-success">Guardar Laboratorio</button>
        <a href="/laboratorio" class="btn btn-secondary">Cancelar</a>
    </form>
</div>