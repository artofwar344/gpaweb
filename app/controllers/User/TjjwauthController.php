<?php
namespace User;

use \DB,
	\Input,
	\Response,
	\Log,
	Ca\Service\ParamsService,
	Ca\Service\IdpService,
	Ca\Service\ManagerService,
	Ca\Service\KeyService,
	Ca\Service\UserService;

/**
 * 在idpauth的基础上 添加了userinfo1的验证
 * Class Idpauth2Controller
 * @package User
 */
class TjjwauthController extends BaseController {

	public function index()
	{
		$username = Input::get('username');
		$password = Input::get('password');
		$hash     = Input::get('hash');
		$departmentId = Input::get('departmentId');
		$user      = null;
		Log::info('login info: username: ' . $username . ' password: ' . $password);
		if($hash == 'ay7TZnpElKxWO76fEe8ehB2BzuWfa2bnS02z')
		{
			self::common($departmentId);
			$user_id = IdpService::check2($username, $password, $this->wsdl, $this->pubkey, $this->clientId);
			if ($user_id)
			{
				if (ParamsService::get('autoassignopen'))
				{
					$reason = 'IDP登录自动分配';
					$managers = ManagerService::managers();
					KeyService::auto_assign($user_id, $managers[0]->managerid, $reason);
				}
				UserService::save_password($user_id, $password);
				$user = UserService::get_user_by_userid($user_id);
			}
			else //查询gp数据库
			{
				Log::info('GP sites login start...');

				$user = UserService::get_user_by_info($username, md5($password), $departmentId);
			}

			if ($user)
			{
				Log::info('login success');
				echo json_encode(array(
					'id' => $user->userid,
					'name' => $user->name,
					'username' => $user->username,
					'email' => $user->email,
					'status' => $user->status,
					'department' => array(
						'name' => $user->department_name,
						'id' => $user->departmentid,
					)
				));
			}
			else
			{
				Log::info('login fail');
				echo json_encode(array('status' => -1));
			}

			exit;
		}

		return Response::error(404);
	}

	public function validate()
	{
		$username = Input::get('username');
		$password = Input::get('password');
		$hash     = Input::get('hash');
		$departmentId = Input::get('departmentId');
		Log::info('IDP validate: username: ' . $username . ' password: ' . $password);
		if ($hash == 'ay7TZnpElKxWO76fEe8ehB2BzuWfa2bnS02z')
		{
			self::common($departmentId);
			$user_id = IdpService::check2($username, $password, $this->wsdl, $this->pubkey, $this->clientId);
			if ($user_id)
			{
				if (ParamsService::get('autoassignopen'))
				{
					$reason = 'IDP登录自动分配';
					$managers = ManagerService::managers();
					KeyService::auto_assign($user_id, $managers[0]->managerid, $reason);
				}
				UserService::save_password($user_id, $password);
				$user = UserService::get_user_by_userid($user_id);
			}
			else //查询gp数据库
			{
				Log::info('GP sites login start...');
				$user = UserService::get_user_by_info($username, md5($password), $departmentId);
			}
			if($user)
			{
				$user_id = $user->userid;
			}
			echo json_encode(array(
				'status' => empty($user_id) ? -1 : 1
			));
		}
	}
	protected function common($departmentId)
	{
		switch ($departmentId)
		{
			case '53': // 天津工业大学
				$this->wsdl = 'http://211.68.112.120:85/united-login/services/login?wsdl';
				$this->pubkey = '-----BEGIN PUBLIC KEY-----
MFwwDQYJKoZIhvcNAQEBBQADSwAwSAJBALBEsefW9sfaiPDouFOSjjYUNtkm33uI
        jAnAe64K0tkHFfGQh8VxLOE+kh5oczz09JH0I7xyd7tMxGO1IbtGUr8CAwEAAQ==
-----END PUBLIC KEY-----';
				$this->clientId = 'client2';

				break;
			case '54': //天津教委
				$this->wsdl = 'http://211.81.21.236:85/united-login/services/login?wsdl';
				$this->pubkey = '-----BEGIN PUBLIC KEY-----
MIIBIjANBgkqhkiG9w0BAQEFAAOCAQ8AMIIBCgKCAQEA5mxcrn+JpWsFcng5UNtW
fqEdeBRawGxWOaYkbUnZpE7ZCOiLOyFDP+lUhrlB381etcdqQppWLrkTrXWHkcTO
f2cOi1cUWRpwRuPcD35Vm8K9c1MaHROQKmNyAwRzukTmOyd4RrRzF0RpjHjrXcD6
GmrJ8INPVrpe66y6cPDA/0srqJs8Cn9LpzSv90rhrCqRHymHIDhpDjvXuIpg6Muy
qeFnME+vA9zzHNSGk2IqdikwGQVpZrleIcBxWdnNNwfEsTVGw19XzK39dRsq5BVU
8RWAAuOzFAXhjdc+xk2rIQaNYHqewNr3yNF/n6ZUmy/DHpitUmYI3Hio4Tl4d0yf
kQIDAQAB
-----END PUBLIC KEY-----';
				$this->clientId = 'client2';
				break;
			case '55': //南开大学
				$this->wsdl = 'http://222.30.44.141:8080/united-login/services/login?wsdl';
				$this->pubkey = '-----BEGIN PUBLIC KEY-----
MIIBIjANBgkqhkiG9w0BAQEFAAOCAQ8AMIIBCgKCAQEA9c1wbnhVeZAn9yP2w0FS
oy+Key5EB94mCExuCBoz+wLWjCNoI1Gs/42k9sL5jMSLr40/uvPFm95S0TJtVE5Z
ImUFrTWYym60UMe8OUSQR0Kp47179LSHOx3O1eUlY7A1YR3eTpPaUNEvuG8b1+s2
YXGJNyejISsSwK9QoiN+K7h9Lz+dLtwHJpv7XXC+ON2+LgblnYw6T2paaryD8mgj
q/wAusMC2J2wuN+bQS8cIo6XQSk9wFaTtUSkUGlFk1xX9uBEm1GYGShiChJpEUAu
BCbFfplUaGTSI9eEQVdp4YD4soNUtt1E0VQwsOknL2zVIFGxEf8gFC1AoBclIvoB
CQIDAQAB
-----END PUBLIC KEY-----';
				$this->clientId = 'client2';
				break;
			case '52': //天津科技大学
				$this->wsdl = 'http://59.67.5.42:85/united-login/services/login?wsdl';
				$this->pubkey = '-----BEGIN PUBLIC KEY-----
MIIBIjANBgkqhkiG9w0BAQEFAAOCAQ8AMIIBCgKCAQEA4HecYvLc/0XXU8RAwrSk
nPR7GZdp/BB85Hygn9WT2PI9j8IQILfJYMmvmMLyzdiworKFH8wDcePBJjnB55dE
vlENoRISE8cGDG5VT2OiwUkGwKF37Ynu6ympeN9/eOQvmxzm13DkV5bbmZ2xHAka
kgPLDa9Cdr5U8/YqWnlX0v6A09s606iy1K9XVtEXf7MKJp0ewacXLrDgzoY8QFnJ
M8ev8+5QjABrlsoN6nva4I4ocNyxW+3m/D0io2GE/b9009kv20YF6l7Yy8To/Wm9
gSN+VofQrLh5Fpnnu0FluIKRAYGMndmzW5VPK/ldKRUG7U8vLGvfZaK7dGNl1TVy
PwIDAQAB
-----END PUBLIC KEY-----';
				$this->clientId = 'client2';
				break;
			default:
				exit;

		}
	}
}