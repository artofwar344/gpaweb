<?php
namespace Ca\Service;

use \Config,
	Log,
	Ca\Common,
	Ca\UserType;


class ApiService {

	public static function get_info($username, $password)
	{
		$apiserver = ParamsService::get('apiserver');
		$post = array(
			'username' => $username,
			'password' => $password,
			'hash' => 'ay7TZnpElKxWO76fEe8ehB2BzuWfa2bnS02z'
		);
		try
		{
			Log::info('API login start...');
			// 将用户名 密码 和hash值post到api接口地址 得到返回值
			$ch = curl_init();
			curl_setopt($ch, CURLOPT_HEADER, 0);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
			curl_setopt($ch, CURLOPT_URL, $apiserver);
			curl_setopt($ch, CURLOPT_POST, true);
			curl_setopt($ch, CURLOPT_POSTFIELDS, $post);
			$result = curl_exec($ch);
			curl_close($ch);
			$result = json_decode($result);

			if ($result->login)
			{
				if (array_key_exists('user', $result))
				{
					return json_decode($result->user);
				}
				return array('username' => $username);
			}
			else
			{
				Log::info($result->errormessage);
				return array();
			}
		}
		catch (\Exception $e)
		{	
			Log::error($e);
		}
		return array();
	}

	public static function check($username, $password)
	{
		$info = ApiService::get_info($username, $password);
		if (!empty($info))
		{
			$user = UserService::get_user_by_username($username);
			if (empty($user)) //若用户名不存在则添加新用户
			{
				$username = $info['username'];
				$password = md5($password);
				$email = $username . '@' . App()->env;
				$name = $info['username'];
				$userType = UserType::Unknow;

				try
				{
					//add_user($email, $password, $username, $name, $department_id, $status, $token, $type = UserType::Unknow)
					 $user_id = UserService::add_user($email, $password, $username, $name, 4, 1, str_random(32), $userType);
				}
				catch (\Exception $e)
				{
					return false;
				}
			}
			else
			{
				$user_id = $user->userid;
				if (md5($password) != $user->password) //更新gp数据库密码
				{
					UserService::update($user_id, array('password' => md5($password)));
				}
			}
			return $user_id;
		}
		return false;
	}






}