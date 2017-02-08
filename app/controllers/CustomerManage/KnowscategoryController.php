<?php
namespace CustomerManage;

use DB,
	Input,
	View,
	InputExt,
	Ca\Data,
	Ca\Common;

class KnowscategoryController extends BaseController {
	public $layout = 'customermanage/layouts/common';

	public function getIndex()
	{
		$this->layout->title = "问答分类";
		$this->layout->body = View::make('customermanage/knows/category');
	}

	public function postList()
	{
		$name = InputExt::get('name');
		$page = InputExt::getInt('page');

		$query = DB::table('questioncategory')
			->select(array(DB::raw('COUNT(DISTINCT(question.questionid)) as count'),
				DB::raw('group_concat(DISTINCT(children.name) separator ", ") as children_name'),
				DB::raw('COUNT(DISTINCT(children.categoryid)) as children_count'),
				'questioncategory.categoryid', 'questioncategory.name'))
			->leftJoin('questioncategory as children', 'questioncategory.categoryid', '=', 'children.parentid')
			->leftJoin('question', 'children.categoryid', '=', 'question.categoryid')
			->whereNull('questioncategory.parentid')
			->orderBy('questioncategory.categoryid', 'desc')
			->groupBy('questioncategory.categoryid');


		$count_query = DB::table('questioncategory')
			->select(array(DB::raw('COUNT(*) as count')))
			->where('questioncategory.parentid', 'IS', DB::raw('NULL'));

		$ret = Data::queryList($query, $count_query, $page, array(
			array('type' => 'string', 'field' => 'questioncategory.name', 'value' => $name)
		), null, null, array(array('children_count', '==', '0')));

		echo json_encode($ret);
	}

	public function postEntity()
	{
		$eid = InputExt::getInt('eid');
		$fields = array('name');
		Common::empty_check(array('name'));
		Data::updateEntity('questioncategory', array('categoryid', '=', $eid), $fields);
	}

	public function postGet()
	{
		$eid = InputExt::getInt("eid");
		$entity = DB::table('questioncategory')
			->select(array('name', 'parentid'))
			->where('categoryid', '=', $eid)->first();
		echo json_encode($entity);
	}

	public function postDelete()
	{
		$eid = InputExt::getInt("eid");
		DB::table('questioncategory')->where('categoryid', '=', $eid)->delete();
	}


}

