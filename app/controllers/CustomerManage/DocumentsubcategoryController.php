<?php
namespace CustomerManage;

use DB,
	View,
	Input,
	Response,
	InputExt,
	Ca\Data,
	Ca\Common,
	Ca\Service\DocumentService;

class DocumentsubcategoryController extends BaseController {
	public $layout = 'customermanage/layouts/common';

	public function getIndex()
	{
		$categoryid = InputExt::getInt('id');
		$category = DocumentService::get_category($categoryid);
		$this->layout->title = "子级分类管理";
		$this->layout->body = View::make('customermanage/document/subcategory')
			->with('category', $category)
			->with('categoryid', $categoryid);
	}

	public function postList()
	{
		$parentid = InputExt::getInt('id');
		$name = InputExt::get('name');
		$page = InputExt::getInt('page');

		$query = DB::table('category')
			->select(array(DB::raw('COUNT(document__category.documentid) as count'), 'category.categoryid', 'category.name'))
			->leftJoin('document__category', 'document__category.categoryid', '=', 'category.categoryid')
			->orderBy('category.categoryid', 'desc')
			->groupBy('category.categoryid');

		$count_query = DB::table('category')->select(array(DB::raw('COUNT(*) as count')));

		$ret = Data::queryList($query, $count_query, $page, array(
			array('type' => 'string', 'field' => 'name', 'value' => $name),
			array('type' => 'int', 'field' => 'category.parentid', 'value' => $parentid),
		), null, null, array(array('count', '==', '0')));

		echo json_encode($ret);
	}

	public function postGet()
	{
		$eid = InputExt::getInt("eid");
		$entity = DB::table('category')->where('categoryid', '=', $eid)->first();
		return Response::json($entity);
	}

	public function postEntity()
	{
		$eid = InputExt::getInt('eid');
		$fields = array('name', 'parentid');
		Common::empty_check(array('name', 'parentid'));
		Data::updateEntity('category', array('categoryid', '=', $eid), $fields);
	}

	public function postDelete()
	{
		$eid = InputExt::getInt("eid");
		DB::table('category')
			->where('categoryid', '=', $eid)
			->delete();
	}



}

