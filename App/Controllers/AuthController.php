<?php

namespace App\Controllers;

use App\Models\DocenteModel; // Correctly use the DocenteModel for authentication

class AuthController extends BaseController
{
    protected $helpers = ['form', 'url']; // Autoload helpers

    public function login()
    {
        $session = session();

        // If already logged in, redirect to home
        if ($session->get('isLoggedIn')) {
            return redirect()->to(site_url('/'));
        }

        $data['title'] = 'Iniciar Sesión';
        return view('auth/login', $data);
    }

    public function attemptLogin()
    {
        $session = session();
        $validation = \Config\Services::validation();
        $docenteModel = new DocenteModel();

        // Define validation rules
        $rules = [
            'email_doc' => 'required|valid_email',
            'cedula_doc' => 'required|string' // Cedula is used as password
        ];
        $messages = [
            'email_doc' => [
                'required' => 'El correo electrónico es obligatorio.',
                'valid_email' => 'Por favor, ingrese un correo electrónico válido.'
            ],
            'cedula_doc' => [
                'required' => 'La cédula (contraseña) es obligatoria.'
            ]
        ];

        if (!$this->validate($rules, $messages)) {
            // If validation fails, redirect back with errors and old input
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        // Validation passed, attempt to log in
        $email = $this->request->getPost('email_doc');
        $password_cedula = $this->request->getPost('cedula_doc');

        $docente = $docenteModel->where('email_doc', $email)->first();

        if ($docente) {
            // Verify "password" (which is cedula_doc in this case)
            // Note: This is NOT secure password handling. Passwords should always be hashed.
            // This is implemented as per the prompt's requirement of matching cedula_doc.
            if ($password_cedula === $docente['cedula_doc']) {
                // Valid credentials
                $sessionData = [
                    'id_doc'       => $docente['id_doc'],
                    'nombre_doc'   => trim($docente['nombre_doc'] . ' ' . $docente['primer_apellido_doc'] . ' ' . ($docente['segundo_apellido_doc'] ?? '')),
                    'email_doc'    => $docente['email_doc'],
                    // Add any other relevant user data, e.g., role, fk_id_usu
                    'fk_id_usu'    => $docente['fk_id_usu'] ?? null,
                    'isLoggedIn'   => true,
                ];
                $session->set($sessionData);
                session()->setFlashdata('success', '¡Bienvenido! Sesión iniciada correctamente.');
                return redirect()->to(site_url('/')); // Redirect to dashboard or home page
            }
        }

        // Invalid credentials or docente not found
        session()->setFlashdata('error', 'Credenciales inválidas. Por favor, intente de nuevo.');
        return redirect()->back()->withInput();
    }

    public function logout()
    {
        $session = session();
        $session->destroy();
        return redirect()->to(site_url('auth/login'))->with('success', 'Sesión cerrada correctamente.');
    }
}
