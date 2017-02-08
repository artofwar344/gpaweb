<?php
class DBExt extends DB
{
	public static function get_sql($query)
	{
		return $query->toSql();
	}
}
