<?php
namespace Ca\Service;

use \DB,
	Ca\Common,
	Ca\UserType;

class UserService {
	/** 是否已经分配密钥
	 * @param $userid
	 * @return bool
	 */
	public static function key_assigned($userid)
	{
		$count = DB::table('userkey')
			->select(array(DB::raw('COUNT(keyid) as count')))
			->where('status', '!=', 1)
			->where('userid', '=', $userid)
			->first();
		return $count->count > 0;
	}

	public static function check_customer($userid)
	{
		if ($userid <= 0) return false;

		$count = DB::table('user')
			->leftJoin('department', 'department.departmentid', '=', 'user.departmentid')
			->where('userid', '=', $userid)
			->count();

		return $count > 0;
	}

	public static function save_password($userid, $password)
	{
		$count = DB::table('userpassword')->whereUserid($userid)->count();
		if ($count > 0)
		{
			DB::table('userpassword')
				->where('userid', '=', $userid)
				->update(array('password' => DB::raw('aes_encrypt("' . $password . '", "b$^#4G2$%#$%")')));
		}
		else
		{
			DB::table('userpassword')
				->insert(array('userid' => $userid, 'password' => DB::raw('aes_encrypt("' . $password . '", "b$^#4G2$%#$%")')));
		}
	}

	public static function add_user($email, $password, $username, $name, $department_id, $status, $token, $type = UserType::Unknow)
	{
		$query = DB::table('user');
		$data = array(
			'email' => $email,
			'password' => $password,
			'username' => $username,
			'name' => $name,
			'departmentid' => $department_id,
			'status' => $status,
			'token' => $token,
			'type' => $type,
			'createdate' => DB::raw('NOW()')
		);
		return $query->insertGetId($data);
	}


	public static function update_user_lastlogin($user_id, $date)
	{
		DB::table('user')->whereUserid($user_id)->update(array('lastlogin' =>$date));
	}

	public static function add_useraccesslog($user_id, $date)
	{
		$data = array(
			'userid' => $user_id,
			'createdate' => $date
		);
		DB::table('useraccesslog')->insert($data);
	}

	public static function get_user_by_email($email)
	{
		return DB::table('user')->whereEmail($email)->first();
	}

	public static function get_user_by_username($username)
	{
		return DB::table('user')->whereUsername($username)->first();
	}

	public static function get_user_by_username_and_password($username, $password)
	{
		$query = DB::table('user')
			->select(array('user.*', 'department.name as department_name'))
			->leftJoin('department', 'user.departmentid', '=', 'department.departmentid')
			->whereUsername($username)
			->wherePassword($password);
		return $query->first();
	}

	public static function get_user_by_userid($user_id, $token = null)
	{
		$query = DB::table('user')
			->select(array('user.*', 'department.name as department_name'))
			->leftJoin('department', 'user.departmentid', '=', 'department.departmentid');
		$query->whereUserid($user_id);
		if ($token != null)
		{
			$query->whereToken($token);
		}
		return $query->first();
	}

	public static function update($user_id, $data)
	{
		DB::table('user')
			->whereUserid($user_id)
			->update($data);
	}

	/**
	 * 返回userid, 如果用户名不存在, 则保存到数据库
	 * @param $username
	 * @param $email
	 * @param $name
	 * @param null $password
	 * @return bool
	 */
	public static function saveUser($username, $email, $name, $password=null)
	{
		$user = UserService::get_user_by_username($username);
		if (empty($user))
		{
			if (empty($password))
			{
				$password = str_random(8);
			}
			$password = md5($password);
			try
			{
				$user_id = UserService::add_user($email, $password, $username, $name, 2, 1, str_random(32));
			}
			catch (\Exception $e)
			{
				return false;
			}
		}
		else
		{
			$user_id = $user->userid;
			if ((!is_null($password) && md5($password) != $user->password) || $user->name == $username)
			{
				UserService::update($user_id, array('password' => md5($password), 'name' => $name));
			}
		}
		return $user_id;
	}
	/**
	 * 判断指定ip段
	 * @return bool
	 */
	public static function special_ip()
	{
		$client_ip = Common::client_ip();
		if (empty($client_ip))
		{
			return false;
		}
		$ip_rules = ParamsService::get('specialip');
		if (!empty($ip_rules))
		{
			$ip_sections = explode(';', $ip_rules);
			foreach ($ip_sections as $section)
			{
				if (strpos($section, '-') !== false)
				{
					$ip = explode('-', $section);
					if (count($ip) == 2)
					{
						if (ip2long($ip[0]) <= ip2long($client_ip) && ip2long($ip[1]) >= ip2long($client_ip))
						{
							return true;
						}
					}
				}
				else
				{
					list($net_addr, $net_mask) = explode('/', $section);
					if ($net_mask > 0)
					{
						$ip_binary_string  = sprintf("%032b", ip2long($client_ip));
						$net_binary_string = sprintf("%032b", ip2long($net_addr));
						if ((substr_compare($ip_binary_string, $net_binary_string, 0, $net_mask) === 0))
						{
							return true;
						}
					}
				}
			}
		}
		return false;
	}

	public static function get_user_by_info($username, $password, $departmentId, $from = 1)
	{
		$results = DepartmentService::getChildDepartments($departmentId, $departmentIds);
//		var_dump($results);exit;
		$query = DB::table('user')
			->select(array('user.*', 'department.name as department_name'))
			->leftJoin('department', 'user.departmentid', '=', 'department.departmentid')
			->where('from', $from)
			->whereIn('user.departmentid', $results)
			->whereUsername($username)
			->wherePassword($password);
		return $query->first();
	}

//	public static function checkDelete($userid)
//	{
//		$documentCount = DB::table('document')
//			->where('userid', '=', $userid)
//			->where('status', '=', \Ca\DocumentStatus::normal)
//			->where('publish', '=', \Ca\DocumentPublish::submit_d)
//			->count();
//		if ($documentCount > 0)
//		{
//			return false;
//		}
//		$questionCount = DB::table('question')
//			->where('userid', '=', $userid)
//			->where('status', '=', \Ca\QuestionStatus::normal)
//			->count();
//		if ($questionCount > 0)
//		{
//			return false;
//		}
//		$answerCount = DB::table('answer')
//			->where('userid', '=', $userid)
//			->count();
//		if ($answerCount > 0)
//		{
//			return false;
//		}
//		$answerCount = DB::table('topic')
//			->where('userid', '=', $userid)
//			->where('status', '=', \Ca\TopicStatus::normal)
//			->count();
//		if ($answerCount > 0)
//		{
//			return false;
//		}
//		return true;
//	}
}