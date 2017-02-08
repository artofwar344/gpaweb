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

class IdpauthController extends BaseController {

	public function index()
	{
		$username = Input::get('username');
		$password = Input::get('password');
		$hash     = Input::get('hash');
		$user     = null;
		Log::info('login info: username: ' . $username . ' password: ' . $password);
		if (ParamsService::get('idpwsdl') && $hash == 'ay7TZnpElKxWO76fEe8ehB2BzuWfa2bnS02z')
		{
			Log::info('IDP login start...');

			$user_id = IdpService::check($username, $password);
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
		Log::info('IDP validate: username: ' . $username . ' password: ' . $password);
		if (ParamsService::get('idpwsdl') && $hash == 'ay7TZnpElKxWO76fEe8ehB2BzuWfa2bnS02z')
		{
			$user_id = IdpService::check($username, $password);
			if ($user_id && ParamsService::get('autoassignopen'))
			{
				$reason = 'IDP登录自动分配';
				$managers = ManagerService::managers();
				KeyService::auto_assign($user_id, $managers[0]->managerid, $reason);
			}
			if (!$user_id)
			{
				$user = UserService::get_user_by_username_and_password($username, md5($password));
				if ($user)
				{
					$user_id = $user->userid;
				}
			}
			echo json_encode(array(
				'status' => empty($user_id) ? -1 : 1
			));
		}

	}
}