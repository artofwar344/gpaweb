<?php
namespace CustomerManage;

use DB,
	Input,
	InputExt,
	View,
	Ca\Consts,
	Ca\Data,
	Ca\Service\SoftService;

class SoftlogController extends BaseController {
	public $layout = 'customermanage/layouts/common';

	public function getIndex()
	{
		$this->layout->title = "软件记录";
		$soft_id = InputExt::getInt('id');
		$soft = null;
		if ($soft_id)
		{
			$soft = SoftService::soft_by_id($soft_id);
		}
		$this->layout->body = View::make('customermanage/soft/softlog')
			->with('soft_id', $soft_id)
			->with('soft', $soft);
	}

	public function postList()
	{
		$name = InputExt::get('name');
		$soft_id = InputExt::getInt('softid');
		$page = InputExt::getInt('page');

		$query = DB::table('softlog')
			->select(array('softlog.*', 'soft.name'))
			->orderBy('logid', 'desc')
			->leftJoin('soft', 'soft.softid', '=', 'softlog.softid');

		$count_query = DB::table('softlog')
			->select(array(DB::raw('COUNT(*) as count')))
			->leftJoin('soft', 'soft.softid', '=', 'softlog.softid');

		$ret = Data::queryList($query, $count_query, $page, array(
			array('type' => 'string', 'field' => 'soft.name', 'value' => $name),
			array('type' => 'int', 'field' => 'softlog.softid', 'value' => $soft_id)
		), array('type' => array(Consts::$softtype_type_texts, 'array')));

		echo json_encode($ret);
	}

}

