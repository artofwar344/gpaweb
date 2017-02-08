<?php
namespace Manage;

use DB,
	View,
	Response,
	Input,
	InputExt,
	Ca\ProductStatus,
	Ca\Common,
	Ca\Data;

class KeyassignController extends BaseController {
	public $layout = 'manage/layouts/common';

	public function getIndex()
	{
		$customerid = InputExt::getInt('id');
		$customer = DB::table('customer')->where('customerid', '=', $customerid)->first();
		$this->layout->title = "密钥分配";
		$this->layout->body = View::make('manage/customer/keyassign')->with('customer', $customer)->with('customerid', $customerid);

	}

	public function postList()
	{
		$name = InputExt::get('name');
		$page = InputExt::getInt('page');
		$customerid = InputExt::getInt('id');
		$customer = DB::table('customer')->where('customerid', '=', $customerid)->first();
		if ($customer == null)
		{
			return;
		}
		$database = 'ca_' . $customer->alias;

		$query = DB::table($database . '.key')
			->select(array('key.*', 'product.name AS product_name', 'product.type AS product_type',
				DB::raw('SUM(IFNULL(department__key.count, 0)) as assign_count')))
			->leftJoin($database . '.product', 'product.productid', '=', 'key.productid')
			->leftJoin($database . '.department__key', 'department__key.keyid', '=', 'key.keyid')
			->orderBy('key.keyid', 'desc')
			->groupBy('key.keyid');

		$count_query = DB::table($database . '.key')->select(array(DB::raw('COUNT(*) as count')));

		$ret = Data::queryList($query, $count_query, $page, array(
			array('type' => 'string', 'field' => 'key.name', 'value' => $name)
		), array(),null, array(array('assign_count', '==', 0)));

		echo json_encode($ret);
	}

	public function postEntity()
	{
		$eid = InputExt::getInt('eid');
		$customerid = InputExt::getInt('customerid');
		$customer = DB::table('customer')->where('customerid', '=', $customerid)->first();
		if ($customer == null)
		{
			return;
		}
		$key = $_POST['key'];
		$section = explode('-', $key);
		$_POST['section'] = $section[0];
		$_POST['key'] = Common::encrypt_key("12345", $key);

		$database = 'ca_' . $customer->alias;
		Common::empty_check(array('productid', 'name', 'count', 'key','note'));
		$fields = array('productid', 'name', 'count', 'key', 'section', 'server', 'note');
		Data::updateEntity($database . '.key', array('keyid', '=', $eid), $fields);
	}

	public function postGet()
	{
		$eid = InputExt::getInt("eid");
		$customerid = InputExt::getInt('id');
		$customer = DB::table('customer')->where('customerid', '=', $customerid)->first();
		if ($customer == null)
		{
			return;
		}
		$database = 'ca_' . $customer->alias;
		$entity = DB::table($database . '.key')
			->where('keyid', '=', $eid)
			->first();
		if ($entity != null)
		{
			$entity->key = Common::decrypt_key('12345', $entity->key);
		}
		echo json_encode($entity);

	}


	public function postSelects()
	{
		$customerid = InputExt::getInt('id');
		$customer = DB::table('customer')->where('customerid', '=', $customerid)->first();
		if ($customer == null)
		{
			return;
		}
		$database = 'ca_' . $customer->alias;

		$select = DB::table($database . '.product')
			->select(array('productid', DB::raw('CONCAT(product.name, " [", product.type, "]") AS name')))
			->where('status', '=', ProductStatus::available)
			->get();
		return Response::json(array($select));
	}


	public function postDelete()
	{
		$eid = InputExt::getInt("eid");
		$customerid = InputExt::getInt('id');
		$customer = DB::table('customer')->where('customerid', '=', $customerid)->first();
		if ($customer == null)
		{
			return;
		}
		$database = 'ca_' . $customer->alias;
		$count = DB::table($database . '.department__key')->where('keyid', '=', $eid)->count();
		$subkeycount = DB::table($database . '.subkey')->where('keyid', '=', $eid)->whereNotNull('userid')->count();
		if ($count == 0 && $subkeycount == 0)
		{
			DB::table($database . '.subkey')->where('keyid', '=', $eid)->delete();
			DB::table($database . '.key')->where('keyid', '=', $eid)->delete();
		}
	}

	public function postNewadobe()
	{
//		var_dump($_REQUEST);
//		var_dump($_FILES);
		$customerid = InputExt::getInt("customerid");
		$customer = DB::table('customer')->where('customerid', '=', $customerid)->first();
		if ($customer == null)
		{
			return;
		}
		$database = 'ca_' . $customer->alias;
		$productid = InputExt::getInt("productid");
		$name = trim(Input::get("name"));
		$note = Input::get("note");
		$filetmp = $_FILES['importfile']['tmp_name'];
		if ($name == '' || empty($filetmp)) {
			return;
		}

		$fp = fopen($filetmp, 'r');
		$keys = array();
		while ($data = fgetcsv($fp))
		{
			if (!empty($data[0]))
			{
				$key = @iconv('gb2312', 'utf-8', trim($data[0]));
				$keys[] = $key;
			}
		}
		$keys = array_unique($keys);

		$data = array(
			'productid' => $productid,
			'name' => $name,
			'note' => $note,
			'count' => count($keys)
		);

		$keyid = DB::table($database . '.key')->insertGetId($data);
		foreach ($keys as $key)
		{
			$section = explode('-', $key);
			DB::table($database . '.subkey')->insert(array(
				'keyid' => $keyid,
				'key' => Common::encrypt_key("12345", $key),
				'section' => $section[0],
				'createdate' => DB::raw('now()')
			));
		}
		return Response::json(array('status' => 1, 'subkeycount' => count($keys)));
	}


	public function postKeyinfo()
	{
		$customerid = InputExt::getInt("customerid");
		$customer = DB::table('customer')->where('customerid', '=', $customerid)->first();
		if ($customer == null)
		{
			return;
		}
		$database = 'ca_' . $customer->alias;

		$eid = InputExt::getInt('eid');
		$key = DB::table($database . '.key')
			->select(array('key.*', 'product.type as type', DB::raw('concat(product.name, " - [ ", product.type, " ]") as product_name')))
			->leftJoin($database . '.product', 'key.productid', '=', 'product.productid')
			->where('keyid', '=', $eid)
			->first();
		if (!$key)
		{
			return;
		}
		$key->key = Common::decrypt_key("12345", $key->key);
		return Response::json(array('key' => $key));
	}

	public function postUpdateadobe()
	{
		$eid = InputExt::getInt('eid');
		$customerid = InputExt::getInt('customerid');
		$customer = DB::table('customer')->where('customerid', '=', $customerid)->first();
		if ($customer == null)
		{
			return;
		}
		$database = 'ca_' . $customer->alias;

		$name = Input::get('name');
		$note = Input::get('note');

		$data = array(
			'name' => $name,
			'note' => $note
		);
		DB::table($database . '.key')->where('keyid', '=', $eid)->update($data);
		return Response::json(array('status' => 1));
	}

	public function postUpdatemicrosoft()
	{
		$eid = InputExt::getInt('eid');
		$customerid = InputExt::getInt('customerid');
		$customer = DB::table('customer')->where('customerid', '=', $customerid)->first();
		if ($customer == null)
		{
			return;
		}
		$database = 'ca_' . $customer->alias;

		$name = Input::get('name');
		$note = Input::get('note');
		$key = Input::get('key');
		$server = Input::get('server');
		$count = InputExt::getInt('count');

		$section = explode('-', $key);
		$data = array(
			'name' => $name,
			'count' => $count,
			'key' => Common::encrypt_key("12345", $key),
			'section' => $section[0],
			'server' => $server,
			'note' => $note
		);
		DB::table($database . '.key')->where('keyid', '=', $eid)->update($data);
		return Response::json(array('status' => 1));
	}

}

