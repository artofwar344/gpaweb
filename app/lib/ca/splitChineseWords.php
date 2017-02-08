<?php
namespace Ca;
use PhpAnalysis;

class SplitChineseWords {

	/**
	 * @param $string
	 * @param array $wordType 取出的词组类型
	 * @return array
	 * 将字符串分隔为查询需要的词组
	 */
	public static function splitWords($string, $wordType = array('/n','/l'))
	{
		$simpleSplitWords = preg_split('/[\s,]+/', trim($string)); //用空白符或逗号分隔字符串
		$splitWords = $simpleSplitWords;

		//自动分词
		PhpAnalysis::$loadInit = false;
		$pa = new PhpAnalysis('utf-8', 'utf-8', false);
		//载入词典
		$pa->LoadDict();
		$pa->differMax = true;
		$pa->SetResultType(2); //设置结果类型 1 为全部， 2去除特殊符号

		foreach ($simpleSplitWords as $word)
		{
			$pa->SetSource($word);
			$pa->StartAnalysis(true);
			$splitresult = $pa->GetResultByAttr($wordType);  //获取名词
			foreach ($splitresult as $result)
			{
				$splitWords[] = $result['word'];
			}
		}
		return array_values(array_unique($splitWords)); //去重复
	}
}