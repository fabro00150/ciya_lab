<?php

namespace App\Controllers;

use App\Models\LaboratorioModel;
use App\Models\DocenteModel; // For fetching docentes

class LaboratorioController extends BaseController // Ensure it extends your BaseController
{
    protected $laboratorioModel;
    protected $docenteModel;
    protected $helpers = ['form', 'url'];

    public function __construct()
    {
        $this->laboratorioModel = new LaboratorioModel();
        $this->docenteModel = new DocenteModel();
        helper($this->helpers);
    }

    public function index()
    {
        $data['laboratorios'] = $this->laboratorioModel->findAll();
        // Consider joining with docente model here if you want to display responsable name in index
        return view('laboratorios/index', $data);
    }

    public function show($id_lab = null)
    {
        $laboratorio = $this->laboratorioModel->find($id_lab);

        if (empty($laboratorio)) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException('No se encontró el laboratorio con ID: ' . $id_lab);
        }

        // Fetch responsible docente's name
        if (!empty($laboratorio['fk_docente_responsable_lab'])) {
            $docente_responsable = $this->docenteModel->find($laboratorio['fk_docente_responsable_lab']);
            $laboratorio['docente_responsable_nombre'] = $docente_responsable ? trim($docente_responsable['nombre_doc'] . ' ' . $docente_responsable['primer_apellido_doc']) : 'N/D';
        } else {
            $laboratorio['docente_responsable_nombre'] = 'No asignado';
        }

        $data['laboratorio'] = $laboratorio;
        return view('laboratorios/show', $data);
    }

    private function _loadDropdownData()
    {
        // Fetch only necessary fields for the dropdown
        return $this->docenteModel->select('id_doc, nombre_doc, primer_apellido_doc, segundo_apellido_doc, cedula_doc')
                                  ->orderBy('primer_apellido_doc', 'ASC')
                                  ->findAll();
    }

    // Renamed from new()
    public function crear()
    {
        $data['laboratorio'] = null;
        $data['docentes'] = $this->_loadDropdownData();
        $data['errors'] = session()->getFlashdata('errors');
        $data['old_input'] = session()->getFlashdata('old_input');
        return view('laboratorios/form', $data);
    }

    // Renamed from edit()
    public function editar($id_lab = null)
    {
        $laboratorio = $this->laboratorioModel->find($id_lab);

        if (empty($laboratorio)) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException('No se encontró el laboratorio con ID: ' . $id_lab);
        }

        $data['laboratorio'] = $laboratorio;
        $data['docentes'] = $this->_loadDropdownData();
        $data['errors'] = session()->getFlashdata('errors');
        // old_input is handled by withInput()

        return view('laboratorios/form', $data);
    }

    public function guardar()
    {
        $postData = $this->request->getPost();
        $id_lab = $postData['id_lab'] ?? null;

        $validation = \Config\Services::validation();
        // Get base rules from Model for all fields
        $rules = $this->laboratorioModel->getValidationRules();

        if (!empty($id_lab)) { // UPDATE
            // For updates, if 'nombre_lab' needs to be unique BUT ignore current record:
            // $rules['nombre_lab'] = "required|is_unique[laboratorios.laboratorio.nombre_lab,id_lab,{$id_lab}]";
            // For now, we assume basic required validation is enough, or unique validation is not needed for nombre_lab on update,
            // or the model handles it correctly if PK is part of data in save().
            // If specific unique fields are there, they need this kind of rule adjustment.
            // Let's assume for now the model's default rules are sufficient or `save` handles it.
            // If not, explicit rule setting like in DocenteController is needed.
            // $validation->setRules($rules); // If you need to modify rules for update

            $postData['usuario_actualizacion_lab'] = 'SYSTEM_USER_LAB_UPDATE'; // Placeholder

            // If using $this->laboratorioModel->update(), validation must be run first.
            if ($validation->run($postData, 'laboratorio')) { // Use validation group if defined in Validation.php
                 if ($this->laboratorioModel->update($id_lab, $postData)) {
                    session()->setFlashdata('success', 'Laboratorio actualizado correctamente.');
                    return redirect()->to(site_url('laboratorio'));
                } else {
                    session()->setFlashdata('error', 'Error al actualizar el laboratorio.');
                    return redirect()->back()->withInput()->with('errors', $this->laboratorioModel->errors());
                }
            } else {
                 session()->setFlashdata('errors', $validation->getErrors());
                 return redirect()->to(site_url('laboratorio/editar/'.$id_lab))->withInput()->with('errors', $validation->getErrors());
            }

        } else { // INSERT
            $postData['usuario_creacion_lab'] = 'SYSTEM_USER_LAB_CREATE';
            $postData['usuario_actualizacion_lab'] = 'SYSTEM_USER_LAB_CREATE';

            // The model's save() method handles validation internally using rules defined in the model.
            if ($this->laboratorioModel->save($postData)) {
                session()->setFlashdata('success', 'Laboratorio creado correctamente.');
                return redirect()->to(site_url('laboratorio'));
            } else {
                session()->setFlashdata('errors', $this->laboratorioModel->errors());
                return redirect()->to(site_url('laboratorio/crear'))->withInput()->with('errors', $this->laboratorioModel->errors());
            }
        }
    }

    // Renamed from delete()
    public function eliminar($id_lab = null)
    {
        // Check if laboratory exists
        $laboratorio = $this->laboratorioModel->find($id_lab);
        if (empty($laboratorio)) {
            session()->setFlashdata('error', 'Laboratorio no encontrado.');
            return redirect()->to(site_url('laboratorio'));
        }

        if ($this->laboratorioModel->delete($id_lab)) {
            session()->setFlashdata('success', 'Laboratorio eliminado correctamente.');
        } else {
            // More specific error message if possible (e.g., foreign key constraint)
            $dbError = $this->laboratorioModel->db->error();
            if ($dbError && !empty($dbError['message'])) {
                 session()->setFlashdata('error', 'Error al eliminar el laboratorio. Es posible que esté en uso. (DB Error: ' . $dbError['code'] .')');
            } else {
                 session()->setFlashdata('error', 'Error al eliminar el laboratorio.');
            }
        }
        return redirect()->to(site_url('laboratorio'));
    }
}
