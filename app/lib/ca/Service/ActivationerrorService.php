<?php
namespace Ca\Service;

use \DB;

class ActivationerrorService {
	public static function all()
	{
		return DB::table('ca.activationerror')->get();
	}

	public static function get($errorId)
	{
		return DB::table('ca.activationerror')->where('errorid', '=', $errorId)->first();
	}
}