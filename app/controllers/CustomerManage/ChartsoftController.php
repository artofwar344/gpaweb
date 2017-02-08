<?php
namespace CustomerManage;

use Session,
	View,
	DB,
	Response,
	InputExt,
	Ca\SoftLogType;

class ChartsoftController extends BaseController {
	public $layout = 'customermanage/layouts/chart';

	public function getIndex()
	{
		Session::forget('exportdata');
		$this->layout->title = "软件情况-[图表]";
		$this->layout->body = View::make('customermanage/chart/soft');
	}

	public function postList()
	{
		$month = InputExt::get_time('month');
		$query = DB::table('softlog')
			->select(array(
				DB::raw('COUNT(logid) as count'),
				DB::raw('date(createdate) as date'),
				'type'
			))
			->groupBy('type')
			->orderBy('createdate');
		$chart_subtitle = '';
		if ($month)
		{
			$query->where(DB::raw('YEAR(createdate)'), '=', date('Y', $month))
				->where(DB::raw('MONTH(createdate)'), '=', date('m', $month));
			$chart_subtitle =  date('Y', $month) . '年' .  date('m', $month) . '月';
		}

		$query = $query->get();

		$series = array(
			array('name' => '下载', 'data' => array()),
			array('name' => '升级', 'data' => array()),
			array('name' => '卸载', 'data' => array())
		);
		$categories = array();
		foreach ($query as $entity) {
			$date = $entity->date;
			if (!in_array($date, $categories))
			{
				$categories[] = "总量";
			}

			switch ($entity->type) {
				case SoftLogType::download:
					$series[0]['data'][] = $entity->count;
					break;
				case SoftLogType::upgrade:
					$series[1]['data'][] = $entity->count;
					break;
				case SoftLogType::uninstall:
					$series[2]['data'][] = $entity->count;
					break;
			}
		}
		$chart_title = '软件使用情况';
		Session::put('exportdata', $query);
		Session::put('exporttitle', $chart_title);
		$ret = array("categories" => $categories, "series" => $series, 'title' => $chart_title, 'subtitle' => $chart_subtitle);

		return Response::json($ret);
	}

	public function postSelects()
	{
		$select_1 = DB::table('softlog')
			->select(array(
					DB::raw('CONCAT(YEAR(createdate), "-", MONTH(createdate)) as month'),
					DB::raw('CONCAT(YEAR(createdate), "年", CAST(MONTH(createdate) AS CHAR), "月") as name'))
			)
			->groupBy(DB::raw('YEAR(createdate)'))
			->groupBy(DB::raw('MONTH(createdate)'))
			->order_by('createdate')
			->get();

		return Response::json(array($select_1));
	}

	public function postExport()
	{
		$exportdata = Session::get('exportdata');
		$exporttitle = Session::get('exporttitle');

		$objPHPExcel = new \PHPExcel();
		// Add some data
		$objPHPExcel->setActiveSheetIndex(0);
		$objPHPExcel->getActiveSheet()
			->setCellValue('A1', '下载数量')
			->setCellValue('B1', '升级数量')
			->setCellValue('C1', '卸载数量');

		$dataArray = array();
		if (is_array($exportdata))
		{
			foreach ($exportdata as $value)
			{
				switch ($value->type)
				{
					case SoftLogType::download :
						$dataArray[1] = $value->count;
						break;
					case SoftLogType::upgrade :
						$dataArray[2] = $value->count;
						break;
					case SoftLogType::uninstall :
						$dataArray[3] = $value->count;
						break;
				}
			}
		}
		$objPHPExcel->getActiveSheet()->fromArray($dataArray, NULL, 'A2');

		// Redirect output to a client’s web browser (Excel2007)
		header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
		header('Content-Disposition: attachment;filename="' . $exporttitle . time() . '.xlsx"');
		header('Cache-Control: max-age=0');

		\PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007')->save('php://output');
		exit;
	}
}