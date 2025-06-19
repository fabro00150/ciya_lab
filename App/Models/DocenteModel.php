<?php

namespace App\Models;

use CodeIgniter\Model;

class DocenteModel extends Model
{
    protected $table            = 'public.docente';
    protected $primaryKey       = 'id_doc';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;

    // Adjusted allowedFields:
    // - Automatic timestamps (fecha_creacion_doc, fecha_actualizacion_doc) are handled by useTimestamps, so removed.
    // - User audit fields (usuario_creacion_doc, usuario_actualizacion_doc) are included to be set from controller.
    protected $allowedFields    = [
        'cedula_doc', 'primer_apellido_doc', 'segundo_apellido_doc', 'nombre_doc',
        'abreviatura_titulo_doc', 'fotografia_doc', 'perfil_profesional_doc',
        'telefono_doc', 'email_doc', 'oficina_doc', 'facebook_doc', 'twitter_doc',
        'pagina_web_doc', 'fk_id_car', 'usuario_creacion_doc',
        'usuario_actualizacion_doc', 'fk_id_usu', 'linkedin_doc', 'sexo_doc'
    ];

    // Dates
    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime'; // Adjust if your DB uses a different format
    protected $createdField  = 'fecha_creacion_doc';
    protected $updatedField  = 'fecha_actualizacion_doc';
    // protected $deletedField  = 'deleted_at'; // Only if using soft deletes

    // Validation
    protected $validationRules      = [
        'email_doc' => 'required|valid_email|is_unique[public.docente.email_doc,id_doc,{id_doc}]',
        'cedula_doc' => 'required|is_unique[public.docente.cedula_doc,id_doc,{id_doc}]',
        'primer_apellido_doc' => 'required',
        'nombre_doc' => 'required',
        'fk_id_car' => 'required|integer',
        'fk_id_usu' => 'required|integer',
    ];
    protected $validationMessages   = [
        'email_doc' => [
            'is_unique' => 'Sorry, that email has already been taken. Please choose another.',
            'required' => 'The email field is required.',
            'valid_email' => 'Please enter a valid email address.'
        ],
        'cedula_doc' => [
            'is_unique' => 'Sorry, that cedula already exists. Please choose another.',
            'required' => 'The cedula field is required.'
        ],
        'primer_apellido_doc' => [
            'required' => 'The first last name field is required.'
        ],
        'nombre_doc' => [
            'required' => 'The name field is required.'
        ],
        'fk_id_car' => [
            'required' => 'The career ID field is required.',
            'integer' => 'The career ID must be an integer.'
        ],
        'fk_id_usu' => [
            'required' => 'The user ID field is required.',
            'integer' => 'The user ID must be an integer.'
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
