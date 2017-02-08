<?php
namespace User;

use \Request,
	\Auth,
	\Redirect,
	\Response,
	\Config,
	Ca\Service\ParamsService,
	Ca\Consts;

class BaseController extends \Controller {

	public function __construct()
	{
		$user = !Auth::guest() ? Auth::user() : null;
		$this->user = $user;
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
			$this->layout->sitename = '用户中心 - ' . Consts::$app_name . '(' . ParamsService::get('customername') . ')';
			$this->layout->user = $this->user;
		}
	}

}