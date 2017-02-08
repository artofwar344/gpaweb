<?php

class SessionExt extends Session
{
	public static function get_int($key)
	{
		return intval(Session::get($key));
	}
}
