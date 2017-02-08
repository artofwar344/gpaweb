<?php
namespace Activate;
use View,
	Auth,
	Ca\Consts,
	Ca\Service\ParamsService;

class BaseController extends \Controller {

	/**
	 * Setup the layout used by the controller.
	 *
	 * @return void
	 */
	protected function setupLayout()
	{
		if ( ! is_null($this->layout))
		{
			$user = !Auth::guest() ? Auth::user() : null;
			$this->layout = View::make($this->layout);
			$this->layout->sitename = 'GP应用激活 - ' . Consts::$app_name . '(' . ParamsService::get('customername') . ')';
			$this->layout->user = $user;
			$this->user = $user;

		}
	}
}