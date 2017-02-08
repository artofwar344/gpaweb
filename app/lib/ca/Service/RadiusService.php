<?php
namespace Ca\Service;

use \DB,
	\Str;

class RadiusService {

	public static function hash($user, $password)
	{
		$radius_pass = ParamsService::get('radiuspass');
		for($i = 0; $i < 16; $i ++)
		{
			if (strlen($password) < 16)
			{
				$password = $password . chr(0);
			}
		}
		$data = md5($user . $radius_pass, true);
		$data = $data ^ $password;

		$pwm = "";
		for($i = 0; $i < 16; $i ++)
		{
			$pwm = $pwm . bin2hex($data[$i]);
		}
		return $pwm;
	}

	/**
	 * @param $username
	 * @param $password
	 * @return bool|int
	 */
	public static function check($username, $password)
	{
		$pwm = static::hash($username, $password);
		$ch = curl_init('http://' . ParamsService::get('radiushost') . ':61671/ch&uid=' . $username . '&pwm=' . $pwm);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true) ;
		curl_setopt($ch, CURLOPT_BINARYTRANSFER, true) ;
		$output = curl_exec($ch);
		$pos = strpos($output,'code=');
		if ($pos)
		{
			$ret = substr($output, $pos + 5,2);
			if ($ret == '00')
			{
				$user = UserService::get_user_by_username($username);
				if (empty($user))
				{
					$password = md5($password);
					$email = $username . '@' . app()->environment();
					$name = $username;
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
					if (md5($password) != $user->password)
					{
						UserService::update($user_id, array('password' => md5($password)));
					}
				}
				return $user_id;
			}
		}
		return false;
	}
}