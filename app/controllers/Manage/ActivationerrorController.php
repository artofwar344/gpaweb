<?php
namespace Manage;

use DB,
	View,
	InputExt,
	Ca\Data,
	Ca\Common;

class ActivationerrorController extends BaseController {
	public $layout = 'manage/layouts/common';

	public function getIndex()
	{
		$this->layout->title = "激活错误代码";
		$this->layout->body = View::make('manage/activationerror/list');
	}

	public function postList()
	{
		$name = InputExt::get('name');
		$page = InputExt::getInt('page');
		$message = InputExt::get('message');

		$query = DB::table('activationerror')
			->orderBy('errorid', 'desc');

		$count_query = DB::table('activationerror')->select(array(DB::raw('COUNT(errorid) as count')));

		$ret = Data::queryList($query, $count_query, $page, array(
			array('type' => 'string', 'field' => 'name', 'value' => $name),
			array('type' => 'string', 'field' => 'message', 'value' => $message),
		));

		echo json_encode($ret);
	}

	public function postEntity()
	{
		$eid = InputExt::getInt('eid');

		$fields = array('code', 'message', 'solution');

		Common::empty_check(array('code', 'message', 'solution'));
		Data::updateEntity('activationerror', array('errorid', '=', $eid), $fields, null, null);

	}

	public function postGet()
	{
		$eid = InputExt::getInt('eid');
		$entity = DB::table('activationerror')
			->where('errorid', '=', $eid)
			->first();
		echo json_encode($entity);
	}

	public function postDelete()
	{
		$eid = InputExt::getInt('eid');
		DB::table('activationerror')->where('errorid', '=', $eid)->delete();
	}
} 