<?php

namespace App\Controllers;

use App\Controllers\BaseController; // Explicitly use BaseController

class HomeController extends BaseController
{
    public function index(): string
    {
        helper('url'); // Load the URL helper

        $data['title'] = 'Sistema de Gestión - Inicio';

        // Assuming you have a view file at App/Views/home/index.php
        // If not, this will cause an error when the route is accessed.
        // This task only asks for the controller generation.
        return view('home/index', $data);
    }
}
