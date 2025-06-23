<?php

namespace Config;

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
$routes->get('/', 'HomeController::index'); // Main navigation page


// AUTHENTICATION ROUTES
// -------------------------------
$routes->get('login', 'AuthController::login');          // Display login form
$routes->post('login', 'AuthController::attemptLogin');   // Process login form submission
$routes->get('logout', 'AuthController::logout');         // Process logout


// DOCENTE
// -------------------------------
// Using specified method names: crear, guardar, editar, eliminar, index, show
$routes->get('docente', 'DocenteController::index');
$routes->get('docente/crear', 'DocenteController::crear');      // To show the creation form
$routes->post('docente/guardar', 'DocenteController::guardar');    // To process saving (new and updates)
$routes->get('docente/editar/(:num)', 'DocenteController::editar/$1');  // To show the edit form
$routes->get('docente/eliminar/(:num)', 'DocenteController::eliminar/$1'); // To process deletion
$routes->get('docente/show/(:num)', 'DocenteController::show/$1');      // To show a specific docente


// LABORATORIO
// -------------------------------
// Using specified method names: crear, guardar, editar, eliminar, index, show
$routes->get('laboratorio', 'LaboratorioController::index');
$routes->get('laboratorio/crear', 'LaboratorioController::crear');
$routes->post('laboratorio/guardar', 'LaboratorioController::guardar');
$routes->get('laboratorio/editar/(:num)', 'LaboratorioController::editar/$1');
$routes->get('laboratorio/eliminar/(:num)', 'LaboratorioController::eliminar/$1');
$routes->get('laboratorio/show/(:num)', 'LaboratorioController::show/$1');


// RESERVA
// -------------------------------
// Using specified method names: crear, guardar, editar, eliminar, index, show
$routes->get('reserva', 'ReservaController::index');
$routes->get('reserva/crear', 'ReservaController::crear');
$routes->post('reserva/guardar', 'ReservaController::guardar');
$routes->get('reserva/editar/(:num)', 'ReservaController::editar/$1');
$routes->get('reserva/eliminar/(:num)', 'ReservaController::eliminar/$1');
$routes->get('reserva/show/(:num)', 'ReservaController::show/$1');

/*
 * --------------------------------------------------------------------
 * Router Setup (Standard CodeIgniter Defaults)
 * --------------------------------------------------------------------
 */
$routes->setDefaultNamespace('App\Controllers');
$routes->setDefaultController('HomeController'); // Ensure HomeController.php exists
$routes->setDefaultMethod('index');
$routes->setTranslateURIDashes(false);
$routes->set404Override();
// It's generally recommended to set autoRoute to false when defining all routes explicitly.
// $routes->setAutoRoute(true); // Default CI setting, can be true or false based on preference.
$routes->setAutoRoute(false); // Setting to false as routes are explicitly defined.


/*
 * --------------------------------------------------------------------
 * Additional Routing
 * --------------------------------------------------------------------
 */
if (is_file(APPPATH . 'Config/' . ENVIRONMENT . '/Routes.php')) {
    require APPPATH . 'Config/' . ENVIRONMENT . '/Routes.php';
}

?>
