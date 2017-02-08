<?php
namespace Ca\Service;
use \DB,
	Ca\Common,
	Ca\KeyUsageStatus;
class KeyUsageService {
	public static function key_inuse($key_id)
	{
		if ($key_id == 0) return false;

		$entity = DB::table('userkey')
			->select(array(DB::raw('COUNT(keyid) as count')))
			->where('keyid', '=', $key_id)
			->first();
		return $entity->count > 0;
	}

	public static function check_customer($keyusage_id)
	{
		if ($keyusage_id <= 0) return false;

		$count = DB::table('keyusage')
			->leftJoin('key', 'key.keyid', '=', 'keyusage.keyid')
			->leftJoin('department', 'department.departmentid', '=', 'key.departmentid')
			->where('usageid', '=', $keyusage_id)
			->count();

		return $count > 0;
	}

	public static function get_usage_keys_by_userid($user_id, $limit)
	{
		return DB::table('keyusage')
			->select(array('product.name as product_name', 'product.aliasname', 'product.productid', 'product.type as product_type', 'keyusage.status as keyusage_status',
				'ip', 'computerid', 'errorcode', 'begindate', 'enddate'))
			->leftJoin('key', 'key.keyid', '=', 'keyusage.keyid')
			->leftJoin('product', 'key.productid', '=', 'product.productid')
			->where('keyusage.userid', '=', $user_id)
			->orderBy('keyusage.usageid', 'desc')
			->paginate($limit);
	}

	public static function get_key($user_id, $product_id)
	{
		$user_keys = DB::table('userkey')
			->select(array(DB::raw('SUM(assigncount) as sum'), 'keyid'))
			->where('userid', '=', $user_id)
			->where('productid', '=', $product_id)
			->groupBy('keyid')
			->get();
		$key_ids = array();
		foreach ($user_keys as $user_key)
		{
			$key_ids[] = $user_key->keyid;
		}
		if (empty($key_ids))
		{
			return null;
		}
		$used_keys = DB::table('keyusage')
			->select(array(DB::raw('COUNT(*) as used'), 'keyid'))
			->where('userid', '=', $user_id)
			->whereIn('keyid', $key_ids)
			->whereNotIn('status', array(KeyUsageStatus::activation_failed, KeyUsageStatus::activation_reset))
			->groupBy('keyid')
			->get();
		if (empty($used_keys))
		{
			return $key_ids[0];
		}
		foreach ($user_keys as $user_key)
		{
			foreach ($used_keys as $used_key)
			{
				if ($user_key->keyid == $used_key->keyid && $user_key->sum > $used_key->used)
				{
					return $user_key->keyid;
				}
			}
		}
		return null;
	}
}