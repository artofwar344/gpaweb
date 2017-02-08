<?php
namespace Ca\Service;
use \DB;

class AttentionTagService {

	public static function check($userid, $tagid)
	{
		return DB::table('attentiontag')
			->where('userid', '=', $userid)
			->where('tagid', '=', $tagid)
			->count() > 0;
	}

	public static function add($userid, $tagid)
	{
		DB::table('attentiontag')->insert(array('userid' => $userid, 'tagid' => $tagid));
	}

	public static function delete($userid, $tagid)
	{
		DB::table('attentiontag')
			->where('userid', '=', $userid)
			->where('tagid', '=', $tagid)
			->delete();
	}

	public static function getAttentionTag($userid)
	{
		return DB::table('attentiontag')
			->select(array('tag.*'))
			->leftJoin('tag', 'attentiontag.tagid', '=', 'tag.tagid')
			->where('attentiontag.userid', '=', $userid)
			->get();
	}

	public static function countByUser($userid)
	{
		return DB::table('attentiontag')
			->where('attentiontag.userid', '=', $userid)
			->count();
	}

}