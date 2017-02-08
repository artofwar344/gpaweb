<?php
namespace Ca\Service;

use \DB,
	\DBExt,
	\Auth,
	\Ca\Common,
	\Ca\UserKeyStatus;

class KeyService {
	/**
	 * 计算剩余可分配次数
	 * @param $status
	 * @param $key_id
	 * @param $assigncount
	 * @param null $department_id 如果没有指定某个部门, 就按照当前管理员帐号所在部门计算
	 * @return int
	 */
	public static function check_remain($status, $key_id, $assigncount, $department_id = null)
	{
//		$total = 0;
//		$assigned = 0;
//		$department_assigned = 0;
//		$top = 0;
		try
		{
			$manager = Auth::user();
		}
		catch(\Exception $e) {}

		if ($department_id == null)
		{
			if (!empty($manager))
			{
				$department_id = $manager->departmentid;
			}
		}
		if (empty($department_id))
		{
			return -1;
		}

		if ($status == UserKeyStatus::agree)
		{
			$remains = KeyService::check_remains($department_id, $key_id);
			foreach ($remains as $remain)
			{
				if ($remain->departmentid == $department_id && $remain->keyid == $key_id)
				{
					return $remain->remain - $assigncount;
				}
			}
			//$remain = $total - $department_assigned - $assigned - $assigncount;
		}
		return 0;
	}

	/**
	 * 查询所有部门里的所有剩余激活次数
	 */
	public static  function check_remains($department_id = null, $key_id = null)
	{
		if ($department_id != null)
		{
			$department = DB::table('department')
				->where('departmentid', '=', $department_id)
				->first();
		}

		if (empty($department))
		{
			return array();
		}

		// 所有部门分配给下级部门的激活次数
		$assignQuery = DB::table('department__key')
			->select(array(DB::raw('SUM(department__key.count) as count'), 'keyid', 'department.parentid as departmentid'))
			->leftJoin('department', 'department.departmentid', '=', 'department__key.departmentid')
			->where('department__key.status', '=', DB::raw('1'))
			->groupBy('keyid')
			->groupBy('department.parentid');

		// top manager
		if ($department_id == null || $department->parentid == null)
		{
			$did = $department->departmentid;

			// 激活码数量
			$keycodeQuery = DB::table('exchangecode')
				->select(array('exchangecode.keyid', DB::raw('COUNT(*) AS count')))
				->groupBy('exchangecode.keyid');

			$query = DB::table('key')
				->select(array(DB::raw("'{$did}' as departmentid"), 'key.keyid', 'key.productid', 'section', 'product.name as product_name', 'product.type', 'server', 'note', 'key.createdate',
					DB::raw('CONCAT(key.name, " - [", product.type, "]", IF(key.section IS NULL, "", CONCAT(" - [", key.section, "]"))) as name'),
					DB::raw('key.count - IFNULL(keyassign.count, 0) - IFNULL(keycode.count, 0) as count'),
					DB::raw('key.count as total'),
				))
				->leftJoin(DB::raw("({$assignQuery->toSql()}) AS keyassign"), function($join) use($did) {
					$join->on('keyassign.keyid', '=', 'key.keyid');
					$join->on('keyassign.departmentid', '=', DB::raw($did));
				})
				->leftJoin(DB::raw("({$keycodeQuery->toSql()}) AS keycode"), 'keycode.keyid', '=', 'key.keyid')
				->leftJoin('product', 'product.productid', '=', 'key.productid')
				->orderBy('keyid', 'desc');
			if (!is_null($key_id))
			{
				$query->where('key.keyid', '=', DB::raw($key_id));
			}
		}
		else
		{
			//所有部门的用户分配激活次数
			$userkeyQuery = DB::table('userkey')
				->select(array(DB::raw('SUM(userkey.assigncount) as count'), 'keyid', 'user.departmentid' ))
				->leftJoin('user', 'user.userid', '=', 'userkey.userid')
				->where('userkey.status', '=', DB::raw(UserKeyStatus::agree))
				->groupBy('keyid')
				->groupBy('user.departmentid');

			$query = DB::table('department__key')
				->select(array('department__key.departmentid', 'key.keyid', 'product.productid',
					DB::raw('CONCAT(key.name, " - [", product.type, "]", IF(key.section IS NULL, "", CONCAT(" - [", key.section, "]"))) as name'),
					DB::raw('IFNULL(SUM(department__key.count), 0) as total'),
					DB::raw('IFNULL(SUM(department__key.count), 0) - IFNULL(keyassign.count, 0) - IFNULL(userkey.count, 0) as count')
				))
				->leftJoin('key', 'department__key.keyid', '=', 'key.keyid')
				->leftJoin('product', 'product.productid', '=', 'key.productid')
				->leftJoin(DB::raw("({$assignQuery->toSql()}) AS keyassign"), function ($join) {
					$join->on('keyassign.keyid', '=', 'key.keyid');
					$join->on('keyassign.departmentid', '=', 'department__key.departmentid');
				})
				->leftJoin(DB::raw("({$userkeyQuery->toSql()}) AS userkey"), function ($join) {
					$join->on('userkey.keyid', '=', 'key.keyid');
					$join->on('userkey.departmentid', '=', 'department__key.departmentid');
				})
				->groupBy('department__key.keyid')
				->groupBy('department__key.departmentid')
				->where('department__key.departmentid', '=', $department_id);
			if (!is_null($key_id))
			{
				$query->where('department__key.keyid', '=', DB::raw($key_id));
			}
		}

		$select = $query->get();

//		var_dump($select);
		$ret = array();
		foreach ($select as $key => $sel)
		{
			if (!is_null($department_id) && $department_id != $sel->departmentid) continue;
			if (!is_null($key_id) && $key_id != $sel->keyid) continue;
			$sel->name = $select[$key]->name . ' (剩余: ' . $sel->count . ')';
			$sel->remain = $sel->count;
			$ret[] = $sel;
		}
//		var_dump($ret);
		return $ret;
	}

	public static function check_reamount2($status, $managerid, $keyid, $assigncount)
	{
		$total = 0;
		$leftcount = 0;
		$assignedtotal = 0;
		if ($status == 3) {
			if ($managerid) {
				$managekeys = DB::table('managekey')
					->select(array('assigncount'))
					->where('managerid', '=', $managerid)
					->where('keyid', '=', $keyid)
					->where('status', '=', 3)
					->get();
				foreach ($managekeys as $mkey) $total += $mkey->assigncount;

				$managekeys = DB::table('managekey')
					->select(array('assigncount'))
					->where('keyid', '=', $keyid)
					->where('assignerid', '=', $managerid)
					->where('status', '=', 3)
					->get();
				foreach ($managekeys as $mkey) $assignedtotal += $mkey->assigncount;

				$userkeys = DB::table('managekey')
					->select(array('assigncount'))
					->where('assignerid', '=', $managerid)
					->where('keyid', '=', $keyid)
					->where('status', '=', 3)
					->get();

				foreach ($userkeys as $mkey) $assignedtotal += $mkey->assigncount;
			} else {
				$keys = DB::table('key')
					->select('count')
					->where("keyid", '=', $keyid)
					->get();
				foreach ($keys as $k) $total += $k->count;

				$managekeys = DB::table('managekey')
					->select(array('assigncount'))
					->where('keyid', '=', $keyid)
					->where('status', '=', 3)
					->get();
				foreach ($managekeys as $mkey) $assignedtotal += $mkey->assigncount;
			}
		}
		$leftcount = $total - $assignedtotal - $assigncount;
		return $leftcount;
	}

	public static function check_customer($keyid)
	{
		if ($keyid <= 0) return false;

		$count = DB::table('key')
			->leftJoin('department', 'department.departmentid', '=', 'key.departmentid')
			->where('keyid', '=', $keyid)
			->count();

		return $count > 0;
	}

	public static function auto_assign($user_id, $manage_id, $reason)
	{
		if (ParamsService::get('autoassignopen') && !UserService::key_assigned($user_id))
		{
			// 查出用户信息, 用于KeyService::check_remain  查询用户所在部门是否有可用密钥
			$user = DB::table('user')->where('userid', '=', $user_id)->first();

			$assign_keys = DB::table('autoassign__user')
				->leftJoin('autoassign', 'autoassign.autoassignid', '=', 'autoassign__user.autoassignid')
				->where('username', '=', $user->username)
				->where('autoassign.status', '=', \Ca\AutoAssignStatus::available)
				->pluck('autoassign.keyassign');
			$assign_keys = $assign_keys != null ? $assign_keys : ParamsService::get('autoassignkeys');
			$assign_keys = json_decode($assign_keys);

			foreach ($assign_keys as $key)
			{
				// 用户类型是否和分配类型一致
				if ($user->type != $key->type)  continue;
				$row = DB::table('key')->where('keyid', '=', $key->keyid)->first();
				if (!empty($row) && KeyService::check_remain(UserKeyStatus::agree, $key->keyid, $key->amount, $user->departmentid) > 0)
				{
					DB::table('userkey')
						->insert(array(
							'userid'       => $user_id,
							'keyid'        => $key->keyid,
							'productid'    => $row->productid,
							'managerid'    => $manage_id,
							'requestcount' => $key->amount,
							'assigncount'  => $key->amount,
							'reason'       => $reason,
							'status'       => UserKeyStatus::agree,
							'requestdate'  => DB::raw('NOW()'),
							'assigndate'   => DB::raw('NOW()'),
						));
				}
			}
		}



	}

}