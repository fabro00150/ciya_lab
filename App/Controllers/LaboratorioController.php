<?php

namespace App\Controllers;

use App\Models\LaboratorioModel;
use App\Models\DocenteModel; // For fetching docentes
use CodeIgniter\Controller;

class LaboratorioController extends BaseController
{
    protected $laboratorioModel;
    protected $docenteModel;
    protected $helpers = ['form', 'url'];

    public function __construct()
    {
        $this->laboratorioModel = new LaboratorioModel();
        $this->docenteModel = new DocenteModel(); // Instantiate DocenteModel
        helper($this->helpers); // Load helpers
    }

    public function index()
    {
        $data['laboratorios'] = $this->laboratorioModel->findAll();
        return view('laboratorios/index', $data);
    }

    public function show($id_lab = null)
    {
        $laboratorio = $this->laboratorioModel->find($id_lab);

        if (empty($laboratorio)) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException('Cannot find the laboratorio item: ' . $id_lab);
        }

        // Optionally, fetch responsible docente's name
        $docente_responsable = $this->docenteModel->find($laboratorio['fk_docente_responsable_lab']);
        $laboratorio['docente_responsable_nombre'] = $docente_responsable ? $docente_responsable['nombre_doc'] . ' ' . $docente_responsable['primer_apellido_doc'] : 'N/A';

        // You might want to do the same for fk_administrativo_responsable_lab if you have an AdminModel

        $data['laboratorio'] = $laboratorio;
        return view('laboratorios/show', $data);
    }

    private function _loadDocentesData()
    {
        // Fetch only necessary fields for the dropdown
        return $this->docenteModel->select('id_doc, nombre_doc, primer_apellido_doc, segundo_apellido_doc, cedula_doc')
                                  ->orderBy('primer_apellido_doc', 'ASC')
                                  ->findAll();
    }

    public function new()
    {
        $data['laboratorio'] = null; // For form consistency
        $data['docentes'] = $this->_loadDocentesData();
        $data['errors'] = session()->getFlashdata('errors');
        $data['old_input'] = session()->getFlashdata('old_input');
        return view('laboratorios/form', $data);
    }

    public function create()
    {
        $data_post = $this->request->getPost();

        // Conceptual: Set user audit fields
        // $loggedInUserId = session()->get('user_id'); // Example
        // $data_post['usuario_creacion_lab'] = $loggedInUserId;
        // $data_post['usuario_actualizacion_lab'] = $loggedInUserId;
        $data_post['usuario_creacion_lab'] = 'SYSTEM_USER_LAB_CREATE'; // Placeholder
        $data_post['usuario_actualizacion_lab'] = 'SYSTEM_USER_LAB_CREATE'; // Placeholder

        if ($this->laboratorioModel->save($data_post)) {
            session()->setFlashdata('success', 'Laboratorio created successfully.');
            return redirect()->to(site_url('laboratorios'));
        } else {
            session()->setFlashdata('errors', $this->laboratorioModel->errors());
            session()->setFlashdata('old_input', $this->request->getPost());
            // Need to pass docentes again to the form if validation fails
            // $data_view['docentes'] = $this->_loadDocentesData();
            // $data_view['errors'] = $this->laboratorioModel->errors();
            // $data_view['laboratorio'] = $data_post; // old input
            // return view('laboratorios/form', $data_view);
            return redirect()->back()->withInput(); // Simpler way, relies on session flash for errors/old_input
        }
    }

    public function edit($id_lab = null)
    {
        $laboratorio = $this->laboratorioModel->find($id_lab);

        if (empty($laboratorio)) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException('Cannot find the laboratorio item: ' . $id_lab);
        }

        $data['laboratorio'] = $laboratorio;
        $data['docentes'] = $this->_loadDocentesData();
        $data['errors'] = session()->getFlashdata('errors');
        // old_input will be set by withInput() if coming from a failed update attempt
        // or could be manually set if needed: $data['old_input'] = session()->getFlashdata('old_input');

        return view('laboratorios/form', $data);
    }

    public function update($id_lab = null)
    {
        $data_post = $this->request->getPost();

        // Conceptual: Set user audit fields
        // $loggedInUserId = session()->get('user_id'); // Example
        // $data_post['usuario_actualizacion_lab'] = $loggedInUserId;
        $data_post['usuario_actualizacion_lab'] = 'SYSTEM_USER_LAB_UPDATE'; // Placeholder

        if ($this->laboratorioModel->update($id_lab, $data_post)) {
            session()->setFlashdata('success', 'Laboratorio updated successfully.');
            return redirect()->to(site_url('laboratorios'));
        } else {
            session()->setFlashdata('errors', $this->laboratorioModel->errors());
            session()->setFlashdata('old_input', $this->request->getPost());
            // Need to pass docentes again to the form if validation fails
            // $data_view['docentes'] = $this->_loadDocentesData();
            // $data_view['errors'] = $this->laboratorioModel->errors();
            // $data_view['laboratorio'] = array_merge($this->laboratorioModel->find($id_lab), $data_post); // merge existing with new attempt
            // return view('laboratorios/form', $data_view);
            return redirect()->back()->withInput(); // Simpler way
        }
    }

    public function delete($id_lab = null)
    {
        if ($this->laboratorioModel->delete($id_lab)) {
            session()->setFlashdata('success', 'Laboratorio deleted successfully.');
        } else {
            // You might want to check for specific errors, e.g., protected foreign key
            $errors = $this->laboratorioModel->errors(); // Check if model produces specific delete errors
            $dbError = $this->laboratorioModel->db->error(); // More generic DB error
            if (!empty($errors) && isset($errors['foreignKey'])) {
                 session()->setFlashdata('error', 'Failed to delete laboratorio: ' . $errors['foreignKey']);
            } else if ($dbError && !empty($dbError['message'])) {
                 session()->setFlashdata('error', 'Failed to delete laboratorio. DB Error: ' . $dbError['message']);
            }
            else {
                session()->setFlashdata('error', 'Failed to delete laboratorio. It might be in use or does not exist.');
            }
        }
        return redirect()->to(site_url('laboratorios'));
    }
}
