<?php
namespace Ca\Service;

use \DB,
	\Config,
	Log,
	SoapClient,
	Ca\Common,
	Ca\UserType;

class WebserviceService {

	public static function get_info($username, $password)
	{
		try
		{
			ini_set('default_socket_timeout', 5); 
			//创建WebService对象
			$wsdl = ParamsService::get('webserverhost');

			header('Content-Type: application/soap+xml; charset=utf-8');
			$client = new SoapClient($wsdl);
			
			//调用方法         
			if(app()->customer->alias == 'gtcfla') {
				$arguments = array('myusernmae' => $username, 'mypassword' => $password);
				if(app()->customer->alias == 'gtcfla') Log::info(time().'************login info Ftcfla WebService Login Begin  : ' . json_encode($arguments));
				$result = $client->getuser($arguments);
				$res = $result->getuserResult;
				if(app()->customer->alias == 'gtcfla') Log::info(time().'************login info Ftcfla WebService Login Result  : ' . json_encode($res));

				if($res) {
					$result->js = '教职工';
					$result->xm = explode('*', $res)[1];
					$result->username = explode("*", $res)[0];
					//var_dump($result);
					return $result;
				}else{
					return false;
				}
			}else{
				$arguments = array('arg0' => $username, 'arg1' => $password);
				$result = $client->checkAccountPwd($arguments);
				if ($result->return)
				    return DB::table('userdata')
							->select(array('*'))
							->where('id','=',$username)
							->first();
				else
				    return false;
			}
			//返回验证结果 true验证通过 false验证失败
		} catch (\Exception $e) {
			//Log::error('#########################################' . app()->customer->alias);
			Log::error($e);
			return false;
		}
		
		//返回验证结果 true验证通过 false验证失败
		if ($result->return)
		    return DB::table('userdata')
					->select(array('*'))
					->where('id','=',$username)
					->first();
		else
		    return false;
		exit;
		//\phpinfo();exit;
		/*$parmas = array();
		$parmas[0] = new \stdClass();
		$parmas[0]->Account = $username;
		$parmas[0]->Pwd = $password;*/
		//var_dump($parmas);exit;
		$param = array($username,$password);
		$parmas = array('checkAccountPwd' => $param);
		$url = ParamsService::get('webserverhost');
		try 
		{
			$client = new SoapClient($url);
			//var_dump($client->__getFunctions());
			//var_dump($client->__getTypes());
			//var_dump($parmas);exit;
			//$result = $client->__soapCall("checkAccountPwd", $parmas);
			$result = $client->checkAccountPwd($parmas);
			//var_dump($result);exit;
			$val = $result->return;
			if ($val)
			{
				return $val;
			}else{
				return false;
			}
		} catch (\Exception $e) {
			Log::error($e);
		}

		return array();
	}

	public static function check($username, $password)
	{
		$info = self::get_info($username, $password);
		$email = $name = "";
		$userid = null;
		ini_set('default_socket_timeout', 180); 
		if($info)
		{
			if(app()->customer->alias == 'gtcfla') {
				$username = $info->username;
			}
			$user = UserService::get_user_by_username($username);
			if (empty($user))
			{
				
				//$vals = explode(",", $info);
				switch ($info->js)
				{
						case '学生':
							$userType = UserType::Student;
							break;
						case '教职工':
							$userType = UserType::Teacher;	// 教职工
							break;
						default:
							$userType = UserType::Unknow;;	// 未知
							break;
				}
				$password = md5($username);
				$email = $username . '@'.app()->customer->alias.'.gpa.edu.cn';
				$name = $info->xm;
				$user_id = UserService::add_user($email, $password, $username, $name, 2, 1, str_random(32), $userType);	
			}
			else
			{
				$user_id = $user->userid;
				if (md5($password) != $user->password)
				{
					UserService::update($user_id, array('password' => md5($password)));
				}
			}
			if($info->js == '学生')return false;
			return $user_id;
		}
		return false;
	}
}