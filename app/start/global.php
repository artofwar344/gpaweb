<?php

/*
|--------------------------------------------------------------------------
| Register The Laravel Class Loader
|--------------------------------------------------------------------------
|
| In addition to using Composer, you may use the Laravel class loader to
| load your controllers and models. This is useful for keeping all of
| your classes in the "global" namespace without Composer updating.
|
*/

ClassLoader::addDirectories(array(

	app_path().'/commands',
	app_path().'/controllers',
	app_path().'/models',
	app_path().'/database/seeds',

));

/*
|--------------------------------------------------------------------------
| Application Error Logger
|--------------------------------------------------------------------------
|
| Here we will configure the error logger setup for the application which
| is built on top of the wonderful Monolog library. By default we will
| build a rotating log file setup which creates a new file each day.
|
*/

$logFile = 'log-'.php_sapi_name().'.txt';

Log::useDailyFiles(storage_path().'/logs/'.$logFile);

/*
|--------------------------------------------------------------------------
| Application Error Handler
|--------------------------------------------------------------------------
|
| Here you may handle any errors that occur in your application, including
| logging them or displaying custom views for specific errors. You may
| even register several error handlers to handle different types of
| exceptions. If nothing is returned, the default error view is
| shown, which includes a detailed stack trace during debug.
|
*/

App::error(function(Exception $exception, $code)
{
	Log::error($exception);
//	if ($code == 404)
//	{
//		return View::make("share.error.404");
//	}
//	elseif ($code == 500)
//	{
//		return View::make("share.error.500");
//	}
});

/*
|--------------------------------------------------------------------------
| Maintenance Mode Handler
|--------------------------------------------------------------------------
|
| The "down" Artisan command gives you the ability to put an application
| into maintenance mode. Here, you will define what is displayed back
| to the user if maintenace mode is in effect for this application.
|
*/

App::down(function()
{
	return Response::make("Be right back!", 503);
});

/*
|--------------------------------------------------------------------------
| Require The Filters File
|--------------------------------------------------------------------------
|
| Next we will load the filters file for the application. This gives us
| a nice separate location to store our route and application filter
| definitions instead of putting them all in the main routes file.
|
*/

Auth::extend('caauth', function($app) {
	$provider =  new Ca\Auth\Auth();
	return new Illuminate\Auth\Guard($provider, App::make('session.store', array()));
});

Validator::extend('captcha', '\Ca\Captcha@check');

// Blade Template Extend
Blade::extend(function($value) {
	return preg_replace('/\{\?(.+)\?\}/', '<?php ${1} ?>', $value);
});

Blade::extend(function($value) {
	return preg_replace('/\@actions(.+)/', '<?php echo HtmlExt::htmlActions${1}; ?>', $value);
});

Blade::extend(function($value) {
	$value = preg_replace('/@search/', '<?php echo HtmlExt::htmlMainSearch(array(', $value);
	$value = preg_replace('/@endsearch/', ')); ?>', $value);
	return $value;
});

Blade::extend(function($value) {
	$value = preg_replace('/@table/', '<?php echo HtmlExt::htmlTable1(array(', $value);
	$value = preg_replace('/@endtable/', ')); ?>', $value);
	return $value;
});

Blade::extend(function($value) {
	$value = preg_replace('/@dialog/', '<?php echo HtmlExt::htmlDialogNew(array(', $value);
	$value = preg_replace('/@enddialog/', ')); ?>', $value);
	return $value;
});

App::instance('customer', Ca\Customer::instance());

require app_path().'/filters.php';