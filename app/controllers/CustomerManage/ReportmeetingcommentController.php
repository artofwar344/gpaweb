<?php
namespace CustomerManage;

use DB,
	Input,
	View,
	InputExt,
	Response,
	Ca\ReportType,
	Ca\CommentStatus,
	Ca\ReportStatus,
	Ca\Consts,
	Ca\Data;

class ReportmeetingcommentController extends BaseController {
	public $layout = 'customermanage/layouts/common';

	public function getIndex()
	{
		$this->layout->title = "举报记录";
		$this->layout->body = View::make('customermanage/report/meetingcomment');
	}

	public function postList()
	{
		$status = InputExt::getInt('status');
		$page = InputExt::getInt('page');

		$query = DB::table('report')
			->select(array('report.*', 'comment.content as report_content', 'user.name as reporter_name'))
			->leftJoin('user', 'user.userid', '=', 'report.reporterid')
			->join('comment', 'comment.commentid', '=', 'report.targetid')
			->where('report.type', '=', ReportType::comment)
			->orderBy('report.status')
			->orderBy('report.createdate', 'desc');

		$count_query = DB::table('report')
			->join('comment', 'comment.commentid', '=', 'report.targetid')
			->where('report.type', '=', ReportType::comment)
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
		$targetid = DB::table('report')
			->leftJoin('comment', 'comment.commentid', '=', 'report.targetid')
			->where('reportid', '=', $eid)
			->pluck('comment.targetid');
		return Response::json($targetid);
	}

	public function postDisabletarget()
	{
		$eid = InputExt::getInt("eid");
		$targetid = DB::table('report')->where('reportid', '=', $eid)->where('status', '=', ReportStatus::pending)->pluck('targetid');
		if ($targetid == null)
		{
			return Response::json(array('status' => 0));
		}
		DB::table('comment')->where('commentid', '=', $targetid)->update(array('status' => CommentStatus::disabled,));
		DB::table('report')->where('reportid', '=', $eid)->where('status', '=', ReportStatus::pending)->update(array('status'=> ReportStatus::disabled));
		return Response::json(array('status' => 1));
	}

	public function postRejectreport()
	{
		$eid = InputExt::getInt("eid");
		DB::table('report')->where('reportid', '=', $eid)->where('status', '=', ReportStatus::pending)->update(array('status'=> ReportStatus::reject));
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
			DB::table('comment')
				->whereIn('commentid', function($query) use ($eids) {
					$query->select('report.targetid')
						->from('report')
						->whereIn('reportid', $eids)
						->where('status', '=', ReportStatus::pending);
				})
				->update(array('status' => CommentStatus::disabled));
			DB::table('report')
				->whereIn('reportid', $eids)
				->where('status', '=', ReportStatus::pending)
				->update(array('status' => ReportStatus::disabled));
		}
		return Response::json(array('status' => 1));
	}
}