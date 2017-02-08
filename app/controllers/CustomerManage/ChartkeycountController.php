<?php
namespace CustomerManage;

use Session,
	DB,
	View,
	Response,
	PHPExcel,
	PHPExcel_IOFactory;

class ChartKeyCountController extends BaseController {
	public $layout = 'customermanage/layouts/chart';

	public function getIndex()
	{
		Session::forget('exportdata');
		$this->layout->title = '密钥统计-[图表]';
		$this->layout->body = View::make('customermanage/chart/keycount');
	}

	public function postList()
	{
		$departmentid = $this->manager->departmentid;
		if ($this->manager->top)
		{
			$query = DB::table('product')
				->select(array('product.name', 'product.type', DB::raw('IFNULL(SUM(key.count),0) AS totalkey')))
				->leftJoin('key', function($join)
				{
					$join->on('product.productid', '=', 'key.productid');
				})
				->groupBy('product.productid')
				->get();
		}
		else
		{
			$query = DB::table('product')
				->select(array('product.name', 'product.type', DB::raw('IFNULL(SUM(department__key.count),0) AS totalkey')))
				->leftJoin('key', function($join)
				{
					$join->on('product.productid', '=', 'key.productid');
				})
				->leftJoin('department__key', function($join) use ($departmentid)
				{
					$join->on('department__key.keyid', '=', 'key.keyid');
					$join->on('department__key.departmentid', '=', DB::raw($departmentid));
					$join->on('department__key.status', '=', DB::raw(1));
				})
				->groupBy('product.productid')
				->get();
		}

		$categories = array('商品');
		$chart_title = '密钥总量';
		Session::put('exportdata', $query);
		Session::put('exporttitle', $chart_title);
		$series = array();
		foreach ($query as $value)
		{
			$series[] = array('name' => $value->name .'(' . $value->type . ')', 'data' => array($value->totalkey));
		}

		$ret = array('categories' => $categories, 'series' => $series);
		return Response::json($ret);
	}

	public function getExport()
	{
		$exportdata = Session::get('exportdata');
		$exporttitle = Session::get('exporttitle');

		$objPHPExcel = new PHPExcel();
		// Add some data
		$objPHPExcel->setActiveSheetIndex(0);
		$objPHPExcel->getActiveSheet()->setCellValue('A1', '商品名称')
			->setCellValue('B1', '激活方式')
			->setCellValue('C1', '密钥数量');

		$dataArray = array();
		if (is_array($exportdata))
		{
			foreach ($exportdata as $value)
			{
				$dataArray[] =  array($value->name, $value->type, $value->totalkey);
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