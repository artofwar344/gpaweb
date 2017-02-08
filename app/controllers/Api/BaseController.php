<?php
namespace Api;

use View,
	Input,
	App,
	Request,
	Response;

class BaseController extends \Controller {

	public function __contruct()
	{
		if (Request::getMethod() != 'POST')
		{
			return App::abort(403);
		}
		$skey = Input::get('skey');
		if ($skey != App::make('customer')->securekey)
		{
			print json_encode(array('status' => 0, 'message' => '拒绝访问'));exit;
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
			$this->layout = View::make($this->layout);
		}

	}
}