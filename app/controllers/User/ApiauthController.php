<?php
namespace User;

use \Input,
	\Response,
	\Log,
	Ca\Service\ParamsService,
	Ca\Service\ApiService,
	Ca\Service\ManagerService,
	Ca\Service\KeyService,
	Ca\Service\UserService;

/**
 * 通过API接口（apiserver）进行用户验证
 * 目前大连海事使用该验证方式
 * Class ApiauthController
 * @package User
 */
class ApiauthController extends BaseController {

	public function index()
	{
		$username = Input::get('username');
		$password = Input::get('password');
		$hash     = Input::get('hash');
		$user     = null;
		Log::info('login info: username: ' . $username . ' password: ' . $password);
		if (ParamsService::get('apiserver') && $hash == 'ay7TZnpElKxWO76fEe8ehB2BzuWfa2bnS02z')
		{
			Log::info('API login start...');
			$user_id = ApiService::check($username, $password);
			if ($user_id)
			{
				if (ParamsService::get('autoassignopen'))//自动分配
				{
					$reason = 'API登录自动分配';
					$managers = ManagerService::managers();
					KeyService::auto_assign($user_id, $managers[0]->managerid, $reason);
				}
				UserService::save_password($user_id, $password);
				$user = UserService::get_user_by_userid($user_id);
			}
//			else
//			{
//				Log::info('GP sites login start...');
//				$user = UserService::get_user_by_username_and_password($username, md5($password));
//			}

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

	/**
	 * 登录检测
	 * 成功返回status(json) 1，失败返回-1
	 */
	public function validate()
	{
		$username = Input::get('username');
		$password = Input::get('password');
		$hash     = Input::get('hash');
		Log::info('API validate: username: ' . $username . ' password: ' . $password);
		if (ParamsService::get('apiserver') && $hash == 'ay7TZnpElKxWO76fEe8ehB2BzuWfa2bnS02z')
		{
			$user_id = ApiService::check($username, $password);
			if ($user_id && ParamsService::get('autoassignopen'))
			{
				$reason = 'API登录自动分配';
				$managers = ManagerService::managers();
				KeyService::auto_assign($user_id, $managers[0]->managerid, $reason);
			}
			echo json_encode(array(
				'status' => empty($user_id) ? -1 : 1
			));
		}

	}
}