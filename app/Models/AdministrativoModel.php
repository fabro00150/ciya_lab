<?php
namespace App\Models;

use CodeIgniter\Model;

class AdministrativoModel extends Model
{
    protected $table = 'public.administrativo';
    protected $primaryKey = 'id_admin';
    protected $allowedFields = ['nombre_admin', 'primer_apellido_admin', 'segundo_apellido_admin', 'ci_admin'];
    
    public function listarAdministrativos()
    {
        return $this->select('id_admin, nombre_admin, primer_apellido_admin')
                    ->orderBy('primer_apellido_admin')
                    ->findAll();
    }
}