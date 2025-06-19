<?php
namespace App\Controllers;

use App\Models\LaboratorioModel;
use App\Models\DocenteModel;
use App\Models\AdministrativoModel;

class Laboratorio extends BaseController
{
    protected $laboratorioModel;
    protected $docenteModel;
    protected $adminModel;
    
    public function __construct()
    {
        $this->laboratorioModel = new LaboratorioModel();
        $this->docenteModel = new DocenteModel();
        $this->adminModel = new AdministrativoModel();
        helper('form');
    }
    
    // Listar todos los laboratorios
    public function index()
    {
        $data = [
            'title' => 'Gestión de Laboratorios',
            'laboratorios' => $this->laboratorioModel->getLaboratoriosConResponsables(),
        ];
        
        return view('laboratorio/index', $data);
    }
    
    // Mostrar formulario de creación
    public function crear()
    {
        $data = [
            'title' => 'Nuevo Laboratorio',
            'docentes' => $this->docenteModel->findAll(),
            'administrativos' => $this->adminModel->findAll(),
            'facultades' => $this->laboratorioModel->distinct()->select('facultad_lab')->findAll()
        ];
        
        return view('laboratorio/crear', $data);
    }
    
    // Guardar nuevo laboratorio
    public function guardar()
    {
        $rules = [
            'nombre_lab' => 'required|min_length[3]|max_length[100]',
            'facultad_lab' => 'required',
            'ubicacion_lab' => 'required'
        ];
        
        if ($this->validate($rules)) {
            // Procesar imágenes si se suben
            $foto1 = $this->processImage('fotografia1_lab');
            $foto2 = $this->processImage('fotografia2_lab');
            
            $data = [
                'nombre_lab' => $this->request->getPost('nombre_lab'),
                'descripcion_lab' => $this->request->getPost('descripcion_lab'),
                'fotografia1_lab' => $foto1,
                'fotografia2_lab' => $foto2,
                'fk_docente_responsable_lab' => $this->request->getPost('fk_docente_responsable_lab'),
                'fk_administrativo_responsable_lab' => $this->request->getPost('fk_administrativo_responsable_lab'),
                'tipo_lab' => $this->request->getPost('tipo_lab'),
                'ubicacion_lab' => $this->request->getPost('ubicacion_lab'),
                'color_lab' => $this->request->getPost('color_lab'),
                'fk_administrativo_responsable_secundario_lab' => $this->request->getPost('fk_administrativo_responsable_secundario_lab'),
                'siglas_lab' => $this->request->getPost('siglas_lab'),
                'paralelo_guia' => $this->request->getPost('paralelo_guia'),
                'facultad_lab' => $this->request->getPost('facultad_lab'),
                'usuario_creacion_lab' => session()->get('user_id'), // Asumiendo que hay sesión de usuario
                'usuario_actualizacion_lab' => session()->get('user_id')
            ];
            
            $this->laboratorioModel->insert($data);
            return redirect()->to('/laboratorio')->with('success', 'Laboratorio creado exitosamente');
        }
        
        return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
    }
    
    // Mostrar formulario de edición
    public function editar($id)
    {
        $data = [
            'title' => 'Editar Laboratorio',
            'laboratorio' => $this->laboratorioModel->find($id),
            'docentes' => $this->docenteModel->findAll(),
            'administrativos' => $this->adminModel->findAll(),
            'facultades' => $this->laboratorioModel->distinct()->select('facultad_lab')->findAll()
        ];
        
        return view('laboratorio/editar', $data);
    }
    
    // Actualizar laboratorio
    public function actualizar($id)
    {
        $rules = [
            'nombre_lab' => 'required|min_length[3]|max_length[100]',
            'facultad_lab' => 'required',
            'ubicacion_lab' => 'required'
        ];
        
        if ($this->validate($rules)) {
            $laboratorio = $this->laboratorioModel->find($id);
            
            $data = [
                'nombre_lab' => $this->request->getPost('nombre_lab'),
                'descripcion_lab' => $this->request->getPost('descripcion_lab'),
                'fk_docente_responsable_lab' => $this->request->getPost('fk_docente_responsable_lab'),
                'fk_administrativo_responsable_lab' => $this->request->getPost('fk_administrativo_responsable_lab'),
                'tipo_lab' => $this->request->getPost('tipo_lab'),
                'ubicacion_lab' => $this->request->getPost('ubicacion_lab'),
                'color_lab' => $this->request->getPost('color_lab'),
                'fk_administrativo_responsable_secundario_lab' => $this->request->getPost('fk_administrativo_responsable_secundario_lab'),
                'siglas_lab' => $this->request->getPost('siglas_lab'),
                'paralelo_guia' => $this->request->getPost('paralelo_guia'),
                'facultad_lab' => $this->request->getPost('facultad_lab'),
                'usuario_actualizacion_lab' => session()->get('user_id')
            ];
            
            // Procesar imágenes solo si se suben nuevas
            if ($foto1 = $this->processImage('fotografia1_lab')) {
                $data['fotografia1_lab'] = $foto1;
                // Eliminar foto anterior si existe
                if ($laboratorio['fotografia1_lab']) {
                    unlink(WRITEPATH . 'uploads/laboratorios/' . $laboratorio['fotografia1_lab']);
                }
            }
            
            if ($foto2 = $this->processImage('fotografia2_lab')) {
                $data['fotografia2_lab'] = $foto2;
                if ($laboratorio['fotografia2_lab']) {
                    unlink(WRITEPATH . 'uploads/laboratorios/' . $laboratorio['fotografia2_lab']);
                }
            }
            
            $this->laboratorioModel->update($id, $data);
            return redirect()->to('/laboratorio')->with('success', 'Laboratorio actualizado exitosamente');
        }
        
        return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
    }
    
    // Eliminar laboratorio
    public function eliminar($id)
    {
        $laboratorio = $this->laboratorioModel->find($id);
        
        // Eliminar fotos asociadas
        if ($laboratorio['fotografia1_lab']) {
            unlink(WRITEPATH . 'uploads/laboratorios/' . $laboratorio['fotografia1_lab']);
        }
        if ($laboratorio['fotografia2_lab']) {
            unlink(WRITEPATH . 'uploads/laboratorios/' . $laboratorio['fotografia2_lab']);
        }
        
        $this->laboratorioModel->delete($id);
        return redirect()->to('/laboratorio')->with('success', 'Laboratorio eliminado exitosamente');
    }
    
    // Método privado para procesar imágenes
    private function processImage($fieldName)
    {
        $file = $this->request->getFile($fieldName);
        
        if ($file->isValid() && !$file->hasMoved()) {
            $newName = $file->getRandomName();
            $file->move(WRITEPATH . 'uploads/laboratorios', $newName);
            return $newName;
        }
        
        return null;
    }
}