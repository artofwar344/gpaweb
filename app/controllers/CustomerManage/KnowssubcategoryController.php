<?php
namespace CustomerManage;

use DB,
	View,
	Input,
	Response,
	InputExt,
	Ca\Data,
	Ca\Common,
	Ca\Service\KnowsService;

class KnowssubcategoryController extends BaseController {
	public $layout = 'customermanage/layouts/common';

	public function getIndex()
	{
		$categoryid = InputExt::getInt('id');
		$category = KnowsService::get_category($categoryid);
		$this->layout->title = "子级分类管理";
		$this->layout->body = View::make('customermanage/knows/subcategory')->with('category', $category)->with('categoryid', $categoryid);
	}

	public function postList()
	{
		$parentid = InputExt::getInt('id');
		$name = InputExt::get('name');
		$page = InputExt::getInt('page');

		$query = DB::table('questioncategory')
			->select(array(DB::raw('COUNT(DISTINCT(question.questionid)) as count'), 'questioncategory.categoryid', 'questioncategory.name'))
			->leftJoin('question', 'questioncategory.categoryid', '=', 'question.categoryid')
			->orderBy('questioncategory.categoryid', 'desc')
			->groupBy('questioncategory.categoryid');

		$count_query = DB::table('questioncategory')->select(array(DB::raw('COUNT(*) as count')));

		$ret = Data::queryList($query, $count_query, $page, array(
			array('type' => 'string', 'field' => 'name', 'value' => $name),
			array('type' => 'int', 'field' => 'questioncategory.parentid', 'value' => $parentid),
		), null, null, array(array('count', '==', '0')));

		echo json_encode($ret);
	}

	public function postGet()
	{
		$eid = InputExt::getInt("eid");
		$entity = DB::table('questioncategory')->where('categoryid', '=', $eid)->first();
		return Response::json($entity);
	}

	public function postEntity()
	{
		$eid = InputExt::getInt('eid');
		$fields = array('name', 'parentid');
		Common::empty_check(array('name', 'parentid'));
		Data::updateEntity('questioncategory', array('categoryid', '=', $eid), $fields);
	}

	public function postDelete()
	{
		$eid = InputExt::getInt("eid");
		DB::table('questioncategory')
			->where('categoryid', '=', $eid)
			->delete();
	}



}

