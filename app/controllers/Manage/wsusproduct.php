<?php

class Wsusproduct_Controller extends Base_Controller {
	public $layout = 'manage/layouts/common';

	public function action_index()
	{
		$this->layout->title = "WSUS产品管理";
		$this->layout->body = View::make('wsus/product');
	}

	public function action_list()
	{
		$page = InputExt::get('page');
		$name = InputExt::get('name');

		$query = DB::table('wsusproduct')
			->select(array('wsusproduct.productid', 'wsusproduct.name', DB::raw('COUNT(wsus__wsusproduct.productid) as count')))
			->left_join('wsus__wsusproduct', 'wsusproduct.productid', '=', 'wsus__wsusproduct.productid')
			->group_by('wsusproduct.productid')
			->order_by('productid', 'desc');

		$count_query = DB::table('wsusproduct')->select(array(DB::raw('COUNT(*) as count')));

		$ret = Data::queryList($query, $count_query, $page, array(
			array('type' => 'string', 'field' => 'name', 'value' => $name)
		));

		echo json_encode($ret);
	}

	public function action_entity()
	{
		$eid = InputExt::getInt('eid');
		Data::updateEntity('wsusproduct', array('productid', '=', $eid), array('name'));
	}

	public function action_get()
	{
		$eid = InputExt::getInt('eid');
		$entity = DB::table('wsusproduct')
			->select(array('name'))
			->where('productid', '=', $eid)->first();
		echo json_encode($entity);
	}

	public function action_delete()
	{
		$eid = InputExt::getInt('eid');
		DB::table('wsusproduct')->where('productid', '=', $eid)->delete();
	}
}

