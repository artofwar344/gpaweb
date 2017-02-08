<?php
namespace CustomerManage;

use Session,
	DB,
	View,
	InputExt,
	Input,
	Response,
	PHPExcel,
	PHPExcel_IOFactory;

class ChartUserController extends BaseController {
	public $layout = 'customermanage/layouts/chart';

	public function getIndex()
	{
		Session::forget('exportdata');
		$this->layout->title = '用户统计-[图表]';
		$this->layout->body = View::make('customermanage/chart/user');
	}

	public function postList()
	{
		$years = Input::get('years', date('Y'));
		$type = max(1, InputExt::getInt('type'));
		$departmentid = $this->manager->departmentid;
		$top = $this->manager->top;
		switch ($type)
		{
			case 1:
				$query = DB::table('user')
					->select(array('departmentid', DB::raw('YEAR(createdate) AS years'), DB::raw('MONTH(createdate) AS months'),
						DB::raw('COUNT(*) AS usercount')))
					->where( DB::raw('YEAR(createdate)'), '=', $years)
					->groupBy('months');
				if (!$top)
				{
					$query->where('departmentid', '=', $departmentid);
				}
				$query = $query->get();

				$chart_title = '用户新增统计';
				break;
			case 2:
				if ($top)
				{
					$query = DB::table(DB::raw('(SELECT YEAR(createdate) AS years, MONTH(createdate) AS months FROM `user` GROUP BY years,months) newtable'))
						->select(array('departmentid', 'newtable.years', 'newtable.months', DB::raw('COUNT(*) AS usercount')))
						->join('user', function($join) use($departmentid){
							$join->on(DB::raw('YEAR(user.createdate)'), '=', 'newtable.years');
							$join->on(DB::raw('MONTH(user.createdate)'), '<=', 'newtable.months');
						})
						->where('newtable.years', '=', $years)
						->groupBy('newtable.months')
						->get();
				}
				else
				{
					$query = DB::table(DB::raw('(SELECT YEAR(createdate) AS years, MONTH(createdate) AS months FROM `user` WHERE departmentid =' . $departmentid . ' GROUP BY years,months) newtable'))
						->select(array('departmentid', 'newtable.years', 'newtable.months', DB::raw('COUNT(*) AS usercount')))
						->join('user', function($join) use($departmentid){
							$join->on(DB::raw('YEAR(user.createdate)'), '=', 'newtable.years');
							$join->on(DB::raw('MONTH(user.createdate)'), '<=', 'newtable.months');
							$join->on('user.departmentid', '=', DB::raw($departmentid));
						})
						->where('newtable.years', '=', $years)
						->groupBy('newtable.months')
						->get();
				}
				$chart_title = '用户总量统计';
				break;
		}
		Session::put('exportdata', $query);
		Session::put('exporttitle', $chart_title);
		$categories = array('月份');
		$series = array();
		foreach ($query as $value)
		{
			$series[] = array('name' => $value->months .'月', 'data' => array($value->usercount));
		}
		$chart_subtitle = $years . '年';
		$ret = array('categories' => $categories, 'series' => $series, 'title' => $chart_title, 'subtitle' => $chart_subtitle);
		return Response::json($ret);
	}

	public function postSelects()
	{
		$query = DB::table('user')
			->select(array(
					DB::raw('YEAR(createdate) AS years'),
					DB::raw('CONCAT(CAST(YEAR(createdate) AS CHAR), "年") AS name'))
			)
			->groupBy('years')
			->orderBy('years', 'desc');
		if (!$this->manager->top)
		{
			$query->where('departmentid', '=', $this->manager->departmentid);
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
		$objPHPExcel->getActiveSheet()->setCellValue('A1', '注册日期')
			->setCellValue('B1', '用户数量');

		$dataArray = array();
		if (is_array($exportdata))
		{
			foreach ($exportdata as $value)
			{
				$dataArray[] =  array($value->years . '-' . $value->months, $value->usercount);
			}
		}
		$objPHPExcel->getActiveSheet()->fromArray($dataArray, NULL, 'A2');

		// Redirect output to a client’s web browser (Excel2007)
		header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
		header('Content-Disposition: attachment;filename="' . $exporttitle . time() . '.xlsx"');
		header('Cache-Control: max-age=0');

		$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
//		ob_end_clean();
		$objWriter->save('php://output');
		exit;
	}

}