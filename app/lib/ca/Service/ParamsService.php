<?php
namespace Ca\Service;

use \DB;

class ParamsService {
	static $values = null;

	public static function get($key, $default = null)
	{
		self::all();
		if (array_key_exists($key, (array)self::$values))
		{
			return self::$values[$key];
		}
		return $default;
	}

	public static function all()
	{
		if (self::$values == null)
		{
			$rows = DB::table('params')->get();
			foreach ($rows as $row)
			{
				self::$values[$row->key] = $row->value;
			}
		}
		return self::$values;
	}

	public static function has($key)
	{
		return DB::table('params')
			->where('key', '=', $key)
			->where('value', '!=', '')
			->count() > 0;
	}
}