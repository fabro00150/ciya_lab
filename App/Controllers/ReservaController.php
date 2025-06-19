<?php

namespace App\Controllers;

use App\Models\ReservaModel;
use App\Models\DocenteModel;
use App\Models\LaboratorioModel;
// use CodeIgniter\Controller; // BaseController is already extended
use CodeIgniter\I18n\Time; // For date/time manipulation

class ReservaController extends BaseController
{
    protected $reservaModel;
    protected $docenteModel;
    protected $laboratorioModel;
    protected $helpers = ['form', 'url', 'date'];

    public function __construct()
    {
        $this->reservaModel = new ReservaModel();
        $this->docenteModel = new DocenteModel();
        $this->laboratorioModel = new LaboratorioModel();
        helper($this->helpers);
    }

    public function index()
    {
        $data['reservas'] = $this->reservaModel->getReservasWithDetails();
        return view('reservas/index', $data);
    }

    public function show($id_res = null)
    {
        $reserva = $this->reservaModel->getReservaWithDetails($id_res);

        if (empty($reserva)) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException('No se encontró la reserva con ID: ' . $id_res);
        }
        $data['reserva'] = $reserva;
        return view('reservas/show', $data);
    }

    private function _loadDropdownData()
    {
        $data['docentes'] = $this->docenteModel->select('id_doc, nombre_doc, primer_apellido_doc, segundo_apellido_doc, cedula_doc')
                                             ->orderBy('primer_apellido_doc', 'ASC')->findAll();
        $data['laboratorios'] = $this->laboratorioModel->select('id_lab, nombre_lab, siglas_lab')
                                                    ->orderBy('nombre_lab', 'ASC')->findAll();
        $data['estados_reserva'] = [
            'Solicitada' => 'Solicitada',
            'Confirmada' => 'Confirmada',
            'Cancelada' => 'Cancelada',
            'Realizada' => 'Realizada',
            'Rechazada' => 'Rechazada',
            'En Curso' => 'En Curso'
        ];
        // Add other data like tipo_reserva, areas if needed from other models
        return $data;
    }

    // Renamed from new()
    public function crear()
    {
        $data = $this->_loadDropdownData();
        $data['reserva'] = null;
        $data['errors'] = session()->getFlashdata('errors');
        $data['old_input'] = session()->getFlashdata('old_input');
        return view('reservas/form', $data);
    }

    // Renamed from edit()
    public function editar($id_res = null)
    {
        $data = $this->_loadDropdownData();
        $data['reserva'] = $this->reservaModel->find($id_res);

        if (empty($data['reserva'])) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException('No se encontró la reserva con ID: ' . $id_res);
        }

        $data['errors'] = session()->getFlashdata('errors');
        // old_input is handled by withInput() redirect
        return view('reservas/form', $data);
    }

    public function guardar()
    {
        $postData = $this->request->getPost();
        $id_res = $postData['id_res'] ?? null;

        $validation = \Config\Services::validation();
        // Model rules will be used by $this->reservaModel->save/update if not set explicitly on $validation
        // However, for custom error messages or specific scenarios, setting them here is useful.
        $validation->setRules($this->reservaModel->getValidationRules());

        // Prepare data for saving, ensuring all fields from $allowedFields are considered
        // and keys for special column names match exactly.
        $dataToSave = [];
        foreach ($this->reservaModel->allowedFields as $field) {
            if (isset($postData[$field])) {
                $dataToSave[$field] = $postData[$field];
            }
        }
        // Ensure boolean value for pedidodocente_res if it's a checkbox or select
        $dataToSave['pedidodocente_res'] = isset($postData['pedidodocente_res']) ? filter_var($postData['pedidodocente_res'], FILTER_VALIDATE_BOOLEAN) : false;


        // Calculate fecha_hora_fin_res
        $calculatedEndTime = null;
        if (!empty($dataToSave['fecha_hora_res']) && !empty($dataToSave['duracion_res'])) {
            try {
                $startTime = new Time($dataToSave['fecha_hora_res']);
                $durationMinutes = (int)$dataToSave['duracion_res'];
                if ($durationMinutes <= 0) {
                    // Add error to validation instance directly
                    $validation->setError('duracion_res', 'La duración debe ser un número positivo de minutos.');
                } else {
                    $calculatedEndTime = $startTime->addMinutes($durationMinutes)->toDateTimeString();
                    $dataToSave['fecha_hora_fin_res'] = $calculatedEndTime; // Add to data to be saved
                }
            } catch (\Exception $e) {
                $validation->setError('fecha_hora_res', 'Formato de fecha/hora de inicio inválido.');
            }
        } else {
            if (empty($dataToSave['fecha_hora_res'])) $validation->setError('fecha_hora_res', 'La fecha y hora de inicio es requerida.');
            if (empty($dataToSave['duracion_res'])) $validation->setError('duracion_res', 'La duración es requerida.');
        }

        // Run initial validation for basic field requirements
        if (!$validation->run($dataToSave)) {
            // If basic validation fails (e.g. required fields missing before availability check)
            $viewData = $this->_loadDropdownData();
            $viewData['errors'] = $validation->getErrors();
            $viewData['old_input'] = $postData; // Use original postData for old_input
            $viewData['reserva'] = $id_res ? $this->reservaModel->find($id_res) : null; // Keep existing data if editing

            $form_view_path = 'reservas/form';
            // session()->setFlashdata('errors', $validation->getErrors());
            // session()->setFlashdata('old_input', $postData);
            // $redirect_url = $id_res ? site_url('reserva/editar/'.$id_res) : site_url('reserva/crear');
            // return redirect()->to($redirect_url)->withInput()->with('errors', $validation->getErrors());
            return view($form_view_path, array_merge($viewData, ['errors' => $validation->getErrors()]));
        }


        // Availability Check (only if basic validation passed and we have necessary data)
        if ($calculatedEndTime) { // Ensure calculation was successful
            $isAvailable = $this->reservaModel->checkAvailability(
                (int)$dataToSave['fk_id_lab'],
                $dataToSave['fecha_hora_res'],
                $calculatedEndTime,
                $id_res ? (int)$id_res : null // Pass $id_res if updating
            );

            if (!$isAvailable) {
                // Add custom error to the validation service instance
                $validation->setError('fk_id_lab', 'El laboratorio no está disponible en el horario seleccionado.');
            }
        }

        // Re-check validation after availability check might have added an error
        if (!$validation->run($dataToSave) || (isset($isAvailable) && !$isAvailable) ) {
            $viewData = $this->_loadDropdownData();
            // Merge existing errors with potential new availability error
            $currentErrors = $validation->getErrors();
            if (isset($isAvailable) && !$isAvailable && !isset($currentErrors['fk_id_lab'])) {
                 $currentErrors['fk_id_lab'] = 'El laboratorio no está disponible en el horario seleccionado.';
            }

            $viewData['errors'] = $currentErrors;
            $viewData['old_input'] = $postData;
            $viewData['reserva'] = $id_res ? $this->reservaModel->find($id_res) : $dataToSave; // Use dataToSave for create form repopulation

            $form_view_path = 'reservas/form';
            // session()->setFlashdata('errors', $currentErrors);
            // session()->setFlashdata('old_input', $postData);
            // $redirect_url = $id_res ? site_url('reserva/editar/'.$id_res) : site_url('reserva/crear');
            // return redirect()->to($redirect_url)->withInput()->with('errors', $currentErrors);
             return view($form_view_path, array_merge($viewData, ['errors' => $currentErrors]));
        }


        // Proceed to save/update if all checks passed
        if (!empty($id_res)) { // UPDATE
            $dataToSave['usuario_actualizacion_res'] = 'SYSTEM_USER_RES_UPDATE'; // Placeholder
            if ($this->reservaModel->update($id_res, $dataToSave)) {
                session()->setFlashdata('success', 'Reserva actualizada correctamente.');
                return redirect()->to(site_url('reserva'));
            } else {
                session()->setFlashdata('error', 'Error al actualizar la reserva.');
                // $this->reservaModel->errors() might be empty if DB error not validation
            }
        } else { // INSERT
            $dataToSave['usuario_creacion_res'] = 'SYSTEM_USER_RES_CREATE';
            $dataToSave['usuario_actualizacion_res'] = 'SYSTEM_USER_RES_CREATE';
            if ($this->reservaModel->save($dataToSave)) {
                session()->setFlashdata('success', 'Reserva creada correctamente.');
                return redirect()->to(site_url('reserva'));
            } else {
                session()->setFlashdata('error', 'Error al crear la reserva.');
                 // $this->reservaModel->errors() will contain validation errors if save failed due to them
            }
        }

        // Fallback redirect if save/update fails for non-validation reasons (e.g. DB issue)
        // Or if specific errors from model need to be passed
        $viewData = $this->_loadDropdownData();
        $viewData['errors'] = $this->reservaModel->errors() ?: (session()->getFlashdata('errors') ?: ['general_error' => session()->getFlashdata('error') ?? 'Ocurrió un error desconocido.']);
        $viewData['old_input'] = $postData;
        $viewData['reserva'] = $id_res ? $this->reservaModel->find($id_res) : $dataToSave;

        // session()->setFlashdata('errors', $viewData['errors']);
        // session()->setFlashdata('old_input', $postData);
        // $redirect_url = $id_res ? site_url('reserva/editar/'.$id_res) : site_url('reserva/crear');
        // return redirect()->to($redirect_url)->withInput();
        return view('reservas/form', array_merge($viewData, ['errors' => $viewData['errors']]));
    }

    // Renamed from delete()
    public function eliminar($id_res = null)
    {
        $reserva = $this->reservaModel->find($id_res);
        if (empty($reserva)) {
            session()->setFlashdata('error', 'Reserva no encontrada.');
            return redirect()->to(site_url('reserva'));
        }

        if ($this->reservaModel->delete($id_res)) {
            session()->setFlashdata('success', 'Reserva eliminada correctamente.');
        } else {
            $dbError = $this->reservaModel->db->error();
            if ($dbError && !empty($dbError['message'])) {
                 session()->setFlashdata('error', 'Error al eliminar la reserva. (DB Error: ' . $dbError['code'] .')');
            } else {
                 session()->setFlashdata('error', 'Error al eliminar la reserva.');
            }
        }
        return redirect()->to(site_url('reserva'));
    }
}
