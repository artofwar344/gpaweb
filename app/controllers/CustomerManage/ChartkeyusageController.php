<?php
namespace CustomerManage;

use Session,
	DB,
	View,
	InputExt,
	Response,
	PHPExcel,
	PHPExcel_IOFactory,
	Ca\Consts,
	Ca\KeyUsageStatus;

class ChartKeyUsageController extends BaseController {
	public $layout = 'customermanage/layouts/chart';

	public function getIndex()
	{
		Session::forget('exportdata');
		$this->layout->title = '密钥使用情况-[图表]';
		$this->layout->body = View::make('customermanage/chart/keyusage');
	}

	public function postList()
	{
		$keyid = InputExt::getInt('keyid');
		$month = InputExt::get_time('month');

		if ($month === false) $month = time();

		$query = DB::table('keyusage')
			->select(array(
				DB::raw('COUNT(usageid) AS count'),
				DB::raw('DATE(begindate) AS date'),
				'keyusage.status'
			))
			->groupBy('keyusage.status')
			->groupBy(DB::raw('DATE(begindate)'))
			->orderBy('begindate');
		if (!$this->manager->top)
		{
			$query->leftJoin('user', 'user.userid', '=', 'keyusage.userid')
				->where('user.departmentid', '=', $this->manager->departmentid);
		}
		$chart_title = '激活情况统计';
		$chart_subtitle = '';
		if ($keyid > 0)
		{
			$query->where('keyid', '=', $keyid);
		}
		if ($month)
		{
			$query->where(DB::raw('YEAR(begindate)'), '=', date('Y', $month))
				->where(DB::raw('MONTH(begindate)'), '=', date('m', $month));
			$chart_subtitle = date('Y年m月', $month);
		}

		$query = $query->get();
		Session::put('exportdata', $query);
		Session::put('exporttitle', $chart_title);
		$series = array(
			array('name' => '激活成功', 'data' => array()),
			array('name' => '激活失败', 'data' => array()),
			array('name' => '激活重置', 'data' => array())
		);
		$categories = array();
		foreach ($query as $entity) {
			$date = $entity->date;
			if (!in_array($date, $categories))
			{
				$categories[] = $date;

				$series[0]['data'][] = 0;
				$series[1]['data'][] = 0;
				$series[2]['data'][] = 0;
			}

			switch ($entity->status) {
				case KeyUsageStatus::activation_success:
					array_pop($series[0]['data']);
					$series[0]['data'][] = $entity->count;
					break;
				case KeyUsageStatus::activation_failed:
					array_pop($series[1]['data']);
					$series[1]['data'][] = $entity->count;
					break;
				case KeyUsageStatus::activation_reset:
					array_pop($series[2]['data']);
					$series[2]['data'][] = $entity->count;
					break;
			}
		}

		$ret = array('categories' => $categories, 'series' => $series, 'title' => $chart_title, 'subtitle' => $chart_subtitle);

		return Response::json($ret);
	}

	public function postSelects()
	{
		$query = DB::table('department__key')
			->select(array('key.keyid', DB::raw('CONCAT(key.name, " - [", key.section, "]") AS name')))
			->leftJoin('key', 'department__key.keyid', '=', 'key.keyid')
			->where('department__key.status', '=', 1)
			->groupBy('key.keyid');
		if (!$this->manager->top)
		{
			$query->where('department__key.departmentid', '=', $this->manager->departmentid);
		}
		$select_1 = $query->get();

		$select_2 = DB::table('keyusage')
			->select(array(
				DB::raw('CONCAT(YEAR(begindate), "-", MONTH(begindate)) AS month'),
				DB::raw('CONCAT(YEAR(begindate), "年", CAST(MONTH(begindate) AS CHAR), "月") AS name'))
			)
			->groupBy(DB::raw('YEAR(begindate)'))
			->groupBy(DB::raw('MONTH(begindate)'))
			->orderBy('begindate')
			->get();

		return Response::json(array($select_1, $select_2));
	}

	public function getExport()
	{
		$exportdata = Session::get('exportdata');
		$exporttitle = Session::get('exporttitle');

		$objPHPExcel = new PHPExcel();
		// Add some data
		$objPHPExcel->setActiveSheetIndex(0);
		$objPHPExcel->getActiveSheet()
			->setCellValue('A1', '激活时间')
			->setCellValue('B1', '激活状态')
			->setCellValue('C1', '激活数量');

		$dataArray = array();
		if (is_array($exportdata))
		{
			foreach ($exportdata as $value)
			{
				$dataArray[] = array($value->date, Consts::$keyusage_status_texts[$value->status], $value->count);
			}
		}
		$objPHPExcel->getActiveSheet()->fromArray($dataArray, NULL, 'A2');

		// Redirect output to a client’s web browser (Excel2007)
		header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
		header('Content-Disposition: attachment;filename="' . $exporttitle . time() . '.xlsx"');
		header('Cache-Control: max-age=0');

		PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007')->save('php://output');
		exit;
	}

}