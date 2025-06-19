<?php

namespace App\Controllers;

use App\Models\DocenteModel;
use CodeIgniter\Controller;

class DocenteController extends BaseController
{
    protected $docenteModel;
    protected $helpers = ['form', 'url'];

    public function __init() // Changed from __construct to __init for BaseController if it uses it. Or remove if not needed by BaseController.
    {
        // In CodeIgniter 4, services are typically loaded in the constructor or via service() function.
        // For models, it's common to instantiate them in the constructor.
        // If BaseController has an __init, ensure this doesn't conflict.
        // Typically, direct instantiation or service() is preferred.
        $this->docenteModel = new DocenteModel();
    }

    // If __init is not standard for your BaseController, use a constructor or instantiate on demand.
    // For simplicity and standard CI4 practice, let's use a constructor.
    public function __construct()
    {
        $this->docenteModel = new DocenteModel();
        helper($this->helpers); // Load helpers
    }

    public function index()
    {
        $data['docentes'] = $this->docenteModel->findAll();
        return view('docentes/index', $data);
    }

    public function show($id_doc = null)
    {
        $data['docente'] = $this->docenteModel->find($id_doc);

        if (empty($data['docente'])) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException('Cannot find the docente item: ' . $id_doc);
        }

        return view('docentes/show', $data);
    }

    public function new()
    {
        $data['docente'] = null; // For form view consistency
        $data['errors'] = session()->getFlashdata('errors'); // Get validation errors if redirected
        $data['old_input'] = session()->getFlashdata('old_input'); // Get old input if redirected
        return view('docentes/form', $data);
    }

    public function create()
    {
        $data = $this->request->getPost();

        // Conceptual: Set user audit fields. Replace with actual session user ID.
        // $loggedInUserId = session()->get('user_id'); // Example: Get logged-in user ID
        // $data['usuario_creacion_doc'] = $loggedInUserId;
        // $data['usuario_actualizacion_doc'] = $loggedInUserId;
        $data['usuario_creacion_doc'] = 'SYSTEM_USER_CREATE'; // Placeholder
        $data['usuario_actualizacion_doc'] = 'SYSTEM_USER_CREATE'; // Placeholder


        if ($this->docenteModel->save($data)) {
            session()->setFlashdata('success', 'Docente created successfully.');
            return redirect()->to(site_url('docentes'));
        } else {
            session()->setFlashdata('errors', $this->docenteModel->errors());
            session()->setFlashdata('old_input', $this->request->getPost());
            return redirect()->back()->withInput();
            // Or: return view('docentes/form', ['errors' => $this->docenteModel->errors(), 'docente' => $data]);
        }
    }

    public function edit($id_doc = null)
    {
        $data['docente'] = $this->docenteModel->find($id_doc);

        if (empty($data['docente'])) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException('Cannot find the docente item: ' . $id_doc);
        }

        $data['errors'] = session()->getFlashdata('errors'); // Get validation errors if redirected
        $data['old_input'] = session()->getFlashdata('old_input'); // Get old input if redirected (values for form)

        return view('docentes/form', $data);
    }

    public function update($id_doc = null)
    {
        $data = $this->request->getPost();

        // Conceptual: Set user audit fields. Replace with actual session user ID.
        // $loggedInUserId = session()->get('user_id'); // Example
        // $data['usuario_actualizacion_doc'] = $loggedInUserId;
        $data['usuario_actualizacion_doc'] = 'SYSTEM_USER_UPDATE'; // Placeholder

        // The model's update method needs the primary key as the first argument if it's not in $data.
        // If $id_doc is part of the form data (e.g. hidden field), it would be in $data.
        // Otherwise, pass it explicitly.
        // The save() method can also handle updates if primary key is present in $data.
        // For clarity, using update() method.

        if ($this->docenteModel->update($id_doc, $data)) {
            session()->setFlashdata('success', 'Docente updated successfully.');
            return redirect()->to(site_url('docentes'));
        } else {
            session()->setFlashdata('errors', $this->docenteModel->errors());
            session()->setFlashdata('old_input', $this->request->getPost());
             return redirect()->back()->withInput();
            // Or: return view('docentes/form', ['errors' => $this->docenteModel->errors(), 'docente' => array_merge($this->docenteModel->find($id_doc), $data)]);
        }
    }

    public function delete($id_doc = null)
    {
        if ($this->docenteModel->delete($id_doc)) {
            session()->setFlashdata('success', 'Docente deleted successfully.');
        } else {
            session()->setFlashdata('error', 'Failed to delete docente.');
        }
        return redirect()->to(site_url('docentes'));
    }
}
