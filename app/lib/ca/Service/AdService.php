<?php
namespace Ca\Service;

use \DB;

class AdService {
	public static  function show($name, $module)
	{
		$ad = DB::table('ad')
			->where('name', '=', $name)
			->where('status', '=', \Ca\AdStatus::available)
			->where('module', '=', $module)
			->first();
		if (!empty($ad))
		{
			return '<a target="' . $ad->target . '" href="' . $ad->link . '"><img src="' . $ad->image . '"></a>';
		}
		return '';
	}
}