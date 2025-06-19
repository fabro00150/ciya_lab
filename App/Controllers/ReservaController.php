<?php

namespace App\Controllers;

use App\Models\ReservaModel;
use App\Models\DocenteModel;
use App\Models\LaboratorioModel;
use CodeIgniter\Controller;
use CodeIgniter\I18n\Time; // For date/time manipulation

class ReservaController extends BaseController
{
    protected $reservaModel;
    protected $docenteModel;
    protected $laboratorioModel;
    protected $helpers = ['form', 'url', 'date']; // Added 'date' helper

    public function __construct()
    {
        $this->reservaModel = new ReservaModel();
        $this->docenteModel = new DocenteModel();
        $this->laboratorioModel = new LaboratorioModel();
        helper($this->helpers);
    }

    public function index()
    {
        // Using the new model method to fetch details
        $data['reservas'] = $this->reservaModel->getReservasWithDetails();
        return view('reservas/index', $data);
    }

    public function show($id_res = null)
    {
        // Using the new model method to fetch details
        $reserva = $this->reservaModel->getReservaWithDetails($id_res);

        if (empty($reserva)) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException('Cannot find the reserva item: ' . $id_res);
        }
        $data['reserva'] = $reserva;
        return view('reservas/show', $data);
    }

    private function _loadDropdownData()
    {
        $data['docentes'] = $this->docenteModel->select('id_doc, nombre_doc, primer_apellido_doc, cedula_doc')
                                             ->orderBy('primer_apellido_doc', 'ASC')->findAll();
        $data['laboratorios'] = $this->laboratorioModel->select('id_lab, nombre_lab, siglas_lab')
                                                    ->orderBy('nombre_lab', 'ASC')->findAll();
        // Add other data like tipo_reserva, areas if needed from other models
        $data['estados_reserva'] = [
            'Solicitada' => 'Solicitada',
            'Confirmada' => 'Confirmada',
            'Cancelada' => 'Cancelada',
            'Realizada' => 'Realizada',
            'Rechazada' => 'Rechazada',
            'En Curso' => 'En Curso'
        ];
        return $data;
    }

    public function new()
    {
        $data = $this->_loadDropdownData();
        $data['reserva'] = null; // For form consistency
        $data['errors'] = session()->getFlashdata('errors');
        $data['old_input'] = session()->getFlashdata('old_input');
        return view('reservas/form', $data);
    }

    public function create()
    {
        $validation = \Config\Services::validation();
        $validation->setRules($this->reservaModel->getValidationRules()); // Use rules from model

        $postData = $this->request->getPost();

        // Ensure keys for special column names match exactly
        $dataToSave = [
            'fk_id_tipres' => $postData['fk_id_tipres'] ?? null,
            'fk_id_doc' => $postData['fk_id_doc'] ?? null,
            'fk_id_lab' => $postData['fk_id_lab'] ?? null,
            'fk_id_area' => $postData['fk_id_area'] ?? null,
            'fk_id_guia' => $postData['fk_id_guia'] ?? null,
            'tema_res' => $postData['tema_res'] ?? null,
            'comentario_res' => $postData['comentario_res'] ?? null,
            'estado_res' => $postData['estado_res'] ?? null,
            'fecha_hora_res' => $postData['fecha_hora_res'] ?? null,
            'duracion_res' => $postData['duracion_res'] ?? null,
            'numero_participantes_res' => $postData['numero_participantes_res'] ?? null,
            'descripcion_participantes_res' => $postData['descripcion_participantes_res'] ?? null,
            'materiales_res' => $postData['materiales_res'] ?? null,
            'observaciones_finales_res' => $postData['observaciones_finales_res'] ?? null,
            'asistencia_res' => $postData['asistencia_res'] ?? null,
            'guia_adjunta_res' => $postData['guia_adjunta_res'] ?? null,
            'curso_res' => $postData['curso_res'] ?? null,
            'materia_res' => $postData['materia_res'] ?? null,
            'fk_id_car-2' => $postData['fk_id_car-2'] ?? null, // Special name
            'paralelo_res' => $postData['paralelo_res'] ?? null,
            'tipo_texto_res' => $postData['tipo_texto_res'] ?? null,
            'fk_id_usu-2' => $postData['fk_id_usu-2'] ?? null, // Special name
            'software_res' => $postData['software_res'] ?? null,
            'tipo_res' => $postData['tipo_res'] ?? null,
            'pedidodocente_res' => $postData['pedidodocente_res'] ?? null, // Special name
            // usuario_creacion_res, usuario_actualizacion_res, and fecha_hora_fin_res will be handled
        ];


        // Conceptual: Set user audit fields
        $dataToSave['usuario_creacion_res'] = 'SYSTEM_USER_RES_CREATE'; // Placeholder
        $dataToSave['usuario_actualizacion_res'] = 'SYSTEM_USER_RES_CREATE'; // Placeholder

        // Calculate end time for availability check (Model's beforeInsert will also do this for saving)
        $calculatedEndTime = null;
        if (!empty($dataToSave['fecha_hora_res']) && !empty($dataToSave['duracion_res'])) {
            try {
                $startTime = new Time($dataToSave['fecha_hora_res']);
                $calculatedEndTime = $startTime->addMinutes((int)$dataToSave['duracion_res'])->toDateTimeString();
                $dataToSave['fecha_hora_fin_res'] = $calculatedEndTime; // Ensure it's set for saving if not using callback for this check
            } catch (\Exception $e) {
                // Error handling for date conversion
                session()->setFlashdata('error', 'Invalid date format or duration.');
                return redirect()->back()->withInput()->with('errors', ['date_error' => 'Invalid date format or duration.']);
            }
        } else {
             // This should be caught by validation, but as a safeguard:
            session()->setFlashdata('error', 'Start time and duration are required to check availability.');
            return redirect()->back()->withInput()->with('errors', ['availability_error' => 'Start time and duration are required.']);
        }


        if ($validation->run($dataToSave)) { // Validate the prepared data
            // Check availability
            $isAvailable = $this->reservaModel->checkAvailability(
                (int)$dataToSave['fk_id_lab'],
                $dataToSave['fecha_hora_res'],
                $calculatedEndTime // Use the calculated end time
            );

            if (!$isAvailable) {
                session()->setFlashdata('error', 'The selected laboratory is not available for the chosen time slot.');
                session()->setFlashdata('old_input', $postData); // Pass original post data back
                return redirect()->back()->withInput()->with('errors', ['availability_error' => 'Laboratory not available.']);
            }

            if ($this->reservaModel->save($dataToSave)) {
                session()->setFlashdata('success', 'Reserva created successfully.');
                return redirect()->to(site_url('reservas'));
            } else {
                // This case might be rare if validation and availability check passed, but could be DB error
                session()->setFlashdata('error', 'Failed to create reserva. Please try again.');
                session()->setFlashdata('old_input', $postData);
                session()->setFlashdata('errors', $this->reservaModel->errors()); // Get model's own errors if any
                return redirect()->back()->withInput();
            }
        } else {
            session()->setFlashdata('errors', $validation->getErrors());
            session()->setFlashdata('old_input', $postData); // Pass original post data back
            return redirect()->back()->withInput();
        }
    }

    public function edit($id_res = null)
    {
        $data = $this->_loadDropdownData();
        $data['reserva'] = $this->reservaModel->find($id_res);

        if (empty($data['reserva'])) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException('Cannot find the reserva item: ' . $id_res);
        }

        $data['errors'] = session()->getFlashdata('errors');
        // old_input is typically handled by withInput() redirect
        return view('reservas/form', $data);
    }

    public function update($id_res = null)
    {
        $validation = \Config\Services::validation();
        // Add {id_res} to unique rules if any were defined for update (not in this case yet)
        $validation->setRules($this->reservaModel->getValidationRules());

        $postData = $this->request->getPost();

        $dataToSave = [
            'fk_id_tipres' => $postData['fk_id_tipres'] ?? null,
            'fk_id_doc' => $postData['fk_id_doc'] ?? null,
            'fk_id_lab' => $postData['fk_id_lab'] ?? null,
            'fk_id_area' => $postData['fk_id_area'] ?? null,
            'fk_id_guia' => $postData['fk_id_guia'] ?? null,
            'tema_res' => $postData['tema_res'] ?? null,
            'comentario_res' => $postData['comentario_res'] ?? null,
            'estado_res' => $postData['estado_res'] ?? null,
            'fecha_hora_res' => $postData['fecha_hora_res'] ?? null,
            'duracion_res' => $postData['duracion_res'] ?? null,
            'numero_participantes_res' => $postData['numero_participantes_res'] ?? null,
            'descripcion_participantes_res' => $postData['descripcion_participantes_res'] ?? null,
            'materiales_res' => $postData['materiales_res'] ?? null,
            'observaciones_finales_res' => $postData['observaciones_finales_res'] ?? null,
            'asistencia_res' => $postData['asistencia_res'] ?? null,
            'guia_adjunta_res' => $postData['guia_adjunta_res'] ?? null,
            'curso_res' => $postData['curso_res'] ?? null,
            'materia_res' => $postData['materia_res'] ?? null,
            'fk_id_car-2' => $postData['fk_id_car-2'] ?? null,
            'paralelo_res' => $postData['paralelo_res'] ?? null,
            'tipo_texto_res' => $postData['tipo_texto_res'] ?? null,
            'fk_id_usu-2' => $postData['fk_id_usu-2'] ?? null,
            'software_res' => $postData['software_res'] ?? null,
            'tipo_res' => $postData['tipo_res'] ?? null,
            'pedidodocente_res' => $postData['pedidodocente_res'] ?? null,
        ];

        $dataToSave['usuario_actualizacion_res'] = 'SYSTEM_USER_RES_UPDATE'; // Placeholder

        $calculatedEndTime = null;
        if (!empty($dataToSave['fecha_hora_res']) && !empty($dataToSave['duracion_res'])) {
             try {
                $startTime = new Time($dataToSave['fecha_hora_res']);
                $calculatedEndTime = $startTime->addMinutes((int)$dataToSave['duracion_res'])->toDateTimeString();
                $dataToSave['fecha_hora_fin_res'] = $calculatedEndTime;
            } catch (\Exception $e) {
                session()->setFlashdata('error', 'Invalid date format or duration for update.');
                return redirect()->back()->withInput()->with('errors', ['date_error' => 'Invalid date format or duration.']);
            }
        } else {
            session()->setFlashdata('error', 'Start time and duration are required for update.');
            return redirect()->back()->withInput()->with('errors', ['availability_error' => 'Start time and duration are required.']);
        }

        if ($validation->run($dataToSave)) {
            $isAvailable = $this->reservaModel->checkAvailability(
                (int)$dataToSave['fk_id_lab'],
                $dataToSave['fecha_hora_res'],
                $calculatedEndTime,
                (int)$id_res // Exclude current reserva ID
            );

            if (!$isAvailable) {
                session()->setFlashdata('error', 'The selected laboratory is not available for the chosen time slot.');
                session()->setFlashdata('old_input', $postData);
                return redirect()->back()->withInput()->with('errors', ['availability_error' => 'Laboratory not available.']);
            }

            if ($this->reservaModel->update($id_res, $dataToSave)) {
                session()->setFlashdata('success', 'Reserva updated successfully.');
                return redirect()->to(site_url('reservas'));
            } else {
                session()->setFlashdata('error', 'Failed to update reserva. Please try again.');
                session()->setFlashdata('old_input', $postData);
                session()->setFlashdata('errors', $this->reservaModel->errors());
                return redirect()->back()->withInput();
            }
        } else {
            session()->setFlashdata('errors', $validation->getErrors());
            session()->setFlashdata('old_input', $postData);
            return redirect()->back()->withInput();
        }
    }

    public function delete($id_res = null)
    {
        if ($this->reservaModel->delete($id_res)) {
            session()->setFlashdata('success', 'Reserva deleted successfully.');
        } else {
            session()->setFlashdata('error', 'Failed to delete reserva.');
        }
        return redirect()->to(site_url('reservas'));
    }
}
