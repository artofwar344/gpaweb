<?php
namespace Manage;

use \DB,
	\View,
	\Input,
	Ca\Consts,
	Ca\Service,
	Ca\Data,
	\InputExt;

class CustomersettingController extends BaseController {
	public $layout = 'manage/layouts/common';

	public function getIndex()
	{
		$customerid = InputExt::getInt('id');
		$customer = DB::table('customer')->where('customerid', '=', $customerid)->first();
		$this->layout->title = "子级分类管理";
		$this->layout->body = View::make('manage/customer/setting')->with('customer', $customer)->with('customerid', $customerid);
	}


	public function postList()
	{
		$customerid = InputExt::getInt('id');
		$page = InputExt::getInt('page');

		$customer = DB::table('customer')->where('customerid', '=', $customerid)->first();
		if ($customer == null)
		{
			return;
		}
		$table = 'ca_' . $customer->alias . '.params';
		$query = DB::table('ca.params AS defaultparams')
			->select(array(DB::raw('CONCAT(defaultparams.key, "<br/>", defaultparams.intro) AS key_info'), 'defaultparams.key',
				DB::raw('IFNULL(customerparams.value,"") AS customervalue'), 'defaultparams.value AS defaultvalue'))
			->leftJoin($table . ' AS customerparams' , 'customerparams.key' , '=', 'defaultparams.key');
		$count_query = DB::table('ca.params')->select(array(DB::raw('COUNT(*) AS count')));
		$ret = Data::queryList($query, $count_query, $page, array());
		//将autoassignkeys转为 “产品名: 数量“ 的格式
		foreach($ret['list'] as $index => $value)
		{
			if ($value['key'] == 'autoassignkeys')
			{
				if ($value['customervalue'] != '')
				{
					$autoassignkeys = json_decode($value['customervalue']);
					$autoassignkeys_str = '';
					foreach ($autoassignkeys as $autoassignkey)
					{
						$key = DB::table('ca_' . $customer->alias . '.key')
							->where('keyid', '=', $autoassignkey->keyid)
							->first();
						if($key)
						{
							$autoassignkeys_str .= $key->name .
								' [' . Consts::$user_type_text[$autoassignkey->type]  . ']: ' . $autoassignkey->amount . '<br/>';
						}
					}
					$ret['list'][$index]['customervalue'] = $autoassignkeys_str;
				}
			}
		}
		echo json_encode($ret);
	}


	public function postGet()
	{
		echo json_encode(array());
	}

	public function postEntity()
	{
		$eid = Input::get('eid');
		$value = Input::get($eid);
		$customerid = InputExt::getInt('customerid');
		$customer = DB::table('customer')->where('customerid', '=', $customerid)->first();
		if ($customer == null)
		{
			return;
		}
		$table = 'ca_' . $customer->alias . '.params';
		$autoassignkeys = array();
		if ($eid == 'autoassignkeys')
		{
			$auto_keyid = Input::get('auto_keyid');
			$auto_type = Input::get('auto_type');
			$auto_amount = Input::get('auto_amount');
			if (is_array($auto_keyid))
			{
				foreach ($auto_keyid as $index => $keyid)
				{
					$amount = intval($auto_amount[$index]);
					$type = intval($auto_type[$index]);
					if ($amount > 0)
					{
						$autoassignkeys[] = array('keyid' => $keyid, 'type'=>$type, 'amount' => $amount);
					}
				}
				$value = json_encode($autoassignkeys);
			}
		}
		DB::table($table)->where('key', '=', $eid)->delete();
		DB::table($table)->insert(array(
			'key' => $eid,
			'value' => $value,
		));
	}

	public function postGetvalue()
	{
		$customerid = InputExt::getInt('customerid');
		$key = Input::get('key');

		$customer = DB::table('customer')->where('customerid', '=', $customerid)->first();
		if ($customer == null)
		{
			return;
		}
		$database = 'ca_' . $customer->alias;
		$params = DB::table($database . '.params')->where('key', '=', $key)->first();
		if ($key == 'autoassignkeys')
		{
			$availablekeys = DB::table($database . '.key')
				->select(array('key.keyid', 'key.name', DB::raw('CONCAT(product.name, " [", product.type, "]") AS product_name')))
				->leftJoin($database . '.product', 'product.productid', '=', 'key.productid')
				->get();

			$keydata = array();
			$usertype = Consts::$user_type_text;
			foreach ($availablekeys as $availablekey)
			{
				foreach($usertype as $type_value => $type_text)
				{
					$keydata[] = array(
						'keyid' => $availablekey->keyid,
						'name' => $availablekey->name,
						'type_value' => $type_value,
						'type_text' => $type_text,
						'product_name' => $availablekey->product_name,
						'amount' => '',
					);
				}
			}

			if ($params != null)
			{
				$autoassignkeys = json_decode($params->value);
				if (is_array($autoassignkeys))
				{
					foreach ($autoassignkeys as $value)
					{
						foreach ($keydata as $index => $data)
						{
							if ($data['keyid'] == $value->keyid && $data['type_value'] == $value->type )
							{
								$keydata[$index]['amount'] = $value->amount;
							}
						}
					}
				}
				$params->value = json_encode($keydata);
			}
			else
			{
				$params = array('key' => $key, 'value' => json_encode($keydata));
			}

		}
		else
		{
			if ($params == null)
			{
				$params = array('key' => $key, 'value' => '');
			}
		}
		echo json_encode($params);
	}

	public function postDefaultparams()
	{
		$key = Input::get('eid');
		$customerid = InputExt::getInt('id');
		$customer = DB::table('customer')->where('customerid', '=', $customerid)->first();
		if ($customer == null)
		{
			return;
		}

		$defaultparams = DB::table('ca.params')->where('key', '=', $key)->first();
		if ($defaultparams == null)
		{
			return;
		}
		$table = 'ca_' . $customer->alias . '.params';

		DB::table($table)->where('key', '=', $key)->delete();
		DB::table($table)->insert(array(
			'key' => $key,
			'value' => $defaultparams->value,
		));
	}



}

