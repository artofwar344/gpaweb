<?php
namespace Customer;
use View,
	App,
	Ca\Consts,
	Ca\Service\ParamsService;

class BaseController extends \Controller {

	public function __construct()
	{
//		\Session::save();
//		unset($_SESSION);
//		$session = \Session::setId('6tfbarqp0f0il1os4agrpab0l7');
//		echo \Session::getId();

		$this->customer_alias = App::make('customer')->alias;
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
			if (View::exists('customer.' . $this->customer_alias . '.' . $this->layout))
			{
				$this->layout = 'customer.' . $this->customer_alias . '.' . $this->layout;
			}
			else
			{
				$this->layout = 'customer.common.' . $this->layout;
			}
			$this->layout = View::make($this->layout);
			$this->layout->sitename = Consts::$app_name . '(' . ParamsService::get('customername') . ')';
		}

	}

	public function view($viewName)
	{
		$viewFile = 'customer.' . $this->customer_alias . '.' . $viewName;
		if (View::exists($viewFile))
		{
			$view = View::make($viewFile);
		}
		else
		{
			$viewFile = 'customer.common.' . $viewName;
			if (!View::exists($viewFile))
			{
				App::abort(404);
			}
			$view = View::make('customer.common.' . $viewName);
		}
		$this->layout->content = $view;
		return $view;
	}
}