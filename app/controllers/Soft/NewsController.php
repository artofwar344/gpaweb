<?php
namespace Soft;
use \view,
	Ca\Service\ArticleService;

class NewsController extends BaseController {
	public $layout = 'soft.layouts.common';

	public function detail($customer, $articleid)
	{
		$article = ArticleService::article_by_id($articleid);
		$this->layout->title = $article->title;
		$this->layout->body = View::make('soft.news.detail')->with('article', $article);
	}

	public function newsList()
	{
		$categories = ArticleService::categories();
		$ret = array();
		foreach ($categories as $category)
		{
			$ret[] = array(
				'category_name' => $category->name,
				'categoryid' => $category->categoryid,
				'results' => ArticleService::lastest(16, $category->categoryid)
			);
		}
		$this->layout->title = '所有新闻';
		$this->layout->body = View::make('soft.news.list')->with('articles', $ret);
	}

	public function category($customer, $categoryid)
	{
		$category = ArticleService::category_by_id($categoryid);
		if (is_null($category))
		{
			return Response::error(404);
		}
		$articles = ArticleService::lastest(40, $categoryid, null, true);
		$this->layout->title = $category->name;
		$this->layout->body = View::make('soft.news.category')
			->with('category', $category)
			->with('articles', $articles);
	}
}