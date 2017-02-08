<?php
namespace Manage;
use Request,
	Auth,
	App,
	Redirect,
	Response,
	Config,
	Ca\Service\ManagerService,
	Ca\Consts;
class BaseController extends \Controller {

	public function __construct()
	{
		$this->manager = Auth::user();
		$self = $this;
		$this->beforeFilter(function() use ($self) {
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

				$available_roles = array_merge(Config::get('app.adminer_roles', Consts::$adminer_role_texts));

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
					$self->layout = null;
				}
			}
		}, array());
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
			$this->layout->sitename = '正版软件管理与服务平台';
		}
	}

}