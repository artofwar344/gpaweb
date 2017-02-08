<?php
class Department_Controller extends Base_Controller {
	public $layout = 'manage/layouts/common';

	public function action_index()
	{
		$this->layout->title = "分级管理";
		$this->layout->body = View::make("department/list");
	}

	public function action_list()
	{
		$name = InputExt::get('name');
		$customerid = InputExt::getInt('customerid');
		$page = InputExt::getInt('page');

		$query = DB::table('department')
			->select(array('department.departmentid', 'department.name as name', 'customer.name as customer_name',
				DB::raw('COUNT(managerid) as count'), 'department.createdate'))
			->order_by('departmentid', 'desc')
			->left_join('customer', 'customer.customerid', '=', 'department.customerid')
			->left_join('manager', 'department.departmentid', '=', 'manager.departmentid')
			->group_by('department.departmentid');

		$count_query = DB::table('department')->select(array(DB::raw('COUNT(*) as count')));

		$ret = Data::queryList($query, $count_query, $page, array(
			array('type' => 'int', 'field' => 'department.customerid', 'value' => $customerid),
			array('type' => 'string', 'field' => 'department.name', 'value' => $name)
		), null, null, array(array('count', '==', '0')));

		echo json_encode($ret);
	}

	public function action_entity()
	{
		$eid = InputExt::getInt("eid");
		$modify = $eid > 0;

		if ($modify && !DepartmentService::check_adminer($eid))
		{
			return;
		}
		Common::empty_check(array('name'));
		Data::updateEntity('department', array('departmentid', '=', $eid), array('name', 'customerid'));
	}

	public function action_get()
	{
		$eid = InputExt::getInt("eid");

		if (!DepartmentService::check_adminer($eid))
		{
			return;
		}
		$entity = DB::table('department')
			->select(array('name', 'customerid'))->where('departmentid', '=', $eid)->first();
		echo json_encode($entity);
	}

	public function action_delete()
	{
		$eid = InputExt::getInt("eid");

		if (DepartmentService::check_adminer($eid))
		{
			DB::table('department')->where('departmentid', '=', $eid)->delete();
		}
	}

	public function action_selects()
	{
		$select = DB::table('customer')
			->select(array('customerid', 'name'))
			->where('adminerid', '=', Auth::get_current_user()->adminerid)
			->order_by('customerid', 'desc')
			->get();
		echo json_encode(array($select));
	}
}

