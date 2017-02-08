<?php
namespace Ca\Service;

use \DB,
	Ca\Common;

class DepartmentService
{
	/** 获取部门管理员数量
	 * @param $departmentid
	 * @return bool
	 */
	public static function manager_count($departmentid)
	{
		$count = DB::table('manager')
			->select(array(DB::raw('COUNT(managerid) as count')))
			->where('departmentid', '=', $departmentid)
			->first();

		return $count->count > 0;
	}

	/** 获取部门用户数量
	 * @param $departmentid
	 * @return bool
	 */
	public static function user_count($departmentid)
	{
		$count = DB::table('user')
			->select(array(DB::raw('COUNT(userid) as count')))
			->where('departmentid', '=', $departmentid)
			->first();

		return $count->count > 0;
	}

	/** 获取部门密钥数量
	 * @param $departmentid
	 * @return bool
	 */
	public static function key_count($departmentid)
	{
		$count = DB::table('key')
			->select(array(DB::raw('COUNT(keyid) as count')))
			->where('departmentid', '=', $departmentid)
			->first();

		return $count->count > 0;
	}

	public static function check_customer($departmentid)
	{
		if ($departmentid <= 0) return false;

		$count = DB::table('department')
			->where('departmentid', '=', $departmentid)
			->count();

		return $count > 0;
	}

	public static function check_adminer($departmentid)
	{
		if ($departmentid <= 0 || !Auth::user()->adminerid) return false;

		$count = DB::table('department')
			->where('departmentid', '=', $departmentid)
			->where('adminerid', '=', Auth::user()->adminerid)
			->count();

		return $count > 0;
	}

	public static function departments()
	{
		return DB::table('department')
			->whereNotNull('parentid')
			->get();
	}

	public static function getTopDepartment()
	{
		return DB::table('department')
			->whereNull('parentid')
			->first();
	}
	public static function getDepartment($departmentid)
	{
		return DB::table('department')
			->where('departmentid', '=', $departmentid)
			->first();
	}

	/**
	 * 获取所有下级部门
	 * @param $ids
	 * @param $result
	 * @param bool $returnself 是否返回自身
	 * @return array
	 */
	public static function getChildDepartments($ids, &$result, $returnself = true)
	{
		if (!is_array($ids))
		{
			$ids = array($ids);
		}
		if (!is_array($result))
		{
			$result = $returnself ? $ids : array();
		}

		$departments = DB::table('department')
			->whereIn('parentid', $ids)
			->get();
		foreach($departments as $department)
		{
			$result[] = $department->departmentid;
			self::getChildDepartments($department->departmentid, $result);
		}
		return $result;
	}

	/**
	 * 获取部门的全名 (上级部门 > 次级部门 ...)
	 * @param $departmentid
	 * @return string
	 */
	public static function getFullName($departmentid)
	{
		$fullname = array();
		self::getParentIds($departmentid, $ids);
		foreach ($ids as $id)
		{
			$name =DB::table('department')->where('departmentid', '=', $id)->pluck('name');
			if (!empty($name))
			{
				$fullname[] = $name;
			}
		}
		return implode(' > ', $fullname);
	}

	/**
	 * 获取department的所有上级部门id
	 * @param $departmentid
	 * @param $ids
	 * @return array
	 */
	public static function getParentIds($departmentid, &$ids)
	{
		if (!is_array($ids))
		{
			$ids = array($departmentid);
		}
		$parentid = DB::table('department')->where('departmentid', '=', $departmentid)->pluck('parentid');
		if ($parentid == null)
		{
			return $ids;
		}
		array_unshift($ids, $parentid);
		return self::getParentids($parentid, $ids);
	}
	/**
	 * 检测所选部门是否在当前所列部门中
	 * @param $currentid
	 * @param $departmentid
	 * @return bool
	 */
	public static function check_departmentid($departmentid, $currentid)
	{
		$results = DepartmentService::getChildDepartments($departmentid, $departmentids);
		foreach ($results as $result)
		{
			if($result == $currentid)
			{
				return true;
			}
		}
		return false;
	}


	

}