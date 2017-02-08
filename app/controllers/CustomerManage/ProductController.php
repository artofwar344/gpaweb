<?php
namespace CustomerManage;

use View,
	DB,
	InputExt,
	Ca\Consts,
	Ca\Common,
	Ca\Data;

class ProductController extends BaseController {
	public $layout = 'customermanage/layouts/common';

	public function getIndex()
	{
		$this->layout->title = "商品管理";
		$this->layout->body = View::make('customermanage/product/list');
	}

	public function postList()
	{
		$page = InputExt::getInt('page');
		$name = InputExt::get('name');

		$query = DB::table('product')
			->select(array('product.productid', 'product.name', 'product.intro', 'product.type', 'status'))
			->orderBy('productid', 'desc');

		$count_query = DB::table('product')->select(array(DB::raw('COUNT(*) as count')));

		$ret = Data::queryList($query, $count_query, $page, array(
			array('type' => 'string', 'field' => 'product.name', 'value' => $name),
			array('type' => 'int', 'field' => 'product.status', 'operator' => '!=', 'value' => 3)
		), array('status' => array(Consts::$product_status)));

		echo json_encode($ret);
	}

	public function postEntity()
	{
		$eid = InputExt::getInt('eid');
		Common::empty_check(array('status'));
		Data::updateEntity('product', array('productid', '=', $eid), array('status'));
	}

	public function postGet()
	{
		$eid = InputExt::getInt("eid");
		$entity = DB::table('product')
			->select(array('name', 'productid', 'intro'))
			->where('productid', '=', $eid)->first();
		echo json_encode($entity);
	}

	public function postDelete()
	{
		$eid = InputExt::getInt("eid");
		DB::table('product')->where('productid', '=', $eid)->delete();
	}

	public function postStatus()
	{
		$eid = InputExt::getInt('eid');
		DB::table('product')->where('productid', '=', $eid)->update(array('status' => DB::raw('abs(status - 2) + 1')));
	}
}

