<?php
namespace CustomerManage;

use DB,
	View,
	Response,
	InputExt,
	Ca\Data;

class SubkeyassignController extends BaseController {
	public $layout = 'customermanage/layouts/common';

	public function getIndex()
	{
		$this->layout->title = "密钥分配情况";
		$keyid = InputExt::getInt('keyid');
		$this->layout->body = View::make('customermanage/key/subkeyassign')
			->with('keyid', $keyid);
	}

	public function postList()
	{
//		var_dump($_POST);
		$page = InputExt::getInt('page');
		$name = InputExt::get('name');
		$username = InputExt::get('username');
		$keyid = InputExt::getInt('keyid');
//		$status = InputExt::getInt('status');
//		$productid = InputExt::getInt('productid');
		$departmentid = $this->manager->departmentid;

		$query = DB::table('subkey')
			->select(array('subkey.subkeyid', 'key.name as key_name' ,DB::raw('CONCAT(user.name, " - [", username , "]") as user_name'),
				'product.name as product_name', 'product.type', 'subkey.section', 'key.note', 'outdate'))
			->leftJoin('key', 'key.keyid', '=', 'subkey.keyid')
			->leftJoin('user', 'subkey.userid', '=', 'user.userid')
			->leftJoin('product', 'key.productid', '=', 'product.productid')
			->leftJoin('department__key', 'department__key.keyid', '=', 'key.keyid')
			->whereNotNull('outdate');
		$count_query = DB::table('subkey')->select(array(DB::raw('COUNT(*) as count')))
			->leftJoin('user', 'subkey.userid', '=', 'user.userid')
			->leftJoin('key', 'key.keyid', '=', 'subkey.keyid')
			->leftJoin('department__key', 'department__key.keyid', '=', 'key.keyid')
			->whereNotNull('outdate');

		$query_list_conditions = array(
			array('type' => 'string', 'field' => 'user.name', 'value' => $name),
			array('type' => 'string', 'field' => 'user.username', 'value' => $username),
			array('type' => 'int', 'field' => 'subkey.keyid', 'value' => $keyid)
		);
		if (!$this->manager->top)
		{
			$query_list_conditions[] = 	array('type' => 'int', 'field' => 'department__key.departmentid', 'value' => $departmentid);
		}
		$ret = Data::queryList($query, $count_query, $page, $query_list_conditions);

		return Response::json($ret);
	}





}