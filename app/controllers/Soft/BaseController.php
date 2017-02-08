<?php
namespace Soft;
use View,
	\Auth,
	Ca\Consts,
	Ca\Service\ParamsService;

class BaseController extends \Controller {

	protected function setupLayout()
	{
		if ( ! is_null($this->layout))
		{
			$this->layout = \View::make($this->layout);
			$this->layout->sitename = '软件中心 - ' . Consts::$app_name . '(' . ParamsService::get('customername') . ')';
			$this->user = Auth::user();
		}

	}
}