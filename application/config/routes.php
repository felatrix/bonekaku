<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
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
|	http://codeigniter.com/user_guide/general/routing.html
|
| -------------------------------------------------------------------------
| RESERVED ROUTES
| -------------------------------------------------------------------------
|
| There area two reserved routes:
|
|	$route['default_controller'] = 'welcome';
|
| This route indicates which controller class should be loaded if the
| URI contains no data. In the above example, the "welcome" class
| would be loaded.
|
|	$route['404_override'] = 'errors/page_missing';
|
| This route will tell the Router what URI segments to use if those provided
| in the URL cannot be matched to a valid route.
|
*/
$route['main/arsip/(:any)'] = 'main/arsip/$1';
$route['main/(:any)'] = 'main/index/$1';
$route['main/insert'] = 'main/insert';
$route['main/edit'] = 'main/edit';
$route['main/del'] = 'main/del';
$route['main/arsip'] = 'main/arsip';

$route['client/(:any)'] = 'client/index/$1';
$route['client/insert'] = 'client/insert';
$route['client/edit'] = 'client/edit';
$route['client/del'] = 'client/del';
$route['client/clients'] = 'client/clients';

$route['cut/arsip/(:any)'] = 'cut/arsip/$1';
$route['cut/(:any)'] = 'cut/index/$1';
$route['cut/insert'] = 'cut/insert';
$route['cut/edit'] = 'cut/edit';
$route['cut/del'] = 'cut/del';
$route['cut/set'] = 'cut/set';
$route['cut/arsip'] = 'cut/arsip';
$route['cut/arsip/edit'] = 'cut/edit';
$route['cut/arsip/del'] = 'cut/del';
$route['cut/arsip/insert'] = 'cut/insert';

$route['pekerja/(:any)'] = 'pekerja/index/$1';
$route['pekerja/insert'] = 'pekerja/insert';
$route['pekerja/edit'] = 'pekerja/edit';
$route['pekerja/del'] = 'pekerja/del';
$route['pekerja/json'] = 'pekerja/json';

$route['bhn/set/(:any)'] = 'bhn/set/$1';
$route['bhn/insert'] = 'bhn/insert';
$route['bhn/edit'] = 'bhn/edit';
$route['bhn/del'] = 'bhn/del';

$route['item/(:any)'] = 'item/index/$1';
$route['item/insert'] = 'item/insert';
$route['item/edit'] = 'item/edit';
$route['item/del'] = 'item/del';
$route['item/json'] = 'item/json';

$route['color/(:any)'] = 'color/index/$1';
$route['color/insert'] = 'color/insert';
$route['color/edit'] = 'color/edit';
$route['color/del'] = 'color/del';
$route['color/datas'] = 'color/datas';

$route['bbhn/(:any)'] = 'bbhn/index/$1';
$route['bbhn/insert'] = 'bbhn/insert';
$route['bbhn/edit'] = 'bbhn/edit';
$route['bbhn/del'] = 'bbhn/del';
$route['bbhn/clients'] = 'bbhn/clients';

$route['prtl/arsip/(:any)'] = 'prtl/arsip/$1';
$route['prtl/(:any)'] = 'prtl/index/$1';
$route['prtl/insert'] = 'prtl/insert';
$route['prtl/edit'] = 'prtl/edit';
$route['prtl/del'] = 'prtl/del';
$route['prtl/arsip'] = 'prtl/arsip';
$route['prtl/arsip/edit'] = 'prtl/edit';

$route['sew/arsip/(:any)'] = 'sew/arsip/$1';
$route['sew/set/(:any)'] = 'sew/set/$1';
$route['sew/(:any)'] = 'sew/index/$1';
$route['sew/insert'] = 'sew/insert';
$route['sew/edit'] = 'sew/edit';
$route['sew/del'] = 'sew/del';
$route['sew/set'] = 'sew/set';
$route['sew/arsip'] = 'sew/arsip';
$route['sew/arsip/set'] = 'sew/set';

$route['fin/arsip/(:any)'] = 'fin/arsip/$1';
$route['fin/set/(:any)'] = 'fin/set/$1';
$route['fin/(:any)'] = 'fin/index/$1';
$route['fin/insert'] = 'fin/insert';
$route['fin/edit'] = 'fin/edit';
$route['fin/del'] = 'fin/del';
$route['fin/set'] = 'fin/set';
$route['fin/arsip'] = 'fin/arsip';
$route['fin/arsip/set'] = 'fin/set';

$route['control/arsip/(:any)'] = 'control/arsip/$1';
$route['control/(:any)'] = 'control/index/$1';
$route['control/arsip'] = 'control/arsip';

$route['ongkos/(:any)'] = 'ongkos/index/$1';
$route['ongkos/insert'] = 'ongkos/insert';
$route['ongkos/edit'] = 'ongkos/edit';
$route['ongkos/del'] = 'ongkos/del';

$route['kapasitas/(:any)'] = 'kapasitas/index/$1';
$route['kapasitas/insert'] = 'kapasitas/insert';
$route['kapasitas/edit'] = 'kapasitas/edit';
$route['kapasitas/del'] = 'kapasitas/del';

$route['jenis/(:any)'] = 'jenis/index/$1';
$route['jenis/insert'] = 'jenis/insert';
$route['jenis/edit'] = 'jenis/edit';
$route['jenis/del'] = 'jenis/del';
$route['jenis/json'] = 'jenis/json';

$route['laporan/peritem/(:any)'] = 'laporan/peritem/$1';
$route['laporan/percust/(:any)'] = 'laporan/percust/$1';
$route['laporan/pertulisan/(:any)'] = 'laporan/pertulisan/$1';
$route['laporan/bydate/(:any)'] = 'laporan/bydate/$1';
//$route['laporan/stokbhn/(:any)'] = 'laporan/stokbhn/$1';
//$route['laporan/(:any)'] = 'laporan/index/$1';

$route['bhnin/(:any)'] = 'bhnin/index/$1';
$route['bhnin/insert'] = 'bhnin/insert';
$route['bhnin/edit'] = 'bhnin/edit';
$route['bhnin/del'] = 'bhnin/del';

$route['actlog/(:any)'] = 'actlog/index/$1';

$route['ajaxset'] = 'ajaxset/index';

$route['default_controller'] 	= 'home';
$route['404_override'] 			= '';


/* End of file routes.php */
/* Location: ./application/config/routes.php */