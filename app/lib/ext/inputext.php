<?php
class InputExt extends Input
{
	public static function getInt($key, $abs = true)
	{
		$ret = intval(Input::get($key));
		if ($abs) $ret = abs($ret);

		return $ret;
	}

	public static function get_time($key)
	{
		return strtotime(Input::get($key));
	}

	/**
	 * 转义实体
	 * @param $string
	 * @param bool $keep
	 * @return string
	 */
	public static function xss_clean($string, $keep=false)
	{
		if ($keep == false)
		{
			return strip_tags($string);
		}
		return htmlentities($string);
	}
}
