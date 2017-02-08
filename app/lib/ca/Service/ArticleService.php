<?php
namespace Ca\Service;
use Illuminate\Support\Facades\DB;
class ArticleService {

	public static function count($categoryid = null)
	{
		$query = DB::table('article')
			->select(array(DB::raw('COUNT(*) as count')))
			->where('status', '=', 1)
			->where('updatedate', 'is not', DB::raw('NULL'));

		if (!is_null($categoryid)) $query->where('article.categoryid', '=', $categoryid);

		return $query->count();
	}

	public static function categories()
	{
		return DB::table('articlecategory')
			->get();
	}

	public static function set_article_type($article_id, $types)
	{
		DB::table('articletype')->where('articleid', '=', $article_id)->delete();
		if (!empty($types))
		{
			$values = array();
			foreach ($types as $type)
			{
				$values[] = array(
						'articleid' => $article_id,
						'type' => $type,
					);
			}
			if (!empty($values))
			{
				DB::table('articletype')->insert($values);
			}
		}

	}

	public static function category_by_id($category_id)
	{
		return DB::table('articlecategory')
			->where('categoryid', '=', $category_id)
			->first();
	}

	public static function article_by_id($articleid)
	{
		return DB::table('article')
			->leftJoin('articlecategory', 'article.categoryid', '=', 'articlecategory.categoryid')
			->where('articleid', '=', $articleid)
			->first();
	}

	public static function lastest($limit = 10, $categoryid = null, $type = null, $paginate = false)
	{
		$query = DB::table('article')
			->select(array('article.title', 'articlecategory.name as category_name', 'article.content', 'article.categoryid', 'article.articleid'))
			->leftJoin('articlecategory', 'article.categoryid', '=', 'articlecategory.categoryid')
			->where('status', '=', 1)
			->orderBy('article.articleid', 'desc');

		if (!is_null($categoryid)) $query->where('article.categoryid', '=', $categoryid);

		if (!is_null($type))
		{
			$query->leftJoin('articletype', 'articletype.articleid', '=', 'article.articleid')
				->where('articletype.type', '=', $type);
		}

		if (!$paginate)
		{
			if ($limit > 0)
			{
				$query->take($limit);
			}
			$ret = $query->get();
		}
		else
		{
			$ret = $query->paginate($limit);
		}
		return $ret;
	}

	public static function articleViewPoints($limit = 10)
	{
		return self::lastest($limit, null, \Ca\ArticleType::viewpoint);
	}

	public static function article_hot($limit = 10)
	{
		return self::lastest($limit, null, \Ca\ArticleType::hot);
	}

}