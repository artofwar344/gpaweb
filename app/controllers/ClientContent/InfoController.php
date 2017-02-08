<?php

namespace ClientContent;

use DB;
 
class InfoController extends \Controller
{
	public function getCustomer()
	{
		$rows = DB::table('ca.organize')
			->select(array('organize.provinceid', 'organize.organizeid', 'alias',
				DB::raw('province.name as province'),
				DB::raw('organize.name as organize'),
			))
			->leftJoin('ca.province', 'province.provinceid', '=', 'organize.provinceid')
			->leftJoin('ca.customer', 'customer.organizeid', '=', 'organize.organizeid')
			->whereNotNull('alias')
			->whereStatus(1)
			->orderBy('provinceid')
			->orderBy('alias', 'DESC')
			->orderBy('customerid')
			->get();
		$ret = array();
		foreach ($rows as $row)
		{
			if (!array_key_exists($row->provinceid, $ret))
			{
				$ret[$row->provinceid] = array(
					'id' => $row->provinceid,
					'name' => $row->province,
				);
			}

			$departments = DB::table('ca_' . $row->alias . '.department')
				->select(array(DB::raw('department.departmentid as id'), DB::raw('department.name as name')))
				->where('parentid','1')
				->get();

			$departments_formated = array();
			foreach($departments as $department)
			{
				$departments_formated[] = array(
					'id' => $department->id,
					'name' => $department->name,
				);
			}
			$value = DB::table('ca_'.$row->alias.'.params')->where('key','structtype')->pluck('value');
			$structtype = $value ? $value : 1;

			$ret[$row->provinceid]['customers'][] = array(
				'id' => $row->organizeid,
				'name' => $row->organize,
				'alias' => $row->alias,
				'departments' => $departments_formated,
				'structtype' => $structtype,
			);
		}

		echo json_encode(array_values($ret));
	}
}