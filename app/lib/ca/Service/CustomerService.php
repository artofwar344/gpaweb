<?php
namespace Ca\Service;

use \DB,
	Auth,
	Ca\Service\ManagerService,
	Ca\Common;
class CustomerService {
	public static function checkAdminer($customerid)
	{
		if ($customerid <= 0 || !Auth::user()->adminerid) return false;

		$count = DB::table('ca.customer')
			->where('customerid', '=', $customerid)
			->where('adminerid', '=', Auth::user()->adminerid)
			->count();

		return $count > 0;
	}

	public static function getCustomeridByAlias($alias)
	{
		return DB::table('ca.customer')->where('alias', '=', $alias)->pluck('customerid');
	}
}