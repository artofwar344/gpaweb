<?php
namespace Share;
use View,
	Auth,
	Ca\Consts,
	Ca\Service\ParamsService;

class BaseController extends \Controller {
	public $user = null;
	public function __construct()
	{
	}

	protected function setupLayout()
	{
		if ( ! is_null($this->layout))
		{
			$user = !Auth::guest() ? Auth::user() : null;
			$this->layout = View::make($this->layout);
			$this->layout->sitename = 'GP资源共享 - ' . Consts::$app_name . '(' . ParamsService::get('customername') . ')';
			$this->layout->user = $user;
			$this->user = $user;

		}
	}


	/**
	 * Setup the layout used by the controller.
	 *
	 * @return void
	 */


}