<?php

// Laboratorio
// Ruta básica de ejemplo
$routes->get('/', 'Home::index');
$routes->get('test', 'Test::index');
$routes->get('laboratorio', 'Laboratorio::index');

// Laboratorios
$routes->group('laboratorios', function($routes) {
    $routes->get('/', 'Laboratorio::index');
    $routes->get('crear', 'Laboratorio::crear');
    $routes->post('guardar', 'Laboratorio::guardar');
    $routes->get('editar/(:num)', 'Laboratorio::editar/$1');
    $routes->post('actualizar/(:num)', 'Laboratorio::actualizar/$1');
    $routes->get('eliminar/(:num)', 'Laboratorio::eliminar/$1');
});
// Docente (misma estructura)
$routes->get('docente', 'Docente::index');
// ... otras rutas para docente

// Reserva (misma estructura)
$routes->get('reserva', 'Reserva::index');
// ... otras rutas para reserva