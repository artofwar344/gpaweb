<?php
namespace Share;

use View,
	Config,
	App,
	Input,
	InputExt,
	Redirect,
	Response,
	Ca\Consts,
	Ca\DocumentType,
	Ca\DocumentStatus,
	Ca\Service\DocumentService,
	Ca\Service\FastdfsService,
	Ca\Service\ReportService,
	Ca\Service\DocumentRatingService,
	Ca\Service\TopicService,
	Ca\Service\KnowsService,
	Ca\Service\CurrentUserService;

/**
 * 文档、专题相关页面和操作
 * Class DocumentController
 * @package Share
 */
class DocumentController extends BaseController {

	public $layout = 'share.layouts.common';

	/**
	 * 文档详细页面
	 */
	function getDetail()
	{
		$related_limit = Config::get('share.page_document_related');
		$ranklimit = Config::get('share.page_rank_document');
		$document_id = InputExt::getInt('id');
		if ($document_id <= 0)
		{
			return Redirect::to('/');
		}
		$document = DocumentService::get_document($document_id);
		if (empty($document))
		{
			return Redirect::to('/');
		}
		//如果是收藏文档, 则跳转至原文档
		if ($document->from_documentid != null)
		{
			return Redirect::to('/document/detail?id=' . $document->from_documentid);
		}

		if ($document->status != DocumentStatus::normal || $document->type != DocumentType::file ||
			($document->userid != CurrentUserService::$user_id && $document->publish != \Ca\DocumentPublish::submit_d))
		{
			return Redirect::to('/');
		}

		$attachments = DocumentService::getAttachments($document_id); //获取文档附件
		$related_documents = DocumentService::get_related_document($document_id, $related_limit);  //相关文档
		$data = array('title' => '相关文档', 'documents' => $related_documents);
		$hot_document = DocumentService::get_hot_document($ranklimit); //文档排行
		DocumentService::update_views_document($document_id);  //阅读量+1
		$user_id = CurrentUserService::$user_id;
		$isfavorite = DocumentService::check_favorite($user_id, $document_id);   //收藏状态
		$isReported = ReportService::checkReport(\Ca\ReportType::document, $document_id, $user_id); //举报状态

		$rating_score = DocumentRatingService::get_score($document_id);  //评价分数
		$rating_count = DocumentRatingService::get_count($document_id);  //评价人数
		$israting = DocumentRatingService::check_rating($document_id, $user_id);  //是否已评价过

		$data_rating = array(
			'score' => $rating_score,
			'count' => $rating_count,
			'israting' => $israting,
		);
		$this->layout->title = $document->name;
		$this->layout->content = View::make('share.document.detail')
			->with('document', $document)
			->with('hot_document', $hot_document)
			->with('userid', $user_id)
			->with('isfavorite', $isfavorite)
			->with('isReported', $isReported)
			->with('data_rating', $data_rating)
			->with('attachments', $attachments)
			->with('data', $data);
	}

	/**
	 * 获取文档转换为html的内容
	 */
	function getDetailpage()
	{
		list($document_id, $page) = explode('-', Input::get('id'));
		$document_id = intval($document_id);
		$page = max(1, intval($page));
		if ($document_id < 0) return Redirect::to('/');
		$document = DocumentService::get_document($document_id);
		if ($page > $document->pages)
		{
			App::abort(404, 'Page not found');
		}
		if (empty($document) || in_array($document->extension, array('mp4', 'zip'))) return Redirect::to('/');

		$content = file_get_contents(base_path() . '/content/documents/' . $document->swffile .'/'. $page . '.html');
		if (strpos($content, '<div class="pd w0 h0">') !== 0) //调整pdf2html v0.11和v0.10兼容
		{
			$content = '<div class="pd">' . $content . '</div>';
		}
		echo str_replace('src="', 'src="' . Config::get('app.asset_url') . 'documents/' . $document->swffile . '/', $content);
		exit;
	}

	/**
	 * 收藏文档
	 */
	function postFavorites()
	{
		$document_id = InputExt::getInt('id');
		$user_id = CurrentUserService::$user_id;
		if (DocumentService::check_favorite($user_id, $document_id))
		{
			return Response::json(array('status' => 2));//该文档已被收藏
		}
		DocumentService::add_favorite($user_id, $document_id);
		return Response::json(array('status' => 1));//'收藏成功';
	}


	/**
	 * 文件下载
	 * @return bool
	 */
	function getDownloads()
	{
		$document_id = InputExt::getInt('id');
		if ($document_id < 0)
		{
			return false;
		}
		$user_id = CurrentUserService::$user_id;
		if (!DocumentService::check_download($document_id, $user_id))
		{
			DocumentService::add_download($document_id, $user_id); //添加下载记录
		}
		$document = DocumentService::get_document($document_id);
		if (!empty($document) || $document->type == DocumentType::file)
		{
			if ($document->from_documentid != null) //判断是否收藏文档
			{
				$from_document_id = $document->from_documentid;
				$document = DocumentService::get_document($from_document_id);
			}

			$file = Config::get('app.asset_url') . $document->originalfile;  //文档路径
//			$file = FastdfsService::gen_download_url($document->originalfile);
			$filename = $document->name . '.' . $document->extension;
			header("Content-type: application/octet-stream");
			header('Content-Disposition: attachment; filename=' . $filename);
			ob_end_clean();
			readfile($file);
			exit;
		}
		return Response::error('404');
	}

	/**
	 * 1级分类文档列表
	 */
	function getSubcateList($customer, $name)
	{
		$category_ename = Consts::$category_name[$name];
		$category = DocumentService::get_category_by_name($category_ename); //获取对应分类
		$category_id = $category->categoryid;
		$limit = Config::get('share.page_document_home');
		$ranklimit = Config::get('share.page_rank_document');
		if ($category == null)
		{
			return Redirect::to('/');
		}
		$sub_categorys = DocumentService::category_list($category_id);  //获取对应子分类
		$data = array();
		$category_ids = array($category_id);
		foreach ($sub_categorys as $key => $value)
		{
			$category_ids[] = $value->categoryid;
			$docs = DocumentService::get_documents_by_category($value->categoryid, $limit);  //获取子分类下的文档
			$data[$key] = array(
				'title' => $value->name,
				'url_more' => url('/document/list/' . $value->categoryid),
				'documents' => $docs
			);
		}
		$hot_document = DocumentService::get_hot_document($ranklimit);  //文档排行
		$hot_category_document = DocumentService::get_hot_document($ranklimit, $category_ids);  //分类文档排行
		$hot_question = KnowsService::get_hot_question($ranklimit);  //问答排行

		$this->layout->title = $category->name;
		$this->layout->nav = $category->name;
		$this->layout->content = View::make('share.document.subcatelist')
			->with('category', $category)
			->with('sub_categorys', $sub_categorys)
			->with('hot_document', $hot_document)
			->with('hot_category_document', $hot_category_document)
			->with('hot_question', $hot_question)
			->with('data', $data);
	}

	/**
	 * 2级分类文档列表
	 * @param $customer
	 * @param $id
	 * @return bool|void
	 */
	function getDocumentList($customer, $id)
	{
		$category_id = $id;
		$category = DocumentService::get_category($category_id);   //获取分类
		if($category == null)
		{
			return Redirect::to('/');
		}
		$parent_category = DocumentService::get_parent_category($category_id);  //获取父级分类

		$limit = Config::get('share.page_document_list');
		if ($category == null)
		{
			return false;
		}
		$documents = DocumentService::get_documents_by_category($category_id, $limit, true); //获取2级分类下文档
		$data = array(
			'title' => $category->name,
			'documents' => $documents,
		);
		$hot_document = DocumentService::get_hot_document();  //文档排行
		$this->layout->title = $category->name;
		$this->layout->nav = $parent_category->name;
		$this->layout->content = View::make('share.document.documentlist')
			->with('title', $parent_category->name)
			->with('category', $category)
			->with('hot_document', $hot_document)
			->with('data', $data);
	}

	/**
	 * 获取mp4文件
	 * @return mixed
	 */
	function anyPreview()
	{
		$document_id = InputExt::getInt('id');
		$document = DocumentService::get_document($document_id);
		if (empty($document))
		{
			return Response::error(404);
		}
		if ($document->from_documentid != null)
		{
			$from_document_id = $document->from_documentid;
			$document = DocumentService::get_document($from_document_id);
		}
		if ($document->status != \Ca\DocumentStatus::normal ||
			($document->userid != CurrentUserService::$user_id && $document->publish != \Ca\DocumentPublish::submit_d) ||
			$document->extension != 'mp4')
		{
			return Response::error(404);
		}
		$download_url = FastdfsService::gen_download_url($document->originalfile); //文件地址
		Redirect::to($download_url);
	}

	/**
	 * 评价文档
	 * @return mixed
	 */
	function postRating()
	{
		$userid = CurrentUserService::$user_id;
		$documentid = InputExt::getInt('id');
		$score = InputExt::getInt('score');
		//判断文档、分数合法性和是否评价过
		if ($documentid <= 0 || $score <= 0 || DocumentRatingService::check_rating($documentid, $userid))
		{
			return Response::json(array('status' => 0));
		}
		$score = min(5, $score);
		DocumentRatingService::add_rating($documentid, $userid, $score);  //添加评价
		$rating_score = DocumentRatingService::get_score($documentid); //获取新的评价分数
		$rating_count = DocumentRatingService::get_count($documentid); //获取新的评价人数
		return Response::json(array('status' => 1, 'score' => $rating_score, 'count' => $rating_count));
	}

	/**
	 * 举报
	 * @return mixed
	 */
	function postReport()
	{
		$userid = CurrentUserService::$user_id;
		$documentid = InputExt::getInt('id');
		$reason = InputExt::getInt('reason');

		//检查数据合法性
		if ($documentid <= 0 || !array_key_exists($reason, \Ca\Consts::$report_document_reason_texts))
		{
			return Response::json(array('status' => 0));
		}
		if (ReportService::checkReport(\Ca\ReportType::document, $documentid, $userid))
		{
			// 已经举报
			return Response::json(array('status' => 2));
		}
		ReportService::add_report(\Ca\ReportType::document, $documentid, $reason, $userid);
		// 举报成功
		return Response::json(array('status' => 1));
	}

	/**
	 * 检查举报
	 * @return mixed
	 */
	function postCheckReport()
	{
		$documentid = InputExt::getInt('id');
		if (ReportService::check_report(\Ca\ReportType::document, $documentid))
		{
			// 已经举报
			return Response::json(array('status' => 2));
		}
		// 没有举报
		return Response::json(array('status' => 1));
	}

	/**
	 * 获取文档专题
	 */
	public function getTopic()
	{
		$limit = Config::get('share.page_topic_list');
		$ranklimit = Config::get('share.page_rank_document');
		$hot_document = DocumentService::get_hot_document($ranklimit); //文档排行
		$topics = TopicService::getTopics($limit);

		$this->layout->title = '文档专题';
		$this->layout->nav = '文档专题';
		$this->layout->content = View::make('share.document.topiclist')
			->with('topics', $topics)
			->with('hot_document', $hot_document);
	}

	/**
	 * 专题详细页面
	 */
	public function getTopicDetail()
	{
		$topicid = InputExt::getInt('id');
		$userid = CurrentUserService::$user_id;
		$limit = Config::get('share.page_topic_document');
		$ranklimit = Config::get('share.page_rank_document');
		$hot_document = DocumentService::get_hot_document($ranklimit);  //文档排行
		$topic = TopicService::getTopicDetail($topicid); //获取专题
		if ($topic == null)
		{
			return Redirect::to('/topic');
		}
		$documents = TopicService::getTopicDocument($topicid, $limit);  //获取专题内文档
		$isfavorite = TopicService::checkFavorite($userid, $topicid);   //检测是否收藏

		TopicService::increaseTopicViews($topicid);  //专题查看数
		$this->layout->title = '文档专题';
		$this->layout->nav = '文档专题';
		$this->layout->content = View::make('share.document.topicdetail')
			->with('topic', $topic)
			->with('documents', $documents)
			->with('isfavorite', $isfavorite)
			->with('hot_document', $hot_document);
	}

	/**
	 * @return mixed
	 * 收藏专题  Ajax
	 */
	public function postTopicfavorites()
	{
		$topicid = InputExt::getInt('topicid');
		$userid = CurrentUserService::$user_id;
		if (TopicService::checkFavorite($userid, $topicid))
		{
			return Response::json(array('status' => 2));
		}
		TopicService::addFavorite($userid, $topicid);
		return Response::json(array('status' => 1));
	}

}