<?php
namespace CustomerManage;

use DB,
	Input,
	Response,
	View,
	Hash,
	InputExt,
	Ca\Consts,
	Ca\Common,
	Ca\Data,
	Ca\Service\DepartmentService,
	Ca\Service\ManagerService,
	Ca\Service\TreeService,
	Ca\Service\PermissionService;

class ManagerController extends BaseController {
	public $layout = 'customermanage/layouts/common';

	public function getIndex()
	{
		$this->layout->title = "管理员管理";
		$department_id = InputExt::getInt('id');
		$departmentName = DepartmentService::getFullName($department_id);
		$this->layout->body = View::make('customermanage/manager/list')
			->with('department_id', $department_id)
			->with('department_name', $departmentName);
	}

	public function postList()
	{
		$page = InputExt::getInt('page');
		$name = InputExt::get('name');
		$department_id = InputExt::getInt('departmentid');
		$status = InputExt::getInt('status');
		$role = InputExt::get('role');

		$assigncount_query = DB::table('userkey')
			->select(array('managerid', DB::raw('SUM(userkey.assigncount) as count')))
			->groupBy('managerid')->toSql();

		$query = DB::table('manager')
			->select(array('manager.managerid', 'manager.name as name', 'department.departmentid', 'department.parentid', 'department.name as department_name', 'role',
				'manager.status as status', 'manager.createdate', DB::raw('IFNULL(assgincount.count, 0) as assign_count')))
			->orderBy('manager.managerid', 'desc')
			->leftJoin('department', 'manager.departmentid', '=', 'department.departmentid')
			->leftJoin(DB::raw("({$assigncount_query}) as assgincount"), 'assgincount.managerid', '=', 'manager.managerid');

		$count_query = DB::table('manager')
			->select(array(DB::raw('COUNT(*) as count')))
			->leftJoin('department', 'manager.departmentid', '=', 'department.departmentid');

		$query_list_conditions = array(
			array('type' => 'string', 'field' => 'manager.name', 'value' => $name),
			array('type' => 'null', 'field' => 'department.parentid', 'operator' => 'NOT', 'value' => DB::raw('null')),
			array('type' => 'int', 'field' => 'manager.status', 'value' => $status),
			array('type' => 'string', 'field' => 'manager.role', 'value' => $role),
		);

		if (!empty($department_id))
		{
			DepartmentService::getChildDepartments($department_id, $deparmentids);
		}
		else
		{
			DepartmentService::getChildDepartments($this->manager->departmentid, $deparmentids, false);
		}
		if (count($deparmentids) == 0)
		{
			return Response::json(array('list' => array(), 'count' => 0, 'entityCount' => 0));
		}

		$query_list_conditions[] = array('type' => 'int', 'field' => 'manager.departmentid', 'operator' => 'in', 'value' => $deparmentids);

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
		$ret = Data::queryList($query, $count_query, $page, $query_list_conditions, array('status' => array(Consts::$manager_status_texts),
				'role' => function($value) {
					$available_roles = PermissionService::all();
					$roles = array();
					foreach ($available_roles as $role_name => $role)
					{
						if (is_array($role) && array_key_exists('list', $role) && is_array($role['list']))
						{
							$roles += $role['list'];
						}
						else
						{
							$roles[$role_name] = $role;
						}
					}
					$vals = explode(',', $value);
					$val_text = '';
					foreach ($vals as $val)
					{
						if (array_key_exists($val, $roles))
						{
							$role_text = is_array($roles[$val]) ? $roles[$val][0] : $roles[$val];
							$val_text .= ($val ? $role_text : '') . ', ';
						}
					}
					return trim($val_text, ', ');
				},
				'departmentid' => function($value) use($tree_service) {
					$parents = $tree_service->get_parents($value);
					$parents = array_reverse($parents);
					$ret = array();
					foreach ($parents as $parent)
					{
						$ret[] = $parent['name'];
					}
					return implode(' > ', $ret);
				}
			));

		return Response::json($ret);
	}

	public function postEntity()
	{
		$eid = InputExt::getInt('eid');
		if ($eid > 0 && !ManagerService::check_customer($eid))
		{
			return;
		}

		$fields = array('departmentid', 'name', 'role', 'status');
		Common::empty_check(array('departmentid', 'name', 'role', 'status'));

		$available_roles = PermissionService::all();
		$roles = array();
		foreach ($available_roles as $role_name => $role)
		{
			if (is_array($role['list']))
			{
				$roles += $role['list'];
			}
			else
			{
				$roles[$role_name] = $role;
			}
		}
		$_POST['role'] = array_intersect(array_keys($roles), $_POST['role']);
		$_POST['role'] = implode(',', $_POST['role']);

		if (InputExt::get('password'))
		{
			$fields[] = 'password';
			$_POST['password'] = Hash::make($_POST['password']);
		}
		try
		{
			$departmentid = $this->manager->departmentid;
			$parentid = InputExt::getInt('departmentid');

			if(DepartmentService::check_departmentid($departmentid, $parentid))
			{
				Data::updateEntity('manager', array('managerid', '=', $eid), $fields);
			}
		}
		catch (\Exception $e)
		{
			$message = $e->getMessage();
			$name = '';
			if (strpos($message, 'un_name') !== false)
			{
				$code = 2; // 帐号名重复
				$name = 'name';
				$message = '该账号已经存在';
			}
			else
			{
				$code = 2; // 其他错误
				$message = '未知错误';
			}
			return Response::json(array('code' => $code, 'id' => $name, 'message' => $message));
		}
		return Response::json(array('code' => 1));
	}

	public function postGet()
	{
		$eid = InputExt::getInt('eid');

		if(!ManagerService::check_customer($eid))
		{
			return;
		}

		$entity = DB::table('manager')
			->select(array('manager.departmentid', 'manager.name', 'manager.role', 'manager.status'))
			->leftJoin('department', 'department.departmentid', '=', 'manager.departmentid')
			->where('managerid', '=', $eid)->first();
		return Response::json($entity);
	}

	public function postDelete()
	{
		$eid = InputExt::getInt('eid');

		if (!ManagerService::check_customer($eid))
		{
			return;
		}

		if (ManagerService::assigned($eid) > 0)
		{
			return;
		}

		$count = DB::table('userkey')->where('managerid', '=', $eid)->count();
		if ($count == 0) DB::table('manager')->where('managerid', '=', $eid)->delete();
	}

	public function postSelects()
	{
		DepartmentService::getChildDepartments($this->manager->departmentid, $departmentids);
		$select = DB::table('department')
			->select(array('department.departmentid'))
			->whereIn('department.parentid', $departmentids) //所有下级部门
			->orderBy('department.departmentid', 'desc')
			->get();
		foreach ($select as $key => $item)
		{
			$select[$key]->name = DepartmentService::getFullName($item->departmentid);
		}
		return Response::json(array($select));
	}

	public function postStatus()
	{
		$eid = InputExt::getInt('eid');
		DB::table('manager')->where('managerid', '=', $eid)->update(array('status' => DB::raw('abs(status - 2) + 1')));
	}
}

