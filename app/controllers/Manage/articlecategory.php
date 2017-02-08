<?php

class Articlecategory_Controller extends Base_Controller {
	public $layout = 'manage/layouts/common';

	public function action_index()
	{
		$this->layout->title = "文章分类";
		$this->layout->body = new View('article/category');
	}

	public function action_list()
	{
		$name = InputExt::get('name');
		$page = InputExt::getInt('page');

		$query = DB::table('articlecategory')
			->select(array(DB::raw('COUNT(article.articleid) as count'), 'articlecategory.categoryid', 'articlecategory.name'))
			->order_by('categoryid', 'desc')
			->group_by('articlecategory.categoryid')
			->left_join('article', 'articlecategory.categoryid', '=', 'article.categoryid');

		$count_query = DB::table('articlecategory')->select(array(DB::raw('COUNT(*) as count')));

		$ret = Data::queryList($query, $count_query, $page, array(
			array('type' => 'string', 'field' => 'articlecategory.name', 'value' => $name)
		), null, null, array(array('count', '==', '0')));

		echo json_encode($ret);
	}

	public function action_entity()
	{
		$eid = InputExt::getInt('eid');
		Common::empty_check(array('name'));
		Data::updateEntity('articlecategory', array('categoryid', '=', $eid), array('name'));
	}

	public function action_get()
	{
		$eid = InputExt::getInt("eid");
		$entity = DB::table('articlecategory')
			->select(array('name'))
			->where('categoryid', '=', $eid)->first();
		echo json_encode($entity);
	}

	public function action_delete()
	{
		$eid = InputExt::getInt("eid");
		DB::table('articlecategory')->where('categoryid', '=', $eid)->delete();
	}
}

