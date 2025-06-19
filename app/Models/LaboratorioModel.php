<?php

namespace App\Models;

use CodeIgniter\Model;

class LaboratorioModel extends Model
{
    protected $table            = 'laboratorios.laboratorio'; // Schema included
    protected $primaryKey       = 'id_lab';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;

    // AllowedFields: Timestamps (fecha_creacion_lab, fecha_actualizacion_lab) removed as handled by useTimestamps.
    // Audit fields (usuario_creacion_lab, usuario_actualizacion_lab) included.
    protected $allowedFields    = [
        'nombre_lab', 'descripcion_lab', 'fotografia1_lab', 'fotografia2_lab',
        'fk_docente_responsable_lab', 'fk_administrativo_responsable_lab',
        'usuario_creacion_lab', 'usuario_actualizacion_lab', 'tipo_lab',
        'ubicacion_lab', 'color_lab', 'fk_administrativo_responsable_secundario_lab',
        'siglas_lab', 'paralelo_guia', 'facultad_lab'
    ];

    // Dates
    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime'; // Adjust if DB uses a different format
    protected $createdField  = 'fecha_creacion_lab';
    protected $updatedField  = 'fecha_actualizacion_lab';
    // protected $deletedField  = 'deleted_at';

    // Validation
    protected $validationRules      = [
        'nombre_lab' => 'required',
        'fk_docente_responsable_lab' => 'required|integer',
        'fk_administrativo_responsable_lab' => 'required|integer',
        // Add more rules as needed, e.g. for tipo_lab, ubicacion_lab
        'tipo_lab' => 'permit_empty|string|max_length[100]',
        'ubicacion_lab' => 'permit_empty|string|max_length[255]',
        'color_lab' => 'permit_empty|string|max_length[20]', // Could be hex, rgba, etc.
        'fk_administrativo_responsable_secundario_lab' => 'permit_empty|integer',
        'siglas_lab' => 'permit_empty|string|max_length[20]',
        'paralelo_guia' => 'permit_empty|string|max_length[50]',
        'facultad_lab' => 'permit_empty|string|max_length[255]',
        'fotografia1_lab' => 'permit_empty|string|max_length[255]', // Assuming URL or path
        'fotografia2_lab' => 'permit_empty|string|max_length[255]', // Assuming URL or path
    ];
    protected $validationMessages   = [
        'nombre_lab' => [
            'required' => 'The laboratory name is required.'
        ],
        'fk_docente_responsable_lab' => [
            'required' => 'The responsible docente ID is required.',
            'integer' => 'The responsible docente ID must be an integer.'
        ],
        'fk_administrativo_responsable_lab' => [
            'required' => 'The responsible administrative ID is required.',
            'integer' => 'The responsible administrative ID must be an integer.'
        ]
    ];
    protected $skipValidation       = false;
    protected $cleanValidationRules = true;

    // Callbacks
    protected $allowCallbacks = true;
    protected $beforeInsert   = [];
    protected $afterInsert    = [];
    protected $beforeUpdate   = [];
    protected $afterUpdate    = [];
    protected $beforeFind     = [];
    protected $afterFind      = [];
    protected $beforeDelete   = [];
    protected $afterDelete    = [];
}
