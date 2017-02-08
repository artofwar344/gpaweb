<?php
namespace User;

use \Input,
	\Response,
	Log,
	Ca\Service\ParamsService,
	Ca\Service\RadiusService,
	Ca\Service\ManagerService,
	Ca\Service\KeyService,
	Ca\Service\UserService;

class RadiusauthController extends BaseController {

	public function index()
	{
		$username = Input::get('username');
		$password = Input::get('password');
		$hash     = Input::get('hash');
		if (ParamsService::get('radiusopen') && $hash == 'ay7TZnpElKxWO76fEe8ehB2BzuWfa2bnS02z')
		{
			Log::info('Radius login start...');
			$user_id = RadiusService::check($username, $password);
			if ($user_id && ParamsService::get('autoassignopen'))
			{
				$reason = 'RADIUS登录自动分配';
				$managers = ManagerService::managers();
				KeyService::auto_assign($user_id, $managers[0]->managerid, $reason);
			}

			if ($user_id != false)
			{
				Log::info('login success');
				$user = UserService::get_user_by_userid($user_id);
				echo json_encode(array(
					'id'       => $user_id,
					'name'     => $user->name,
					'username' => $user->username,
					'email'    => $user->email,
					'status'   => $user->status,
					'department' => array(
						'name' => $user->department_name,
						'id'   => $user->departmentid,
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
}