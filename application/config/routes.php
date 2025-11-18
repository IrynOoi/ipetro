<?php
defined('BASEPATH') OR exit('No direct script access allowed');


$route['default_controller'] = 'welcome';
$route['404_override'] = '';
$route['translate_uri_dashes'] = FALSE;

$route['abouts']='PageController/about';
//http://localhost:8000/PageController/about
$route['home']='PageController/index';

$route['about']='PageController/aboutus';

// $route['blog/(:any)']='PageController/blog/$1';

$route['blog/(:num)']='PageController/blog/$1';

//----------------------------------------------------
//Employee route

    $route['employee']='Frontend/EmployeeController/index';
    $route['employee/add']='Frontend/EmployeeController/create';
    $route['employee/store']='Frontend/EmployeeController/store';
    $route['employee/edit/(:any)']='Frontend/EmployeeController/edit/$1';
    $route['employee/update/(:any)']='Frontend/EmployeeController/update/$1';
    $route['employee/delete/(:any)']='Frontend/EmployeeController/delete/$1';
    // $route['employee/confirmdelete/(:any)']='Frontend/EmployeeController/delete/$1';
    
    $route['employee/confirmdelete/(:any)']['DELETE']='Frontend/EmployeeController/delete/$1';
   
    $route['excel_export/completed_excel'] = 'excel_export/completed_excel';
    
  $route['extractTables'] = 'PdfController/extractTables';

  $route['pdf/downloadExcel'] = 'PdfController/downloadExcel';






