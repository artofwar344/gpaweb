<?php
class Ad_Controller extends Base_Controller {
	public $layout = 'manage/layouts/common';

	public function action_index()
	{
		$this->layout->title = "广告管理";
		$this->layout->body = new View('ad/list');
	}

	public function action_list()
	{
		$name = InputExt::get('name');
		$page = InputExt::getInt('page');

		$query = DB::table('ad')
			->select(array('adid', 'name', 'image', 'link', 'blank', 'createdate'))
			->order_by('adid', 'desc');

		$count_query = DB::table('ad')->select(array(DB::raw('COUNT(*) as count')));

		$ret = Data::queryList($query, $count_query, $page, array(
			array('type' => 'string', 'field' => 'name', 'value' => $name),
		), array('blank' => array(Consts::$ad_targets)));

		echo json_encode($ret);
	}

	public function action_entity()
	{
		$eid = InputExt::getInt('eid');
		Common::empty_check(array('name', 'image', 'link', 'blank'));
		Data::updateEntity('ad', array('adid', '=', $eid), array('name', 'image', 'link', 'blank'));
	}

	public function action_get()
	{
		$eid = InputExt::getInt('eid');
		$entity = DB::table('ad')->select()->where('adid', '=', $eid)->first();
		echo json_encode($entity);
	}

	public function action_delete()
	{
		$eid = InputExt::getInt('eid');
		DB::table('ad')->where('adid', '=', $eid)->delete();
	}
}

