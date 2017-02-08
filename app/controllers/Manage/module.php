<?php

class Module_Controller extends Base_Controller {
	public $layout = 'manage/layouts/common';

	public function action_index()
	{
		$this->layout->title = "模块管理";
		$this->layout->body = new View('module/list');
	}

	public function action_list()
	{
		$name = InputExt::get('name');
		$page = InputExt::getInt('page');

		$query = DB::table('customer')
			->select(array('customer.customerid', 'customer.name', 'customer.alias',
				'customer.status', 'customer.module'))
			->order_by('customerid', 'desc')
			->group_by('customer.customerid');

		$count_query = DB::table('customer')->select(array(DB::raw('COUNT(*) as count')));

		$ret = Data::queryList($query, $count_query, $page, array(
			array('type' => 'string', 'field' => 'customer.name', 'value' => $name),
			array('type' => 'int', 'field' => 'customer.adminerid', 'value' => Auth::get_current_user()->adminerid)
		), array('status' => array(Consts::$customer_status_texts),'module' => array(Consts::$module_texts, 'array')));

		echo json_encode($ret);
	}

	public function action_get()
	{
		$eid = InputExt::getInt('eid');
		$entity = DB::table('customer')
			->select(array('name', 'module'))
			->where('customerid', '=', $eid)->first();
		$entity->_disable_fields = array('name');
		echo json_encode($entity);
	}

	public function action_entity()
	{
		$eid = InputExt::getInt('eid');
		$fields = array('module');
		$_POST['module'] = isset($_POST['module']) ? implode(',', $_POST['module']) : '';

		Data::updateEntity('customer', array('customerid', '=', $eid), $fields);
	}

}

