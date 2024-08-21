<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
$routes->get('/', 'Home::index');
$routes->get('/dashboard', 'Home::dashboard');
// Auth
$routes->get('/login', 'Auth::index');
$routes->post('/login/auth', 'Auth::login');
$routes->delete('/logout', 'Auth::logout');

$routes->get('/show', 'Show::index');
$routes->post('/show/store', 'Show::store');
$routes->patch('/show/update/(:num)', 'Show::update/$1');
$routes->delete('/show/delete/(:num)', 'Show::delete/$1');


$routes->get('/tiket', 'Ticket::index');
$routes->post('/tiket/store', 'Ticket::store');
$routes->patch('/tiket/update/(:num)', 'Ticket::update/$1');
$routes->delete('/tiket/delete/(:num)', 'Ticket::delete/$1');