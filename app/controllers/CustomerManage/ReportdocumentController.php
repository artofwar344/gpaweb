<?php
namespace CustomerManage;

use DB,
	View,
	Input,
	Response,
	InputExt,
	Ca\ReportType,
	Ca\ReportStatus,
	Ca\DocumentStatus,
	Ca\Consts,
	Ca\Data;

class ReportdocumentController extends BaseController {
	public $layout = 'customermanage/layouts/common';

	public function getIndex()
	{
		$this->layout->title = "举报记录";
		$this->layout->body = View::make('customermanage/report/document');
	}

	public function postList()
	{
		$type = InputExt::getInt('type');
		$status = InputExt::getInt('status');
		$page = InputExt::getInt('page');

		$query = DB::table('report')
			->select(array('report.*', 'document.name as report_content', 'user.name as reporter_name'))
			->leftJoin('user', 'user.userid', '=', 'report.reporterid')
			->join('document', 'document.documentid', '=', 'report.targetid')
			->where('report.type', '=', ReportType::document)
			->orderBy('report.status')
			->orderBy('report.createdate', 'desc');

		$count_query = DB::table('report')
			->join('document', 'document.documentid', '=', 'report.targetid')
			->where('report.type', '=', ReportType::document)
			->select(array(DB::raw('COUNT(*) as count')));

		$ret = Data::queryList($query, $count_query, $page, array(
			array('type' => 'int', 'field' => 'report.type', 'value' => $type),
			array('type' => 'int', 'field' => 'report.status', 'value' => $status),
		), array(
			'type' => array(Consts::$report_type_texts),
			'status' => array(Consts::$report_status_texts),
			'reason' => array(Consts::$report_document_reason_texts)
		));

		echo json_encode($ret);
	}

	public function postEntity()
	{
		$eid = InputExt::getInt("eid");
		Data::updateEntity('report', array('reportid', '=', $eid), array('status'));
	}

	public function postGet()
	{
		$eid = InputExt::getInt("eid");
		$entity = DB::table('report')
			->select('status')
			->where('reportid', '=', $eid)->first();
		return Response::json($entity);
	}

	public function postGettarget()
	{
		$eid = InputExt::getInt("eid");
		$targetid = DB::table('report')->where('reportid', '=', $eid)->pluck('targetid');
		return Response::json($targetid);
	}

	public function postDisabletarget()
	{
		$eid = InputExt::getInt("eid");
		$targetid = DB::table('report')
			->where('reportid', '=', $eid)
			->where('status', '=', ReportStatus::pending)
			->pluck('targetid');
		if ($targetid == null)
		{
			return Response::json(array('status' => 0));
		}
		DB::table('document')->where('documentid', '=', $targetid)->update(array('status' => DocumentStatus::deleted));
		DB::table('report')
			->where('reportid', '=', $eid)
			->where('status', '=', ReportStatus::pending)
			->update(array('status'=> ReportStatus::disabled));
		return Response::json(array('status' => 1));
	}

	public function postRejectreport()
	{
		$eid = InputExt::getInt("eid");
		DB::table('report')
			->where('reportid', '=', $eid)
			->where('status', '=', ReportStatus::pending)
			->update(array('status'=> ReportStatus::reject));
		return Response::json(array('status' => 1));
	}

	public function postRejectreportmulti()
	{
		$eids = InputExt::get('eids');
		if (is_array($eids) && !empty($eids))
		{
			DB::table('report')
				->whereIn('reportid', $eids)
				->where('status', '=', ReportStatus::pending)
				->update(array('status' => ReportStatus::reject));
		}
		return Response::json(array('status' => 1));
	}

	public function postDisabletargetmulti()
	{
		$eids = InputExt::get('eids');
		if (is_array($eids) && !empty($eids))
		{
			DB::table('document')
				->whereIn('documentid', function($query) use ($eids) {
					$query->select('report.targetid')
						->from('report')
						->whereIn('reportid', $eids)
						->where('status', '=', ReportStatus::pending);
				})
				->update(array('status' => DocumentStatus::deleted));
			DB::table('report')
				->whereIn('reportid', $eids)
				->where('status', '=', ReportStatus::pending)
				->update(array('status' => ReportStatus::disabled));
		}
		return Response::json(array('status' => 1));
	}

}