<?php
namespace CustomerManage;

use DB,
	View,
	Input,
	InputExt,
	Ca\Consts,
	Ca\Data,
	Ca\Common;

class AppController extends BaseController {
	public $layout = 'customermanage/layouts/common';

	public function getIndex()
	{
		$this->layout->title = "应用管理";
		$this->layout->body = View::make('customermanage/app/list');
	}

	public function postList()
	{
		$name = InputExt::get('name');
		$categoryid = InputExt::getInt('categoryid');
		$page = InputExt::getInt('page');

		$query = DB::table('app')
			->select(array('app.appid', 'app.name', 'app.guid', 'appcategory.name as category_name', 'app.type',
				'app.color', 'app.params', 'app.version', 'app.status', 'app.description', 'app.createdate'))
			->orderBy('appid', 'desc')
			->groupBy('app.appid')
			->leftJoin('appcategory', 'appcategory.categoryid', '=', 'app.categoryid');

		$count_query = DB::table('app')->select(array(DB::raw('COUNT(*) as count')));

		$ret = Data::queryList($query, $count_query, $page, array(
			array('type' => 'string', 'field' => 'app.name', 'value' => $name),
			array('type' => 'int', 'field' => 'app.categoryid', 'value' => $categoryid)
		), array('type' => array(Consts::$app_type_texts), 'status' => array(Consts::$app_status_texts)));

		echo json_encode($ret);
	}

	public function postEntity()
	{
		$eid = InputExt::getInt('eid');
		Common::empty_check(array('status'));
		Data::updateEntity('app', array('appid', '=', $eid), array('status'));
	}

	public function postGet()
	{
		$eid = InputExt::getInt('eid');
		$entity = DB::table('app')->select()->where('appid', '=', $eid)->first();
		echo json_encode($entity);
	}

	public function postDelete()
	{
		$eid = InputExt::getInt('eid');
		DB::table('app')->where('appid', '=', $eid)->delete();
	}

	public function postSelects()
	{
		$select = DB::table('appcategory')
			->select(array('categoryid', 'name'))
			->orderBy("categoryid", "desc")->get();
		echo json_encode(array($select));
	}

	public function postStatus()
	{
		$eid = InputExt::getInt('eid');
		DB::table('app')->where('appid', '=', $eid)->update(array('status' => DB::raw('abs(status - 2) + 1')));
	}
}

