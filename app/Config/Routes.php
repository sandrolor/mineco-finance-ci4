<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
$routes->get('/', 'Auth::login');
$routes->get('login', 'Auth::login');
$routes->post('auth/login', 'Auth::login');
$routes->get('auth/logout', 'Auth::logout');

$routes->get('dashboard', 'Dashboard::index', ['filter' => 'auth']);

$routes->group('grupocontas', ['filter' => 'auth'], function ($routes) {
    $routes->get('/', 'GrupoContas::index');
    $routes->get('create', 'GrupoContas::create');
    $routes->post('store', 'GrupoContas::store');
    $routes->get('edit/(:num)', 'GrupoContas::edit/$1');
    $routes->post('update/(:num)', 'GrupoContas::update/$1');
    $routes->get('delete/(:num)', 'GrupoContas::delete/$1');
});