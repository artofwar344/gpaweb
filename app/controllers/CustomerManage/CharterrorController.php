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

class ChartErrorController extends BaseController {
	public $layout = 'customermanage/layouts/chart';
	private $chartTitle = '激活错误次数';

	public function getIndex()
	{
		$this->layout->title = '激活错误统计-[图表]';
		$this->layout->body = View::make('customermanage/chart/error');
	}

	public function postList()
	{
		$data = $this->exportData();
		$series = array(
			array('name' => '出错次数', 'data' => array())
		);
		foreach ($data['result'] as $value)
		{
			$series[0]['data'][] = array($value->errorcode, (int)$value->count);
		}
		$ret = array('series' => $series, 'type' => 'pie', 'title' => $this->chartTitle, 'subtitle' => $data['subtitle']);
		return Response::json($ret);
	}

	private function exportData()
	{
		$date = InputExt::get_time('date');
		$productid = InputExt::getInt('productid');
		$query = DB::table('keyusage')
			->select(array('errorcode', DB::raw('COUNT(usageid) AS count')))
			->leftJoin('key', 'key.keyid', '=', 'keyusage.keyid')
			->leftJoin('product', 'product.productid', '=', 'key.productid')
			->where('keyusage.status', '=', DB::raw(KeyUsageStatus::activation_failed))
			->groupBy('errorcode');
		if (!$this->manager->top)
		{
			$query->leftJoin('user', 'user.userid', '=', 'keyusage.userid')
				->where('user.departmentid', '=', $this->manager->departmentid);
		}
		$chart_subtitle = '';
		if ($date)
		{
			$query->where(DB::raw('YEAR(keyusage.begindate)'), '=', date('Y', $date))
				->where(DB::raw('MONTH(keyusage.begindate)'), '=', date('m', $date));
			$chart_subtitle .= date('Y', $date) . '年' . date('m', $date) .'月 ';
		}
		if ($productid > 0)
		{
			$query->where('product.productid', '=', $productid);
			$product = DB::table('product')->select(array('name', 'type'))->where('productid', '=', $productid)->first();
			$chart_subtitle .= $product->name . '(' . $product->type .')';
		}
		$query = $query->get();
		return array(
			'result' => $query,
			'subtitle' => $chart_subtitle
		);
	}

	public function postSelects()
	{
		$query = DB::table('keyusage')
			->select(array(
				DB::raw('CONCAT(CAST(YEAR(begindate) AS CHAR), "-", CAST(MONTH(begindate) AS CHAR)) as date'),
				DB::raw('CONCAT(CAST(YEAR(begindate) AS CHAR), "年", CAST(MONTH(begindate) AS CHAR), "月") as name')
			))
			->where('keyusage.status', '=', DB::raw(KeyUsageStatus::activation_failed))
			->groupBy('date')
			->orderBy('date');
		if (!$this->manager->top)
		{
			$query->leftJoin('user', 'user.userid', '=', 'keyusage.userid')
				->where('user.departmentid', '=', $this->manager->departmentid);
		}
		$select_1 = $query->get();
		$select_2 = DB::table('product')
			->select(array('productid', DB::raw('CONCAT(name, "(", type, ")") as name')))
			->get();
		return Response::json(array($select_1, $select_2));
	}

	public function getExport()
	{
		$data = $this->exportData();
		$type = Input::get('type');

		$objPHPExcel = new PHPExcel();
		// Add some data
		$objPHPExcel->setActiveSheetIndex(0);
		$objPHPExcel->getActiveSheet()->setCellValue('A1', '错误代码')
			->setCellValue('B1', '出错次数');
		$dataArray = array();
		if (is_array($data['result']))
		{
			foreach ($data['result'] as $value)
			{
				$dataArray[] =  array($value->errorcode, $value->count);
			}
		}

		$objPHPExcel->getActiveSheet()->fromArray($dataArray, NULL, 'A2');

		$contentType = 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet';
		$ext = '.xlsx';
		$writer = 'Excel2007';
		if ($type == 'pdf')
		{
			$contentType = 'application/pdf';
			$ext = '.pdf';
			$writer = 'PDF';
			\PHPExcel_Settings::setPdfRendererPath(app_path() . '/lib/tcpdf/');
			\PHPExcel_Settings::setPdfRendererName(\PHPExcel_Settings::PDF_RENDERER_TCPDF);
		}

		header('Content-Type: ' . $contentType);
		header('Content-Disposition: attachment;filename="' . $this->chartTitle . time() . $ext . '"');
		header('Cache-Control: max-age=0');
		PHPExcel_IOFactory::createWriter($objPHPExcel, $writer)->save('php://output');
		exit;
	}

}