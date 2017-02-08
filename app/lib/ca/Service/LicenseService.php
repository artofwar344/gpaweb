<?php
namespace Ca\Service;

use \Illuminate\Support\Facades\DB;

class LicenseService {
	static $values = null;

	public static function get($number,$type,$status='1',$computerId='')
	{
		$license = DB::table('code')
			->where('number', '=', $number)
			->where('license.type', '=', $type)
			->where('code.status','=','0')
			->leftJoin('license', 'license.licenseid', '=', 'code.licenseid')
			->first();
		if($license!="")
		{
			if($license->status == 0) self::update($license->codeid,$status,$computerId);
		} else {
			$license = DB::table('code')
			->where('number', '=', $number)
			->where('license.type', '=', $type)
			->where('code.status','=','1')
			->leftJoin('license', 'license.licenseid', '=', 'code.licenseid')
			->first();
		}
		
		return $license;	
	}

	public static function get2($number,$type)
	{
		$license = DB::table('code')
			->where('number', '=', $number)
			->first();
		return $license;	
	}
	public static function add($number,$licenseid)
	{
		$query = DB::table('code');
		$data = array(
			'number' => $number,
			'licenseid' => $licenseid
		);
		return $query->insertGetId($data);
	}

	public static function update($codeid,$status,$computerId)
	{
		return DB::table('code')
				->where('codeid', '=', $codeid)
				->where('status','=','0')
				->update(array('selecttime' => date("Y-m-d H:i:s"),'status' => $status,'computerid' => $computerId));
	}
	public static function updateNumber($number,$status,$error,$computerId)
	{
		return DB::table('code')
				->where('number', '=', $number)
				->where('status','=','2')
				->where('computerid','=',$computerId)
				->update(array('selecttime' => date("Y-m-d H:i:s"),'status' => $status,'error' => $error,'computerid' => $computerId));
	}
}