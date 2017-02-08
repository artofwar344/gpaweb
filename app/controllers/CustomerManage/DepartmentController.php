<?php
namespace CustomerManage;

use \View,
	\DB,
	\Input,
	\Response,
	\InputExt,
	Ca\Data,
	Ca\Service\DepartmentService,
	Ca\Service\TreeService;

class DepartmentController extends BaseController {
	public $layout = 'customermanage/layouts/common';

	public function getIndex()
	{
		$this->layout->title = "部门管理";
		$this->layout->body = View::make("customermanage/department/list");
	}

	public function postList()
	{
		$name = Input::get('name');
		$page = InputExt::getInt('page');
		DepartmentService::getChildDepartments($this->manager->departmentid, $departmentids);

		$department_subquery = DB::table('department')
			->select(array('parentid', DB::raw('COUNT(departmentid) as count')))
			->groupBy('parentid')->toSql();

		$manager_subquery = DB::table('manager')
			->select(array('departmentid', DB::raw('COUNT(managerid) as count')))
			->groupBy('departmentid')->toSql();

		$user_subquery = DB::table('user')
			->select(array('departmentid', DB::raw('COUNT(userid) as count')))
			->groupBy('departmentid')->toSql();

		$departmentkey_subquery = DB::table('department__key')
			->select(array('departmentid', DB::raw('COUNT(keyid) as count')))
			->groupBy('departmentid')->toSql();

		$query = DB::table('department')
			->select(array('department.departmentid', 'department.name as name',
				DB::raw('IFNULL(manager.count, 0) as manager_count'), DB::raw('IFNULL(user.count, 0) as user_count'),
				DB::raw('IFNULL(subdepartment.count, 0) as department_count'),
				DB::raw('(IFNULL(departmentkey.count, 0) + IFNULL(subdepartment.count, 0) + IFNULL(user.count, 0) + IFNULL(manager.count, 0)) as count'),
				'department.createdate'))
			->orderBy('departmentid', 'desc')
			->leftJoin(DB::raw("({$manager_subquery}) as manager"), 'department.departmentid', '=', 'manager.departmentid')
			->leftJoin(DB::raw("({$user_subquery}) as user"), 'department.departmentid', '=', 'user.departmentid')
			->leftJoin(DB::raw("({$department_subquery}) as subdepartment"), 'department.departmentid', '=', 'subdepartment.parentid')
			->leftJoin(DB::raw("({$departmentkey_subquery}) as `departmentkey`"), 'department.departmentid', '=', 'departmentkey.departmentid');

		$count_query = DB::table('department')->select(array(DB::raw('COUNT(*) as count')));

		//部门
		$departments = DepartmentService::departments();
		foreach ($departments as $key => $row)
		{
			if (is_object($row))
			{
				$departments[$key] = (array)$row;
			}
		}
		$tree_service = new TreeService($departments, array(
			'_id' => 'departmentid',
			'_pid' => 'parentid',
			'_default_pid' => 1
		));

		$ret = Data::queryList($query, $count_query, $page, array(
			array('type' => 'string', 'field' => 'department.name', 'value' => $name),
			array('type' => 'null', 'field' => 'department.parentid', 'operator' => 'NOT', 'value' => DB::raw('null')),
			array('type' => 'int', 'field' => 'department.parentid', 'operator' => 'in', 'value' => $departmentids)
		),
		array(
			'departmentid' => function($value) use($tree_service) {
				$parents = $tree_service->get_parents($value);
				$parents = array_reverse($parents);
				$ret = array();
				foreach ($parents as $parent)
				{
					$ret[] = $parent['name'];
				}
				return join(' > ', $ret);
			}
		), null, array(array('count', '==', '0')));

		return Response::json($ret);
	}

	public function postEntity()
	{
		$eid = InputExt::getInt('eid');
		$parentid = InputExt::getInt('parentid');

		$modify = $eid > 0;

		if($modify && !DepartmentService::check_customer($eid))
		{
			return;
		}

		$_POST['parentid'] = $parentid;

		$departmentid = $this->manager->departmentid;

		if(DepartmentService::check_departmentid($departmentid, $parentid))
		{
			Data::updateEntity('department', array('departmentid', '=', $eid), array('name', 'parentid'));
		}
	}

	public function postGet()
	{
		$eid = InputExt::getInt('eid');

		if(!DepartmentService::check_customer($eid))
		{
			return;
		}

		$entity = DB::table('department')
			->select(array('department.name', 'department.parentid'))
			->where('departmentid', '=', $eid)->first();

		return Response::json($entity);
	}

	public function postDelete()
	{
		$eid = InputExt::getInt('eid');

		if (DepartmentService::check_customer($eid)
			&& DepartmentService::manager_count($eid) == 0
			&& DepartmentService::key_count($eid) == 0
			&& DepartmentService::user_count($eid) == 0)
		{
			DB::table('department')
				->where('departmentid', '=', $eid)
				->delete();
		}
	}

	public function postSelects()
	{
		$departmentid = $this->manager->departmentid;
		DepartmentService::getChildDepartments($departmentid, $departmentids);
		$select = DB::table('department')
			->select(array('department.departmentid as parentid'))
			->whereIn('department.departmentid', $departmentids) //所有下级部门
			->orderBy('department.departmentid', 'desc')
			->get();
		foreach ($select as $key => $item)
		{
			$select[$key]->name = DepartmentService::getFullName($item->parentid);
		}
		return Response::json(array($select));
	}

}

