<?php
namespace Ca\Service;

use \DB;

class HelpService {

	public static function get_category_info()
	{
		return DB::table('helpcategory')->get();
	}
	public static function get_help_detail($categoryid)
	{
		return DB::table('help')->where('categoryid',$categoryid)
			->where('status',1)
			->get();
	}
}