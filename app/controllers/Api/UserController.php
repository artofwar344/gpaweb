<?php
namespace Api;

use Request,
	Input,
	Validator,
	Ca\UserType,
	Ca\Common,
	Ca\Consts,
	Ca\Service\UserService,
	Ca\Service\ManagerService,
	Ca\Service\ParamsService,
	Ca\Service\KeyService,
	Ca\Logger;

class UserController extends BaseController {

	public function getIndex()
	{
		echo 1;
	}

	public function postAdd()
	{
		$logger = Logger::start('api');
		$logger->log('uri: ' . Request::path() . ', ip:' . Common::client_ip() . ' \n params:' . print_r(Input::all(), 1));

		$input = null;
		$rules = array(
			'username' => 'required|unique:user|min:4|max:32',
			'name'     => 'required',
			'email'    => 'required|email|unique:user',
			'password' => 'required',
		);
		$messages = array(
			'username.required'  => '您必须提供用户名',
			'username.unique'    => '用户名已注册',
			'username.min'       => '用户名长度不得少于4位',
			'username.max'       => '用户名长度不得多于32位',
			'email.required'     => '您必须提供正确的邮箱帐号',
			'email.email'        => '您必须提供正确的邮箱帐号',
			'email.unique'       => '邮箱已经注册',
			'password.required'  => '您必须提供密码',
		);
		$input = Input::all();
		$validation = Validator::make($input, $rules, $messages);
		if (!$validation->fails())
		{
			$email    = $input['email'];
			$username = $input['username'];
			$name     = $input['name'];
			$password = $input['password'];
			$status = 1;
			$type     = Input::get('type');
			//file_put_contents(print_r($input, 1), path('app') . '/log.log', FILE_APPEND);
			if (!in_array($type, array_keys(Consts::$user_type_text)))
			{
				$type = UserType::Unknow;
			}
			$user_id = UserService::add_user($email, $password, $username, $name, 2, 1, str_random(32), $type, $status);
			// 自动分配密钥
			if ($user_id && ParamsService::get('autoassignopen'))
			{
				$reason = '审核通过自动分配';
				$managers = ManagerService::managers();
				KeyService::auto_assign($user_id, $managers[0]->managerid, $reason);
			}
			if ($user_id)
			{
				print json_encode(array('status' => 1));exit;
			}
			else
			{
				print json_encode(array('status' => 0, 'message' => '未知错误'));exit;
			}
		}
		else
		{
			foreach ($validation->messages()->all() as $message)
			{
				print json_encode(array('status' => 0, 'message' => $message));exit;
			}
		}
	}

	public function postUpdate()
	{
		$logger = Logger::start('api');
		$logger->log('uri: ' . Request::path() . ',ip:' . Common::client_ip() . ' \n params:' . print_r(Input::all(), 1));

		$input = null;
		$rules = array(
			'username' => 'required|min:4|max:32',
			'name'     => 'required',
			'email'    => 'required|email',
			'password' => 'required',
		);
		$messages = array(
			'username.required'  => '您必须提供用户名',
			'username.min'       => '用户名长度不得少于4位',
			'username.max'       => '用户名长度不得多于32位',
			'email.required'     => '您必须提供正确的邮箱帐号',
			'email.email'        => '您必须提供正确的邮箱帐号',
			'password.required'  => '您必须提供密码',
		);
		$input = Input::all();
		$validation = Validator::make($input, $rules, $messages);
		if (!$validation->fails())
		{
			$email    = $input['email'];
			$username = $input['username'];
			$name     = $input['name'];
			$password = $input['password'];
			$type     = Input::get('type');
			$status   = 1;
			if (!in_array($type, array_keys(Consts::$user_type_text)))
			{
				$type = UserType::Unknow;
			}
			$user     = UserService::get_user_by_username($username);
			if (empty($user))
			{
				print json_encode(array('status' => 0, 'message' => "该用户不存在"));exit;
			}
			$user_id  = $user->userid;
			$data = array();
			$data['type'] = $type;
			$data['status'] = $status;
			if (!empty($email))
			{
				$data['email'] = $email;
			}
			if (!empty($name))
			{
				$data['name'] = $name;
			}
			if (!empty($password))
			{
				$data['password'] = $password;
				UserService::save_password($user_id, $password);
			}
			UserService::update($user_id, $data);
			print json_encode(array('status' => 1));exit;
		}
		else
		{
			foreach ($validation->messages()->all() as $message)
			{
				print json_encode(array('status' => 0, 'message' => $message));exit;
			}
		}
	}
}