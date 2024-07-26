<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
$routes->get('/', 'Home::index');


//API Routes
$routes->group('api', ['namespace' => 'App\Controllers\Api'], function ($routes) {
    $routes->post('add-student', 'ApiController::addStudent');
    $routes->get('list-students', 'ApiController::listStudents');
    $routes->get('single-student/(:segment)', 'ApiController::singleStudentData/$1');

    $routes->put('update-student/(:segment)', 'ApiController::updateStudent/$1');
    $routes->delete('delete-student/(:segment)', 'ApiController::deleteStudent/$1');
});
