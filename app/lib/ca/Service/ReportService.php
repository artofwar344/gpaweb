<?php
namespace Ca\Service;
use Illuminate\Support\Facades\DB;
class ReportService {
	public static function checkReport($type, $targetid, $reporterid)
	{
		return DB::table('report')
			->where('type', '=', $type)
			->where('targetid', '=', $targetid)
			->where('reporterid', '=', $reporterid)
			->count('reportid') > 0;
	}

	public static function add_report($type, $targetid, $reason, $reporterid)
	{
		$data = array(
			'type' => $type,
			'targetid' => $targetid,
			'reason' => $reason,
			'reporterid' => $reporterid,
		);
		return DB::table('report')
			->insertGetId($data);
	}
}