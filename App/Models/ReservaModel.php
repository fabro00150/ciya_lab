<?php

namespace App\Models;

use CodeIgniter\Model;
use CodeIgniter\I18n\Time;

class ReservaModel extends Model
{
    protected $table            = 'laboratorios.reserva'; // Schema included
    protected $primaryKey       = 'id_res';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;

    // AllowedFields: Timestamps (fecha_creacion_res, fecha_actualizacion_res) removed.
    // Audit fields (usuario_creacion_res, usuario_actualizacion_res) included.
    // Calculated fecha_hora_fin_res included.
    // Special column names as plain strings.
    protected $allowedFields    = [
        'fk_id_tipres', 'fk_id_doc', 'fk_id_lab', 'fk_id_area', 'fk_id_guia',
        'tema_res', 'comentario_res', 'estado_res', 'fecha_hora_res',
        'duracion_res', 'numero_participantes_res', 'descripcion_participantes_res',
        'materiales_res', 'usuario_creacion_res', 'usuario_actualizacion_res',
        'fecha_hora_fin_res', 'observaciones_finales_res', 'asistencia_res',
        'guia_adjunta_res', 'curso_res', 'materia_res', 'fk_id_car-2',
        'paralelo_res', 'tipo_texto_res', 'fk_id_usu-2', 'software_res',
        'tipo_res', 'pedidodocente_res'
    ];

    // Dates
    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'fecha_creacion_res';
    protected $updatedField  = 'fecha_actualizacion_res';
    // protected $deletedField  = 'deleted_at';

    // Validation
    // Note: For fields like 'fk_id_car-2', the key in validationRules must match the field name exactly.
    protected $validationRules      = [
        'fk_id_doc'     => 'required|integer',
        'fk_id_lab'     => 'required|integer',
        'tema_res'      => 'required|string|max_length[255]',
        'estado_res'    => 'required|string|max_length[50]', // Consider in_list[...]
        'fecha_hora_res'=> 'required', // Add valid_date if not automatically handled by CI for datetime
        'duracion_res'  => 'required|integer|greater_than[0]',
        'fk_id_car-2'   => 'required|integer', // Special column name
        'fk_id_usu-2'   => 'required|integer', // Special column name
        'fk_id_tipres'  => 'permit_empty|integer',
        'fk_id_area'    => 'permit_empty|integer',
        'fk_id_guia'    => 'permit_empty|integer',
        'numero_participantes_res' => 'permit_empty|integer|greater_than_equal_to[0]',
        'fecha_hora_fin_res' => 'required', // Also needs validation
    ];
    protected $validationMessages   = [
        'fk_id_doc' => ['required' => 'Responsible docente is required.', 'integer' => 'Docente ID must be an integer.'],
        'fk_id_lab' => ['required' => 'Laboratory is required.', 'integer' => 'Laboratory ID must be an integer.'],
        'tema_res'  => ['required' => 'The reservation theme/topic is required.'],
        'estado_res'=> ['required' => 'Reservation status is required.'],
        'fecha_hora_res' => ['required' => 'Reservation start date/time is required.'],
        'duracion_res' => ['required' => 'Duration is required.', 'integer' => 'Duration must be an integer (minutes).', 'greater_than' => 'Duration must be positive.'],
        'fk_id_car-2' => ['required' => 'Career ID (fk_id_car-2) is required.', 'integer' => 'Career ID must be an integer.'],
        'fk_id_usu-2' => ['required' => 'User ID (fk_id_usu-2) is required.', 'integer' => 'User ID must be an integer.'],
    ];
    protected $skipValidation       = false;
    protected $cleanValidationRules = true;

    // Callbacks
    protected $allowCallbacks = true;
    protected $beforeInsert   = ['calculateEndTime'];
    protected $beforeUpdate   = ['calculateEndTime'];
    // protected $afterInsert    = [];
    // protected $afterUpdate    = [];
    // protected $beforeFind     = [];
    // protected $afterFind      = [];
    // protected $beforeDelete   = [];
    // protected $afterDelete    = [];

    /**
     * Callback function to calculate fecha_hora_fin_res.
     */
    protected function calculateEndTime(array $data): array
    {
        if (isset($data['data']['fecha_hora_res']) && isset($data['data']['duracion_res'])) {
            try {
                $startTime = new Time($data['data']['fecha_hora_res']);
                $durationMinutes = (int)$data['data']['duracion_res'];
                $data['data']['fecha_hora_fin_res'] = $startTime->addMinutes($durationMinutes)->toDateTimeString();
            } catch (\Exception $e) {
                // Handle invalid date format if necessary, though CI's Time class is robust.
                // Consider logging the error.
                // For now, if calculation fails, it might be caught by DB constraints or further validation.
            }
        }
        return $data;
    }

    /**
     * Checks if a laboratory is available for a given time slot, excluding a specific reservation.
     *
     * @param int    $lab_id          ID of the laboratory.
     * @param string $startTime       Start time of the reservation (Y-m-d H:i:s).
     * @param string $endTime         End time of the reservation (Y-m-d H:i:s).
     * @param int|null $excludeReservaId ID of a reservation to exclude from the check (e.g., when updating).
     * @return bool True if available, false otherwise.
     */
    public function checkAvailability(int $lab_id, string $startTime, string $endTime, ?int $excludeReservaId = null): bool
    {
        // Ensure times are in the correct format for DB comparison
        // $startTimeDB = Time::parse($startTime)->toDateTimeString();
        // $endTimeDB = Time::parse($endTime)->toDateTimeString();

        $builder = $this->db->table($this->table)
            ->where('fk_id_lab', $lab_id)
            ->where('estado_res !=', 'Cancelada') // Only consider active or confirmed reservations
            // Check for overlapping reservations:
            // A reservation overlaps if its start time is before the new one ends,
            // AND its end time is after the new one starts.
            ->where('fecha_hora_res <', $endTime)   // Existing reservation starts before the new one ends
            ->where('fecha_hora_fin_res >', $startTime); // Existing reservation ends after the new one starts

        if ($excludeReservaId !== null) {
            $builder->where('id_res !=', $excludeReservaId);
        }

        log_message('debug', 'Availability Check Query: ' . $builder->getCompiledSelect(false));
        $overlappingReservations = $builder->countAllResults();
        log_message('debug', 'Overlapping reservations found: ' . $overlappingReservations);

        return $overlappingReservations === 0;
    }

    /**
     * Get reservations with related data (laboratorio name, docente name).
     */
    public function getReservasWithDetails($limit = 10, $offset = 0)
    {
        $builder = $this->db->table($this->table . ' r');
        $builder->select('r.*, l.nombre_lab, d.nombre_doc, d.primer_apellido_doc');
        $builder->join('laboratorios.laboratorio l', 'l.id_lab = r.fk_id_lab', 'left');
        $builder->join('public.docente d', 'd.id_doc = r.fk_id_doc', 'left');
        $builder->orderBy('r.fecha_hora_res', 'DESC');
        $builder->limit($limit, $offset);
        return $builder->get()->getResultArray();
    }

    /**
     * Get a single reservation with related data.
     */
    public function getReservaWithDetails($id_res)
    {
        $builder = $this->db->table($this->table . ' r');
        $builder->select('r.*, l.nombre_lab, d.nombre_doc, d.primer_apellido_doc, d.segundo_apellido_doc');
        $builder->join('laboratorios.laboratorio l', 'l.id_lab = r.fk_id_lab', 'left');
        $builder->join('public.docente d', 'd.id_doc = r.fk_id_doc', 'left');
        $builder->where('r.id_res', $id_res);
        return $builder->get()->getRowArray();
    }
}
