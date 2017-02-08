<?php
namespace CustomerManage;

use View,
	DB,
	Input,
	InputExt,
	Response,
	\Ca\Consts,
	\Ca\Common,
	\Ca\Data,
	\Exception;

class ProductpermissionController extends BaseController {
	public $layout = 'customermanage/layouts/common';

	public function getIndex()
	{
		$this->layout->title = "商品权限管理";
		$this->layout->body = View::make('customermanage/productpermission/list');
	}

	public function postList()
	{
		$page = InputExt::getInt('page');
		$type = InputExt::getInt('type');
		$productid = InputExt::getInt('productid');

		$query = DB::table('productpermission')
			->leftJoin('product', 'product.productid', '=', 'productpermission.productid')
			->select(array('productpermission.*', DB::raw('CONCAT(product.name, " [", product.type, "]") as product_name')))
			->orderBy('productpermission.type');

		$count_query = DB::table('productpermission')
			->leftJoin('product', 'product.productid', '=', 'productpermission.productid')
			->select(array(DB::raw('COUNT(*) as count')));

		$condition = array(
			array('type' => 'int', 'field' => 'product.status', 'value' => 1),
			array('type' => 'int', 'field' => 'productpermission.type', 'value' => $type),
			array('type' => 'int', 'field' => 'productpermission.productid', 'value' => $productid)
		);
		$text_fileds = array(
			'type' => array(Consts::$user_type_text),
		);
		$ret = Data::queryList($query, $count_query, $page, $condition, $text_fileds);
		echo json_encode($ret);
	}

	public function postEntity()
	{
		$eid = InputExt::getInt('eid');

		try
		{
			Data::updateEntity('productpermission', array('permissionid', '=', $eid), array('productid', 'type'));
		}
		catch(Exception $e)
		{
			$message = $e->getMessage();
			$name = '';
			if (strpos($message, 'un_productid_type') !== false)
			{
				$code = 2;
				$name = 'productid';
				$message = '该商品已选择';
			}
			else
			{
				$code = 2; // 其他错误
				$message = '未知错误';
			}
			return Response::json(array('code' => $code, 'id' => $name, 'message' => $message));
		}
	}

	public function postGet()
	{
		$eid = InputExt::getInt('eid');
		$entity = DB::table('productpermission')->where('permissionid', '=', $eid)->first();
		return Response::json($entity);
	}

	public function postSelects()
	{
		$select_1 = DB::table('product')
			->select(array('productid', DB::raw('CONCAT(product.name, " [", product.type, "]") as name')))
			->where('status', '=', 1)
			->get();
		$select_2 = array();
		foreach (Consts::$user_type_text as $key => $type)
		{
			$select_2[] = array(
				'type' => $key,
				'name' => $type
			);
		}
		echo json_encode(array($select_1, $select_2));
	}

	public function postDelete()
	{
		$eid = InputExt::getInt("eid");
		DB::table('productpermission')->where('permissionid', '=', $eid)->delete();
	}


}

