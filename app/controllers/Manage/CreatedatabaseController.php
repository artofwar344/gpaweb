<?php
namespace Manage;

use DB,
	View,
	Input,
	Config,
	Response,
	PDO,
	Ca\Service,
	InputExt;

class CreatedatabaseController extends BaseController {

	public $layout = null;

	public function postIndex()
	{
		$customer_id = InputExt::getInt('eid');
		$customer = DB::table('customer')->where('customerid', '=', $customer_id)->first();
		if ($customer == null)
		{
			exit;
		}
		$database_name = 'ca_' . $customer->alias;
		if (DB::table('information_schema.schemata')->where('schema_name', '=', $database_name)->count() > 0)
		{
			return Response::json('数据库已存在');
		}
		$sql = 'CREATE DATABASE ' . $database_name;
		DB::getPdo()->query($sql);
		$databse_config = Config::get('database.connections.mysql');
		$pdo = new PDO(
			'mysql:host='. $databse_config['host'] . ';dbname=' . $database_name,
			$databse_config['username'],
			$databse_config['password']
		);


		$sql = file_get_contents(app_path() . '/sql/database_ca.sql');
		$sql = explode('/*split*/', $sql);
		foreach($sql as $value)
		{
			$pdo->exec(trim($value));
		}
		if ($customer->module)
		{
			$modules = explode(',', $customer->module);
			if (in_array(1, $modules))
			{
				$sql = file_get_contents(app_path() . '/sql/database_ca_share.sql');
				$sql = explode('/*split*/', $sql);
				foreach($sql as $value)
				{
					$pdo->exec(trim($value));
				}
			}
			if (in_array(2, $modules))
			{
				$sql = file_get_contents(app_path() . '/sql/database_ca_soft.sql');
				$sql = explode('/*split*/', $sql);
				foreach($sql as $value)
				{
					$pdo->exec(trim($value));
				}
			}
		}
		return Response::json('创建数据库成功');
	}
}

