<?php
namespace Ca\Service;
use \DB;

class AttentionCategoryService {

	public static function check($userid, $categoryid)
	{
		return DB::table('attentioncategory')
			->where('userid', '=', $userid)
			->where('categoryid', '=', $categoryid)
			->count() > 0;
	}

	public static function add($userid, $categoryid)
	{
		DB::table('attentioncategory')->insert(array('userid' => $userid, 'categoryid' => $categoryid));
	}

	public static function delete($userid, $categoryid)
	{
		DB::table('attentioncategory')
			->where('userid', '=', $userid)
			->where('categoryid', '=', $categoryid)
			->delete();
	}

	public static function getAttentionCategory($userid)
	{
		return DB::table('attentioncategory')
			->select(array('questioncategory.*', 'parent.name as parent_name'))
			->leftJoin('questioncategory', 'questioncategory.categoryid', '=', 'attentioncategory.categoryid')
			->leftJoin('questioncategory as parent', 'parent.categoryid', '=', 'questioncategory.parentid')
			->where('attentioncategory.userid', '=', $userid)
			->get();
	}

	public static function countByUser($userid)
	{
		return DB::table('attentioncategory')
			->where('userid', '=', $userid)
			->count();
	}
}