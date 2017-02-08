<?php
namespace Ca\Service;
use Illuminate\Support\Facades\DB;
class SensitiveService {

	public static function check($string)
	{
		$words = DB::table('sensitive')->get();
		$flag = true;
		foreach ($words as $word)
		{
			if (strpos($string, $word->word) !== false)
			{
				$flag = false;
				break;
			}
		}
		return $flag;
	}

	public static function replace($string, $replacement = '**')
	{
		$words = DB::table('sensitive')->get();
		$words = array_map(function($word) {return $word->word;}, $words);
		return str_replace($words, $replacement, $string);
	}

	/**
	 * 添加敏感词
	 * @param $words
	 * @return array 返回新增ids
	 */
	public static function add($words)
	{
		if (!is_array($words))
		{
			$words = explode(',', $words);
		}
		$wordNames = array_unique($words);
		$existWords = DB::table('sensitive')->whereIn('word', $wordNames)->get();
		$existWordNames = array_map(function($word) {return $word->word;}, $existWords);
		$newWords = array_diff($wordNames, $existWordNames);
		$newIds = array();
		foreach ($newWords as $word)
		{
			if ($word == '')
			{
				continue;
			}
			$newIds[] = DB::table('sensitive')->insertGetId(array('word' => $word));
		}
		return $newIds;
	}


}