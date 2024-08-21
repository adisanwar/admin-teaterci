<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
$routes->get('/', 'Home::dashboard');
$routes->get('/dashboard', 'Home::dashboard');
// Auth
$routes->get('/login', 'Auth::index');
$routes->post('/login/auth', 'Auth::login');
$routes->delete('/logout', 'Auth::logout');

$routes->get('/users', 'Users::index');
$routes->post('/users/store', 'Users::store');
$routes->patch('/users/update/(:any)', 'Users::update/$1');
$routes->delete('/users/delete/(:any)', 'Users::delete/$1');

$routes->get('/show', 'Show::index');
$routes->post('/show/store', 'Show::store');
$routes->patch('/show/update/(:num)', 'Show::update/$1');
$routes->delete('/show/delete/(:num)', 'Show::delete/$1');


$routes->get('/tiket', 'Ticket::index');
$routes->post('/tiket/store', 'Ticket::store');
$routes->patch('/tiket/update/(:num)', 'Ticket::update/$1');
$routes->delete('/tiket/delete/(:num)', 'Ticket::delete/$1');


$routes->get('/theaters', 'Theater::index');
$routes->post('/theaters/store', 'Theater::store');
$routes->patch('/theaters/update/(:num)', 'Theater::update/$1');
$routes->delete('/theaters/delete/(:num)', 'Theater::delete/$1');

$routes->get('/orders', 'Order::index');
$routes->post('/orders/store', 'Order::store');
$routes->patch('/orders/update/(:num)', 'Order::update/$1');
$routes->delete('/orders/delete/(:num)', 'Order::delete/$1');

$routes->get('/showtime', 'Showtime::index');
$routes->post('/showtime/store', 'Showtime::store');
$routes->patch('/showtime/update/(:num)', 'Showtime::update/$1');
$routes->delete('/showtime/delete/(:num)', 'Showtime::delete/$1');
