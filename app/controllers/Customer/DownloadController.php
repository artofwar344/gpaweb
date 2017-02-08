<?php
namespace Customer;
use Redirect,
	Request,
	Input,
	Auth,
	View,
	Config,
	App,
	Ca\Common,
	Ca\Service\ParamsService;

class DownloadController extends BaseController
{
	public $layout = 'layouts.page';
	public function index()
	{
		$blockdownloadproducts = json_decode(ParamsService::get('blockdownloadproducts'));
		if (!is_array($blockdownloadproducts))
		{
			$blockdownloadproducts = array();
		}
		$view_file = $this->customer_alias . '.download/index';
		if (View::exists($view_file))
		{
			$view = View::make($view_file);
		}
		else
		{
			$view = View::make('customer.common.download.index');
		}

		$this->layout->content = $view;
		$this->layout->content->with('blockdownloadproducts', $blockdownloadproducts);
	}

	public function detail($alias, $name)
	{
		
		if(in_array($alias, array('jzhj', 'wku','cdsu','dlmu')))
			$products = Config::get('products_'.$alias);
		else
			$products = Config::get('products');	
		$blockdownloadproducts = json_decode(ParamsService::get('blockdownloadproducts'));
		if (!is_array($blockdownloadproducts))
		{
			$blockdownloadproducts = array();
		}

		if (array_key_exists($name, $products) && !in_array($name, $blockdownloadproducts))
		{
			$view_file = $this->customer_alias . '.download.detail';
			if (View::exists($view_file))
			{
				$view = View::make($view_file);
			}
			else
			{
				$view = View::make('customer.common.download.detail');
			}

			$this->layout->content = $view;
			$this->layout->content
				->with('productName', $name)
				->with('blockdownloadproducts', $blockdownloadproducts)
				->with('product', $products[$name]);
			return;
		}

		return App::abort(404, 'Page not found');
	}

	public function file()
	{
		if (ParamsService::get('login2downloadproduct') == 1 && Auth::guest())
		{
			return Redirect::to('/');
		}
		if (Request::getMethod() == 'POST')
		{
			$products = Config::get('products');
			$name = Input::get('name');
			$bit = Input::get('bit');
			if (array_key_exists($name, $products))
			{
				$links = $products[$name]['links'];
				if (array_key_exists($bit, $links))
				{
					return Redirect::to($links[$bit]);
				}
			}
		}
		return Redirect::to('/');
	}

}