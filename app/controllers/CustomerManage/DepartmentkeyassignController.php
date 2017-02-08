<?php
namespace CustomerManage;

use Ca\Service\DepartmentService;
use DB,
	View,
	Input,
	Response,
	InputExt,
	Ca\UserKeyStatus,
	Ca\Data,
	Ca\Service\KeyService;

class DepartmentkeyassignController extends BaseController {
	public $layout = 'customermanage/layouts/common';

	public function getIndex()
	{
		$department_id = InputExt::getInt('id');
		$department = null;
		$managerdepartment = $this->manager->departmentid;
//		if ($department_id)
//		{
//			$department = DB::table('department')
//				->select(array('department.name'))
//				->where('departmentid', '=', $department_id)->first();
//		}
		$departmentName = DepartmentService::getFullName($department_id);

		$this->layout->title = "部门激活分配";
		$this->layout->body = View::make('customermanage/key/departmentkeyassign')
			->with('managerdepartment', $managerdepartment)
			->with('department_id', $department_id)
			->with('department_name', $departmentName);
//			->with('department', $department);
	}

	public function postList()
	{
		$page = InputExt::getInt('page');
		$productid = InputExt::getInt('productid');
		$department_id = InputExt::getInt('departmentid');

		$keyid = InputExt::getInt('keyid');

		$query = DB::table('department__key')
			->orderBy('department__key.departmentkeyid', 'desc')
			->leftJoin('key', 'department__key.keyid', '=', 'key.keyid')
			->leftJoin('product', 'key.productid', '=', 'product.productid')
			->leftJoin('department', 'department__key.departmentid', '=', 'department.departmentid');
		if (Input::get('log') != '1')
		{
			$query->select(array('departmentkeyid', 'department__key.departmentid', 'department__key.keyid', 'department.name as department_name', 'department.parentid', 'product.name as product_name', 'product.type',
				DB::raw('CONCAT(key.name, IF(key.section IS NULL, "", CONCAT(" - [", key.section, "]"))) as key_name'),
				DB::raw('SUM(department__key.count) as count'),
				'assigndate',
				DB::raw('CONCAT(departmentkeyid, "", department__key.keyid) as departmentkeyid_keyid')
			))
				->groupBy('department__key.departmentid')
				->groupBy('department__key.keyid');
		}
		else
		{
			$query->select(array('departmentkeyid', 'department__key.departmentid', 'department__key.keyid', 'department.name as department_name', 'department.parentid', 'product.name as product_name', 'product.type',
				DB::raw('CONCAT(key.name, IF(key.section IS NULL, "", CONCAT(" - [", key.section, "]"))) as key_name'), 'department__key.count', 'assigndate'
			));
		}

		$count_query = DB::table('department__key')
			->select(array(DB::raw('COUNT(distinct department__key.departmentid, department__key.keyid) AS count')))
			->leftJoin('key', 'department__key.keyid', '=', 'key.keyid')
			->leftJoin('product', 'key.productid', '=', 'product.productid')
			->leftJoin('department', 'department__key.departmentid', '=', 'department.departmentid');

		DepartmentService::getChildDepartments($this->manager->departmentid, $departmentids);
		$ret = Data::queryList($query, $count_query, $page, array(
			array('type' => 'int', 'field' => 'product.productid', 'value' => $productid),
			array('type' => 'int', 'field' => 'key.keyid', 'value' => $keyid),
			array('type' => 'int', 'field' => 'department.departmentid', 'value' => $department_id),
			array('type' => 'int', 'field' => 'department__key.status', 'value' => 1),
			array('type' => 'int', 'field' => 'department.parentid', 'operator' => 'in', 'value' => $departmentids),
		), array(), array(), array());
		foreach ($ret['list'] as $key => $item)
		{
			$ret['list'][$key]['department_name'] = DepartmentService::getFullName($item['departmentid']);
		}

		return Response::json($ret);
	}

	public function postEntity()
	{
		$eid = InputExt::getInt('eid');
		$department_id = InputExt::getInt('departmentid');
		$key_id = InputExt::getInt('keyid');
		$assigncount = InputExt::getInt('assigncount');
		$modify = $eid > 0;

		if ($modify)
		{
			return;
		}

		$fields = array('keyid', 'departmentid', 'count');
		$values = array(
			'keyid' => $key_id,
			'departmentid' => $department_id,
			'count' => $assigncount
		);
		$department = DepartmentService::getDepartment($department_id);
		$remain = KeyService::check_remain(UserKeyStatus::agree, $key_id, $assigncount, $department->parentid);
		// 可分配
		if ($remain >= 0)
		{
			Data::updateEntity('department__key', array(), $fields, $values);
		}
	}

	public function postSelects()
	{
		$select_1 = DB::table('product')
			->select(array('productid', DB::raw('CONCAT(name, " [", type, "]") as name')))
			->where('status', '=', 1)
			->orderBy("productid", "desc")
			->get();

		$select_2 = DB::table('key')->select(array('key.keyid', DB::raw('CONCAT(key.name, IF(key.section IS NULL, "", CONCAT(" - [", key.section, "]"))) as name')))
			->groupBy('key.keyid')
			->orderBy("key.keyid", "desc")
			->get();

		DepartmentService::getChildDepartments($this->manager->departmentid, $departmentids);
		$select_3 = DB::table('department')->select(array('department.departmentid', 'department.name'))
			->whereIn('department.parentid', $departmentids)
			->orderBy("department.departmentid", "desc")
			->get();
		foreach ($select_3 as $key => $item)
		{
			$select_3[$key]->name = DepartmentService::getFullName($item->departmentid);
		}

		return Response::json(array($select_1, $select_2, $select_3));
	}

	public function postKeys()
	{
		$departmentid = InputExt::getInt('departmentid');
		$department = DepartmentService::getDepartment($departmentid);
		if (empty($department))
		{
			return;
		}
		$remains = KeyService::check_remains($department->parentid);
		return Response::json(array($remains));
	}

	/**
	 * 收回激活次数
	 */
	public function postRetrieve()
	{
		$eid = InputExt::getInt('eid');
		$count = InputExt::getInt('count');
		$row = DB::table('department__key')
			->where('departmentkeyid', '=', $eid)
			->first();
		$department_id = $row->departmentid;
		$key_id = $row->keyid;

		$remain = KeyService::check_remain(UserKeyStatus::agree, $key_id, 0, $department_id);

		// 如果没有提供count值, 仅做检查
		if ($count == 0)
		{
			return Response::json(array('remain' => $remain));
		}
		else
		{
			if ($count <= $remain)
			{
				$values = array(
					'departmentid' => $department_id,
					'keyid' => $key_id,
					'count' => 0 - abs($count),
					'status' => 1
				);
				DB::table('department__key')
					->insert($values);
			}
		}
	}
}