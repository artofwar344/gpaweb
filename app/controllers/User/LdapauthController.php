<?php
namespace User;

use \Input,
	\Response,
	Log,
	Ca\Service\ParamsService,
	Ca\Service\LdapService,
	Ca\Service\ManagerService,
	Ca\Service\KeyService,
	Ca\Service\UserService;

class LdapauthController extends BaseController {

	public function index()
	{
		$username = Input::get('username');
		$password = Input::get('password');
		$hash     = Input::get('hash');
		$user     = null;
		Log::info('login info: username: ' . $username . ' password: ' . $password);
		if (ParamsService::get('ldaphost') && $hash == 'ay7TZnpElKxWO76fEe8ehB2BzuWfa2bnS02z')
		{
			Log::info('LDAP login start...');
			$user_id = LdapService::check($username, $password);
			if ($user_id)
			{
				if (ParamsService::get('autoassignopen'))
				{
					$reason = 'LDAP登录自动分配';
					$managers = ManagerService::managers();
					KeyService::auto_assign($user_id, $managers[0]->managerid, $reason);
				}
				UserService::save_password($user_id, $password);
				$user = UserService::get_user_by_userid($user_id);
			}
			else
			{
				Log::info('GP sites login start...');
				$user = UserService::get_user_by_username_and_password($username, md5($password));
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
		Log::info('LDAP validate: username: ' . $username . ' password: ' . $password);
		if (ParamsService::get('ldaphost') && $hash == 'ay7TZnpElKxWO76fEe8ehB2BzuWfa2bnS02z')
		{
			$user_id = LdapService::check($username, $password);
			if ($user_id && ParamsService::get('autoassignopen'))
			{
				$reason = 'LDAP登录自动分配';
				$managers = ManagerService::managers();
				KeyService::auto_assign($user_id, $managers[0]->managerid, $reason);
			}
			$status =  empty($user_id) ? -1 : 1;
			Log::info('/nlogin status: ' . $status);
			echo json_encode(array(
				'status' => $status
			));
		}

	}
}