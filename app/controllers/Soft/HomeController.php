<?php
namespace Soft;
use View,
	Auth,
	Ca\Consts,
	Ca\Service\SoftService,
	Ca\Service\ArticleService;
class HomeController extends BaseController {
	public $layout = 'soft.layouts.common';
	public function index()
	{
		$this->layout->title = "首页";
		$softs = SoftService::lastest(17);
		$articles = ArticleService::lastest(20);
		$viewpoints = ArticleService::articleViewPoints(4);
		$recommend = SoftService::softRecommend(11);
		$top_download = SoftService::topDownload();
		$indispensably = SoftService::softIndispensably();
		$this->layout->body = View::make('soft.home.index')
			->with('softs', $softs)
			->with('articles', $articles)
			->with('viewpoints', $viewpoints)
			->with('recommend', $recommend)
			->with('top_download', $top_download)
			->with('indispensably', $indispensably);
	}

}