<?php
namespace CustomerManage;

use Session,
	DB,
	View,
	InputExt,
	Response,
	PHPExcel,
	PHPExcel_IOFactory;

class ChartKeyAssignController extends BaseController {
	public $layout = 'customermanage/layouts/chart';

	public function getIndex()
	{
		Session::forget('exportdata');
		$this->layout->title = '激活分配情况-[图表]';
		$this->layout->body = View::make('customermanage/chart/keyassign');
	}

	public function postList()
	{
		$date = InputExt::get_time('date');
		if (!$date)
		{
			$query = DB::table('userkey')
				->select(array('product.name', 'product.type', 'requestcount', 'assigncount', DB::raw('date(assigndate) as date')))
				->leftJoin('product','product.productid', '=', 'userkey.productid')
				->orderBy('assigndate', 'DESC');
			if (!$this->manager->top)
			{
				$query->leftJoin('user','userkey.userid', '=', 'user.userid')
					->where('user.departmentid', '=', $this->manager->departmentid);
			}
			$query = $query->take(10)->get();
			$chart_subtitle = '最近10次';
		}
		else
		{
			$query = DB::table('userkey')
				->select(array('product.name', 'product.type', 'requestcount', 'assigncount', DB::raw('date(assigndate) as date')))
				->leftJoin('product','product.productid', '=', 'userkey.productid')
				->where(DB::raw('YEAR(assigndate)'), '=', date('Y', $date))
				->where(DB::raw('MONTH(assigndate)'), '=', date('m', $date));
			if (!$this->manager->top)
			{
				$query->leftJoin('user','userkey.userid', '=', 'user.userid')
					->where('user.departmentid', '=', $this->manager->departmentid);
			}
			$query = $query->get();
			$chart_subtitle = date('Y', $date) . '年' . date('m', $date) .'月';
		}

		$categories = array();
		$series = array(
			array('name' => '请求数量', 'data' => array()),
			array('name' => '分配数量', 'data' => array())
		);
		foreach ($query as $value)
		{
			$series[0]['data'][] = $value->requestcount;
			$series[1]['data'][] = $value->assigncount;
			$categories[] = $value->name . '(' . $value->type . ')<br/>' . $value->date;
		}
		$chart_title = '激活分配记录';
		Session::put('exportdata', $query);
		Session::put('exporttitle', $chart_title);
		$ret = array('categories' => $categories, 'series' => $series, 'title' => $chart_title, 'subtitle' => $chart_subtitle);
		return Response::json($ret);
	}

	public function postSelects()
	{
		$query = DB::table('userkey')
			->select(array(
					DB::raw('CONCAT(CAST(YEAR(assigndate) AS CHAR), "-", CAST(MONTH(assigndate) AS CHAR)) as date'),
					DB::raw('CONCAT(CAST(YEAR(assigndate) AS CHAR), "年", CAST(MONTH(assigndate) AS CHAR), "月") as name')
			))
			->whereNotNull('assigndate')
			->groupBy('date')
			->orderBy('date');
		if (!$this->manager->top)
		{
			$query->leftJoin('user','userkey.userid', '=', 'user.userid')
				->where('user.departmentid', '=', $this->manager->departmentid);
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
			->setCellValue('C1', '请求数量')
			->setCellValue('D1', '分配数量')
			->setCellValue('E1', '分配时间');

		$dataArray = array();
		if (is_array($exportdata))
		{
			foreach ($exportdata as $value)
			{
				$dataArray[] =  array($value->name, $value->type, $value->requestcount, $value->assigncount, $value->date);
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