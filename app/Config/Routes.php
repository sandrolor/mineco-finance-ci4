<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */

$routes->get('/', 'Auth::login');
$routes->get('login', 'Auth::login');
$routes->post('auth/login', 'Auth::login');
$routes->get('auth/logout', 'Auth::logout');

// Rotas protegidas pelo filtro de autenticação
$routes->group('', ['filter' => 'auth'], function ($routes) {

    $routes->get('dashboard', 'Dashboard::index');

    $routes->get('/', 'GrupoContas::index');
    $routes->get('create', 'GrupoContas::create');
    $routes->post('store', 'GrupoContas::store');
    $routes->get('edit/(:num)', 'GrupoContas::edit/$1');
    $routes->post('update/(:num)', 'GrupoContas::update/$1');
    $routes->get('delete/(:num)', 'GrupoContas::delete/$1');

    $routes->get('/', 'GrupoCategorias::index');
    $routes->get('create', 'GrupoCategorias::create');
    $routes->post('store', 'GrupoCategorias::store');
    $routes->get('edit/(:num)', 'GrupoCategorias::edit/$1');
    $routes->post('update/(:num)', 'GrupoCategorias::update/$1');
    $routes->get('delete/(:num)', 'GrupoCategorias::delete/$1');

    $routes->get('', 'Contas::index');              // Listagem de contas
    $routes->get('create', 'Contas::create');       // Formulário de criação
    $routes->post('store', 'Contas::store');        // Salvar nova conta
    $routes->get('edit/(:num)', 'Contas::edit/$1'); // Formulário de edição
    $routes->post('update/(:num)', 'Contas::update/$1'); // Atualizar conta
    $routes->get('delete/(:num)', 'Contas::delete/$1');  // Excluir conta

    $routes->get('', 'Categorias::index');              // Listagem de categorias
    $routes->get('create', 'Categorias::create');       // Formulário de criação
    $routes->post('store', 'Categorias::store');        // Salvar nova categoria
    $routes->get('edit/(:num)', 'Categorias::edit/$1'); // Formulário de edição
    $routes->post('update/(:num)', 'Categorias::update/$1'); // Atualizar categoria
    $routes->get('delete/(:num)', 'Categorias::delete/$1');  // Excluir categoria

    $routes->get('/', 'Movimento::index');
    $routes->get('create', 'Movimento::create');
    $routes->post('store', 'Movimento::store');
    $routes->get('edit/(:num)', 'Movimento::edit/$1');
    $routes->post('update/(:num)', 'Movimento::update/$1');
    $routes->get('delete/(:num)', 'Movimento::delete/$1');

    $routes->get('/', 'Transferencias::index'); // Listagem de transferências
    $routes->get('create', 'Transferencias::create'); // Formulário de nova transferência
    $routes->post('store', 'Transferencias::store'); // Salvar nova transferência
    $routes->get('edit/(:num)', 'Transferencias::edit/$1'); // Formulário de edição
    $routes->post('update/(:num)', 'Transferencias::update/$1'); // Atualizar transferência
    $routes->get('delete/(:num)', 'Transferencias::delete/$1'); // Excluir transferência

    $routes->get('saldo-contas', 'Relatorios::saldoContas');
    $routes->get('resultado-categorias', 'Relatorios::resultadoCategorias');
});
