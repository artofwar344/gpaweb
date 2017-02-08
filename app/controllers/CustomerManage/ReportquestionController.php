<?php
namespace CustomerManage;

use DB,
	Input,
	View,
	InputExt,
	Response,
	Ca\ReportType,
	Ca\QuestionStatus,
	Ca\ReportStatus,
	Ca\Consts,
	Ca\Data;

class ReportquestionController extends BaseController {
	public $layout = 'customermanage/layouts/common';

	public function getIndex()
	{
		$this->layout->title = "举报记录";
		$this->layout->body = View::make('customermanage/report/question');
	}

	public function postList()
	{
		$status = InputExt::getInt('status');
		$page = InputExt::getInt('page');

		$query = DB::table('report')
			->select(array('report.*', 'question.title as report_content', 'user.name as reporter_name'))
			->leftJoin('user', 'user.userid', '=', 'report.reporterid')
			->join('question', 'question.questionid', '=', 'report.targetid')
			->where('report.type', '=', ReportType::question)
			->orderBy('report.status')
			->orderBy('report.createdate', 'desc');

		$count_query = DB::table('report')
			->join('question', 'question.questionid', '=', 'report.targetid')
			->where('report.type', '=', ReportType::question)
			->select(array(DB::raw('COUNT(*) as count')));

		$ret = Data::queryList($query, $count_query, $page, array(
			array('type' => 'int', 'field' => 'report.status', 'value' => $status),
		), array(
			'type' => array(Consts::$report_type_texts),
			'status' => array(Consts::$report_status_texts),
			'reason' => array(Consts::$report_reason_text)
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
		DB::table('question')->where('questionid', '=', $targetid)->update(array('status' => QuestionStatus::deleted));
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
			DB::table('question')
				->whereIn('questionid', function($query) use ($eids) {
					$query->select('report.targetid')
						->from('report')
						->whereIn('reportid', $eids)
						->where('status', '=', ReportStatus::pending);
				})
				->update(array('status' => QuestionStatus::deleted));
			DB::table('report')
				->whereIn('reportid', $eids)
				->where('status', '=', ReportStatus::pending)
				->update(array('status' => ReportStatus::disabled));
		}
		return Response::json(array('status' => 1));
	}
}