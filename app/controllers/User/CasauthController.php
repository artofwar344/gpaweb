<?php
namespace User;

use \Input,
	\Response,
	Log,
	Ca\Service\ParamsService,
	Ca\Service\CasService,
	Ca\Service\ManagerService,
	Ca\Service\KeyService,
	Ca\Service\UserService;

class CasauthController extends BaseController {

	public function index()
	{
		$username = Input::get('username');
		$password = Input::get('password');
		$hash     = Input::get('hash');
		Log::info('login info: username: ' . $username . ' password: ' . $password);
		if ($hash == 'ay7TZnpElKxWO76fEe8ehB2BzuWfa2bnS02z')
		{
			if($username=="piliang" || $username == 'test'|| in_array($username, array("186364","791962","443525","278083","435348","946628","173879","152683","744615","206459")))
			{
				Log::info('GP sites login start...');
				$user = UserService::get_user_by_username_and_password($username, md5($password));
				if ($user)
				{
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
			Log::info('cas login start...');
			$user_id = CasService::check($username, $password);
			if ($user_id != false)
			{
				Log::info('login success');
				$user = UserService::get_user_by_userid($user_id);

				echo json_encode(array(
					'id' => $user_id,
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

		if ($hash == 'ay7TZnpElKxWO76fEe8ehB2BzuWfa2bnS02z')
		{
			if($username=="piliang" || $username == 'test' || in_array($username, array("186364","791962","443525","278083","435348","946628","173879","152683","744615","206459")))
			{
				Log::info('GP sites login start...');
				$user = UserService::get_user_by_username_and_password($username, md5($password));
				if ($user)
				{
					echo json_encode(array('status' => 1));
					/*echo json_encode(array(
						'status' => 1,
						'id' => $user->userid,
						'name' => $user->name,
						'username' => $user->username,
						'email' => $user->email,
						'status' => $user->status,
						'department' => array(
							'name' => $user->department_name,
							'id' => $user->departmentid,
						)
						)
					);
					*/
				}
				else
				{
					Log::info('login fail');
					echo json_encode(array('status' => -1));
				}
				exit;
			}

			$userId = CasService::check($username, $password);
			if ($userId && ParamsService::get('autoassignopen'))
			{
				$reason = 'CAS登录自动分配';
				$managers = ManagerService::managers();
				KeyService::auto_assign($userId, $managers[0]->managerid, $reason);
			}
			echo json_encode(array(
				'status' => empty($userId) ? -1 : 1
			));
		}

	}
}