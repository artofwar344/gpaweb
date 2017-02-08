<?php

class Database_Controller extends Base_Controller {
	public $layout = 'manage/layouts/common';

	public function action_index()
	{
		$this->layout->title = "数据库管理";
		$this->layout->body = new View('database/list');
	}

	public function action_list()
	{
		$name = InputExt::get('name');
		$page = InputExt::getInt('page');

		$query = DB::table('customer')
			->select(array('customer.customerid', 'customer.name', 'customer.alias',
				DB::raw('IFNULL((SELECT "1" FROM information_schema.schemata WHERE schema_name = (SELECT CONCAT("ca_", customer.alias))), "2") AS database_status'),
				'customer.status', 'customer.createdate'))
			->order_by('customerid', 'desc')
			->group_by('customer.customerid');

		$count_query = DB::table('customer')->select(array(DB::raw('COUNT(*) as count')));

		$ret = Data::queryList($query, $count_query, $page, array(
			array('type' => 'string', 'field' => 'customer.name', 'value' => $name),
			array('type' => 'int', 'field' => 'customer.adminerid', 'value' => Auth::get_current_user()->adminerid)
		), array('status' => array(Consts::$customer_status_texts), 'database_status' => array(Consts::$database_status_texts)));

		echo json_encode($ret);
	}

	public function action_entity()
	{
		$adminerid = $this->manager->id;
		$eid = InputExt::getInt('eid');
		$modify = $eid > 0;
		$_POST['adminerid'] = $adminerid;

		if ($modify && !CustomerService::check_adminer($eid))
		{
			return;
		}

		Common::empty_check(array('name', 'alias'));
		Data::updateEntity('customer', array('customerid', '=', $eid), array('adminerid', 'name', 'alias', 'status'));
	}

	public function action_get()
	{
		$eid = InputExt::getInt("eid");
		if (!CustomerService::check_adminer($eid))
		{
			return;
		}
		$entity = DB::table('customer')
			->select(array('name', 'alias', 'status'))
			->where('customerid', '=', $eid)->first();
		echo json_encode($entity);
	}

	public function action_delete()
	{
		$eid = InputExt::getInt("eid");

		if (CustomerService::check_adminer($eid))
		{
			DB::table('customer')->where('customerid', '=', $eid)->delete();
		}
	}
}

