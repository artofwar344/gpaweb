<?php
namespace CustomerManage;

use Session,
	DB,
	View,
	InputExt,
	Input,
	Response,
	PHPExcel,
	PHPExcel_IOFactory,
	Ca\KeyUsageStatus;

class ChartProductActivateController extends BaseController {
	public $layout = 'customermanage/layouts/chart';

	public function getIndex()
	{
		Session::forget('exportdata');
		$this->layout->title = '商品激活数量-[图表]';
		$this->layout->body = View::make('customermanage/chart/productactivate');
	}

	public function postList()
	{
		$departmentid = $this->manager->departmentid;
		$date = InputEXt::get_time('date');

		$query = DB::table('product')
			->select(array('product.name', 'product.type', DB::raw('COUNT(keyusage.usageid) AS activatedcount')))
			->leftJoin('key', function($join)
			{
				$join->on('product.productid', '=', 'key.productid');
			})
			->leftJoin('keyusage', function($join)
			{
				$join->on('key.keyid', '=', 'keyusage.keyid');
				$join->on('keyusage.status', '=', DB::raw(KeyUsageStatus::activation_success));
			})
			->groupBy('product.productid');
		if (!$this->manager->top)
		{
			$query->leftJoin('user', 'user.userid', '=', 'keyusage.userid')
				->where('user.departmentid', '=', $departmentid);
		}
		$chart_title = '商品激活数量';
		$chart_subtitle = '';
		if ($date)
		{
			$query->where(DB::raw('YEAR(keyusage.begindate)'), '=', date('Y', $date))
				->where(DB::raw('MONTH(keyusage.begindate)'), '=', date('m', $date));
			$chart_subtitle = date('Y', $date) . '年' . date('m', $date) .'月';
		}
//		echo DBExt::get_sql($departmentid); exit;
		$query = $query->get();
		$series = array(
			array('name' => '激活数量', 'data' => array())
		);
		foreach ($query as $value)
		{
			$series[0]['data'][] = array($value->name . '(' . $value->type . ')', (int)$value->activatedcount);
		}
		Session::put('exportdata', $query);
		Session::put('exporttitle', $chart_title);
		$ret = array('series' => $series, 'type' => 'pie', 'title' => $chart_title, 'subtitle' => $chart_subtitle);
		return Response::json($ret);
	}

	public function postSelects()
	{
		$departmentid = $this->manager->departmentid;
		$query = DB::table('keyusage')
			->select(array(
				DB::raw('CONCAT(CAST(YEAR(begindate) AS CHAR), "-", CAST(MONTH(begindate) AS CHAR)) AS date'),
				DB::raw('CONCAT(CAST(YEAR(begindate) AS CHAR), "年", CAST(MONTH(begindate) AS CHAR), "月") AS name')
			))
			->where('keyusage.status', '=', DB::raw(KeyUsageStatus::activation_success))
			->groupBy('date')
			->orderBy('date');
		if (!$this->manager->top)
		{
			$query->leftJoin('user', 'user.userid', '=', 'keyusage.userid')
				->where('user.departmentid', '=', $departmentid);
		}
		$select = $query->get();
		return Response::json(array($select));
	}

	public function getExport()
	{
		$exportdata = Session::get('exportdata');
		$exporttitle = Session::get('exporttitle');

		$objPHPExcel = new PHPExcel();
		// Add some data
		$objPHPExcel->setActiveSheetIndex(0);
		$objPHPExcel->getActiveSheet()
			->setCellValue('A1', '商品名称')
			->setCellValue('B1', '激活方式')
			->setCellValue('C1', '激活数量');

		$dataArray = array();
		if (is_array($exportdata))
		{
			foreach ($exportdata as $value)
			{
				$dataArray[] = array($value->name, $value->type, $value->activatedcount);
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