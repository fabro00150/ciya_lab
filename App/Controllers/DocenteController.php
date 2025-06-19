<?php

namespace App\Controllers;

use App\Models\DocenteModel;
use CodeIgniter\Controller; // Should be App\Controllers\BaseController

class DocenteController extends BaseController // Ensure it extends your BaseController
{
    protected $docenteModel;
    protected $helpers = ['form', 'url'];

    public function __construct()
    {
        $this->docenteModel = new DocenteModel();
        helper($this->helpers);
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

    // Renamed from new()
    public function crear()
    {
        $data['docente'] = null;
        $data['errors'] = session()->getFlashdata('errors');
        $data['old_input'] = session()->getFlashdata('old_input');
        return view('docentes/form', $data);
    }

    // Renamed from edit()
    public function editar($id_doc = null)
    {
        $data['docente'] = $this->docenteModel->find($id_doc);

        if (empty($data['docente'])) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException('Cannot find the docente item: ' . $id_doc);
        }

        $data['errors'] = session()->getFlashdata('errors');
        // Old input is typically handled by redirect()->withInput() but can be explicitly passed if needed
        // $data['old_input'] = session()->getFlashdata('old_input') ?? $data['docente'];
        // Let's rely on withInput() for now, form.php will handle fetching old_input or $docente data.

        return view('docentes/form', $data);
    }

    public function guardar()
    {
        $postData = $this->request->getPost();
        $id_doc = $postData['id_doc'] ?? null; // Check for id_doc to determine insert or update

        $validation = \Config\Services::validation();
        $rules = $this->docenteModel->getValidationRules(); // Get base rules

        if (!empty($id_doc)) { // UPDATE
            // Adjust validation rules for update (is_unique needs to ignore current ID)
            // The placeholder {id_doc} in model rules is for the current ID.
            // So, we need to replace it with the actual $id_doc.
            // This is simpler if model rules are defined with {id} placeholder.
            // Let's assume the model rules are like: 'email_doc' => 'required|valid_email|is_unique[public.docente.email_doc,id_doc,{id_doc}]'
            // If the model rule is 'email_doc' => 'required|valid_email|is_unique[public.docente.email_doc,id_doc,{id}]'
            // then the model's save method (when id is present) or update method handles this automatically.
            // However, if we are calling $validation->setRules() manually, we need to be careful.
            // For this refactor, we will rely on the model's `save()` method to correctly handle update validation if an ID is present in the data.
            // Or, more explicitly for `update()` method of model:

            // Re-fetch rules to ensure we have the base set.
            $currentRules = $this->docenteModel->getValidationRules();
            $updateRules = [
                'cedula_doc' => "required|is_unique[public.docente.cedula_doc,id_doc,{$id_doc}]",
                'email_doc'  => "required|valid_email|is_unique[public.docente.email_doc,id_doc,{$id_doc}]",
                'primer_apellido_doc' => $currentRules['primer_apellido_doc'] ?? 'required',
                'nombre_doc' => $currentRules['nombre_doc'] ?? 'required',
                'fk_id_car' => $currentRules['fk_id_car'] ?? 'required|integer',
                'fk_id_usu' => $currentRules['fk_id_usu'] ?? 'required|integer',
                // Add other fields that need validation on update
            ];
            $validation->setRules($updateRules);

            // Conceptual: Set user audit fields.
            $postData['usuario_actualizacion_doc'] = 'SYSTEM_USER_UPDATE'; // Placeholder

            if ($validation->run($postData)) {
                if ($this->docenteModel->update($id_doc, $postData)) {
                    session()->setFlashdata('success', 'Docente actualizado correctamente.');
                    return redirect()->to(site_url('docente'));
                } else {
                    session()->setFlashdata('error', 'Error al actualizar el docente.');
                    // $data_view = ['errors' => $this->docenteModel->errors(), 'docente' => array_merge($this->docenteModel->find($id_doc), $postData)];
                    // return view('docentes/form', $data_view);
                    return redirect()->back()->withInput()->with('errors', $this->docenteModel->errors());
                }
            } else {
                session()->setFlashdata('errors', $validation->getErrors());
                // $data_view = ['errors' => $validation->getErrors(), 'docente' => array_merge($this->docenteModel->find($id_doc), $postData)];
                // return view('docentes/form', $data_view);
                return redirect()->to(site_url('docente/editar/'.$id_doc))->withInput()->with('errors', $validation->getErrors());
            }

        } else { // INSERT
            // Conceptual: Set user audit fields.
            $postData['usuario_creacion_doc'] = 'SYSTEM_USER_CREATE'; // Placeholder
            $postData['usuario_actualizacion_doc'] = 'SYSTEM_USER_CREATE'; // Placeholder

            // For insert, use the model's default validation rules.
            // The model's save method will use its validation rules automatically if no rules are passed to validate().
            // $validation->setRules($this->docenteModel->getValidationRules());

            // The model's save() method handles validation internally if $data is passed.
            if ($this->docenteModel->save($postData)) {
                session()->setFlashdata('success', 'Docente creado correctamente.');
                return redirect()->to(site_url('docente'));
            } else {
                session()->setFlashdata('errors', $this->docenteModel->errors());
                // $data_view = ['errors' => $this->docenteModel->errors(), 'docente' => $postData, 'old_input' => $postData];
                // return view('docentes/form', $data_view);
                return redirect()->to(site_url('docente/crear'))->withInput()->with('errors', $this->docenteModel->errors());
            }
        }
    }

    // Renamed from delete()
    public function eliminar($id_doc = null)
    {
        if ($this->docenteModel->delete($id_doc)) {
            session()->setFlashdata('success', 'Docente eliminado correctamente.');
        } else {
            session()->setFlashdata('error', 'Error al eliminar el docente.');
        }
        return redirect()->to(site_url('docente'));
    }
}
