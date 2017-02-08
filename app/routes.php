<?php
/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the Closure to execute when that URI is requested.
|
*/
include __DIR__ . '/routesDetail.php';


//gpa routes
if (ends_with($_SERVER['HTTP_HOST'], 'gpa.edu.cn'))
{
	Route::group(array('domain' => 'manage.{customer}.gpa.edu.cn'), $routesDetail['customerManage']);
	Route::group(array('domain' => 'manage.gpa.edu.cn'), $routesDetail['manage']);
	Route::group(array('domain' => '{customer}.gpa.edu.cn'), $routesDetail['customer']);
	Route::group(array('domain' => 'user.{customer}.gpa.edu.cn'), $routesDetail['user']);
	Route::group(array('domain' => 'soft.{customer}.gpa.edu.cn'), $routesDetail['soft']);
	Route::group(array('domain' => 'share.{customer}.gpa.edu.cn'), $routesDetail['share']);
	Route::group(array('domain' => 'api.{customer}.gpa.edu.cn'), $routesDetail['api']);
	Route::group(array('domain' => 'client.gpa.edu.cn'), $routesDetail['client']);
	Route::group(array('domain' => 'activate.{customer}.gpa.edu.cn'), $routesDetail['activate']);
}
//天津教委
elseif (ends_with($_SERVER['HTTP_HOST'], 'tjjw.edu.cn'))
{
	Route::group(array('domain' => 'manage.{customer}.tjjw.edu.cn'), $routesDetail['customerManage']);
	Route::group(array('domain' => 'manage.tjjw.edu.cn'), $routesDetail['manage']);
	Route::group(array('domain' => '{customer}.tjjw.edu.cn'), $routesDetail['customer']);
	Route::group(array('domain' => 'user.{customer}.tjjw.edu.cn'), $routesDetail['user']);
	Route::group(array('domain' => 'soft.{customer}.tjjw.edu.cn'), $routesDetail['soft']);
	Route::group(array('domain' => 'share.{customer}.tjjw.edu.cn'), $routesDetail['share']);
	Route::group(array('domain' => 'api.{customer}.tjjw.edu.cn'), $routesDetail['api']);
	Route::group(array('domain' => 'client.tjjw.edu.cn'), $routesDetail['client']);
	Route::group(array('domain' => 'activate.{customer}.tjjw.edu.cn'), $routesDetail['activate']);
}
// 欧亚学院
elseif (ends_with($_SERVER['HTTP_HOST'], 'ms.eurasia.edu'))
{
	Route::group(array('domain' => 'manage.ms.{customer}.edu'), $routesDetail['customerManage']);
	//Route::group(array('domain' => 'manage.gpa.edu.cn'), $routesDetail['manage']);
	Route::group(array('domain' => 'ms.{customer}.edu'), $routesDetail['customer']);
	Route::group(array('domain' => 'user.ms.{customer}.edu'), $routesDetail['user']);
	Route::group(array('domain' => 'soft.ms.{customer}pa.edu'), $routesDetail['soft']);
	Route::group(array('domain' => 'share.ms.{customer}.edu'), $routesDetail['share']);
	Route::group(array('domain' => 'api.ms.{customer}.edu'), $routesDetail['api']);
	Route::group(array('domain' => 'client.ms.{customer}.edu'), $routesDetail['client']);
	Route::group(array('domain' => 'activate.ms.{customer}.edu'), $routesDetail['activate']);
}
// 云南中医学院
elseif (ends_with($_SERVER['HTTP_HOST'], 'ynutcm.edu.cn'))
{
	Route::group(array('domain' => 'manage.ms.{customer}.edu.cn'), $routesDetail['customerManage']);
	//Route::group(array('domain' => 'manage.gpa.edu.cn'), $routesDetail['manage']);
	Route::group(array('domain' => 'ms.{customer}.edu.cn'), $routesDetail['customer']);
	Route::group(array('domain' => 'user.ms.{customer}.edu.cn'), $routesDetail['user']);
	Route::group(array('domain' => 'soft.ms.{customer}pa.edu.cn'), $routesDetail['soft']);
	Route::group(array('domain' => 'share.ms.{customer}.edu.cn'), $routesDetail['share']);
	Route::group(array('domain' => 'api.ms.{customer}.edu.cn'), $routesDetail['api']);
	Route::group(array('domain' => 'client.ms.{customer}.edu.cn'), $routesDetail['client']);
	Route::group(array('domain' => 'activate.ms.{customer}.edu.cn'), $routesDetail['activate']);
}
//天津教委2
elseif (ends_with($_SERVER['HTTP_HOST'], 'gp.tust.edu.cn'))
{
	Route::group(array('domain' => 'manage.{customer}.gp.tust.edu.cn'), $routesDetail['customerManage']);
	Route::group(array('domain' => 'manage.gp.tust.edu.cn'), $routesDetail['manage']);
	Route::group(array('domain' => '{customer}.gp.tust.edu.cn'), $routesDetail['customer']);
	Route::group(array('domain' => 'user.{customer}.gp.tust.edu.cn'), $routesDetail['user']);
	Route::group(array('domain' => 'soft.{customer}.gp.tust.edu.cn'), $routesDetail['soft']);
	Route::group(array('domain' => 'share.{customer}.gp.tust.edu.cn'), $routesDetail['share']);
	Route::group(array('domain' => 'api.{customer}.gp.tust.edu.cn'), $routesDetail['api']);
	Route::group(array('domain' => 'client.gp.tust.edu.cn'), $routesDetail['client']);
	Route::group(array('domain' => 'activate.{customer}.tjjw.edu.cn'), $routesDetail['activate']);
}
//test routes
elseif (ends_with($_SERVER['HTTP_HOST'], 'gp.edu.cn'))
{
	Route::group(array('domain' => 'manage.{customer}.gp.test'), $routesDetail['customerManage']);
	Route::group(array('domain' => 'manage.gp.test'), $routesDetail['manage']);
	Route::group(array('domain' => '{customer}.gp.edu.cn'), $routesDetail['customer']);
	Route::group(array('domain' => 'user.{customer}.gp.test'), $routesDetail['user']);
	Route::group(array('domain' => 'soft.{customer}.gp.test'), $routesDetail['soft']);
	Route::group(array('domain' => 'share.{customer}.gp.test'), $routesDetail['share']);
	Route::group(array('domain' => 'api.{customer}.gp.test'), $routesDetail['api']);
	Route::group(array('domain' => 'client.gp.test'), $routesDetail['client']);
	Route::group(array('domain' => 'activate.{customer}.gp.test'), $routesDetail['activate']);
}
else
{
	Route::group(array('domain' => 'manage.{customer}.gp.test'), $routesDetail['customerManage']);
	Route::group(array('domain' => 'manage.gp.test'), $routesDetail['manage']);
	Route::group(array('domain' => '{customer}.gp.test'), $routesDetail['customer']);
	Route::group(array('domain' => 'user.{customer}.gp.test'), $routesDetail['user']);
	Route::group(array('domain' => 'soft.{customer}.gp.test'), $routesDetail['soft']);
	Route::group(array('domain' => 'share.{customer}.gp.test'), $routesDetail['share']);
	Route::group(array('domain' => 'api.{customer}.gp.test'), $routesDetail['api']);
	Route::group(array('domain' => 'client.gp.test'), $routesDetail['client']);
	Route::group(array('domain' => 'activate.{customer}.gp.test'), $routesDetail['activate']);
}