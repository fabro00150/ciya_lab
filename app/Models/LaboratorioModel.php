<?php
namespace App\Models;

use CodeIgniter\Model;

class LaboratorioModel extends Model
{
    protected $table = 'laboratorios.laboratorio';
    protected $primaryKey = 'id_lab';
    protected $allowedFields = [
        'nombre_lab',
        'descripcion_lab',
        'fotografia1_lab',
        'fotografia2_lab',
        'fk_docente_responsable_lab',
        'fk_administrativo_responsable_lab',
        'tipo_lab',
        'ubicacion_lab',
        'color_lab',
        'fk_administrativo_responsable_secundario_lab',
        'siglas_lab',
        'paralelo_guia',
        'facultad_lab'
    ];

    protected $useTimestamps = true;
    protected $createdField = 'fecha_creacion_lab';
    protected $updatedField = 'fecha_actualizacion_lab';

    public function getLaboratoriosConResponsables()
    {
        return $this->select('laboratorio.*,
                             docente.nombre_doc as nombre_responsable,
                             administrativo.nombre_admin as nombre_admin_responsable')
                    ->join('public.docente', 'docente.id_doc = laboratorio.fk_docente_responsable_lab', 'left')
                    ->join('public.administrativo', 'administrativo.id_admin = laboratorio.fk_administrativo_responsable_lab', 'left')
                    ->findAll();
    }
}