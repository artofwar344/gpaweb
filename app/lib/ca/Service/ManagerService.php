<?php
namespace Ca\Service;

use \DB,
	\Auth,
	\Session,
	Ca\Common;

class ManagerService {

	public static function check_customer($managerid)
	{
		if ($managerid <= 0) return false;

		$count = DB::table('manager')
			->leftJoin('department', 'department.departmentid', '=', 'manager.departmentid')
			->where('managerid', '=', $managerid)
			->count();

		return $count > 0;
	}

	public static function check_adminer($managerid)
	{
		if ($managerid <= 0 || !Auth::user()->adminerid) return false;

		$count = DB::table('manager')
			->leftJoin('department', 'department.departmentid', '=', 'manager.departmentid')
			->leftJoin('customer', 'customer.customerid', '=', 'department.customerid')
			->where('managerid', '=', $managerid)
			->where('adminerid', '=', Auth::user()->adminerid)
			->count();

		return $count > 0;
	}

	public static function check_role($role)
	{
		return strpos(Auth::user()->role, '[' . $role . ']') !== false;
	}

	public static function managers()
	{
		return DB::table('manager')->get();
	}

	/**
	 * 管理员已分配给用户的激活数量
	 * @param $manager_id
	 * @return mixed
	 */
	public static function assigned($manager_id)
	{
		$user_assigned = DB::table('userkey')
			->select(array(DB::raw('SUM(userkey.assigncount) as count')))
			->where('managerid', '=', $manager_id)
			->first('count');
		return $user_assigned->count;
	}

	/**
	 * Put User object to session
	 *
	 * @param stdClass $manager
	 */
	public static function putCurrentUser($manager)
	{
		Session::put('current_manager', $manager);
	}

	/**
	 * Get User object from session
	 *
	 * @return mixed
	 */
	public static function getCurrentUser()
	{
		return Session::get('current_manager');
	}

}