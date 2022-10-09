<?php

namespace Config;

// Create a new instance of our RouteCollection class.
$routes = Services::routes();

// Load the system's routing file first, so that the app and ENVIRONMENT
// can override as needed.
if (file_exists(SYSTEMPATH . 'Config/Routes.php')) {
    require SYSTEMPATH . 'Config/Routes.php';
}

/*
 * --------------------------------------------------------------------
 * Router Setup
 * --------------------------------------------------------------------
 */
$routes->setDefaultNamespace('App\Controllers');
$routes->setDefaultController('HomeController');
$routes->setDefaultMethod('index');
$routes->setTranslateURIDashes(false);
$routes->set404Override();
$routes->setAutoRoute(true);

/*
 * --------------------------------------------------------------------
 * Route Definitions
 * --------------------------------------------------------------------
 */

// We get a performance increase by specifying the default
// route since we don't have to scan directories.
$routes->get('/login', 'HomeController::login');
$routes->post('/login-process', 'HomeController::loginProcess');
$routes->get('/logout-process', 'HomeController::logoutProcess');

$routes->get('/', 'HomeController::index');

$routes->get('/master/user', 'HomeController::user');
$routes->get('/master/user/add', 'HomeController::userAdd');
$routes->post('/master/user/add-process', 'HomeController::userAddProcess');
$routes->get('/master/user/edit/(:any)', 'HomeController::userEdit/$1');
$routes->post('/master/user/edit-process', 'HomeController::userEditProcess');
$routes->get('/master/user/hapus/(:any)', 'HomeController::userHapus/$1');
$routes->post('/master/user/hapus-process', 'HomeController::userHapusProcess');

$routes->get('/master/produk', 'HomeController::produk');
$routes->get('/master/produk/add', 'HomeController::produkAdd');
$routes->post('/master/produk/add-process', 'HomeController::produkAddProcess');
$routes->get('/master/produk/edit/(:any)', 'HomeController::produkEdit/$1');
$routes->post('/master/produk/edit-process', 'HomeController::produkEditProcess');
$routes->get('/master/produk/hapus/(:any)', 'HomeController::produkHapus/$1');
$routes->post('/master/produk/hapus-process', 'HomeController::produkHapusProcess');

$routes->get('/master/supplier', 'HomeController::supplier');
$routes->get('/master/supplier/add', 'HomeController::supplierAdd');
$routes->post('/master/supplier/add-process', 'HomeController::supplierAddProcess');
$routes->get('/master/supplier/edit/(:any)', 'HomeController::supplierEdit/$1');
$routes->post('/master/supplier/edit-process', 'HomeController::supplierEditProcess');
$routes->get('/master/supplier/hapus/(:any)', 'HomeController::supplierHapus/$1');
$routes->post('/master/supplier/hapus-process', 'HomeController::supplierHapusProcess');

$routes->get('/transaksi/efaktur', 'HomeController::efaktur');
$routes->get('/transaksi/efaktur/add', 'HomeController::efakturAdd');
$routes->post('/transaksi/efaktur/add-process', 'HomeController::efakturAddProcess');
$routes->get('/transaksi/efaktur/edit/(:any)', 'HomeController::efakturEdit/$1');
$routes->post('/transaksi/efaktur/edit-process', 'HomeController::efakturEditProcess');
$routes->get('/transaksi/efaktur/hapus/(:any)', 'HomeController::efakturHapus/$1');
$routes->post('/transaksi/efaktur/hapus-process', 'HomeController::efakturHapusProcess');
$routes->get('/transaksi/efaktur/print/(:any)/(:any)', 'HomeController::efakturPrint/$1/$2');

$routes->post('/transaksi/efaktur/(:any)/add-process', 'HomeController::efakturDtlAddProcess/$1');
$routes->post('/transaksi/efaktur/(:any)/hapus-process', 'HomeController::efakturDtlHapusProcess/$1');

/*
 * --------------------------------------------------------------------
 * Additional Routing
 * --------------------------------------------------------------------
 *
 * There will often be times that you need additional routing and you
 * need it to be able to override any defaults in this file. Environment
 * based routes is one such time. require() additional route files here
 * to make that happen.
 *
 * You will have access to the $routes object within that file without
 * needing to reload it.
 */
if (file_exists(APPPATH . 'Config/' . ENVIRONMENT . '/Routes.php')) {
    require APPPATH . 'Config/' . ENVIRONMENT . '/Routes.php';
}
