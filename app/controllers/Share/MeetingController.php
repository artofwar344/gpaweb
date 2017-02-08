<?php
namespace Share;
use View,
	Config,
	InputExt,
	Redirect,
	Response,
	Ca\Service\MeetingService,
	Ca\Service\CommentService,
	Ca\Service\ReportService,
	Ca\Service\TagService,
	Ca\Service\DocumentService,
	Ca\Service\KnowsService,
	Ca\Service\CurrentUserService;

/**
 * 讲座相关页面和操作
 * Class MeetingController
 * @package Share
 */
class MeetingController extends BaseController {
	public $layout = 'share.layouts.common';

	/**
	 * 讲座首页
	 */
	public function getIndex()
	{
		$userid = CurrentUserService::$user_id;
		$limit = Config::get('share.page_meeting_home');
		$meetings['active'] = MeetingService::get_all_meeting($limit, false, true); //正在报名的讲座
		$meetings['over'] = MeetingService::get_all_meeting($limit, false, false); //报名结束的讲座

		$data = array(
			array(
				'type' => 'category',
				'title' => '正在报名',
				'url_more' => '/meeting/active',
				'meetings' => $meetings['active'],
				'more_link' => true
			),
			array(
				'type' => 'category',
				'title' => '报名结束',
				'url_more' => '/meeting/over',
				'meetings' => $meetings['over'],
				'more_link' => true
			),
		);

		$hot_document = DocumentService::get_hot_document(); //文档排行
		$tags = MeetingService::get_hot_tag(); //讲座排行

		$this->layout->title = '知识讲座';
		$this->layout->nav = '知识讲座';
		$this->layout->content = View::make('share.meeting.index')
			->with('title', '最新讲座')
			->with('data', $data)
			->with('hot_document', $hot_document)
			->with('tags', $tags)
			->with('tagurl', '/meeting/tag/')
			->with('userid', $userid);
	}

	/**
	 * 讲座详细页面
	 */
	public function getDetail()
	{
		$userid = CurrentUserService::$user_id;
		$meetingId = InputExt::getInt('id');
		$meeting = MeetingService::get_meeting($meetingId);
		if ($meeting == null || $meeting->status != 1)
		{
			return Redirect::to('/meeting');
		}
		$applied = MeetingService::check_apply($meetingId, $userid); //检查是否报名
		$hot_document = DocumentService::get_hot_document(); //文档排行
		$tags = MeetingService::get_hot_tag(); //热门标签

		$limit = Config::get('share.comment_limit', 10);
		$comments = CommentService::getCommentByTargetId($meetingId, \Ca\CommentType::meeting, $limit); //讲座评论
		//评论举报信息
		foreach ($comments as $i => $comment)
		{
			$comments[$i]->isReported = ReportService::checkReport(\Ca\ReportType::comment, $comment->commentid, $userid);
		}

		$this->layout->title = $meeting->name;
		$this->layout->nav = '知识讲座';
		$this->layout->content = View::make('share.meeting.detail')
			->with('meeting', $meeting)
			->with('comments', $comments)
			->with('applied', $applied)
			->with('hot_document', $hot_document)
			->with('tags', $tags)
			->with('tagurl', '/meeting/tag/')
			->with('userid', $userid);
	}

	/**
	 * 报名
	 * @return mixed
	 */
	public function postApply()
	{
		$user_id = CurrentUserService::$user_id;
		$meeting_id = InputExt::getInt('id');
		$meeting = MeetingService::get_meeting($meeting_id);
		if ($meeting->status != 1)
		{
			return Response::json(array('status' => 0));
		}
		if (!MeetingService::check_apply($meeting_id, $user_id))
		{
			MeetingService::apply($meeting_id, $user_id);
		}
		return Response::json(array('status' => 1));
	}

	/**
	 * 正在报名的讲座列表
	 */
	public function getActive()
	{
		$userid = CurrentUserService::$user_id;
		$limit = Config::get('share.page_meeting_list');
		$meetings = MeetingService::get_all_meeting($limit, true, true);
		$data =array(
			array(
				'type' => 'category',
				'title' => '正在报名',
				'url_more' => '/meeting/active',
				'meetings' => $meetings,
			)
		);

		$hot_document = DocumentService::get_hot_document();
		$tags = MeetingService::get_hot_tag();

		$this->layout->title = '正在报名';
		$this->layout->nav = '知识讲座';
		$this->layout->content = View::make('share.meeting.index')
			->with('title', '知识讲座')
			->with('data', $data)
			->with('hot_document', $hot_document)
			->with('tags', $tags)
			->with('tagurl', '/meeting/tag/')
			->with('userid', $userid);
	}

	/**
	 * 报名结束的讲座列表
	 */
	public function getOver()
	{
		$userid = CurrentUserService::$user_id;
		$limit = Config::get('share.page_meeting_list');
		$meetings = MeetingService::get_all_meeting($limit, true, false);
		$data = array(
			array(
				'type' => 'category',
				'title' => '报名结束',
				'url_more' => '/meeting/over',
				'meetings' => $meetings,
			)
		);

		$hot_document = DocumentService::get_hot_document();
		$tags = MeetingService::get_hot_tag();

		$this->layout->title = '报名结束';
		$this->layout->nav = '知识讲座';
		$this->layout->content = View::make('share.meeting.index')
			->with('title', '知识讲座')
			->with('data', $data)
			->with('hot_document', $hot_document)
			->with('tags', $tags)
			->with('tagurl', '/meeting/tag/')
			->with('userid', $userid);
	}

	/**
	 * 标签分类列表
	 * @param $customer
	 * @param $tag_id
	 */
	public function getTag($customer, $tag_id)
	{
		$limit = Config::get('share.page_meeting_tag');
		$tag = TagService::get_tag($tag_id);
		if ($tag == null) return Redirect::to('/meeting');
		$meetings = MeetingService::get_meeting_by_tag($tag_id, $limit);
		$data = array(
			'type' => 'tag',
			'title' => $tag->name,
			'url_more' => '/meeting/tag/' . $tag->tagid,
			'meetings' => $meetings
		);

		$hot_document = DocumentService::get_hot_document();
		$tags = MeetingService::get_hot_tag();

		$this->layout->title = $tag->name;
		$this->layout->nav = '知识讲座';
		$this->layout->content = View::make('share.meeting.tag')
			->with('hot_document', $hot_document)
			->with('tags', $tags)
			->with('tagurl', '/meeting/tag/')
			->with('data', $data);
	}

	/**
	 * 添加评论
	 * @return mixed
	 */
	public function postComment()
	{
		$userid = CurrentUserService::$user_id;
		$meetingid = InputExt::getInt('meetingid');
		$content = InputExt::xss_clean(InputExt::get('content'));
		if (!$content)
		{
			return Redirect::to('/meeting/detail?id=' . $meetingid . '#comment');
		}
		$meeting = MeetingService::get_meeting($meetingid);
		if ($meeting == null)
		{
			return Redirect::to('/meeting');
		}
		//只能对报名结束的讲座进行评论
		if (strtotime($meeting->enrolldate) > time())
		{
			return Redirect::to('/meeting/detail?id=' . $meetingid . '#comment');
		}
		//已报名的用户才能评论
		if (!MeetingService::check_apply($meetingid, $userid))
		{
			return Redirect::to('/meeting/detail?id=' . $meetingid . '#comment');
		}
		$type = \Ca\CommentType::meeting;
		CommentService::addComment($userid, $meetingid, $content, $type);
		return Redirect::to('/meeting/detail?id=' . $meetingid . '#comment');
	}

	//举报评论
	function postReport()
	{
		$userid = CurrentUserService::$user_id;
		$targetid = InputExt::getInt('id');
		$reason = InputExt::getInt('reason');
		$type = InputExt::getInt('type');
		if ($targetid <= 0 || !array_key_exists($reason, \Ca\Consts::$report_reason_text) || !CommentService::checkExists($targetid))
		{
			return Response::json(array('status' => 0));
		}
		//检测是否已评论
		if (ReportService::checkReport($type, $targetid, $userid))
		{
			return Response::json(array('status' => 2));
		}
		ReportService::add_report($type, $targetid, $reason, $userid);
		// 举报成功
		return Response::json(array('status' => 1));
	}



}