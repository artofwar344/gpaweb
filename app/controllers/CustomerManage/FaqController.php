<?php
namespace CustomerManage;

use DB,
	View,
	Input,
	InputExt,
	Ca\Data,
	Ca\Common;

class FaqController extends BaseController {
	public $layout = 'customermanage/layouts/common';

	public function getIndex()
	{
		$this->layout->title = 'FAQ列表';
		$category_id = InputExt::getInt('id');
		$category = null;
		if ($category_id)
		{
			$category = DB::table('faqcategory')
				->select(array('faqcategory.name'))
				->where('categoryid', '=', $category_id)->first();
		}
		$this->layout->body = View::make('customermanage/faq/list')
			->with('category_id', $category_id)
			->with('category', $category);
	}

	public function postList()
	{
		$title = InputExt::get('title');
		$page = InputExt::getInt('page');
		$categoryid = InputExt::getInt('categoryid');

		$query = DB::table('faq')
			->select(array('faq.*', 'faqcategory.name as category_name'))
			->left_join('faqcategory', 'faq.categoryid', '=', 'faqcategory.categoryid')
			->order_by('faqid', 'desc');
		$count_query = DB::table('faq')->select(array(DB::raw('COUNT(faqid) as count')));
		$ret = Data::queryList($query, $count_query, $page,
			array(
				array('type' => 'string', 'field' => 'title', 'value' => $title),
				array('type' => 'int', 'field' => 'faq.categoryid', 'value' => $categoryid),
			));
		echo json_encode($ret);
	}

	public function postEntity()
	{
		$eid = InputExt::getInt('eid');
		$fields = array('title', 'categoryid', 'content');

		Common::empty_check(array('title', 'categoryid', 'content'));
		Data::updateEntity('faq', array('faqid', '=', $eid), $fields, null, null);
	}

	public function postGet()
	{
		$eid = InputExt::getInt('eid');
		$entity = DB::table('faq')
			->where('faqid', '=', $eid)
			->first();
		echo json_encode($entity);
	}

	public function postDelete()
	{
		$eid = InputExt::getInt('eid');
		DB::table('faq')->where('faqid', '=', $eid)->delete();
	}

	public function postSelects()
	{
		$select_1 = DB::table('faqcategory')
			->select(array('categoryid', 'name'))
			->order_by("categoryid", "desc")
			->get();
		echo json_encode(array($select_1));
	}

}