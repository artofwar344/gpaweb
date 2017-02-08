<?php

class Appcategory_Controller extends Base_Controller {
	public $layout = 'manage/layouts/common';

	public function action_index()
	{
		$this->layout->title = "应用分类";
		$this->layout->body = new View('app/category');
	}

	public function action_list()
	{
		$name = InputExt::get('name');
		$page = InputExt::getInt('page');

		$query = DB::table('appcategory')
			->select(array(DB::raw('COUNT(app.appid) as count'), 'appcategory.categoryid', 'appcategory.name'))
			->order_by('categoryid', 'desc')
			->group_by('appcategory.categoryid')
			->left_join('app', 'appcategory.categoryid', '=', 'app.categoryid');

		$count_query = DB::table('appcategory')->select(array(DB::raw('COUNT(*) as count')));

		$ret = Data::queryList($query, $count_query, $page, array(
			array('type' => 'string', 'field' => 'appcategory.name', 'value' => $name)
		));

		echo json_encode($ret);
	}

	public function action_entity()
	{
		$eid = InputExt::getInt('eid');
		Common::empty_check(array('name'));
		Data::updateEntity('appcategory', array('categoryid', '=', $eid), array('name'));
	}

	public function action_get()
	{
		$eid = InputExt::getInt("eid");
		$entity = DB::table('appcategory')
			->select(array('name'))
			->where('categoryid', '=', $eid)->first();
		echo json_encode($entity);
	}

	public function action_delete()
	{
		$eid = InputExt::getInt("eid");
		DB::table('appcategory')->where('categoryid', '=', $eid)->delete();
	}
}

