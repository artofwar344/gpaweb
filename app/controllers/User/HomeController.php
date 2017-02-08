<?php
namespace User;

use Hash,
	Redirect,
	Request,
	Validator,
	Input,
	Auth,
	View,
	DB,
	Log,
	Session,
	Config,
	Mail,
	Redis,
	URL,
	App,
	Illuminate\Support\MessageBag,
	Ca\Common,
	Ca\UserStatus,
	Ca\Service\LdapService,
	Ca\Service\IdpService,
	Ca\Service\DepartmentService,
	Ca\Service\RadiusService,
	Ca\Service\ManagerService,
	Ca\Service\UserService,
	Ca\Service\ApiService,
	Ca\Service\KeyService,
	Ca\Service\CurrentUserService,
	Ca\Service\ParamsService,
	Ca\Service\TreeService,
	Ca\Service\WebserviceService,
	Ca\Service\CasService;

class HomeController extends BaseController {
	/**
	 * @var stdClass
	 */
	public $layout = 'user.layouts.common';

	function index()
	{
		return Redirect::to('http://' . app()->environment());
	}

	/**
	 * 注册
	 */
	function register()
	{
		// 关闭注册
		if (ParamsService::get('register', 1) != 1)
		{
			return Redirect::to('/');
		}
		if (ParamsService::get('registerurl'))
		{
			return Redirect::to('/');
		}

		$input = null;
		$errors = new MessageBag;
		$rules = array(
			'username' => 'required|unique:user|min:4|max:32',
			'name'     => 'required',
			'email'    => 'required|email|unique:user',
			'password' => 'required|confirmed',
			'departmentid' => 'required',
			'captcha'  => 'captcha|required',
			'terms'    => 'required'
		);
		$messages = array(
			'username.required'  => '您必须提供用户名',
			'username.unique'    => '用户名已注册',
			'username.min'       => '用户名长度不得少于4位',
			'username.max'       => '用户名长度不得多于32位',
			'captcha'            => '验证码错误',
			'email.required'     => '您必须提供正确的邮箱帐号',
			'email.email'        => '您必须提供正确的邮箱帐号',
			'email.unique'       => '邮箱已经注册',
			'password.required'  => '您必须提供密码',
			'password.confirmed' => '确认密码不正确',
			'departmentid'       => '请选择部门',
			'terms.required'     => '您必须同意',
			'captcha.required'   => '请提供验证码'
		);
		$departments = DepartmentService::departments();
		foreach ($departments as $key => $row)
		{
			if (is_object($row))
			{
				$departments[$key] = (array)$row;
			}
		}
		$tree_service = new TreeService($departments, array(
			'_id' => 'departmentid',
			'_pid' => 'parentid',
			'_default_pid' => 1
		));
		$render = $tree_service->render($input['departmentid'], '&nbsp;&nbsp;&nbsp;&nbsp;');
		$departments = array();
		foreach ($render as $r)
		{
			$departments[$r['field']['departmentid']] = $r['extra']['prefix'] . $r['extra']['prefix_name'] . $r['field']['name'];
		}

		if (Request::getMethod() == 'POST')
		{
			$input = Input::all();
			$validation = Validator::make($input, $rules, $messages);
			if (!$validation->fails())
			{
				$email = $input['email'];
				$username = $input['username'];
				$name = $input['name'];
				$password = $input['password'];
				$token = str_random(32);
				$department_id = intval($input['departmentid']);
				$status = UserStatus::pending; // 待审核
				$special_ip = false;
				if (UserService::special_ip())
				{
					$status = UserStatus::normal; //指定IP段用户直接通过验证
					$special_ip = true;
				}
				$user_id = UserService::add_user($email, md5($password), $username, $name, $department_id, $status, $token);

				// 自动分配密钥
				if ($user_id && ParamsService::get('autoassignopen') && $special_ip == true)
				{
					$reason = '指定IP段用户注册自动分配';
					$managers = ManagerService::managers();
					KeyService::auto_assign($user_id, $managers[0]->managerid, $reason);
				}
				return Redirect::to('/regresult?id=' . $user_id . '&token=' . $token . '&authorized=' . (int)$special_ip);
			} else
			{
				$errors = $validation->messages();
			}
		}
		$this->layout->title = "用户注册";
		$this->layout->content = View::make('user.home.register')
			->with('errors', $errors)
			->with('departments', $departments)
			->with('input', $input);
		return;
	}

	/**
	 * 登录
	 * $_GET['ticket']  CAS登录方式
	 * $_GET['token']  客户端传递参数直接登录
	 * ParamsService::get('ldaphost') LDAP登录
	 * ParamsService::get('radiusopen') RADIUS登录
	 */
	function login()
	{
		if (Input::has('ret'))
		{
			Session::put('ret_url', trim(Input::get('ret')));
		}


		// 客户端传递过来的参数,验证成功后直接登录
		if (Input::has('token'))
		{
			if (App::make('customer')->alias == 'eurasia'){
				return Redirect::to('/');
			}else{
				$redis = Redis::connection();
				$decode = Common::decrypt_key('~NE)2vBl=q>$%%bk', rawurldecode(Input::get('token')));

				list($userid, $token) = explode('_', $decode);

				if ($redis->get($token) != 1)
				{
					return Redirect::to('/login');
				}
				else
				{
					$redis->set($token, '');
					Session::set('user.provider', 'user');
					Auth::loginUsingId($userid);
					if (Input::has('ret'))
					{
						return Redirect::to(Input::get('ret'));
					}
					return Redirect::to('/profile');
				}
			}
		}


		if (ParamsService::get('casloginurl') || ParamsService::get('casloginurl1'))
		{
			// 获取用户信息
			$info = CasService::validateTicket($name);
			if (App::make('customer')->alias == 'gtcfla'){
				$username = $name = $info;
			}else{
				$username = $info['username'];
				$name = $info['name'];
			}
			// 如果验证不通过，跳转回CAS登录连接
			if (!$username)
			{
				if (App::make('customer')->alias == 'eurasia')
				{
					$loginUrl = ParamsService::get('casloginurl') . "?service=http://user.ms.eurasia/login";
				}else{
					$loginUrl = ParamsService::get('casloginurl') . "?service=http://user.".App::make('customer')->alias.".gap.edu.cn/login";
				}
				return Redirect::to($loginUrl);
			}
			else
			{
				// var_dump($username);
				// echo "<hr>";
				// echo "<pre>";
				// print_r($username);
				// echo "</pre>";
				// exit;
				// $info = DB::table('userdata')
				// 	->select(array('*'))
				// 	->where('id','=',$username)
				// 	->first();
				// if(!$info)
				// {
				// 	$validation->messages()->add('username', '学生不能登录');
				// 	$this->layout->title = '用户登录';
				// 	$this->layout->content = View::make('user.home.login')->with('errors', $errors)->with('input', $input);
				// 	return;
				// }	
				$email = $username . '@' . app()->env;
				// 获取userid, 并且保存用户信息
				$userId = UserService::saveUser($username, $email, $name);
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


		// if (ParamsService::get('casloginurl'))
		// {
		// 	// 获取用户信息
		// 	$info = CasService::validateTicket($name);
		// 	$username = $info['username'];
		// 	$name = $info['name'];
		// 	// 如果验证不通过，跳转回CAS登录连接
		// 	if (!$username)
		// 	{
		// 		$loginUrl = ParamsService::get('casloginurl') . "?service=http://user.ms.eurasia/login";
		// 		return Redirect::to($loginUrl);
		// 	}
		// 	else
		// 	{
		// 		// var_dump($username);
		// 		// echo "<hr>";
		// 		// echo "<pre>";
		// 		// print_r($username);
		// 		// echo "</pre>";
		// 		// exit;
		// 		// $info = DB::table('userdata')
		// 		// 	->select(array('*'))
		// 		// 	->where('id','=',$username)
		// 		// 	->first();
		// 		// if(!$info)
		// 		// {
		// 		// 	$validation->messages()->add('username', '学生不能登录');
		// 		// 	$this->layout->title = '用户登录';
		// 		// 	$this->layout->content = View::make('user.home.login')->with('errors', $errors)->with('input', $input);
		// 		// 	return;
		// 		// }	
		// 		$email = $username . '@' . app()->env;
		// 		// 获取userid, 并且保存用户信息
		// 		$userId = UserService::saveUser($username, $email, $name);
		// 		Session::set('user.provider', 'user');

		// 		// 获取userid, 登录
		// 		Auth::loginUsingId($userId);
		// 		// 自动分配密钥
		// 		$reason = 'CAS登录自动分配密钥';
		// 		$managers = ManagerService::managers();
		// 		KeyService::auto_assign($userId, $managers[0]->managerid, $reason);

		// 		if (Input::has('ret'))
		// 		{
		// 			return Redirect::to(Input::get('ret'));
		// 		}
		// 		return Redirect::to('/profile');
		// 	}
		// }
		// CAS 登录, 目前大连海事大学(dlmu)使用
//		if (Input::has('ticket'))
//		{
//			// 获取用户信息
//			$username = CasService::validateTicket();
//			// 如果验证不通过，跳转回CAS登录连接
//			if (!$username)
//			{
//				$loginUrl = ParamsService::get('casloginurl') . "?service=http://user." . app()->env . "/login";
//				return Redirect::to($loginUrl);
//			}
//			else
//			{
//				$email = $username . '@' . app()->env;
//				// 获取userid, 并且保存用户信息
//				$userId = UserService::saveUser($username, $email, $username);
//
//				Session::set('user.provider', 'user');
//
//				// 获取userid, 登录
//				Auth::loginUsingId($userId);
//				// 自动分配密钥
//				$reason = 'CAS登录自动分配密钥';
//				$managers = ManagerService::managers();
//				KeyService::auto_assign($userId, $managers[0]->managerid, $reason);
//
//				if (Input::has('ret'))
//				{
//					return Redirect::to(Input::get('ret'));
//				}
//				return Redirect::to('/profile');
//			}
//		}


		if (!Auth::guest())
		{
			if (Input::get('ret'))
			{
				return Redirect::to(Input::get('ret'));
			}
			return Redirect::to("/profile");
		}
		$input = null;
		$errors = new MessageBag;
		$rules = array(
			'username'   => 'required',
			'password'   => 'required|min:3',
			'captcha'    => 'captcha|required'
		);
		$messages = array(
			'captcha'           => '验证码错误',
			'username.required' => '用户名不能为空',
			'password.required' => '密码不能为空',
			'password.min'      => '密码长度不能小于6位',
			'captcha.required'  => '验证码不能为空'
		);
		if (Request::getMethod() == 'POST')
		{
			$input = Input::all();
			$validation = Validator::make($input, $rules, $messages);
			if (!$validation->fails())
			{
				$login = $input["username"];
				$password = $input["password"];
				$remember = (bool)Input::get("remember");
				$credentials = array('username' => $login, 'password' => $password, 'provider' => 'user');
				$loginError = false;
				$loginMethod = 'common';
				Log::info('user login: username: ' . $login . ' password: ' . $password);
				//开启api登录
				if (ParamsService::get('apiserver'))
				{
					Log::info('API login start...');
					$loginMethod = 'api';
					$user_id = ApiService::check($login, $password);
					if (!$user_id)
					{
						$loginError = true;
					}
					// 自动分配密钥
					if ($user_id && ParamsService::get('autoassignopen'))
					{
						$reason = 'API登录自动分配';
						$managers = ManagerService::managers();
						KeyService::auto_assign($user_id, $managers[0]->managerid, $reason);
					}
				}
				// 开启ldap登录方式
				elseif (ParamsService::get('ldaphost'))
				{
					Log::info('LDAP login start...');
					$loginMethod = 'ldap';
					$user_id = LdapService::check($login, $password);
					if (!$user_id)
					{
						$loginError = true;
					}
					// 自动分配密钥
					if ($user_id && ParamsService::get('autoassignopen'))
					{
						$reason = 'LDAP登录自动分配';
						$managers = ManagerService::managers();
						KeyService::auto_assign($user_id, $managers[0]->managerid, $reason);
					}
				}
				// 开启radius登录方式
				elseif (ParamsService::get('radiusopen'))
				{
					Log::info('radius login start...');
					$loginMethod = 'radius';
					$user_id = RadiusService::check($login, $password);
					if (!$user_id)
					{
						$loginError = true;
					}
					// 自动分配密钥
					if ($user_id && ParamsService::get('autoassignopen'))
					{
						$reason = 'Radius登录自动分配';
						$managers = ManagerService::managers();
						KeyService::auto_assign($user_id, $managers[0]->managerid, $reason);
					}
				}
				// Idp登录方式
				elseif (ParamsService::get('idpwsdl'))
				{
					Log::info('IDP login start...');
					$loginMethod = 'idp';
//					//验证userinfo1中是否存在该用户
//					if (DB::table('userinfo1')->where('id', '=', $login)->count() <= 0)
//					{
//						$loginError = true;
//					}
//					else
//					{
					$user_id = IdpService::check($login, $password);
					if (!$user_id)
					{
						$loginError = true;
					}
					// 自动分配密钥
					if ($user_id && ParamsService::get('autoassignopen'))
					{
						$reason = 'IDP登录自动分配';
						$managers = ManagerService::managers();
						KeyService::auto_assign($user_id, $managers[0]->managerid, $reason);
					}
//					}
				}
				// webssrver 登陆信息
				elseif(ParamsService::get('webserverhost'))
				{
					Log::info('WebServer Login start...');
					$user_id = 'webserver';
					$userid = WebserviceService::check($login, $password);
					// 自动分配密钥
					if ($user_id && ParamsService::get('autoassignopen'))
					{
						$reason = 'IDP登录自动分配';
						$managers = ManagerService::managers();
						KeyService::auto_assign($user_id, $managers[0]->managerid, $reason);
					}

				}

				if ($loginError)
				{
					Log::info('GP sites login start...');
				}
				//普通方式登录
				if (($loginMethod != 'common' && !$loginError) || Auth::attempt($credentials, $remember))
				{
					if ($loginMethod != 'common' && !$loginError)
					{
						Auth::attempt($credentials, $remember);
						Log::info('login success');
					}
					UserService::save_password(Auth::user()->userid, $password);
					if (Auth::user()->status == UserStatus::normal)
					{
						$date = DB::raw('NOW()');
						$user_id = Auth::user()->userid;
						UserService::add_useraccesslog($user_id, $date);
						$ret_url = Session::get('ret_url');
						if (!empty($ret_url))
						{
							Session::forget('ret_url');
							return Redirect::to($ret_url);
						}
						else return Redirect::to('/');
					}
					Auth::logout();
					$validation->messages()->add('username', '帐号未激活');
				}
				else
				{
					Log::info('login fail');
					$validation->messages()->add('username', '帐号或密码错误');
				}
			}
			$errors = $validation->messages();
		}
		$this->layout->title = '用户登录';
		$this->layout->content = View::make('user.home.login')->with('errors', $errors)->with('input', $input);
		return;
	}

	/**
	 * 找回密码
	 */
	function forgetpwd()
	{
		if (ParamsService::get('retrievepassword') != 1)
		{
			return Redirect::to('/');
		}
		if (!is_null($this->user)) return Redirect::to("/profile");
		$input = null;
		$errors = new MessageBag;
		$rules = array(
			'email'    => 'required|email',
			'captcha'  => 'captcha|required'
		);
		$messages = array(
			'captcha'    => '验证码错误',
			'email.required' => '邮箱不能为空',
			'email.email'    => '必须是有效的电子邮箱'
		);

		if (Request::getMethod() == 'POST')
		{
			$input = Input::all();
			$validation = Validator::make($input, $rules, $messages);
			if (!$validation->fails())
			{
				$email = $input['email'];
				$user = UserService::get_user_by_email($email);
				if ($user != null)
				{
					$token = str_random(32);
					UserService::update($user->userid, array('token' => $token));
					$link = URL::to('/resetpwd?token=' . $user->userid . '_' . $token);
					Mail::send('emails.auth.forgetpwd', array('link' => $link), function($message) use ($user)
					{
						$message->to($user->email, $user->name)->subject('重置密码');
					});
					$input['email'] = '';
				}
				else
				{
					$validation->messages()->add('email', '该用户不存在');
				}
			}
			$errors = $validation->messages();
		}
		$this->layout->title = '找回密码';
		$this->layout->content = View::make('user.home.forgetpwd')->with('errors', $errors)->with('input', $input);
	}

	/**
	 * 密码重置
	 */
	function resetpwd()
	{
		if (ParamsService::get('retrievepassword') != 1)
		{
			return Redirect::to('/');
		}
		list($user_id, $token) = explode('_', Input::get('token'));
		if ($user_id && $token)
		{
			$input = null;
			$errors = new MessageBag;
			$rules = array(
				'password' => 'required|confirmed',
				'captcha'  => 'captcha|required'
			);
			$messages = array(
				'captcha'        => '验证码错误',
				'password.required'  => '您必须提供密码',
				'password.confirmed' => '确认密码不正确',
				'captcha.required'   => '请提供验证码'
			);
			$user = UserService::get_user_by_userid($user_id, $token);
			if ($user != null)
			{
				if (Request::getMethod() == 'POST')
				{
					$input = Input::all();
					$validation = Validator::make($input, $rules, $messages);
					if (!$validation->fails())
					{
						UserService::update($user_id, array(
							'token' => str_random(32),
							'password' => md5($input['password'])
						));

						UserService::save_password($user_id, $input['password']);
					}
					$errors = $validation->messages();
				}
				$this->layout->title = '重置密码';
				$this->layout->content = View::make('user.home.resetpwd')->with('user', $user)->with('errors', $errors)->with('input', $input);
				return ;
			}
		}
		return Redirect::to('/');
	}

	/**
	 * 注册成功页面
	 */
	function regresult()
	{
		if (!Input::has('id'))
		{
			return Redirect::to('/');
		}
		$user_id = Input::get('id');
		$user = UserService::get_user_by_userid($user_id);
		if (empty($user))
		{
			return Redirect::to('/');
		}
		//$user = User::find($user_id)->first();
		$this->layout->title = '注册成功';

		$this->layout->content = View::make('user.home.regresult')->with('email', $user->email);
	}

	function profile()
	{
		$success = false;
		$input = null;
		$errors = new MessageBag;
		//return Redirect::to("http://ms.eurasia.edu");
		if (Request::getMethod() == 'POST')
		{
			$rules = array(
				'name'    => 'required'
			);
			$messages = array(
				'name.required'    => '姓名不能为空'
			);
			$input = Input::all();
			$validation = Validator::make($input, $rules, $messages);
			if (!$validation->fails())
			{
				UserService::update(CurrentUserService::$user_id, array(
					'name' => $input['name']
				));
				$success = true;
			}
			else
			{
				$errors = $validation->messages();
			}
		}

		$this->layout->title = '用户资料';
		$this->layout->content = View::make('user.home.profile')
			->with('user', CurrentUserService::$user)
			->with('success', $success)
			->with('errors', $errors);
	}

	/**
	 * 修改密码
	 */
	public function changepwd()
	{
		$user_id = WebserviceService::check('154029103', '159357');
		echo $user_id;
		exit;
		// 关闭修改密码
		if (ParamsService::get('changepassword') != 1)
		{
			return Redirect::to('/');
		}
		$input = null;
		$errors = new MessageBag;
		$rules = array(
			'password' => 'required',
			'newpassword' => 'required|confirmed',
			'captcha'  => 'captcha|required'
		);
		$messages = array(
			'captcha'        => '验证码错误',
			'password.required'  => '您必须提供当前密码',
			'newpassword.required' => '您必须提供新密码',
			'newpassword.confirmed' => '确认密码不正确',
			'captcha.required'   => '请提供验证码'
		);
		if (Request::getMethod() == 'POST')
		{
			$input = Input::all();

			$validation = Validator::make($input, $rules, $messages);
			if (!$validation->fails())
			{
				if (!ParamsService::get('ldaphost') && Auth::user()->password != md5($input['password']))
				{
					$validation->messages()->add('password', '当前密码不正确');
				}
				else
				{
					$ret = array('status' => 1);
					if (ParamsService::get('ldaphost'))
					{
						$info = LdapService::get_info(Auth::user()->username, $input['password']);
						if (empty($info))
						{
							$validation->messages()->add('password', '当前密码不正确');
						}
						else
						{
							$ret = LdapService::update_password(Auth::user()->username, $input['password'], $input['newpassword']);
						}
					}
					if ($ret['status'] == 1)
					{
						UserService::update(Auth::user()->userid, array(
							'token' => str_random(32),
							'password' => md5($input['newpassword'])
						));
						UserService::save_password(Auth::user()->userid, $input['password']);
					}
				}
			}
			$errors = $validation->messages();
		}
		$this->layout->title = '修改密码';
		$this->layout->content = View::make('user.home.changepwd')
			->with('user', Auth::user())
			->with('errors', $errors)
			->with('input', $input);
		return ;
	}

	/**
	 * 登出
	 * @return mixed
	 */
	function logout()
	{
		/*$user_id = WebserviceService::check('1762', 'beilangWB3');
		var_dump($user_id);
		exit;*/
		/*KeyService::auto_assign('1799','1','test');
		exit;
		*/Auth::logout();
		if (App::make('customer')->alias == 'eurasia') {
			$params = array('service'=>'http://ms.eurasia.edu/');
			CasService::validateOut($params);
		}
		
		
		if (Input::has('ret'))
		{
			$ret = trim(Input::get('ret'));
			if (strpos($ret, '?params=') !== false) //排除自动登录链接(dlmu)
			{
				return  Redirect::to('/');
			}
			else
			{
				return Redirect::to($ret);
			}
		}
		else
		{
			return Redirect::to('/');
		}
	}
}