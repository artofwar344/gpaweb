<?php
namespace Manage;

use View,
	DB,
	Input,
	InputExt,
	Ca\Data,
	Ca\Common,
	\PHPExcel,
	\PHPExcel_IOFactory,
	\PHPExcel_Settings;

class CustomerstatisticsController extends BaseController {
	public $layout = 'manage/layouts/common';
	public function getIndex()
	{
		$this->layout->title = "客户统计";
		$this->layout->body = View::make('manage/customerstatistics/list');
	}

	public function postList()
	{
		$name = InputExt::get('name');
		$page = InputExt::getInt('page');
		$from_date = Input::get('from', date('Y-m-d', strtotime('-1 Day')));
		$to_date = Input::get('to', date('Y-m-d'));
		$from_date .= ' 00:00:00';
		$to_date   .= ' 23:59:59';

		$query = DB::table('customer')
			->select(array('customer.customerid', 'customer.name', 'customer.alias'))
			->orderBy('customerid', 'desc')
			->groupBy('customer.customerid');

		$count_query = DB::table('customer')->select(array(DB::raw('COUNT(*) as count')));

		$ret = Data::queryList($query, $count_query, $page, array(
			array('type' => 'string', 'field' => 'customer.name', 'value' => $name)
		));
		foreach ($ret['list'] as $key => $customer)
		{
			$database = 'ca_' . $customer['alias'];
			$sql = "select
max(case `name` when 'user_count'   then (count) else 0 end) as 'user_count',
max(case `name` when 'weblogin_count' then (count) else 0 end) as 'weblogin_count',
max(case `name` when 'clientlogin_count' then (count) else 0 end) as 'clientlogin_count',
max(case `name` when 'requestkey_count' then (count) else 0 end) as 'requestkey_count',
max(case `name` when 'assignkey_count' then (count) else 0 end) as 'assignkey_count',
max(case `name` when 'keyusage_count' then (count) else 0 end) as 'keyusage_count'
					from  (select count(*) as count, 'user_count' as name from " . $database . ".user where createdate between ? and ?
					union select count(*) as count, 'weblogin_count' as name from " . $database . ".useraccesslog where createdate between ? and ?
					union select count(*) as count, 'clientlogin_count' as name from " . $database . ".userthread where logindate between ? and ?
					union select count(*) as count, 'requestkey_count' as name from " . $database . ".userkey where requestdate between ? and ?
					union select count(*) as count, 'assignkey_count' as name from " . $database . ".userkey where assigndate between ? and ?
					union select count(*) as count, 'keyusage_count' as name from " . $database . ".keyusage where status=2 and begindate between ? and ?) tb1";
			try
			{
				$query = DB::select($sql, array(
					$from_date,
					$to_date,
					$from_date,
					$to_date,
					$from_date,
					$to_date,
					$from_date,
					$to_date,
					$from_date,
					$to_date,
					$from_date,
					$to_date
				));
			}
			catch (\Exception $e)
			{
				$query = array(
					array(
						'user_count' => 0,
						'weblogin_count' => 0,
						'clientlogin_count' => 0,
						'requestkey_count' => 0,
						'assignkey_count' => 0,
						'keyusage_count' => 0,
					)
				);
			}

			foreach ($query[0] as $key2 => $val)
			{
				$ret['list'][$key][$key2] = intval($val);
			}
		}
		echo json_encode($ret);
		exit;
	}

	private function exportData()
	{
		$name = InputExt::get('name');
		$from_date = Input::get('from', date('Y-m-d', strtotime('-1 Day')));
		$to_date = Input::get('to', date('Y-m-d'));
		$from_date .= ' 00:00:00';
		$to_date   .= ' 23:59:59';

		$query = DB::table('customer')
			->select(array('customer.customerid', 'customer.name', 'customer.alias'))
			->orderBy('customerid', 'desc')
			->groupBy('customer.customerid');
		if ($name)
		{
			$query->where('customer.name', '=', $name);
		}
		$customers = $query->get();
		foreach ($customers as $key => $customer)
		{
			$database = 'ca_' . $customer->alias;
			$sql = "select
max(case `name` when 'user_count'   then (count) else 0 end) as 'user_count',
max(case `name` when 'weblogin_count' then (count) else 0 end) as 'weblogin_count',
max(case `name` when 'clientlogin_count' then (count) else 0 end) as 'clientlogin_count',
max(case `name` when 'requestkey_count' then (count) else 0 end) as 'requestkey_count',
max(case `name` when 'assignkey_count' then (count) else 0 end) as 'assignkey_count',
max(case `name` when 'keyusage_count' then (count) else 0 end) as 'keyusage_count'
					from  (select count(*) as count, 'user_count' as name from " . $database . ".user where createdate between ? and ?
					union select count(*) as count, 'weblogin_count' as name from " . $database . ".useraccesslog where createdate between ? and ?
					union select count(*) as count, 'clientlogin_count' as name from " . $database . ".userthread where logindate between ? and ?
					union select count(*) as count, 'requestkey_count' as name from " . $database . ".userkey where requestdate between ? and ?
					union select count(*) as count, 'assignkey_count' as name from " . $database . ".userkey where assigndate between ? and ?
					union select count(*) as count, 'keyusage_count' as name from " . $database . ".keyusage where status=2 and begindate between ? and ?) tb1";
			try
			{
				$query = DB::select($sql, array(
					$from_date,
					$to_date,
					$from_date,
					$to_date,
					$from_date,
					$to_date,
					$from_date,
					$to_date,
					$from_date,
					$to_date,
					$from_date,
					$to_date
				));
			}
			catch (\Exception $e)
			{
				$query = array(
					array(
						'user_count' => 0,
						'weblogin_count' => 0,
						'clientlogin_count' => 0,
						'requestkey_count' => 0,
						'assignkey_count' => 0,
						'keyusage_count' => 0,
					)
				);
			}

			foreach ($query[0] as $key2 => $val)
			{
				$customers[$key]->$key2 = intval($val);
			}
		}
		return $customers;
	}

	public function getExport()
	{
		$data = $this->exportData();

		$objPHPExcel = new PHPExcel();
		// Add some data
		$objPHPExcel->setActiveSheetIndex(0);
		$objPHPExcel->getActiveSheet()
			->setCellValue('A1', '客户名称')
			->setCellValue('B1', '新用户')
			->setCellValue('C1', '用户网页登录')
			->setCellValue('D1', '用户客户端登录')
			->setCellValue('E1', '用户请求激活')
			->setCellValue('F1', '管理员分配激活')
			->setCellValue('G1', '用户激活');
		$dataArray = array();
		if (is_array($data))
		{
			foreach ($data as $value)
			{
				$dataArray[] =  array(
					$value->name,
					$value->user_count,
					$value->weblogin_count,
					$value->clientlogin_count,
					$value->requestkey_count,
					$value->assignkey_count,
					$value->keyusage_count
				);
			}
		}

		$objPHPExcel->getActiveSheet()->fromArray($dataArray, NULL, 'A2');

		$contentType = 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet';
		$ext = '.xlsx';
		$writer = 'Excel2007';

		header('Content-Type: ' . $contentType);
		header('Content-Disposition: attachment;filename="客户数据统计' . time() . $ext . '"');
		header('Cache-Control: max-age=0');
		PHPExcel_IOFactory::createWriter($objPHPExcel, $writer)->save('php://output');
		exit;
	}
}