<?php
namespace App\Models;

use CodeIgniter\Model;

class DocenteModel extends Model
{
    protected $table = 'public.docente';
    protected $primaryKey = 'id_doc';
    protected $allowedFields = ['nombre_doc', 'primer_apellido_doc', 'segundo_apellido_doc', 'ci_doc'];
    
    public function listarDocentes()
    {
        return $this->select('id_doc, nombre_doc, primer_apellido_doc')
                    ->orderBy('primer_apellido_doc')
                    ->findAll();
    }
}