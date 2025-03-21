<?php
defined('BASEPATH') or exit('No direct script access allowed');

/*
| -------------------------------------------------------------------------
| URI ROUTING
| -------------------------------------------------------------------------
| This file lets you re-map URI requests to specific controller functions.
|
| Typically there is a one-to-one relationship between a URL string
| and its corresponding controller class/method. The segments in a
| URL normally follow this pattern:
|
|	example.com/class/method/id/
|
| In some instances, however, you may want to remap this relationship
| so that a different class/function is called than the one
| corresponding to the URL.
|
| Please see the user guide for complete details:
|
|	https://codeigniter.com/user_guide/general/routing.html
|
| -------------------------------------------------------------------------
| RESERVED ROUTES
| -------------------------------------------------------------------------
|
| There are three reserved routes:
|
|	$route['default_controller'] = 'welcome';
|
| This route indicates which controller class should be loaded if the
| URI contains no data. In the above example, the "welcome" class
| would be loaded.
|
|	$route['404_override'] = 'errors/page_missing';
|
| This route will tell the Router which controller/method to use if those
| provided in the URL cannot be matched to a valid route.
|
|	$route['translate_uri_dashes'] = FALSE;
|
| This is not exactly a route, but allows you to automatically route
| controller and method names that contain dashes. '-' isn't a valid
| class or method name character, so it requires translation.
| When you set this option to TRUE, it will replace ALL dashes in the
| controller and method URI segments.
|
| Examples:	my-controller/index	-> my_controller/index
|		my-controller/my-method	-> my_controller/my_method
*/
$route['default_controller'] = 'auth';
$route['404_override'] = '';
$route['translate_uri_dashes'] = FALSE;
$route['logout'] = 'auth/logout';
$route['register'] = 'auth/register';
$route['blog/tambah'] = 'blog/tambah';
$route['blog/tambah_save'] = 'blog/tambah_save';
$route['brand/get_brand_details/(:num)'] = 'brand/get_brand_details/$1';
$route['brand/get_brand_promos/(:num)'] = 'brand/get_brand_promos/$1';
$route['banner'] = 'banner/index';
$route['banner/add'] = 'banner/add';
$route['banner/save'] = 'banner/save';
$route['banner/edit/(:num)'] = 'banner/edit/$1';
$route['banner/update/(:num)'] = 'banner/update/$1';
$route['banner/delete/(:num)'] = 'banner/delete/$1';
$route['banner/toggle_status/(:num)'] = 'banner/toggle_status/$1';
$route['transaksikasir/tambahTransaksiKasir'] = 'transaksikasir/tambahTransaksiKasir';
$route['transaksikasir/cari_member_kasir'] = 'transaksikasir/cari_member_kasir';
$route['transaksikasir/convert_and_updateKasir'] = 'transaksikasir/convert_and_updateKasir';
$route['transaksikasir/historyTransaksiKasir'] = 'transaksikasir/historyTransaksiKasir';
$route['transaksikasir/getHistorysaldoKasir'] = 'transaksikasir/getHistorysaldoKasir';

// Transaksi Cabang Routes
$route['transaksicabang/tambahTransaksiCabang'] = 'transaksicabang/tambahTransaksiCabang';
$route['transaksicabang/cari_member_cabang'] = 'transaksicabang/cari_member_cabang';
$route['transaksicabang/convert_and_updateCabang'] = 'transaksicabang/convert_and_updateCabang';
$route['transaksicabang/historyTransaksiCabang'] = 'transaksicabang/historyTransaksiCabang';
$route['transaksicabang/saldoCabang'] = 'transaksicabang/saldoCabang';
$route['transaksicabang/getHistorysaldoCabang'] = 'transaksicabang/getHistorysaldoCabang';
$route['transaksicabang/cari_memberSaldo'] = 'transaksicabang/cari_memberSaldo';
$route['transaksicabang/convert_and_updateSaldoCabang'] = 'transaksicabang/convert_and_updateSaldoCabang';