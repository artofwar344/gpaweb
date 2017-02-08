<?php
namespace Manage;

use \DB,
	\View,
	\Input,
	\Response,
	Ca\Consts,
	Ca\Service,
	Ca\Data,
	Ca\SoftVersionStatus,
	\InputExt;

class SoftversionController extends BaseController {

	public $layout = 'manage/layouts/common';

	public function getIndex()
	{
		$this->layout->title = "软件版本审核管理";
		$this->layout->body = View::make('manage/softversion/list');
	}

	public function postList()
	{
		$page = InputExt::getInt('page');
		$name = InputExt::get('name');

		$query = DB::table('softversion')
			->select(array('versionid', 'soft.name', 'softversion.softid', 'soft.version', 'softversion.version as verify_version', 'softversion.filesize',
				'softversion.brief', 'soft.brief as softbrief', 'softversion.feature', 'softversion.status', 'softversion.createdate'))
			->orderBy('softversion.versionid', 'desc')
			->leftJoin('soft', 'soft.softid', '=', 'softversion.softid');

		$count_query = DB::table('softversion')->select(array(DB::raw('COUNT(*) as count')))
			->leftJoin('soft', 'soft.softid', '=', 'softversion.softid');

		$ret = Data::queryList($query, $count_query, $page, array(
			array('type' => 'string', 'field' => 'soft.name', 'value' => $name),
			array('type' => 'int', 'field' => 'softversion.status', 'value' => SoftVersionStatus::pending),
			array('type' => 'null', 'field' => 'softversion.filesize', 'operator' => 'NOT'),
		), array('status' => array(Consts::$version_status_texts), 'filesize' => '\Ca\Common::format_filesize'));

		echo json_encode($ret);
	}

	public function postAuth()
	{
		$eid = InputExt::getInt('eid');
		DB::table('softversion')->where('versionid', '=', $eid)->where('status', '=', SoftVersionStatus::pending)
			->update(array('status' => SoftVersionStatus::agree));
		$version = DB::table('softversion')->where('versionid', '=', $eid)->first();
		$values = array(
			'version' => $version->version,
			'fileurl' => $version->group . "/" . $version->filename,
			'filesize' => $version->filesize,
			'updatedate' => $version->createdate,
		);
		if (!empty($version->brief))
		{
			$values['brief'] = $version->brief;
		}
		DB::table('soft')
			->where('softid', '=', $version->softid)
			->update($values);
		return Response::json(array('code' => 1));
	}

	public function postAuthmulti()
	{
		$eids = InputExt::get('eids');
		if (is_array($eids) && !empty($eids))
		{
			DB::table('softversion')->whereIn('versionid', $eids)->where('status', '=', SoftVersionStatus::pending)
				->update(array('status' => SoftVersionStatus::agree));
			DB::transaction(function() use ($eids) {
				foreach ($eids as $eid)
				{
					$version = DB::table('softversion')->where('versionid', '=', $eid)->first();
					$values = array(
						'version' => $version->version,
						'fileurl' => $version->group . "/" . $version->filename,
						'filesize' => $version->filesize,
						'updatedate' => $version->createdate,
					);
					if (!empty($version->brief))
					{
						$values['brief'] = $version->brief;
					}
					DB::table('soft')
						->where('softid', '=', $version->softid)
						->update($values);
				}
			});
			return Response::json(array('code' => 1));
		}
	}

	public function postDisagree()
	{
		$eid = InputExt::getInt('eid');
		DB::table('softversion')->where('versionid', '=', $eid)->where('status', '=', SoftVersionStatus::pending)
			->update(array('status' => SoftVersionStatus::disagree));
		return Response::json(array('code' => 1));
	}

	public function postDisagreemulti()
	{
		$eids = InputExt::get('eids');
		if (is_array($eids) && !empty($eids))
		{
			DB::table('softversion')->whereIn('versionid', $eids)->where('status', '=', SoftVersionStatus::pending)
				->update(array('status' => SoftVersionStatus::disagree));
			return Response::json(array('code' => 1));
		}
	}
}