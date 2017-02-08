<?php
namespace Ca\Service;

use \DB,
	\Auth,
	Ca\UserKeyStatus;

class UserKeyService {
	public static function check_customer($userkeyid)
	{
		if ($userkeyid <= 0) return false;

		$count = DB::table('userkey')
			->leftJoin('user', 'user.userid', '=', 'userkey.userid')
			->leftJoin('department', 'department.departmentid', '=', 'user.departmentid')
			->where('userkeyid', '=', $userkeyid)
			->count();

		return $count > 0;
	}

	/**
	 * 成功申请密钥
	 * @param $user_id
	 * @param $product_id
	 * @param $request_count
	 * @param $reason
	 * @return mixed
	 */
	public static function add($user_id, $product_id, $request_count, $reason)
	{
		$data = array(
			'userid' => $user_id,
			'productid' => $product_id,
			'requestcount' => $request_count,
			'reason' => $reason,
			'status' => UserKeyStatus::pending
		);
		return DB::table('userkey')->insertGetId($data);
	}

	/**
	 * 申请的密钥记录
	 * @param $user_id
	 * @param $limit
	 * @return \Laravel\Paginator
	 */
	public static function get_request_by_userid($user_id, $limit)
	{
		return DB::table('userkey')
			->select(array('product.name as product_name', 'product.aliasname', 'userkey.productid', 'product.type as product_type', 'requestcount', 'assigncount', 'reason','userkey.status as userkey_status', 'requestdate', 'assigndate'))
			->leftJoin('product', 'product.productid', '=', 'userkey.productid')
			->where('userid', '=', $user_id)
			->orderBy('userkey.userkeyid', 'desc')
			->paginate($limit);
	}

}