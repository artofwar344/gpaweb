<?php
class Manager_Controller extends Base_Controller {
	public $layout = 'manage/layouts/common';

	public function action_index()
	{
		$this->layout->title = "管理员管理";
		$this->layout->body = View::make('manager/list');
	}

	public function action_list()
	{
		$page = InputExt::getInt('page');
		$name = InputExt::get('name');
		$departmentid = InputExt::getInt('departmentid');
		$status = InputExt::getInt('status');
		$role = InputExt::get('role');

		$query = DB::table('manager')
			->select(array('manager.managerid', 'customer.name as customer_name', 'manager.name as name',
				'department.name as department_name', 'role', 'manager.status as status', 'manager.createdate'))
			->order_by('managerid', 'desc')
			->left_join('department', 'manager.departmentid', '=', 'department.departmentid')
			->left_join('customer', 'customer.customerid', '=', 'department.customerid');

		$count_query = DB::table('manager')
			->select(array(DB::raw('COUNT(*) as count')))
			->left_join('department', 'manager.departmentid', '=', 'department.departmentid');

		$ret = Data::queryList($query, $count_query, $page, array(
			array('type' => 'int', 'field' => 'department.parentid', 'operator' => 'IS', 'value' => DB::raw('null')),
			array('type' => 'string', 'field' => 'manager.name', 'value' => $name),
			array('type' => 'int', 'field' => 'manager.departmentid', 'value' => $departmentid),
			array('type' => 'int', 'field' => 'manager.status', 'value' => $status),
			array('type' => 'string', 'field' => 'manager.role', 'value' => $role),
		), array('status' => array(Consts::$manager_status_texts), 'role' => array(Consts::$manager_role_texts, 'array')));

		echo json_encode($ret);
	}

	public function action_entity()
	{
		$eid = InputExt::getInt('eid');
		$modify = $eid > 0;
		if ($modify && !ManagerService::check_adminer($eid))
		{
			return;
		}
		Common::empty_check(array('name', 'role'));

		$fields = array('departmentid', 'name', 'role', 'status');
		$_POST['role'] = implode(',', $_POST['role']);
		if (InputExt::get('password'))
		{
			$fields[] = 'password';
			$_POST['password'] = Hash::make($_POST['password']);
		}
		Data::updateEntity('manager', array('managerid', '=', $eid), $fields);
	}

	public function action_get()
	{
		$eid = InputExt::getInt('eid');
		if (!ManagerService::check_adminer($eid))
		{
			return;
		}
		$entity = DB::table('manager')
			->select(array('departmentid', 'name', 'role', 'status'))
			->where('managerid', '=', $eid)->first();
		echo json_encode($entity);
	}

	public function action_delete()
	{
		$eid = InputExt::getInt('eid');
		if (ManagerService::check_adminer($eid))
		{
			DB::table('manager')->where('managerid', '=', $eid)->delete();
		}
	}

	public function action_selects()
	{
		$select = DB::table('department')
			->select(array('departmentid', 'name'))
			->where('department.parentid', 'IS', DB::raw('null'))
			->order_by('departmentid', 'desc')
			->get();
		echo json_encode(array($select));
	}
}

