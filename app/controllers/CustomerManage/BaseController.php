<?php
namespace CustomerManage;

use Auth,
	App,
	Request,
	Redirect,
	Response,
	Ca\Common,
	Ca\Service\ManagerService,
	Ca\Consts,
	Ca\Service\PermissionService,
	Ca\Service\ParamsService;

class BaseController extends \Controller {

	public function __construct()
	{
		$this->manager = Auth::user();
		$action = 'index';
		$paths = explode('/', Request::path());
		if (count($paths) > 1)
		{
			list($controller, $action) = $paths;
		}
		else
		{
			$controller = $paths[0];
		}
		$controller = $controller ? $controller : 'home';

		if ($controller != 'home' && $action != 'export')
		{
			if (Auth::guest())
			{
				if (Request::ajax())
				{
					Response::make('', 200, array('session_timeout' => 1))->send();
				}
				else
				{
					Redirect::to('/')->header('session_timeout', 1)->send();
				}
				exit;
			}

			$role = Auth::user()->role;
			$role_array = explode(',', $role);

//			$available_roles = array_merge(Consts::$submanager_role_texts, PermissionService::all());//Config::get('application.submanager_roles', Consts::$submanager_role_texts));
			$available_roles = PermissionService::all();

			foreach ($available_roles as $key => $roles)
			{
				if (in_array($key, $role_array))
				{
					if (is_array($roles))
					{
						$role .= ',' . implode(',', $roles[1]);
					}
				}
				else if (is_array($roles) && is_array($roles['list']))
				{
					foreach ($roles['list'] as $key2 => $roles2)
					{
						if (is_array($roles2) && in_array($key2, $role_array))
						{
							$role .= ',' . implode(',', $roles2[1]);
						}
					}
				}
			}

			if (strpos($role, '[meeting]') !== false)
			{
				$role .= ',[meetingenroll]';
			}
			if (strpos($role, '[knows]') !== false)
			{
				$role .= ',[answer]';
			}
			if (strpos($role, '[knowscategory]') !== false)
			{
				$role .= ',[knowssubcategory]';
			}
			if (strpos($role, '[documentcategory.modify]') !== false)
			{
				$role .= ',[documentsubcategory.modify]';
			}
			if (strpos($role, '[documentcategory]') !== false)
			{
				$role .= ',[documentsubcategory]';
			}
			if (strpos($role, '[key]') !== false)
			{
				$role .= ',[subkeyassign]';
			}
			if (strpos($role, '[autoassign]') !== false)
			{
				$role .= ',[autoassignuser]';
			}
			//			if (!is_null(Input::get('eid')) && $action == 'entity') // modify
			//			{
			//				//$controller .= '.modify';
			//			}
			$role .= ',[activationstatus]';
			if (strpos($role, '[' . $controller . ']') === false)
			{
				return App::abort(403, '权限不够');
				exit;
			}
			if ($action != 'index' && !Request::ajax())
			{
				exit;
			}
			if (Request::ajax())
			{
				$this->layout = null;
			}
		}
	}

	/**
	 * Setup the layout used by the controller.
	 *
	 * @return void
	 */
	protected function setupLayout()
	{
		if ( ! is_null($this->layout))
		{
			$this->layout = \View::make($this->layout);
			$this->layout->sitename = '管理中心 - ' . Consts::$app_name . '(' . ParamsService::get('customername') . ')';

		}
	}

}