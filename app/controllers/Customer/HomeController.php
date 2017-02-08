<?php
namespace Customer;
use View,
	App,
	Response,
	\Ca\Service\ParamsService;

class HomeController extends BaseController {
	public $layout = 'layouts.home';
	public function index()
	{
		switch (ParamsService::get('clientpublishversion'))
		{
			case 3:
				$clientversion = ParamsService::get('clientversion3');
				break;
			case 2:
			default:
				$clientversion = ParamsService::get('clientversion');
				break;
		}
		$blockdownloadproducts = json_decode(ParamsService::get('blockdownloadproducts'));
		if (!is_array($blockdownloadproducts))
		{
			$blockdownloadproducts = array();
		}

		$this->view('home.index')
			->with('clientversion', $clientversion)
			->with('blockdownloadproducts', $blockdownloadproducts);
	}

	public function down($customer, $filename)
	{
		switch (ParamsService::get('clientpublishversion'))
		{
			case 3:
				$path = realpath(base_path() . '/content/client/gp3/');
				$clientversion = ParamsService::get('clientversion3');
				break;
			case 2:
			default:
				$path = realpath(base_path() . '/content/client/gp/');
				$clientversion = ParamsService::get('clientversion');
				break;
		}
		if ('GP(' . $customer . ')-'. $clientversion. '.exe' == $filename)
		{
			$filepath = $path . '/' . $filename;
			if (dirname($filepath) == $path && file_exists($filepath))
			{
				return Response::download($filepath);
			}
		}
		return App::abort(404, $filename. ' Not Found!');
		exit;
	}
}