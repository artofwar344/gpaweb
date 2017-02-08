<?php
namespace Ca\Service;
use Illuminate\Support\Facades\DB;
class TagService {

	public static function get_tag($tagid)
	{
		return DB::table('tag')
			->where('tagid', '=', $tagid)
			->first();
	}

	public static function get_tag_by_name($name)
	{
		return DB::table('tag')
			->where('name', '=', $name)
			->first();
	}

	public static function add($name)
	{
		return DB::table('tag')->insertGetId(array('name' => $name));
	}

	public static function add_tags($new_tag_names)
	{
		if (!is_array($new_tag_names))
		{
			$new_tag_names = explode(',', $new_tag_names);
		}
		$new_tag_names = array_unique($new_tag_names);
		// 数据库已经存在的tag
		$tags = DB::table('tag')
			->whereIn('name', $new_tag_names)
			->get();
		$tag_ids = array();
		$exists_names = array();
		foreach ($tags as $tag)
		{
			$exists_names[] = $tag->name;
			$tag_ids[] = $tag->tagid;
		}
		// 需要新添加到数据库的tag
		$new_tags = array_diff(array_map('strtolower', $new_tag_names), array_map('strtolower', $exists_names));
		foreach ($new_tags as $tag_name)
		{
			$tag_name = trim($tag_name);
			if (empty($tag_name))
			{
				continue;
			}
			$tag_ids[] = TagService::add($tag_name);
		}
		return $tag_ids;
	}
}