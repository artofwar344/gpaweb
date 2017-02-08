<?php
namespace Share;
use View,
	Config,
	Auth,
	Ca\Service\DocumentService,
	Ca\Service\TestService,
	Ca\Service\ArticleService,
	Ca\Service\KnowsService,
	Ca\Service\MeetingService;

class HomeController extends BaseController {
	public $layout = 'share.layouts.common';

	/**
	 * share首页
	 */
	public function getIndex()
	{
		$limit =  Config::get('share.page_document_home');
		$ranklimit = Config::get('share.page_rank_knows');
		$recommendedlimit = Config::get('share.page_document_recommended');  //推荐文档限制
		$root_categorys = DocumentService::get_category_by_parentid(null);
		$category_ids = array();
		$category_ids['edu'] = array();
		$category_ids['pro'] = array();
		$category_ids['form'] = array();
		foreach ($root_categorys as $category)
		{
			switch ($category->name)
			{
				case '课件专区':
					$category_ids['edu'] = $category->categoryid;
					break;
				case '专业资料':
					$category_ids['pro'] = $category->categoryid;
					break;
				case '应用文书':
					$category_ids['form'] = $category->categoryid;
					break;
			}
		}
		$documents = array();
		$documents['pro'] = DocumentService::get_documents_by_category($category_ids['pro'], $limit, false, true);
		$documents['edu'] = DocumentService::get_documents_by_category($category_ids['edu'], $limit, false, true);
		$documents['form'] = DocumentService::get_documents_by_category($category_ids['form'], $limit, false, true);

		$data = array();
		$data['edu'] = array('title' => '课件专区', 'url_more' => url('/edu'), 'documents' => $documents['edu']);
		$data['pro'] = array('title' => '专业资料', 'url_more' => url('/pro'), 'documents' => $documents['pro']);
		$data['form'] = array('title' => '应用文书', 'url_more' => url('/form'), 'documents' => $documents['form']);

		$hot_document = DocumentService::get_hot_document($ranklimit);  //文档排行
		$hot_question = KnowsService::get_hot_question($ranklimit);  //问答排行
		$hot_meeting = MeetingService::get_hot_meeting();  //讲座排行

		$recommended_document = DocumentService::get_document_recommended($recommendedlimit);  //推荐文档
		$this->layout->nav = '资源首页';
		$this->layout->content = View::make('share.home.index')
			->with('user', Auth::user())
			->with('hot_document', $hot_document)
			->with('hot_question', $hot_question)
			->with('hot_meeting', $hot_meeting)
			->with('recommended_document', $recommended_document)
			->with('category_ids', $category_ids)
			->with('data', $data);

	}

	/**
	 * 用于ValidationEngine的ajax验证
	 */
	public function postValidationEngine()
	{
		echo json_encode(1);
		exit;
	}


}