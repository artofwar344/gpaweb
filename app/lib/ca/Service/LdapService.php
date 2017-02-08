<?php
namespace Ca\Service;

use \Config,
	Log,
	Auth,
	Ca\Common,
	Ca\UserType;


class LdapService {

	public static function get_info($username, $password)
	{
		$ldap_host = ParamsService::get('ldaphost');
		if (app()->customer->alias == 'seu' )
		{
			$f = substr($username,0,1);
			switch ($f) {
				case '1':
					$ou = 'teacher';
					break;
				case '2':
					$ou = 'student';
					break;
				default:
					$ou = 'others';
					break;
			}

			$ldap_dn = 'ou=' . $ou . ',' . ParamsService::get('ldapdn');
			$ldap_tree = $ldap_dn;
			$ldap_name = $username;
			$ldap_password = $password;
		}
		else if(app()->customer->alias == 'ougd'){
			$ldap_dn = ParamsService::get('ldapdn');
			$ldap_tree = $ldap_dn;
			$ldap_name = $username;
			$ldap_password = $password;
		}else
		{
			$ldap_dn = ParamsService::get('ldapdn');
			$ldap_tree = ParamsService::get('ldaptree');
			$ldap_name = ParamsService::get('ldapname');
			$ldap_password = ParamsService::get('ldappassword');
		}
		
			
		try
		{
			Log::info('LDAP login start... (DN : '.$ldap_dn.')');
			$conn = ldap_connect($ldap_host);//连接ldap服务器
			$ret = ldap_bind($conn, 'uid=' . $ldap_name . ',' . $ldap_dn, $ldap_password); //登录管理员账号
			if ($ret)
			{
				$result = ldap_search($conn, $ldap_tree, 'uid=' . $username);
				
				if ($result)
				{
					$infos = ldap_get_entries($conn, $result);
					if(app()->customer->alias == 'ougd')
					{
						//if(app()->customer->alias == 'ougd'){
							//print_r($infos);exit;
						//}
						return $infos[0];
					}
					Log::info('retInfo: '. json_encode($infos));
					$userpassword = $infos[0]['userpassword'][0];
					// 密码不正确
					if (!(substr($userpassword, 0, 5) == '{MD5}' && static::password_check($password, $userpassword, 'md5'))
						&& !(substr($userpassword, 0, 5) == '{SHA}' && static::password_check($password, $userpassword, 'sha'))
						&& !(substr($userpassword, 0, 6) == '{SSHA}' && static::password_check($password, $userpassword, 'ssha'))
					)
					{
						Log::info('#@# SSHA :'.substr($userpassword, 0, 6));
						return array();
					}
					$ret = array();

					if (isset($infos[0]))
					{
						foreach ($infos[0] as $key => $info)
						{
							if (is_numeric($key))
							{
								continue;
							}
							if (@$info['count'] == 1)
							{
								$ret[$key] = $info[0];
							}
							else
							{
								$ret[$key] = $info;
							}
						}
					}
					ldap_close($conn);
					return $ret;
				}
				else
				{
					Log::info('user name or password error');
				}
			}
			else
			{
				Log::info('ldap bind fail');
				
			}

		}
		catch (\Exception $e)
		{
			Log::error($e);
		}

		return array();
	}

//	public static function update_password($username, $password, $new_password)
//	{
//		$ldap_host = ParamsService::get('ldaphost');
//		$ldap_dn = ParamsService::get('ldapdn');
//		try
//		{
//			$conn = ldap_connect($ldap_host);
//			$ret = @ldap_bind($conn, 'uid=' . $username . ',' . $ldap_dn, $password);
//			if (!$ret)
//			{
//				return array('status' => 0);
//			}
//			$info['userpassword'] = $new_password;
//			ldap_modify($conn, $ldap_dn , $info);
//			ldap_close($conn);
//			return array('status' => 1);
//		} catch (\Exception $e) {}
//		return array('status' => 0);
//	}

	public static function check($username, $password)
	{
		$info = LdapService::get_info($username, $password);
		//print_r($info);exit;
		if($username == 'piliang')
		{
			$remember = true;
			$credentials = array('username' => $username, 'password' => $password, 'provider' => 'user');
			if(Auth::attempt($credentials, $remember))
			{
				Auth::attempt($credentials, $remember);
				Log::info('login success');
				return Auth::user()->userid;
			}else
			{
				Log::info('login fail');
				return false;
			}
		}
		
		if (!empty($info))
		{
			//print_r($info);exit;
			$user = UserService::get_user_by_username($username);
			if (empty($user))
			{
				
				$username = $username;
				$password = md5($password);
				if (app()->customer->alias == 'ougd')
				{
					$email = $info['uid'][0]."@ougd.gpa.edu.cn";
					$name = $info['cn'][0];

				}else
				{
					$email = array_get($info, 'mail', $info['uid'] . '@' . App()->env);
					$name = array_get($info, 'cn', $username);
				}
				if (app()->customer->alias == 'seu')
				{
					$f = substr($username,0,1);
					if ($f == '1')
					{
						$userType = UserType::Teacher;
					}
					elseif ($username == "test004")
					{
						$userType = UserType::Unknow;
					}else return false;
				} 
				if (app()->customer->alias == 'ougd')
				{
					if ($info['initials'][0] == 'teacher')
					{
						$userType = UserType::Teacher;
					}
					else
					{
						$userType = UserType::Student;
					}

				}
				elseif (app()->customer->alias == 'fzu')
				{
					$f = strtoupper(substr($username,0,1));
					if($f == 'T' ){
						$userType = UserType::Teacher;
					}elseif($f == 'N' || $f == "M")
					{
						$userType = 4;
					}elseif(is_numeric($f)){
						$userType = 2;
					}
				}
				else $userType = array_get($info, 'edupersonaffiliation') == '教师' ? UserType::Teacher : UserType::Student;
				
				try
				{
					$user_id = UserService::add_user($email, $password, $username, $name, 2, 1, str_random(32), $userType);
				}
				catch (\Exception $e)
				{
					//echo "2";exit;
					return false;
				}
			}
			else
			{
				$user_id = $user->userid;
				if (md5($password) != $user->password)
				{
					UserService::update($user_id, array('password' => md5($password)));
				}
			}
			return $user_id;
		}
		return false;
	}

	public static function password_check($password, $hash, $type='md5')
	{
		$ret = false;
		switch ($type)
		{
			case 'md5':
				$ret = static::md5_check($password, $hash);
				break;
			case 'sha':
				$ret = static::sha_check($password, $hash);
				break;
			case 'ssha':
				$ret = static::ssha_check($password, $hash);
				break;
		}
		return $ret;
	}

	/**
	 * MD5加密
	 * @param $password  需要加密的字符串
	 * @param $hash
	 * @return bool
	 **/
	public static function md5_check($password, $hash)
	{
		$md5 = '{MD5}'.base64_encode(pack('H*',md5($password)));
		return $md5 == $hash;
	}

	/**
	 * SHA加密
	 * @param $password  需要加密的字符串
	 * @param $hash
	 * @return bool
	 **/
	public static function sha_check($password, $hash)
	{
		$ldap_passwd = '{SHA}'.base64_encode(pack('H*', sha1($password)));
		return $ldap_passwd == $hash;
 }


	/**
	 * SSHA加密算法
	 * @param $password  需要加密的字符串
	 * @param $hash
	 * @return bool
	 **/
	public static function ssha_check($password, $hash)
	{
		$salt = substr(base64_decode(substr($hash,6)),20);
		$encrypted_password = '{SSHA}' . base64_encode(sha1( $password.$salt, TRUE ). $salt);
		log::info('#@#encrypted_password : ' . $encrypted_password);
		log::info('#@#hash : ' . $hash);
		return $encrypted_password == $hash;
	}

	/**
	 * 判断是否加密
	 * @param $password
	 * @param $hash
	 * @return bool
	 */
	public static function ldap_ssha_check($password, $hash)
	{
		$salt = substr(base64_decode(substr($hash,6)),20);
		$encrypted_password = '{SSHA}' . base64_encode(sha1( $password.$salt, TRUE ). $salt);
		return $encrypted_password == $hash;
	}

}