<?php

// This snippet would be placed within the App/Config/Routes.php file,
// typically inside the $routes->group('/', function($routes) { ... }); callback if using default grouping,
// or directly if not using such a group for these routes.

// For Docente module
// Provides routes like:
// GET /docentes -> DocenteController::index()
// GET /docentes/new -> DocenteController::new()
// POST /docentes -> DocenteController::create()
// GET /docentes/(:num) -> DocenteController::show($1)
// GET /docentes/(:num)/edit -> DocenteController::edit($1)
// PUT/PATCH /docentes/(:num) -> DocenteController::update($1)
// DELETE /docentes/(:num) -> DocenteController::delete($1)
$routes->resource('docentes', ['controller' => 'DocenteController']);

// For Laboratorio module
// Provides similar RESTful routes for LaboratorioController mapped to /laboratorios
$routes->resource('laboratorios', ['controller' => 'LaboratorioController']);

// For Reserva module
// Provides similar RESTful routes for ReservaController mapped to /reservas
$routes->resource('reservas', ['controller' => 'ReservaController']);

/**
 * Note:
 * To use PUT/PATCH/DELETE methods correctly, forms in views might need to include a hidden field
 * like <input type="hidden" name="_method" value="PUT"> for updates, or ensure your AJAX calls
 * use the correct HTTP method. For simplicity, the generated views used GET for delete links
 * and standard POST for forms, which CodeIgniter's resource router can often accommodate for basic
 * create (POST) and update (POST, if not specifying PUT/PATCH explicitly in form).
 * True RESTful compliance might require more specific method handling in views/JS.
 *
 * The `resource` method is a convenient way to set up typical CRUD routes.
 * You can customize it further if needed (e.g., using `except` or `only` options in the third parameter).
 * Example: $routes->resource('photos', ['except' => ['new', 'edit']]);
 */

?>
