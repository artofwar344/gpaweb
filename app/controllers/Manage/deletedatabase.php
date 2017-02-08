<?php

class  Deletedatabase_Controller extends Base_Controller {

	public function action_index()
	{
//
		$customer_id = InputExt::getInt('eid');
		$customer = DB::table('customer')->where('customerid', '=', $customer_id)->first();
		if ($customer == null)
		{
			exit;
		}
		$database_name = 'ca_' . $customer->alias;
		$sql = 'DROP DATABASE IF EXISTS ' . $database_name;
		DB::query($sql);

		return Response::json('删除数据库成功');
	}


}

