<?php
namespace Manage;

use \DB,
	\View,
	\Response,
	Ca\Data,
	Ca\Consts,
	Ca\SoftVersionStatus,
	\InputExt;

class SoftversionhistoryController extends BaseController {

	public $layout = 'manage/layouts/common';

	public function getIndex()
	{
		$this->layout->title = "软件版本审核历史";
		$this->layout->body = View::make('manage/softversionhistory/list');
	}

	public function postList()
	{
		$page = InputExt::getInt('page');
		$name = InputExt::get('name');
		$status = InputExt::getInt('status');

		$query = DB::table('softversion')
			->select(array('versionid', 'soft.name', 'softversion.softid', 'soft.version', 'softversion.version as verify_version', 'softversion.filesize',
				'softversion.brief', 'soft.brief as softbrief', 'softversion.feature', 'softversion.status', 'softversion.createdate'))
			->orderBy('softversion.versionid', 'desc')
			->leftJoin('soft', 'soft.softid', '=', 'softversion.softid');

		$count_query = DB::table('softversion')->select(array(DB::raw('COUNT(*) as count')))
			->leftJoin('soft', 'soft.softid', '=', 'softversion.softid');

		$ret = Data::queryList($query, $count_query, $page, array(
			array('type' => 'string', 'field' => 'soft.name', 'value' => $name),
			array('type' => 'int', 'field' => 'softversion.status', 'value' => $status),
			array('type' => 'int', 'field' => 'softversion.status', 'operator'=> '!=', 'value' => SoftVersionStatus::pending),
			array('type' => 'null', 'field' => 'softversion.filesize', 'operator' => 'NOT'),
		), array('status' => array(Consts::$version_status_texts), 'filesize' => '\Ca\Common::format_filesize'));

		echo json_encode($ret);
	}

	public function postRecover()
	{
		$eid = InputExt::getInt('eid');
		DB::table('softversion')
			->where('versionid', '=', $eid)
			->where('status', '=', SoftVersionStatus::disagree)
			->update(array('status' => SoftVersionStatus::pending));
		return Response::json(array('code' => 1));
	}


}