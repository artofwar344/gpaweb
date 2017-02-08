<?php
namespace Ca\Service;

use \DB,
	\Config,
	Log,
	SoapClient,
	Ca\Common,
	Ca\Service\DesUtil,
	Ca\Service\RsaUtil,
	Ca\UserType;


class IdpService {

	public static function get_info($username, $password)
	{
		$wsdl = ParamsService::get('idpwsdl');
		$pubkey = ParamsService::get('idpkey');
		$clientId = ParamsService::get('idpclient');
		try
		{
			$deskey = Common::get_random_str(8);
			$desUtil = new DesUtil($deskey);
			$uid = $desUtil->encrypt($username);
			$password = $desUtil->encrypt($password);
			$clientinfo = $desUtil->encrypt(json_encode(array('clientId' => $clientId)));
			$rsaUtil = new RsaUtil($deskey);
			$veryfy = $rsaUtil->setPublicKey($pubkey)->publicEncrypt($deskey);

			$params = array(
				'uid' => $uid,
				'password' => $password,
				'client' => $clientinfo,
				'veryfy' => $veryfy,
			);

			$soapClient = new SoapClient($wsdl);
			$ret = $soapClient->__soapCall("login", array("parameters" => $params));
			$retJson = json_decode($ret->return);
			if($retJson->success)
			{
				Log::info('idp login success, retJson:' . $ret->return);
				$user = json_decode($desUtil->decrypt($retJson->retJson));
				return $user;
			}
			else
			{
				Log::info('idp login false, retJson:' . $ret->return);
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
		$info = IdpService::get_info($username, $password);
		if (!empty($info))
		{
			$user = UserService::get_user_by_username($username);
			if (empty($user))
			{
				$username = $info->id;
				$password = md5($password);
				$email = $username . '@' . App()->env;
				$name = $info->attributes->cn;
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
		return false;
	}
	public static function check2($username, $password, $wsdl, $pubkey, $clientId)
	{
		$info = IdpService::get_info2($username, $password, $wsdl, $pubkey, $clientId);
		if (!empty($info))
		{
			$user = UserService::get_user_by_username($username);
			if (empty($user))
			{
				$username = $info->id;
				$password = md5($password);
				$email = $username . '@' . App()->env;
				$name = $info->attributes->cn;
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
		return false;
	}
	public static function get_info2($username, $password, $wsdl, $pubkey, $clientId)
	{

		try
		{
			$deskey = Common::get_random_str(8);
			$desUtil = new DesUtil($deskey);
			$uid = $desUtil->encrypt($username);
			$password = $desUtil->encrypt($password);
			$clientinfo = $desUtil->encrypt(json_encode(array('clientId' => $clientId)));

			$rsaUtil = new RsaUtil($deskey);
			$veryfy = $rsaUtil->setPublicKey($pubkey)->publicEncrypt($deskey);
			$params = array(
				'uid' => $uid,
				'password' => $password,
				'client' => $clientinfo,
				'veryfy' => $veryfy,
			);
			$soapClient = new SoapClient($wsdl);
			$ret = $soapClient->__soapCall("login", array("parameters" => $params));
			$retJson = json_decode($ret->return);
			if($retJson->success)
			{
				Log::info('idp login success, retJson:' . $ret->return);
				$user = json_decode($desUtil->decrypt($retJson->retJson));
				return $user;
			}
			else
			{
				Log::info('idp login false, retJson:' . $ret->return);
				return array();
			}

		}
		catch (\Exception $e)
		{
			Log::error($e);
		}
		return array();
	}



}