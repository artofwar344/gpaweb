<?php
namespace CustomerManage;

use DB,
	View,
	InputExt,
	Input,
	Response,
	Ca\Common,
	Ca\Data;

class ArticlecategoryController extends BaseController {
	public $layout = 'customermanage/layouts/common';

	public function getIndex()
	{
		$this->layout->title = "文章分类";
		$this->layout->body = View::make('customermanage/article/category');
	}

	public function postList()
	{
		$name = InputExt::get('name');
		$page = InputExt::getInt('page');

		$query = DB::table('articlecategory')
			->select(array(DB::raw('COUNT(article.articleid) as count'), 'articlecategory.categoryid', 'articlecategory.name'))
			->orderBy('categoryid', 'desc')
			->groupBy('articlecategory.categoryid')
			->leftJoin('article', 'articlecategory.categoryid', '=', 'article.categoryid');

		$count_query = DB::table('articlecategory')->select(array(DB::raw('COUNT(*) as count')));

		$ret = Data::queryList($query, $count_query, $page, array(
			array('type' => 'string', 'field' => 'articlecategory.name', 'value' => $name)
		), null, null, array(array('count', '==', '0')));

		echo json_encode($ret);
	}

	public function postEntity()
	{
		$eid = InputExt::getInt('eid');
		Common::empty_check(array('name'));
		Data::updateEntity('articlecategory', array('categoryid', '=', $eid), array('name'));
	}

	public function postGet()
	{
		$eid = InputExt::getInt("eid");
		$entity = DB::table('articlecategory')
			->select(array('name'))
			->where('categoryid', '=', $eid)->first();
		echo json_encode($entity);
	}

	public function postDelete()
	{
		$eid = InputExt::getInt("eid");
		DB::table('articlecategory')->where('categoryid', '=', $eid)->delete();
	}
}

