<?php

/*
|--------------------------------------------------------------------------
| Create The Application
|--------------------------------------------------------------------------
|
| The first thing we will do is create a new Laravel application instance
| which serves as the "glue" for all the components of Laravel, and is
| the IoC container for the system binding all of the various parts.
|
*/
$app = new Illuminate\Foundation\Application;

$app->redirectIfTrailingSlash();

/*
|--------------------------------------------------------------------------
| Detect The Application Environment
|--------------------------------------------------------------------------
|
| Laravel takes a dead simple approach to your application environments
| so you can just specify a machine name or HTTP host that matches a
| given environment, then we will automatically detect it for you.
|
*/

include_once dirname(__DIR__) . '/app/lib/ca/config.php';
$envs = $environments + array(
		'local' => array('*laravel4.test'),
		'gpa.edu.cn' => array('*gpa.edu.cn'),
		'client.gpa.edu.cn' => array('*client.gpa.edu.cn'),
		'gp.test' => array('*gp.test'),
		'manage.gp.test' => array('*manage.gp.test'),
		'client.gp.test' => array('*client.gp.test'),
		'tjjw.edu.cn' => array('*tjjw.edu.cn'),
		'manage.gp.tust.edu.cn' => array('*manage.gp.tust.edu.cn'),
		'client.gp.tust.edu.cn' => array('*client.gp.tust.edu.cn'),
	);
$env = $app->detectEnvironment($envs);


/*
|--------------------------------------------------------------------------
| Bind Paths
|--------------------------------------------------------------------------
|
| Here we are binding the paths configured in paths.php to the app. You
| should not be changing these here. If you need to change these you
| may do so within the paths.php file and they will be bound here.
|
*/

$app->bindInstallPaths(require __DIR__.'/paths.php');

/*
|--------------------------------------------------------------------------
| Load The Application
|--------------------------------------------------------------------------
|
| Here we will load the Illuminate application. We'll keep this is in a
| separate location so we can isolate the creation of an application
| from the actual running of the application with a given request.
|
*/

$framework = $app['path.base'].'/vendor/laravel/framework/src';

require $framework.'/Illuminate/Foundation/start.php';

/*
|--------------------------------------------------------------------------
| Return The Application
|--------------------------------------------------------------------------
|
| This script returns the application instance. The instance is given to
| the calling script so we can separate the building of the instances
| from the actual running of the application and sending responses.
|
*/


return $app;
