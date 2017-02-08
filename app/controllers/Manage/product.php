<?php

class Product_Controller extends Base_Controller {
	public $layout = 'manage/layouts/common';

	public function action_index()
	{
		$this->layout->title = "商品管理";
		$this->layout->body = View::make('product/list');
	}

	public function action_list()
	{
		$page = InputExt::getInt('page');
		$name = InputExt::get('name');

		$query = DB::table('product')
			->select(array('product.productid', 'product.name', 'product.intro', 'product.type', 'status'))
			->order_by('productid', 'desc');

		$count_query = DB::table('product')->select(array(DB::raw('COUNT(*) as count')));

		$ret = Data::queryList($query, $count_query, $page, array(
			array('type' => 'string', 'field' => 'product.name', 'value' => $name),
			array('type' => 'int', 'field' => 'product.status', 'operator' => '!=', 'value' => 3)
		), array('status' => array(Consts::$product_status)));

		echo json_encode($ret);
	}

	public function action_entity()
	{
		$eid = InputExt::getInt('eid');
		Common::empty_check(array('status'));
		Data::updateEntity('product', array('productid', '=', $eid), array('status'));
	}

	public function action_get()
	{
		$eid = InputExt::getInt("eid");
		$entity = DB::table('product')
			->select(array('name', 'productid', 'intro'))
			->where('productid', '=', $eid)->first();
		echo json_encode($entity);
	}

	public function action_delete()
	{
		$eid = InputExt::getInt("eid");
		DB::table('product')->where('productid', '=', $eid)->delete();
	}

	public function action_status()
	{
		$eid = InputExt::getInt('eid');
		DB::table('product')->where('productid', '=', $eid)->update(array('status' => DB::raw('abs(status - 2) + 1')));
	}
}

