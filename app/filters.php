<?php
use Ca\Service\CasService;
use Illuminate\Support\Facades\DB,
	Illuminate\Support\Facades\Log,
	Illuminate\Support\Facades\Input,
	\Illuminate\Support\Facades\Hash,
//	\Ca\DesUtil,
	\Ca\Service\DesUtil, 
	\Ca\UserStatus,
	\Ca\Service\UserService,
	\Ca\Service\DepartmentService;
/*
|--------------------------------------------------------------------------
| Application & Route Filters
|--------------------------------------------------------------------------
|
| Below you will find the "before" and "after" events for the application
| which may be used to do any work before or after a request into your
| application. Here you may also register your custom route filters.
|
*/

App::before(function($request)
{
	//大连海事自动登录
	if (app()->customer->alias == 'dlmu')
	{
		if (Auth::check() || !Input::has('params'))
		{
			return;
		}
		$params = Input::get('params');
		$desUtil = new DesUtil('FxFWkOsn');
		$paramsJson = $desUtil->decrypt($params);
		//var_dump($paramsJson);
//		Log::info($paramsJson);
		$params = json_decode($paramsJson);
		//var_dump($params);
		if (!$params)
		{
			return;
		}
		$username = $params->username;
		$token = $params->token;
		//验证token是否合法
		if (strpos($token, 'dlmu') !== 0)
		{
			return;
		}
		$time = substr($token, 4);
		//判断是否token过期
		if ($time < time() - 3600)
		{
			//echo 'time out ' .  time();
			//Log::info('token time: '. date('Y-m-i') . ';token time out');
			return;
		}
		$user = UserService::get_user_by_username($username);
		if ($user == null)
		{
			$password = $username;
			$email = $username . '@' . App()->env;
			$name = $username;
			$department = DepartmentService::getTopDepartment();
			$userid = UserService::add_user($email, MD5($password), $username, $name, $department->departmentid, UserStatus::normal, str_random(32));
		}
		else
		{
			$userid = $user->userid;
		}
//		Auth::loginUsingId($userid, true);
		$user = User::find($userid);
		Auth::login($user);
		Session::put('user.provider', 'user');
		return;
	}
	
	if (App::make('customer')->alias == 'XXX')
	{
		// 获取用户信息
		$username = CasService::validateTicket();
		// 如果验证不通过，跳转回CAS登录连接
		if (!$username)
		{
			$loginUrl = ParamsService::get('casloginurl') . "?service=http://user." . app()->env . "/login";
			return Redirect::to($loginUrl);
		}
		else
		{
			var_dump($username);exit;
			$email = $username . '@' . app()->env;
			// 获取userid, 并且保存用户信息
			$userId = UserService::saveUser($username, $email, $username);

			Session::set('user.provider', 'user');

			// 获取userid, 登录
			Auth::loginUsingId($userId);
			// 自动分配密钥
			$reason = 'CAS登录自动分配密钥';
			$managers = ManagerService::managers();
			KeyService::auto_assign($userId, $managers[0]->managerid, $reason);

			if (Input::has('ret'))
			{
				return Redirect::to(Input::get('ret'));
			}
			return Redirect::to('/profile');
		}

	}
});


App::after(function($request, $response)
{
	//
});

/*
|--------------------------------------------------------------------------
| Authentication Filters
|--------------------------------------------------------------------------
|
| The following filters are used to verify that the user of the current
| session is logged into this application. The "basic" filter easily
| integrates HTTP Basic authentication for quick, simple checking.
|
*/

Route::filter('auth', function()
{
	if (Auth::guest()) return Redirect::guest('http://user.' . app()->env . '/login?ret=' . urlencode(URL::full()));
});


Route::filter('auth.basic', function()
{
	return Auth::basic();
});

/*
|--------------------------------------------------------------------------
| Guest Filter
|--------------------------------------------------------------------------
|
| The "guest" filter is the counterpart of the authentication filters as
| it simply checks that the current user is not logged in. A redirect
| response will be issued if they are, which you may freely change.
|
*/

Route::filter('guest', function()
{
	if (Auth::check()) return Redirect::to('/');
});

/*
|--------------------------------------------------------------------------
| CSRF Protection Filter
|--------------------------------------------------------------------------
|
| The CSRF filter is responsible for protecting your application against
| cross-site request forgery attacks. If this special token in a user
| session does not match the one given in this request, we'll bail.
|
*/

Route::filter('csrf', function()
{
	if (Session::token() != Input::get('_token'))
	{
//		throw new Illuminate\Session\TokenMismatchException;
		Session::forget('_token');
		echo 'this page is expired';
		exit;
	}
	else
	{
		Session::forget('_token');
	}
});