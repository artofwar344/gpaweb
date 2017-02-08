<?php
namespace Ca\Service;

use DB,
	Ca\Consts;

class PermissionService {
	static $permissions = array();

	public static function all($customer = null)
	{
		if (self::$permissions == array())
		{
			$table = $customer == null ? 'permission' : 'ca_' . $customer . '.permission';
			$rows = DB::table($table)->get();
			foreach ($rows as $row)
			{
				if (!array_key_exists($row->group, self::$permissions))
				{
					self::$permissions[$row->group]['name'] = Consts::$permission_group_texts[$row->group];
				}
				self::$permissions[$row->group]['list']['[' . $row->name . ']'] = $row->intro;
			}
		}
		return self::$permissions;
	}
}
