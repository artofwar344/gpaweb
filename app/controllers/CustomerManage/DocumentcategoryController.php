<?php
namespace CustomerManage;

use DB,
	View,
	Input,
	InputExt,
	Ca\Data;

class DocumentcategoryController extends BaseController {
	public $layout = 'customermanage/layouts/common';

	public function getIndex()
	{
		$this->layout->title = "文档分类";
		$this->layout->body = View::make('customermanage/document/category');
	}

	public function postList()
	{
		$name = InputExt::get('name');
		$page = InputExt::getInt('page');
		$query = DB::table('category')
			->select(array(DB::raw('COUNT(DISTINCT(document__category.documentid)) as count'),
				DB::raw('group_concat(DISTINCT(children.name) separator ", ") as children_name'),
				DB::raw('COUNT(DISTINCT(children.categoryid)) as children_count'),
				'category.categoryid', 'category.name'))
			->leftJoin('category as children', 'category.categoryid', '=', 'children.parentid')
			->leftJoin('document__category', 'document__category.categoryid', '=', 'children.categoryid')
			->whereNull('category.parentid')
			->orderBy('category.categoryid', 'desc')
			->groupBy('category.categoryid');

		$count_query = DB::table('category')
			->select(array(DB::raw('COUNT(*) as count')))
			->whereNull('category.parentid');

		$ret = Data::queryList($query, $count_query, $page, array(
			array('type' => 'string', 'field' => 'category.name', 'value' => $name)
		));

		echo json_encode($ret);
	}

}

