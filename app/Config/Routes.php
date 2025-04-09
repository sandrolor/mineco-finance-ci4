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

    $routes->get('grupocontas/', 'GrupoContas::index');
    $routes->get('grupocontas/create', 'GrupoContas::create');
    $routes->post('grupocontas/store', 'GrupoContas::store');
    $routes->get('grupocontas/edit/(:num)', 'GrupoContas::edit/$1');
    $routes->post('grupocontas/update/(:num)', 'GrupoContas::update/$1');
    $routes->get('grupocontas/delete/(:num)', 'GrupoContas::delete/$1');

    $routes->get('grupocategorias/', 'GrupoCategorias::index');
    $routes->get('grupocategorias/create', 'GrupoCategorias::create');
    $routes->post('grupocategorias/store', 'GrupoCategorias::store');
    $routes->get('grupocategorias/edit/(:num)', 'GrupoCategorias::edit/$1');
    $routes->post('grupocategorias/update/(:num)', 'GrupoCategorias::update/$1');
    $routes->get('grupocategorias/delete/(:num)', 'GrupoCategorias::delete/$1');

    $routes->get('contas/', 'Contas::index');              // Listagem de contas
    $routes->get('contas/create', 'Contas::create');       // Formulário de criação
    $routes->post('contas/store', 'Contas::store');        // Salvar nova conta
    $routes->get('contas/edit/(:num)', 'Contas::edit/$1'); // Formulário de edição
    $routes->post('contas/update/(:num)', 'Contas::update/$1'); // Atualizar conta
    $routes->get('contas/delete/(:num)', 'Contas::delete/$1');  // Excluir conta

    $routes->get('categorias/', 'Categorias::index');              // Listagem de categorias
    $routes->get('categorias/create', 'Categorias::create');       // Formulário de criação
    $routes->post('categorias/store', 'Categorias::store');        // Salvar nova categoria
    $routes->get('categorias/edit/(:num)', 'Categorias::edit/$1'); // Formulário de edição
    $routes->post('categorias/update/(:num)', 'Categorias::update/$1'); // Atualizar categoria
    $routes->get('categorias/delete/(:num)', 'Categorias::delete/$1');  // Excluir categoria

    $routes->get('movimento/', 'Movimento::index');
    $routes->get('movimento/create', 'Movimento::create');
    $routes->post('movimento/store', 'Movimento::store');
    $routes->get('movimento/edit/(:num)', 'Movimento::edit/$1');
    $routes->post('movimento/update/(:num)', 'Movimento::update/$1');
    $routes->get('movimento/delete/(:num)', 'Movimento::delete/$1');

    $routes->get('transferencias/', 'Transferencias::index'); // Listagem de transferências
    $routes->get('transferencias/create', 'Transferencias::create'); // Formulário de nova transferência
    $routes->post('transferencias/store', 'Transferencias::store'); // Salvar nova transferência
    $routes->get('transferencias/edit/(:num)', 'Transferencias::edit/$1'); // Formulário de edição
    $routes->post('transferencias/update/(:num)', 'Transferencias::update/$1'); // Atualizar transferência
    $routes->get('transferencias/delete/(:num)', 'Transferencias::delete/$1'); // Excluir transferência

    $routes->get('relatorios/saldo-contas/', 'Relatorios::saldoContas');
    $routes->get('relatorios/resultado-categorias/', 'Relatorios::resultadoCategorias');
});
