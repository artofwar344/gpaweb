<?php
namespace Share;
use View,
	Config,
	Session,
	Input,
	InputExt,
	Redirect,
	Response,
	Request,
	Validator,
	Ca\Common,
	Ca\Service\SensitiveService,
	Ca\Service\TagService,
	Ca\Service\KnowsService,
	Ca\Service\ReportService,
	Ca\Service\MessageService,
	Ca\Service\CurrentUserService;

/**
 * 知识问答相关页面和操作
 * Class KnowsController
 * @package Share
 */
class KnowsController extends BaseController {
	public $layout = 'share.layouts.common';

	/**
	 * 知识问答主页面
	 */
	public function getIndex()
	{
		$limit = Config::get('share.page_knows_home');
		$root_categorys = KnowsService::get_category_by_parentid(null);  //获取顶级分类
		$data = array();
		foreach ($root_categorys as $category)
		{
			//获取各分类对应问题
			$questions = KnowsService::get_question_by_category($category->categoryid, $limit, false, true);
			$data[] = array(
				'type' => 'category',
				'title' => $category->name,
				'url_more' => '/knows/list/' . $category->categoryid,
				'questions' => $questions,
				'more_link' => true
			);
		}
		$this->layout->title = '知识问答';
		$this->layout->nav = '知识问答';
		$this->layout->content = View::make('share.knows.index')
			->with('data', $data);
	}

	/**
	 * 查看提问
	 */
	public function getQuestion()
	{
		$question_id = InputExt::getInt('id');
		if ($question_id <= 0)
		{
			return Redirect::to('/knows');
		}
		$question = KnowsService::get_question($question_id);
		if ($question == null)
		{
			return Redirect::to('/knows');
		}
		$limit = Config::get('share.page_knows_answer');
		$answers = KnowsService::get_answer_by_question($question_id, $limit); //获取问题回答
		$best_answer = KnowsService::get_best_answer($question_id); //最佳回答
		$similarquestions = KnowsService::get_similarquestion($question->title, 5, false, $question->questionid);  //相似问题

		KnowsService::update_views_question($question_id);  //阅读量+1
		$userid = CurrentUserService::$user_id;
		$isfavorite = KnowsService::check_favorite($userid, $question_id);  //是否收藏
		$isanswered = KnowsService::checkAnswered($userid, $question_id);   //是否已回答过
		$isReported = ReportService::checkReport(\Ca\ReportType::question, $question_id, $userid);  //是否举报过
		$categories = KnowsService::get_category_all();

		//最佳回答的举报情况
		if ($best_answer != null)
		{
			$best_answer->isReported = ReportService::checkReport(\Ca\ReportType::answer, $best_answer->answerid, $userid);
		}
		//各个回答的举报情况
		foreach ($answers as $i => $answer)
		{
			$answers[$i]->isReported = ReportService::checkReport(\Ca\ReportType::answer, $answer->answerid, $userid);
		}

		$this->layout->title = $question->title;
		$this->layout->nav = '知识问答';
		$this->layout->content = View::make('share.knows.question')
			->with('question', $question)
			->with('answers', $answers)
			->with('best_answer', $best_answer)
			->with('isfavorite', $isfavorite)
			->with('isanswered', $isanswered)
			->with('isReported', $isReported)
			->with('similarquestions', $similarquestions)
			->with('categories', $categories)
			->with('userid', $userid);
		$messages = Session::get('messages');
		if ($messages != null) $this->layout->content->with('messages', $messages);
	}

	/**
	 * 发起提问
	 */
	public function anyNewQuestion()
	{
		$user_id = CurrentUserService::$user_id;
		if (Request::getMethod() == 'POST') //处理post数据
		{
			$category_id = InputExt::getInt('category_id');
			$title = InputExt::xss_clean(trim(Input::get('title')));
			$content = InputExt::xss_clean(trim(Input::get('content')));
			$tag = InputExt::xss_clean(trim(Input::get('tag')));

			//验证分类合法性
			if (!KnowsService::checkSubCategory($category_id))
			{
				return Redirect::to('/knows/new');
			}

			if ($title == '')
			{
				return Redirect::to('/knows/new');
			}

			//敏感词检验
			if (!SensitiveService::check($title) || !SensitiveService::check($content))
			{
				return Redirect::to('/knows/new');
			}
			//添加问题
			$question_id = KnowsService::add_question($user_id, $category_id, $title, $content, $tag);
			return Redirect::to('knows/question?id=' . $question_id);
		}
		else  //发起提问页面
		{
			$categories = KnowsService::get_category_all();
			$this->layout->title = '发起提问';
			$this->layout->nav = '知识问答';
			$this->layout->content = View::make('share.knows.new')
				->with('userid', $user_id)
				->with('categories', $categories);
			$messages = Session::get('messages');
			if ($messages != null) $this->layout->content->with('messages', $messages);
		}
	}

	/**
	 * 回答问题
	 */
	public function postAnswer()
	{
		$user_id = CurrentUserService::$user_id;
		$question_id = InputExt::getInt('question_id');
		$content = InputExt::xss_clean(trim(Input::get('content')));

		$input = array('content' => $content);
		$rules = array('content' => 'required');
		$messages = array('content_required' => '回答不能为空');
		$validation = Validator::make($input, $rules, $messages);
		if ($validation->fails()) //数据验证
		{
			return Redirect::to('/knows/question?id=' . $question_id);
		}
		if (!SensitiveService::check($content)) //敏感词验证
		{
			return Redirect::to('/knows/question?id=' . $question_id);
		}
		if (KnowsService::checkUserQuestion($user_id, $question_id)) //检验是否是用户提出的问题
		{
			return Redirect::to('/knows/question?id=' . $question_id);
		}
		if (KnowsService::checkAnswered($user_id, $question_id)) //检测用户是否回答过该问题
		{
			return Redirect::to('/knows/question?id=' . $question_id);
		}
		if (KnowsService::check_best_answer($question_id)) //检测是否有最佳回答
		{
			return Redirect::to('/knows/question?id=' . $question_id);
		}

		$answerid = KnowsService::add_answer($user_id, $question_id, $content); //添加回答
		MessageService::create_system_message_new_answer($question_id, $answerid); //添加相关系统消息
		return Redirect::to('knows/question?id=' . $question_id);
	}

	/**
	 * 分类列表
	 */
	public function getKnowsList($customer, $category_id)
	{
		$category = KnowsService::get_category($category_id);
		if ($category == null)
		{
			return Redirect::to('/knows');
		}
		if ($category->parentid == null) //一级分类
		{
			$data = array();
			$parent_category = null;
			$subcates = KnowsService::get_category_by_parentid($category->categoryid);
			$limit = Config::get('share.page_knows_home');
			foreach ($subcates as $subcate)
			{
				$questions = KnowsService::get_question_by_category($subcate->categoryid, $limit);
				$data[] = array(
					'type' => 'category',
					'title' => $subcate->name,
					'url_more' => '/knows/list/' . $subcate->categoryid,
					'questions' => $questions,
					'more_link' => true
				);
			}
		}
		else  //二级分类
		{
			$parent_category = KnowsService::get_category($category->parentid);
			$limit = Config::get('share.page_knows_list');
			$questions = KnowsService::get_question_by_category($category->categoryid, $limit, true);
			$data = array(
				'type' => 'category',
				'title' => $category->name,
				'url_more' => url('/knows/list/' . $category->categoryid),
				'questions' => $questions
			);
		}

		$this->layout->title = $category->name;
		$this->layout->nav = '知识问答';
		$this->layout->content = View::make('share.knows.list')
			->with('category', $category)
			->with('parentCategory', $parent_category)
			->with('data', $data);
	}


	/**
	 * 按标签分类
	 * @param $customer
	 * @param $tag_id
	 */
	public function getTag($customer, $tag_id)
	{
		$limit = Config::get('share.page_knows_tag');
		$tag = TagService::get_tag($tag_id);
		if ($tag == null)
		{
			return Redirect::to('/knows');
		}
		$questions = KnowsService::get_question_by_tag($tag_id, $limit);
		$data = array(
			'type' => 'tag',
			'title' => $tag->name,
			'url_more' => '/knows/tag/' . $tag->tagid,
			'questions' => $questions
		);
		$this->layout->title = $tag->name;
		$this->layout->nav = '知识问答';
		$this->layout->content = View::make('share.knows.tag')
			->with('data', $data);
	}

	/**
	 * 采纳为最佳回答
	 * @return mixed
	 */
	public function postAccept()
	{
		$answer_id = InputExt::getInt('answerid');
		$question = KnowsService::get_question_by_answer_id($answer_id);
		if ($question == null)
		{
			return Response::json(array('status' => 0));
		}
		if ($question->userid != CurrentUserService::$user_id) //检查当前用户与提问用户是否相符
		{
			return Response::json(array('status' => 0));
		}
		if (KnowsService::check_best_answer($question->questionid)) //检测是否已经存在最佳回答
		{
			return Response::json(array('status' => 0));
		}

		KnowsService::update_status_answer($answer_id, \Ca\AnswerStatus::best);
		MessageService::create_system_message_accept_answer($question->questionid, $answer_id); //发送相关系统消息
		return Response::json(array('status' => 1));
	}

	/**
	 * 收藏问答
	 */
	function postFavorites()
	{
		$question_id = InputExt::getInt('id');
		$user_id = CurrentUserService::$user_id;
		if (KnowsService::check_favorite($user_id, $question_id))
		{
			return Response::json(array('status' => 2));//该文档已被收藏
		}
		KnowsService::add_favorite($user_id, $question_id);
		return Response::json(array('status' => 1));//'收藏成功';
	}

	/**
	 * @return mixed
	 * 新建提问时查询相似问题 Ajax用
	 */
	function postSimilarQuestion()
	{
		$title = Input::get('title');
		$questions = KnowsService::get_similarquestion($title, 5, true);

		if (count($questions) <= 0)
		{
			echo 'empty';
			exit;
		}
		$this->layout = View::make('share.layouts.empty');
		$this->layout->content = View::make('share.knows.similarQuestion')
			->with('questions', $questions);
	}

	/**
	 * 查询问答
	 */
	function anySearch()
	{
		$keyword = trim(Input::get('q'));

		if (empty($keyword))
		{
			return Redirect::to('/knows');
		}
		if (Request::getMethod() == 'POST')
		{
			return Redirect::to('/knows/search?q=' . $keyword);
		}

		$questions = KnowsService::get_similarquestion($keyword, 10);

		$data = array(
			'type' => 'category',
			'title' => '共有 <strong>' . $questions->getTotal() . '</strong> 条结果包含"' . $keyword . '"',
			'questions' => $questions
		);

		$this->layout->title = htmlspecialchars($keyword);
		$this->layout->nav = '问答中心';
		$this->layout->content = View::make('share.knows.search')
			->with('keyword', htmlspecialchars($keyword))
			->with('data', $data);
		$questions->appends(array('q' => htmlspecialchars($keyword)));
	}

	/**
	 * 敏感词检测
	 * @return mixed
	 */
	function postSensitiveCheck()
	{
		$string = InputExt::get('fieldValue');
		$fieldId = InputExt::get('fieldId');
		return Response::json(array($fieldId, SensitiveService::check($string)));
	}

	/**
	 * 举报问答
	 * @return mixed
	 */
	function postReport()
	{
		$userid = CurrentUserService::$user_id;
		$targetid = InputExt::getInt('id');
		$reason = InputExt::getInt('reason');
		$type = InputExt::getInt('type');
		if ($targetid <= 0 || $reason <= 0)
		{
			return Response::json(array('status' => 0));
		}
		if (ReportService::checkReport($type, $targetid, $userid))
		{
			return Response::json(array('status' => 2));
		}
		ReportService::add_report($type, $targetid, $reason, $userid);
		return Response::json(array('status' => 1));
	}

	/**
	 * 添加追问、回答
	 */
	function postAskMore()
	{
		$userid = CurrentUserService::$user_id;
		$answerid = InputExt::getInt('answerid');
		$questionid = InputExt::getInt('questionid');
		$type = InputExt::getInt('type');
		$content = InputExt::xss_clean(trim(InputExt::get('ask_more_content')));
		if ($content == '')
		{
			return Redirect::to('/knows/question?id=' . $questionid);
		}
		if (!KnowsService::checkAskMore($userid, $questionid, $type, $answerid))
		{
			return Redirect::to('/knows/question?id=' . $questionid);
		}

		KnowsService::add_answer($userid, $questionid, $content, $answerid, $type);
		if ($type == \Ca\AnswerType::answerMore)
		{
			MessageService::create_system_message_new_answer($questionid, $answerid); //发送系统消息
		}
		else
		{
			MessageService::createSystemMessageAskMore($questionid, $answerid); //发送系统消息
		}
		return Redirect::to('/knows/question?id=' . $questionid);
	}

	/**
	 * 修改问题
	 */
	function postUpdateQuestion()
	{
		$userid = CurrentUserService::$user_id;
		$questionid = InputExt::getInt('questionid');
		$content = InputExt::xss_clean(trim(InputExt::get('update_question_content')));
		if (KnowsService::check_best_answer($questionid)) //检测最佳回答
		{
			return Redirect::to('/knows/question?id=' . $questionid);
		}
		KnowsService::updateQuestion($userid, $questionid, $content);
		MessageService::createSystemMessageUpdateQuestion($questionid);

		return Redirect::to('/knows/question?id=' . $questionid);

	}

	/**
	 * 修改分类
	 */
	function postUpdateCategory()
	{
		$userid = CurrentUserService::$user_id;
		$questionid = InputExt::getInt('questionid');
		$categoryid = InputExt::getInt('categoryid');
		$category = KnowsService::get_category($categoryid);
		if ($category == null || $category->parentid == null)
		{
			return Redirect::to('/knows/question?id=' . $questionid);
		}
		if (!KnowsService::checkUserQuestion($userid, $questionid)) //检测问题的用户
		{
			return Redirect::to('/knows/question?id=' . $questionid);
		}
		KnowsService::updateCategory($questionid, $categoryid);
		return Redirect::to('/knows/question?id=' . $questionid);
	}

}