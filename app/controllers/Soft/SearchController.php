<?php
namespace Soft;
use \Input,
	\Request,
	\Redirect,
	\view,
	Ca\Service\SoftService;
class SearchController extends BaseController {
	public $layout = 'soft.layouts.common';

	public function index()
	{
		$kerword = Input::get('kw');
		if (Request::getMethod() == 'POST')
		{
			return Redirect::to('/search?kw=' . $kerword);
		}
		$softs = SoftService::search($kerword);
		$this->layout->title = "搜索 " . $kerword;
		$this->layout->body = View::make('soft.search.index')->with('softs', $softs);
	}

}