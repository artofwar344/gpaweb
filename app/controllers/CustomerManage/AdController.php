<?php
namespace CustomerManage;

use DB,
	View,
	Input,
	InputExt,
	Ca\Data,
	Ca\Consts,
	Ca\Common;

class AdController extends BaseController {
	public $layout = 'customermanage/layouts/common';

	public function getIndex()
	{
		$this->layout->title = "广告管理";
		$this->layout->body = View::make('customermanage/ad/list');
	}

	public function postList()
	{
		$name = InputExt::get('name');
		$page = InputExt::getInt('page');

		$query = DB::table('ad')
			->select(array('adid', 'module', 'name', 'image', 'link', 'target', 'status', 'createdate'))
			->orderBy('adid', 'desc');

		$count_query = DB::table('ad')->select(array(DB::raw('COUNT(*) as count')));

		$ret = Data::queryList($query, $count_query, $page, array(
			array('type' => 'string', 'field' => 'name', 'value' => $name),
		), array('target' => array(Consts::$anchor_target), 'module' => array(Consts::$module_texts), 'status' => array(Consts::$ad_status_text)));

		echo json_encode($ret);
	}

	public function postEntity()
	{
		$eid = InputExt::getInt('eid');
		Common::empty_check(array('image', 'link', 'target', 'status'));
		Data::updateEntity('ad', array('adid', '=', $eid), array('image', 'link', 'target', 'status'));
	}

	public function postGet()
	{
		$eid = InputExt::getInt('eid');
		$entity = DB::table('ad')->select()->where('adid', '=', $eid)->first();
		$entity->_disable_fields = array('name', 'module');
		echo json_encode($entity);
	}

	public function postDelete()
	{
		$eid = InputExt::getInt('eid');
		DB::table('ad')->where('adid', '=', $eid)->delete();
	}

	public function postStatus()
	{
		$eid = InputExt::getInt('eid');
		DB::table('ad')->where('adid', '=', $eid)->update(array('status' => DB::raw('abs(status - 2) + 1')));
	}
}

