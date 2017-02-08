<?php
namespace Activate;
use view,
	Request,
	Response,
	Ca\Service\ProductService,
	Ca\Service\CurrentUserService;
class HomeController extends BaseController {

	public $layout = 'activate.layouts.common';
	public function index()
	{

		$user_id = CurrentUserService::$user_id;
		$opened = 0;
		$this->layout->nav = '激活管理';

		if ($opened)
		{
			$this->layout->title = '激活管理';
			$this->layout->content = View::make('activate.home.opened')
				->with('opened', $opened);
		}
		else
		{
			$products = ProductService::get_available_product($user_id);
			$this->layout->title = '激活管理';
			$this->layout->content = View::make('activate.home.index')
				->with('products', $products);
		}
	}

	public function products()
	{
		if (Request::ajax())
		{
			$user_id = CurrentUserService::$user_id;
			$products = ProductService::get_available_product($user_id);
			return Response::json($products);
		}
	}
}