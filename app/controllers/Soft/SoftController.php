<?php
namespace Soft;
use View,
	Config,
	Response,
	Request,
	App,
	Ca\Consts,
	Ca\Service\SoftService;
class SoftController extends BaseController {
	public $layout = 'soft.layouts.common';
	/**
	 * 最新软件
	 */
	public function lastest()
	{
		$softs = SoftService::lastest(10, null, null, true);
		$this->layout->title = "最近更新软件";
		$this->layout->body = View::make('soft.soft.list')->with('softs', $softs);
	}

	public function category($customer, $categoryid)
	{
		$category = SoftService::category_by_id($categoryid);
		if (is_null($category))
		{
			return Response::error(404);
		}
		$softs = SoftService::lastest(10, $categoryid, null, true);
		$this->layout->title = $category->name . ' - ' . Consts::$soft_top_categories[$category->parentid];
		$this->layout->body = View::make('soft.soft.category')->with('softs', $softs)->with('category', $category);
	}

	public function topCategory($customer, $categoryid)
	{
		$categories = SoftService::categories_by_parentid($categoryid);
		if (count($categories) > 0)
		{
			$category_parent_name = Consts::$soft_top_categories[$categories[0]->parentid];
		}
		else
		{
			$category_parent_name = Consts::$soft_top_categories[$categoryid];
		}
		$ret = array();
		foreach ($categories as $category)
		{
			$ret[] = array(
				'category_name' => $category->name,
				'categoryid' => $category->categoryid,
				'results' => SoftService::lastest(5, $category->categoryid)
			);
		}
		$this->layout->title = Consts::$soft_top_categories[$categoryid];
		$this->layout->body = View::make('soft.soft.topcategory')->with('softcategories', $ret)->with('category_parent_name', $category_parent_name);
	}

	public function detail($customer, $softId)
	{
		$soft = SoftService::soft_by_id($softId);
		$recommend = SoftService::softRecommend(5, $soft->parentid);
		foreach ($recommend as $key => $_soft)
		{
			if ($softId == $_soft->softid)
			{
				unset($recommend[$key]);
			}
		}
		$recommend = array_slice($recommend, 0, 4);
		$this->layout->title = $soft->name;
		$this->layout->body = View::make('soft.soft.detail')->with('soft', $soft)->with('recommend', $recommend);
	}

	public function download($customer, $softid)
	{
		if (stripos(parse_url(\URL::previous(), PHP_URL_HOST), app()->environment()) == false)
		{
			App::abort(403);
		}
		SoftService::log($softid, \Ca\SoftLogType::download);
		$soft = SoftService::soft_by_id($softid);
		if (empty($soft->fileurl))
		{
			App::abort(404);
		}
		//Response::download(Config::get('app.download_host') . $soft->fileurl);

		//调用外部链接
		return \Redirect::to($soft->fileurl);exit;
	}
}