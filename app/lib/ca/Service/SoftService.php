<?php
namespace Ca\Service;

use \DB,
	\Config;

class SoftService {

	public static function count()
	{
		return DB::table('soft')
			->select(array(DB::raw('COUNT(*) as count')))
			->where('status', '=', 1)
			->where('updatedate', 'is not', DB::raw('NULL'))
			->count();
	}

	public static function setSoftType($soft_id, $types)
	{
		DB::table('softtype')->where('softid', '=', $soft_id)->delete();
		if (!empty($types))
		{
			$values = array();
			foreach ($types as $type)
			{
				$values[] = array(
					'softid' => $soft_id,
					'type' => $type,
				);
			}
			if (!empty($values))
			{
				DB::table('softtype')->insert($values);
			}
		}
	}

	public static function lastest($limit = 10, $category_id = null, $type = null, $paginate = false)
	{

		$query = DB::table('soft')
			->select(array('soft.softid', 'soft.name', 'productcode', 'description', 'language', 'platform', 'licensetype', 'version', 'views',
				'feature', 'fileurl', 'filesize', 'createdate', 'updatedate', 'soft.categoryid', 'softcategory.name as category_name', 'softcategory.parentid'))
			->leftJoin('softcategory', 'soft.categoryid', '=', 'softcategory.categoryid')
			->where('status', '=', 1)
			//->where('updatedate', 'is not', DB::raw('NULL'))
			->orderBy('soft.updatedate', 'desc');

		if (!is_null($category_id))
		{
			$query->where(function($query) use ($category_id)
			{
				$query->where('soft.categoryid', '=', $category_id);
				//$query->orWhere('softcategory.parentid', '=', $category_id);
			});
		}

		if (!is_null($type))
		{
			$query->leftJoin('softtype', 'softtype.softid', '=', 'soft.softid')
				->where('softtype.type', '=', $type);
		}

		if (!$paginate)
		{
			if ($limit > 0)
			{
				$query->take($limit);
			}
			$ret = $query->get();
		}
		else
		{
			$ret = $query->paginate($limit);
		}
		return $ret;
	}

	/**
	 * 推荐下载
	 * @param int $limit
	 * @param int|null $category_id
	 * @return array|\Laravel\Paginator
	 */
	public static function softRecommend($limit = 10, $category_id = null)
	{
		return self::lastest($limit, $category_id, \Ca\SoftType::recommend);
	}

	/**
	 * 常用下载
	 * @param int $limit
	 * @return array|\Laravel\Paginator
	 */
	public static function soft_popular($limit = 10)
	{
		return self::lastest($limit, null, \Ca\SoftType::popular);
	}

	public static function softIndispensably()
	{
		$rows = DB::table('soft__indispensably')->get();
		$ret = array();
		foreach ($rows as $row)
		{
			if (!empty($ret[$row->parentid]) && count($ret[$row->parentid]) >= 16)
			{
				continue;
			}
			$ret[$row->parentid][] = $row;
		}

		return $ret;
	}

	/**
	 * 下载排行
	 * @return array
	 */
	public static function topDownload()
	{
		// this week
		$top_week = DB::table('softlog')
			->select(array(DB::raw('COUNT(*) AS count'), 'softid'))
			->where('type', '=', \Ca\SoftLogType::download)
			->where('createdate', '>=', date('Y-m-d 00:00:00', strtotime('this week')))
			->orderBy('count', 'desc')->take(10)
			->groupBy('softid')
			->get();
		// month
		$top_month = DB::table('softlog')
			->select(array(DB::raw('COUNT(*) AS count'), 'softid'))
			->where('type', '=', \Ca\SoftLogType::download)
			->where('createdate', '>=', date('Y-m-01 00:00:00'))
			->orderBy('count', 'desc')->take(10)
			->groupBy('softid')
			->get();
		// year
		$top_year = DB::table('softlog')
			->select(array(DB::raw('COUNT(*) AS count'), 'softid'))
			->where('type', '=', \Ca\SoftLogType::download)
			->where('createdate', '>=', date('Y-01-01 00:00:00'))
			->orderBy('count', 'desc')->take(10)
			->groupBy('softid')
			->get();

		$top_week_ids = $top_month_ids = $top_year_ids = array();
		foreach ($top_week as $row) $top_week_ids[] = $row->softid;
		foreach ($top_month as $row) $top_month_ids[] = $row->softid;
		foreach ($top_year as $row) $top_year_ids[] = $row->softid;
		$ret = array();
		$ret['week']  = empty($top_week_ids)  ? array() :
			DB::table('soft')->whereIn('softid', $top_week_ids)->orderBy(DB::raw('FIND_IN_SET(softid, "' . implode(',', $top_week_ids) . '")'))->get();
		$ret['month'] = empty($top_month_ids) ? array() :
			DB::table('soft')->whereIn('softid', $top_month_ids)->orderBy(DB::raw('FIND_IN_SET(softid, "' . implode(',', $top_month_ids) . '")'))->get();
		$ret['year']  = empty($top_year_ids)  ? array() :
			DB::table('soft')->whereIn('softid', $top_year_ids)->orderBy(DB::raw('FIND_IN_SET(softid, "' . implode(',', $top_year_ids) . '")'))->get();
		return $ret;
	}

	public static function categories_by_parentid($parent_id)
	{
		return DB::table('softcategory')
			->where('parentid', '=', $parent_id)
			->get();
	}

	public static function category_by_id($category_id)
	{
		return DB::table('softcategory')
			->where('categoryid', '=', $category_id)
			->first();
	}

	public static function soft_by_id($soft_id)
	{
		return DB::table('soft')
			->select(array('softid', 'soft.name', 'productcode', 'description', 'language', 'platform', 'licensetype', 'version', 'views',
				'feature', 'fileurl', 'filesize', 'createdate', 'updatedate', 'soft.categoryid', 'softcategory.name as category_name', 'parentid'))
			->leftJoin('softcategory', 'soft.categoryid', '=', 'softcategory.categoryid')
			->where('softid', '=', $soft_id)
			->first();
	}

	public static function log($soft_id, $type)
	{
		DB::table('softlog')
			->insert(array('softid' => $soft_id, 'type' => $type));
	}

	public static function icon($soft_id)
	{
		return Config::get('app.asset_url') . 'images/softicon/' . $soft_id . '.png';
	}

	public static function search($keyword)
	{
		return DB::table('soft')
			->select(array('softid', 'soft.name', 'productcode', 'description', 'language', 'platform', 'licensetype', 'version', 'views',
				'feature', 'fileurl', 'filesize', 'createdate', 'updatedate', 'soft.categoryid', 'softcategory.name as category_name', 'parentid'))
			->leftJoin('softcategory', 'soft.categoryid', '=', 'softcategory.categoryid')
			->where('soft.name', 'LIKE', '%' . $keyword . '%')
			->paginate()
			->appends(array('kw'=>$keyword));
	}
}