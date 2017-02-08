<?php
class App_Controller extends Base_Controller {
	public $layout = 'manage/layouts/common';

	public function action_index()
	{
		$this->layout->title = "应用管理";
		$this->layout->body = new View('app/list');
	}

	public function action_list()
	{
		$name = InputExt::get('name');
		$categoryid = InputExt::getInt('categoryid');
		$page = InputExt::getInt('page');

		$query = DB::table('app')
			->select(array('app.appid', 'app.name', 'app.guid', 'appcategory.name as category_name', 'app.type',
				'app.color', 'app.params', 'app.version', 'app.description', 'app.createdate'))
			->order_by('appid', 'desc')
			->group_by('app.appid')
			->left_join('appcategory', 'appcategory.categoryid', '=', 'app.categoryid');

		$count_query = DB::table('app')->select(array(DB::raw('COUNT(*) as count')));

		$ret = Data::queryList($query, $count_query, $page, array(
			array('type' => 'string', 'field' => 'app.name', 'value' => $name),
			array('type' => 'int', 'field' => 'app.categoryid', 'value' => $categoryid)
		), array('type' => array(Consts::$app_type_texts), 'color' => function($color) { return '<div style="width:16px;height:16px;background:#' . $color . '">&nbsp;</div>';}));

		echo json_encode($ret);
	}

	public function action_entity()
	{
		$eid = InputExt::getInt('eid');
		Common::empty_check(array('name', 'guid', 'color', 'params', 'version'));
		Data::updateEntity('app', array('appid', '=', $eid), array('name', 'guid', 'categoryid', 'type', 'color', 'params', 'version', 'description'));
	}

	public function action_get()
	{
		$eid = InputExt::getInt('eid');
		$entity = DB::table('app')->select()->where('appid', '=', $eid)->first();
		echo json_encode($entity);
	}

	public function action_delete()
	{
		$eid = InputExt::getInt('eid');
		DB::table('app')->where('appid', '=', $eid)->delete();
	}

	public function action_selects()
	{
		$select = DB::table('appcategory')
			->select(array('categoryid', 'name'))
			->order_by("categoryid", "desc")->get();
		echo json_encode(array($select));
	}
}

