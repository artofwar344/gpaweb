<?php
namespace CustomerManage;

use DB,
	View,
	Input,
	InputExt,
	Ca\Data,
	Ca\Common;

class FaqcategoryController extends BaseController {
	public $layout = 'customermanage/layouts/common';

	public function getIndex()
	{
		$this->layout->title = "FAQ分类";
		$this->layout->body = View::make('customermanage/faq/category');
	}

	public function postList()
	{
		$name = InputExt::get('name');
		$page = InputExt::getInt('page');

		$query = DB::table('faqcategory')
			->select(array(DB::raw('COUNT(faq.faqid) as count'), 'faqcategory.categoryid', 'faqcategory.name'))
			->leftJoin('faq', 'faqcategory.categoryid', '=', 'faq.categoryid')
			->orderBy('categoryid', 'desc')
			->groupBy('faqcategory.categoryid');

		$count_query = DB::table('faqcategory')->select(array(DB::raw('COUNT(*) as count')));

		$ret = Data::queryList($query, $count_query, $page, array(
			array('type' => 'string', 'field' => 'faqcategory.name', 'value' => $name)
		), null, null, array(array('count', '==', '0')));

		echo json_encode($ret);
	}

	public function postEntity()
	{
		$eid = InputExt::getInt('eid');
		Common::empty_check(array('name'));
		Data::updateEntity('faqcategory', array('categoryid', '=', $eid), array('name'));
	}

	public function postGet()
	{
		$eid = InputExt::getInt("eid");
		$entity = DB::table('faqcategory')
			->select(array('name'))
			->where('categoryid', '=', $eid)->first();
		echo json_encode($entity);
	}

	public function postDelete()
	{
		$eid = InputExt::getInt("eid");
		DB::table('faqcategory')->where('categoryid', '=', $eid)->delete();
	}
}

