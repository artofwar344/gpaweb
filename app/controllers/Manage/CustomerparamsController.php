<?php
namespace Manage;
use Illuminate\Support\Facades\DB,
	Illuminate\Support\Facades\View,
	Illuminate\Support\Facades\Input,
	Illuminate\Support\Facades\Hash,
	Ca\Common,
	Ca\Consts,
	Ca\Service,
	Ca\Data,
	\InputExt;
class CustomerparamsController extends BaseController {
	public $layout = 'manage/layouts/common';

	public function action_index()
	{
		$this->layout->title = "模块管理";
		$this->layout->body = new View('customerparams/list');
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
			), array('status' => array(Consts::$customer_status_texts), 'database_status' => array(Consts::$database_status_texts)),
			array(array('database_status', '==', '1'))
		);
		echo json_encode($ret);
	}

	public function action_get()
	{
		$eid = InputExt::getInt('eid');
		$customer = DB::table('customer')
//			->select(array('name', 'alias'))
			->where('customerid', '=', $eid)->first();
		if ($customer == null)
		{
			return;
		}
		$databasename = 'ca_' . $customer->alias;
		$table_exists = DB::table(DB::raw('INFORMATION_SCHEMA.TABLES'))
			->select('TABLE_NAME')
			->where('TABLE_SCHEMA', '=', $databasename)
			->where('TABLE_NAME', '=', 'params')
			->count() > 0;
		if (!$table_exists)
		{
			return;
		}
		$sql = 'SELECT * FROM ' . $databasename . '.params';
		$params = DB::query($sql);
		$entity = array('eid' => $eid , 'name' => $customer->name);
		foreach($params as $value)
		{
			$entity[$value->key] = $value->value;
		}

		$entity['_disable_fields'] = array('name');
		echo json_encode($entity);
	}

	public function action_entity()
	{
		$eid = InputExt::getInt('eid');
		$customer = DB::table('customer')
			->select(array('alias'))
			->where('customerid', '=', $eid)->first();
		if ($customer == null)
		{
			return;
		}
		$databasename = 'ca_' . $customer->alias;
		$table_exists = DB::table(DB::raw('INFORMATION_SCHEMA.TABLES'))
			->select('TABLE_NAME')
			->where('TABLE_SCHEMA', '=', $databasename)
			->where('TABLE_NAME', '=', 'params')
			->count() > 0;
		if (!$table_exists)
		{
			return;
		}
		$table = 'ca_' . $customer->alias . '.params';
		$autoassignkeys = array();
		$data = array();
		foreach ($_POST as $key => $value)
		{
			if ($key == 'autoassignkeys')
			{
				foreach ($value as $keyid => $amount)
				{
					$amount = intval($amount);
					if ($amount > 0)
					{
						$autoassignkeys[] = array('keyid' => $keyid, 'amount' => $amount);
					}
				}
			}
			elseif ($key != 'eid')
			{
				$data[$key] = $value;
			}
		}
		$data['autoassignkeys'] = json_encode($autoassignkeys);
		$query = DB::table($table)->select('key')->get();
		$params_keys = array();
		foreach ($query as $val)
		{
			$params_keys[$val->key] = true;
		}
		foreach ($data as $key => $value)
		{
			if (array_key_exists($key, $params_keys))
			{
				DB::table($table)->where('key', '=', $key)->update(array('value' => $value));
			}
			else
			{
				DB::table($table)->insert(array('key' => $key, 'value' => $value));
			}
		}
	}

	public function action_keys()
	{
		$eid = InputExt::getInt('eid');
		$customer = DB::table('customer')->where('customerid', '=', $eid)->first();
		if ($customer == null)
		{
			return;
		}
		$databasename = 'ca_' . $customer->alias;
		$keys = DB::table($databasename . '.key')->select(array('keyid','name'))->get();
		$params = DB::table($databasename . '.params')->where('key', '=', 'autoassignkeys')->first();
		$autoassignkeys = null;
		if ($params != null)
		{
			$autoassignkeys = $params->value;
		}
		echo json_encode(array('keys' => $keys, 'autoassignkeys' => $autoassignkeys));
	}

}

