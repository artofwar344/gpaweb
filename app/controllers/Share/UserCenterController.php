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
	Cookie,
	Ca\Common,
	Ca\Consts,
	Ca\Service\DocumentService,
	Ca\Service\TopicService,
	Ca\Service\MeetingService,
	Ca\Service\KnowsService,
	Ca\Service\MessageService,
	Ca\Service\TagService,
	Ca\Service\AttentionTagService,
	Ca\Service\AttentionCategoryService,
	Ca\Service\ParamsService,
	Ca\Service\CurrentUserService;

/**
 * 用户中心
 * Class UserCenterController
 * @package Share
 */
class UserCenterController extends BaseController {

	public $layout = 'share.layouts.usercenter';

	/**
	 * 我的文档
	 */
	function getDocument()
	{
		$folder_id = intval(Input::get('folderid'));

		$user = CurrentUserService::$user;
		$userid = CurrentUserService::$user_id;
		if (!in_array($userid, Consts::$have_my_document)) {
			return Redirect::to('/usercenter/message');
		}
		$parent_id = null;
		$count_document = DocumentService::count_user_document($user->userid);
		$count_download = DocumentService::count_download_document($user->userid);
		// 进入文件夹
		if ($folder_id > 0)
		{
			$parent_id = DocumentService::get_parent_id($folder_id);

			// 获取文档
			$documents = CurrentUserService::documents_by_parent_id($folder_id);
		}
		else // 第一级列表
		{
			$documents = CurrentUserService::top_level_documents();
		}

		$totalSize = DocumentService::GetTotalSize($userid);
		$limitSize = ParamsService::get('maxuploadlimit') * 1024 * 1024; // 单位为MB
		$freeSize = max(0, $limitSize - $totalSize);

		$this->layout->title = '我的文档';
		$this->layout->nav = '个人中心';
		$this->layout->content = View::make('share.usercenter.document.index')
			->with('user', $user)
			->with('count_document', $count_document)
			->with('count_download', $count_download)
			->with('documents', $documents)
			->with('parent_id', $parent_id)
			->with('folder_id', $folder_id)
			->with('freeSize', $freeSize);
	}

	/**
	 * 已收藏文档
	 */
	function getDocumentFavorite()
	{
		$user = CurrentUserService::$user;
		$favorites = DocumentService::get_favorite($user->userid);
		$count_favorite = DocumentService::count_user_favorite($user->userid);

		$this->layout->title = '已收藏文档';
		$this->layout->nav = '个人中心';
		$this->layout->content = View::make('share.usercenter.document.favorite')
			->with('user', $user)
			->with('count_favorite', $count_favorite)
			->with('favorites', $favorites);
	}

	/**
	 * 已下载文档
	 */
	function getDocumentDownload()
	{
		$user = CurrentUserService::$user;
		$downloads = DocumentService::get_downloads($user->userid);
		$count_download = DocumentService::count_user_download($user->userid);
		$this->layout->title = '已下载文档';
		$this->layout->nav = '个人中心';
		$this->layout->content = View::make('share.usercenter.document.download')
			->with('user', CurrentUserService::$user)
			->with('count_download', $count_download)
			->with('downloads', $downloads);
	}
	/**
	 * 发布文档
	 */
	function anyDocumentPublish()
	{
		$document_id = InputExt::getInt('id');
		if ($document_id < 0) return Redirect::to('/usercenter/document');
		$categories = DocumentService::get_categories();
		$user_id = CurrentUserService::$user_id;
		$document = DocumentService::get($document_id, $user_id, true);

		if (Request::getMethod() == 'POST')
		{
			if (empty($document)) return Redirect::to('/usercenter/document');
			$name = InputExt::xss_clean(trim(Input::get('name')));
			$intro = InputExt::xss_clean(trim(Input::get('intro')));
			$category_id = InputExt::getInt('category_id');
			$tag = InputExt::xss_clean(trim(Input::get('tag')));
			$publish = InputExt::getInt('publish');

			$input = array(
				'name' => $name,
				'intro' => $intro,
				'categoryid' => $category_id,
//				'tag' => $tag
			);
			$rules = array(
				'name'  => 'required',
				'intro' => 'required',
				'categoryid' => 'not_in:0',
//				'tag' => 'required',
			);
			$messages = array(
				'name_required' => '标题不能为空',
				'intro_required' => '简介不能为空',
				'categoryid_not_in' => '未选择分类',
//				'tag_required' => '关键词不能为空',
			);
			$validation = Validator::make($input, $rules, $messages);
			if ($validation->fails())
			{
				return Redirect::to('/usercenter/document/publish?id=' . $document_id);
			}
			DocumentService::publish_document($document_id, $name, $intro, $category_id, $tag, $publish);
			$parentId = DocumentService::get_parent_id($document_id);
			return Redirect::to('/usercenter/document#' . $parentId)->with('information', '文档发布成功, 请等待管理员审核');
		}
		else
		{
			if (empty($document) || $document->status != \Ca\DocumentStatus::normal) return Redirect::to('/usercenter/document');

			$attachments = DocumentService::getAttachments($document_id, $user_id);
			$category = DocumentService::get_document_category($document_id);
			$category_id = (empty($category) ? null : $category->categoryid);
			$catelist = ($category_id == null ? null : array_reverse(DocumentService::document_cateory_list($category_id)));
			$tags = DocumentService::get_tags($document_id);
		}
		$this->layout->title = '我的文档';
		$this->layout->nav = '个人中心';
		$this->layout->content = View::make('share.usercenter.document.publish')
			->with('document', $document)
			->with('document_id', $document_id)
			->with('categories', $categories)
			->with('categoryid', $category_id)
			->with('attachments', $attachments)
			->with('tags', $tags);
	}

	/**
	 * 获取文档列表
	 */
	function postDocumentList()
	{
		$document_id = InputExt::getInt("documentid");
		$user_id = CurrentUserService::$user_id;
		$parent_id = -1; // 顶级
		// 进入文件夹
		if ($document_id > 0)
		{
			if (!CurrentUserService::is_owner($document_id, $user_id) || DocumentService::get_status($document_id) != \Ca\DocumentStatus::normal)
			{
				exit;
			}
			// 获取文档
			$documents = CurrentUserService::documents_by_parent_id($document_id);
			$parent_id = intval(DocumentService::get_parent_id($document_id)); // 没有父级, parent_id = 0
		}
		else // 第一级列表
		{
			$documents = CurrentUserService::top_level_documents();
		}
		View::name('share.layouts.empty', 'empty');
		$this->layout = $layout = View::of('empty');
		$this->layout->content = View::make('share.usercenter.document.list')
			->with('documents', $documents)
			->with('parent_id', $parent_id)
			->with('folder_id', $document_id);
	}

	/**
	 * 移动文件时获取目录列表
	 */
	function postDocumentFolderList()
	{
		$parent_id = InputExt::getInt('parent_id');

		$user_id = CurrentUserService::$user_id;
		$folders = DocumentService::document_list($parent_id, $user_id);

		return Response::json(array("code" => 1, 'folders' => $folders));
	}

	/**
	 * 移动文档
	 */
	function postDocumentMove()
	{
		$document_id = InputExt::getInt("documentid");
		$document_ids = Input::get("documentids");

		if (empty($document_ids)) return Response::json(array("code" => 2));

		if (in_array($document_id, $document_ids)) return Response::json(array("code" => 3));

		// 选择文件夹和当前文件夹一样
		$parent_id = DocumentService::get_parent_id($document_ids[0]);
		if ($parent_id == $document_id) return Response::json(array("code" => 4));

		// 判断接收文档是否是文件夹
		$type = DocumentService::get_type($document_id);
		if ($type == \Ca\DocumentType::file) return Response::json(array("code" => 5));

		$user_id = CurrentUserService::$user_id;
		DocumentService::move($document_ids, $document_id, $user_id);

		return Response::json(array("code" => 1));
	}

	/**
	 * 新建文档
	 */
	function postDocumentNew()
	{
		$user_id = CurrentUserService::$user_id;

		$folder_name = InputExt::xss_clean(trim(Input::get("folder_name")));
		if (empty($folder_name))
		{
			return false;
		}

		$parent_id = InputExt::getInt("parent_id");
		if ($parent_id == 0)
		{
			$parent_id = null;
		}

		$type = InputExt::getInt("type");

		if (!in_array($type, array(\Ca\DocumentType::file, \Ca\DocumentType::folder)))
		{
			return false; // 类型错误
		}

		if (DocumentService::exists($parent_id, $folder_name))
		{
			return Response::json(array(
				"code" => 2,
				"messages" => array("文件夹已存在!")
			));
		}
		$status = \Ca\DocumentStatus::normal;
		$document_id = DocumentService::create($parent_id, $folder_name, $user_id, null, $type, $status);
		$document = array();
		$document['documentid'] = $document_id;
		$document['name'] = $folder_name;
		$document['parentid'] = $parent_id;
		$document['userid'] = $user_id;
		$document['type'] = $type;
		$document['createdate'] = date('Y-m-d');
		return Response::json(array("code" => 1, "document" => $document));
	}

	/**
	 * 修改文档名称
	 */
	function postDocumentChangeName()
	{
		$document_id = InputExt::getInt("documentid");
		if ($document_id == 0) return false;

		$user_id = CurrentUserService::$user_id;
		$name = InputExt::xss_clean(trim(Input::get("name")));

		if (empty($name))
		{
			return false;
		}

		DocumentService::change_name($document_id, $user_id, $name);

		return Response::json(array("code" => 1));
	}

	/**
	 * 删除下载文档
	 */
	function postDocumentDeleteDownload()
	{
		$document_ids = Input::get("eids");
		if (empty($document_ids))
		{
			return false;
		}
		$user_id = CurrentUserService::$user_id;
		foreach ($document_ids as $document_id)
		{
			$document_id = intval($document_id);
			if ($document_id > 0)
			{
				DocumentService::delete_download($document_id, $user_id);
			}
		}
		return Response::json(array("code" => 1));
	}

	/**
	 * 删除文档
	 */
	function postDocumentDelete()
	{
		$document_ids = Input::get("documentids");
		if (empty($document_ids))
		{
			return false;
		}
		$user_id = CurrentUserService::$user_id;
		foreach ($document_ids as $document_id)
		{
			$document_id = intval($document_id);
			if ($document_id > 0)
			{
				DocumentService::delete($document_id, $user_id);
			}
		}
		return Response::json(array("code" => 1));
	}

	/**
	 * 文件上传处理
	 */
	function postDocumentUploads()
	{
		$user_id = intval(\Crypt::decrypt(Input::get('user_id')));
		$parent_id = InputExt::getInt("parent_id");
		if ($parent_id == 0)
		{
			$parent_id = null;
		}
		$target_folder_name = 'documents/files/';
		$target_folder = base_path() . '/content/' . $target_folder_name;
		$target_path = $target_folder;
		$maxsize = Config::get('share.maxsize');
		if (!empty($_FILES))
		{
			$temp_file = $_FILES['Filedata']['tmp_name'];
			$name = $_FILES['Filedata']['name'];
			$size = $_FILES['Filedata']['size'];
			$error = $_FILES['Filedata']['error'];

			if ($error > 0)
			{
				print json_encode(array(
					"code" => 2,
					"messages" => array("未知错误!")
				));
				exit;
			}

			if ($size > $maxsize)
			{
				print json_encode(array(
					"code" => 2,
					"messages" => array("文件超过大小限制!")
				));
				exit;
			}
			$file_types = Config::get('share.allow_extension');
			$file_parts = pathinfo($name);
			$ext = strtolower($file_parts['extension']);
			$target_file_name = str_random(24) . '.' . $ext;
			$target_file = rtrim($target_path, '/') . '/' . $target_file_name;

			$file_name = substr($name, 0, -(strlen($ext) + 1));
			if (in_array($ext, $file_types))
			{
//				print json_encode($temp_file . '--' . $target_file_name . '--' . $target_folder_name); exit;
				$status = Common::upload($temp_file, $target_file_name, $target_folder_name);
				if ($status == false)
				{
					print json_encode(array(
						"code" => 2,
						"messages" => array("未知错误!")
					));
					exit;
				}
				//move_uploaded_file($temp_file, mb_convert_encoding($target_file, 'gbk', 'utf-8'));

				//将txt文件的GBK编码转为utf8
//				if ($ext == 'txt')
//				{
//					$filePath = base_path() . '/content/' . $target_folder_name . $target_file_name;
//					$content = file_get_contents($filePath);
//					$subcontent = substr($content, 0, 120);
//					if ($subcontent === mb_convert_encoding(mb_convert_encoding($subcontent, 'UCS-2', 'GBK'), 'GBK', 'UCS-2'))
//					{
//						$content = mb_convert_encoding($content, 'UCS-2', 'GBK');
//						file_put_contents($filePath, $content);
//					}
//					elseif ($subcontent === mb_convert_encoding(mb_convert_encoding($subcontent, 'UCS-2', 'utf8'), 'utf8', 'UCS-2'))
//					{
//						$content = mb_convert_encoding($content, 'UCS-2', 'utf8');
//						file_put_contents($filePath, $content);
//					}
//				}
				//存入数据库
				$type = \Ca\DocumentType::file;
				$publish = \Ca\DocumentPublish::private_d;
				$from = \Ca\DocumentSource::upload;
				$status = \Ca\DocumentStatus::converting;

				$document_id = DocumentService::create($parent_id, $file_name, $user_id, $ext, $type, $status, $publish, $from);
				DocumentService::create_documentinfo($document_id, $file_name, $size, $target_folder_name . $target_file_name);
				$document = array();
				$document['documentid'] = $document_id;
				$document['name'] = $file_name;
				$document['parentid'] = $parent_id;
				$document['userid'] = $user_id;
				$document['type'] = $type;
				$document['status'] = \Ca\DocumentStatus::converting;
				$document['createdate'] = date('Y-m-d H:i:s');
				print json_encode(array("code" => 1, "document" => $document));
				exit;
			}
			else
			{
				print json_encode(array(
					"code" => 2,
					"messages" => array("文件类型不符合要求!")
				));
				exit;
			}
		}
		exit;
	}

	/**
	 * 我来解答
	 */
	function getKnowsAttention()
	{
		$type = Input::get('type', 'category');
		$limit = Config::get('share.page_usercenter_list');
		$userid = CurrentUserService::$user_id;
		$displayid = InputExt::getInt('displayid') ? InputExt::getInt('displayid') : null;

		$attentionObjects = array();
		switch ($type)
		{
			case 'tag':
				if ($displayid && !AttentionTagService::check($userid, $displayid))
				{
					return Redirect::to('/usercenter/knows?type=' . $type);
				}
				$typeName = '关键词';
				$attentionTags = AttentionTagService::getAttentionTag($userid);
				foreach ($attentionTags as $tag)
				{
					$attentionObjects[] = array('id' => $tag->tagid, 'name' => $tag->name);
				}
				$questions = KnowsService::getQuestionByAttentionTag($userid, $limit, $displayid);
				break;
			case 'category':
			default:
				if ($displayid && !AttentionCategoryService::check($userid, $displayid))
				{
					return Redirect::to('/usercenter/knows?type=' . $type);
				}
				$typeName = '分类';
				$attentionCategories = AttentionCategoryService::getAttentionCategory($userid);
				foreach ($attentionCategories as $category)
				{
					$attentionObjects[] = array('id' => $category->categoryid, 'name' => $category->name);
				}
				$questions = KnowsService::getQuestionByAttentionCategory($userid, $limit, $displayid);
				break;
		}
		$categories = KnowsService::get_category_all();
		$this->layout->title = '我来解答';
		$this->layout->nav = '个人中心';
		$this->layout->content = View::make('share.usercenter.knows.index')
			->with('type', $type)
			->with('typeName', $typeName)
			->with('categories', $categories)
			->with('displayid', $displayid)
			->with('attentionObjects', $attentionObjects)
			->with('questions', $questions);
	}

	/**
	 * 我的提问
	 */
	function getKnowsAsk($customer, $condition = 'all')
	{
		$limit = Config::get('share.page_usercenter_list');
		$user_id = CurrentUserService::$user_id;
		$questions = KnowsService::get_question_by_user($user_id, $limit, $condition);

		$this->layout->title = '我的问题';
		$this->layout->nav = '个人中心';
		$this->layout->content = View::make('share.usercenter.knows.ask')
			->with('condition', $condition)
			->with('questions', $questions);
	}

	/**
	 * 已收藏问答
	 */
	function getKnowsFavorite($customer, $condition = 'all')
	{
		$limit = Config::get('share.page_usercenter_list');
		$user_id = CurrentUserService::$user_id;

		$questions = KnowsService::get_favorite($user_id, $limit, $condition);

		$this->layout->title = '已收藏问题';
		$this->layout->nav = '个人中心';
		$this->layout->content = View::make('share.usercenter.knows.favorite')
			->with('condition', $condition)
			->with('questions', $questions);
	}

	/**
	 * 我的回答
	 */
	function getKnowsAnswer($customer, $condition = 'all')
	{
		$limit = Config::get('share.page_usercenter_list');
		$user_id = CurrentUserService::$user_id;

		$questions = KnowsService::get_question_by_answer_user($user_id, $limit, $condition);

		$this->layout->title = '我参与问题';
		$this->layout->nav = '个人中心';
		$this->layout->content = View::make('share.usercenter.knows.answer')
			->with('condition', $condition)
			->with('questions', $questions);
	}

	/**
	 * 删除问答收藏
	 */
	function postKnowsDeleteFavorite()
	{
		$question_ids = Input::get("eids");
		if (empty($question_ids))
		{
			return false;
		}
		$user_id = CurrentUserService::$user_id;

		KnowsService::delete_favorite($question_ids, $user_id);

		return Response::json(array("code" => 1));
	}

	function postKnowsAddAttentionTag()
	{
		$userid = CurrentUserService::$user_id;
		$limit = 10;
		if (AttentionTagService::countByUser($userid) >= $limit)
		{
			return Response::json(array('status' => 3, 'message' => '你关注的关键词个数已达到上限，请删除后再做添加！'));
		}
		$name = trim(Input::get('name'));
		if ($name == "")
		{
			return Response::json(array('status' => 4, 'message' => '请输入关键词'));
		}
		if (mb_strlen($name) > 20)
		{
			return Response::json(array('status' => 5, 'message' => '您输入的关键词太长拉，重新检查一下吧！'));
		}
		$tag = TagService::get_tag_by_name($name);
		if ($tag == null)
		{
			$tagid = TagService::add($name);
		}
		else
		{
			$tagid = $tag->tagid;
		}
		if (AttentionTagService::check($userid, $tagid))
		{
			return Response::json(array('status' => 2, 'message' => '你输入的关键词已经添加过了，不要重复添加哦！'));
		}
		AttentionTagService::add($userid, $tagid);
		return Response::json(array('status' => 1, 'tag' => array('tagid' => $tagid, 'name' => $name)));
	}

	function postKnowsDeleteAttentionTag()
	{
		$tagid = InputExt::getInt('id');
		$userid = CurrentUserService::$user_id;
		AttentionTagService::delete($userid, $tagid);
		return Response::json(array('status' => 1));
	}

	function postKnowsAddAttentionCategory()
	{
		$categoryid = InputExt::getInt('categoryid');
		$userid = CurrentUserService::$user_id;
		$attentionCategoryLimit = 5;
		if (AttentionCategoryService::countByUser($userid) >= $attentionCategoryLimit)
		{
			return Response::json(array('status' => 3, 'message' => '关注分类已满，删减后可以继续添加'));
		}
		$category = KnowsService::get_category($categoryid);
		if ($category == null || $category->parentid == null)
		{
			return Response::json(array('status' => 4, 'message' => '请选择分类'));
		}
		if (AttentionCategoryService::check($userid, $categoryid))
		{
			return Response::json(array('status' => 2, 'message' => '你选择的分类已经添加过了，不要重复添加哦！'));
		}
		AttentionCategoryService::add($userid, $categoryid);
		return Response::json(array('status' => 1, 'category' => $category));
	}

	function postKnowsDeleteAttentionCategory()
	{
		$categoryid = InputExt::getInt('id');
		$userid = CurrentUserService::$user_id;
		AttentionCategoryService::delete($userid, $categoryid);
		return Response::json(array('status' => 1));
	}

	/**
	 * 参与讲座
	 */

	function getMeeting($customer, $condition = 'all')
	{
		$limit = Config::get('share.page_usercenter_list');
		$user_id = CurrentUserService::$user_id;

		$meetings = MeetingService::get_my_meeting($user_id, $limit, $condition);
		$count_meeting = MeetingService::count_my_meeting($user_id);

		$this->layout->title = '参与讲座';
		$this->layout->nav = '个人中心';
		$this->layout->content = View::make('share.usercenter.meeting.index')
			->with('condition', $condition)
			->with('meetings', $meetings);
	}


//
//	/**
//	 * 新的消息
//	 */
//	function messageNew()
//	{
//		$user_id = CurrentUserService::$user_id;
//		$limit = Config::get('share.page_usercenter_list');
//		$messages = MessageService::get_messages($user_id, true, null, $limit);
//
//		foreach ($messages as $key => $message) {
//			$messages[$key]->content = json_decode($message->content);
//		}
//		MessageService::set_message_read($user_id);
//
//		$this->layout->title = '新的消息';
//		$this->layout->nav = '个人中心';
//		$this->layout->content = View::make('share.usercenter.message.index')
//			->with('messages', $messages);
//	}

	/**
	 * 系统消息
	 */
	function getMessageHistory()
	{
		$user_id = CurrentUserService::$user_id;
		$limit = Config::get('share.page_usercenter_list');
		$messages = MessageService::get_messages($user_id, null, null, $limit);

		foreach ($messages as $key => $message)
		{
			$messages[$key]->content = json_decode($message->content);
		}
		MessageService::set_message_read($user_id);

		$this->layout->title = '系统消息';
		$this->layout->nav = '个人中心';
		$this->layout->content = View::make('share.usercenter.message.index')
			->with('messages', $messages);
	}
	/**
	 * 删除消息记录
	 */
	function postMessageDelete()
	{
		$userId = CurrentUserService::$user_id;
		$messageIds = InputExt::get('eids');
		MessageService::delete_message($userId, $messageIds);
		return Response::json(array("code" => 1));

	}


	/**
	 * 我的专题
	 */
	function getTopic()
	{
		$limit = Config::get('share.page_usercenter_list');
		$user_id = CurrentUserService::$user_id;
		$topics = TopicService::getTopicByUser($user_id, $limit);

		$this->layout->title = '我的专题';
		$this->layout->nav = '个人中心';
		$this->layout->content = View::make('share.usercenter.topic.index')
			->with('topics', $topics);
	}

	/**
	 * 新建专题
	 */
	function anyTopicNew()
	{
		if (Request::getMethod() == 'POST')
		{
			$userid = CurrentUserService::$user_id;
			$name = InputExt::get('name');
			$intro = InputExt::get('intro');
			$documenturls = InputExt::get('documenturl');

			if ($name == '' || $intro == '')
			{
				exit;
			}
			$document_ids = array();
			foreach ($documenturls as $documenturl)
			{
				$documentid = self::checkDocumentUrl($documenturl);
				if ($documentid)
				{
					$document_ids[] = $documentid;
				}
			}
			$document_ids = array_unique($document_ids);
			if (count($document_ids) < 3)
			{
				exit;
			}
			$topicid = TopicService::addTopic($userid, $name, $intro);
			TopicService::addTopicDocument($topicid, $document_ids);
			return Redirect::to('/usercenter/topic')->with('information', '新建专题成功');
		}

		$this->layout->title = '我的专题';
		$this->layout->nav = '个人中心';
		$this->layout->content = View::make('share.usercenter.topic.new');
	}
	/**
	 * 修改专题
	 */
	function anyTopicModify()
	{
		$userid = CurrentUserService::$user_id;
		$topicid = InputExt::getInt('id');
		if (Request::getMethod() == 'POST')
		{
			$name = trim(InputExt::get('name'));
			$intro = trim(InputExt::get('intro'));
			if ($name == '' || $intro == '')
			{
				exit;
			}
			TopicService::updateTopic($userid, $topicid, $name, $intro);
//			return Redirect::to('/usercenter/topic/modify?id=' . $topicid);
			return Redirect::to('/usercenter/topic')->with('information', '修改专题成功');
		}
		else
		{
			$topic = TopicService::getTopic($topicid);
			if ($topic == null || $topic->userid != $userid)
			{
				return Redirect::to('/usercenter/topic');
			}
			$limit = Config::get('share.page_usercenter_list');
			$documents = TopicService::getTopicDocument($topicid, $limit);
			$this->layout->title = '我的专题';
			$this->layout->nav = '个人中心';
			$this->layout->content = View::make('share.usercenter.topic.modify')
				->with('topic', $topic)
				->with('documents', $documents);
		}
	}

	/**
	 * 删除专题
	 */
	function postTopicDelete()
	{
		$userid = CurrentUserService::$user_id;
		$topic_ids = InputExt::get('eids');
		TopicService::deleteTopic($userid, $topic_ids);
		return Response::json(array("code" => 1));
	}

	/**
	 * 删除专辑内文档
	 */
	function postTopicDeleteDocument()
	{
		$userid = CurrentUserService::$user_id;
		$topicid = InputExt::getInt('topicid');
		$documentids = InputExt::get('eids');
		if (!TopicService::checkTopicUser($topicid, $userid))
		{
			return Response::json(array('status' => 0));
		}
		TopicService::deleteTopicDocument($topicid, $documentids);
		return Response::json(array('status' => 1));
	}

	/**
	 * 添加专辑内文档
	 */
	function postTopicAddDocument()
	{
		$userid = CurrentUserService::$user_id;
		$topicid = InputExt::getInt('topicid');
		if (!TopicService::checkTopicUser($topicid, $userid))
		{
			return Response::json(array('status' => 0));
		}
		$documenturls = InputExt::get('documenturl');
		$document_ids = array();
		$documents = array();
		foreach ($documenturls as $documenturl)
		{
			$documentid = self::checkDocumentUrl($documenturl, $topicid);
			if ($documentid)
			{
				$document_ids[] = $documentid;
				$documents[] = DocumentService::get_document($documentid);
			}
		}
		TopicService::addTopicDocument($topicid, $document_ids);
		return Response::json(array('status' => 1, 'documents' => $documents));
	}

	/**
	 * @param $documenturl
	 * @param $topicid
	 * @return bool
	 * 检查文档url是否合法 私有方法
	 */
	private function checkDocumentUrl($documenturl, $topicid = null)
	{
		$pattern = '/^http:\/\/share.' . app()->environment(). '\/document\/detail\?id=(\d+)/';
		if (!preg_match($pattern, $documenturl, $matches))
		{
			return false;
		}
		$documentid = $matches[1];
		if (!DocumentService::check_document($documentid))
		{
			return false;
		}
		if ($topicid != null && TopicService::checkTopicDocument($topicid, $documentid))
		{
			return false;
		}
		return $documentid;
	}

	/**
	 * @return mixed
	 * ajax调用，检查文档url是否合法
	 */
	function postAjaxCheckDocumentUrl()
	{
		$documenturl = InputExt::get('fieldValue');
		$fieldId = InputExt::get('fieldId');
		$topicid = InputExt::getInt('topicid');
		if ($documenturl == '') {
			return Response::json(array($fieldId, true));
		}
		$pattern = '/^http:\/\/share.' . app()->environment() . '\/document\/detail\?id=(\d+)/';
		if (!preg_match($pattern, $documenturl, $matches))
		{
			return Response::json(array($fieldId, false, '该文档地址不可用'));
		}
		$documentid = $matches[1];
		if (!DocumentService::check_document($documentid))
		{
			return Response::json(array($fieldId, false, '该文档地址不可用'));
		}
		if ($topicid && TopicService::checkTopicDocument($topicid, $documentid))
		{
			return Response::json(array($fieldId, false, '该文档地址已存在'));
		}
		return Response::json(array($fieldId, true, '该文档地址可用'));
	}

	function getTopicFavorite()
	{
		$limit = Config::get('share.page_usercenter_list');
		$user_id = CurrentUserService::$user_id;
		$topics = DocumentService::getFavoriteTopic($user_id, $limit);

		$this->layout->title = '已收藏专题';
		$this->layout->nav = '个人中心';
		$this->layout->content = View::make('share.usercenter.topic.favorite')
			->with('topics', $topics);
	}

	function postTopicDeleteFavorite()
	{
		$topicids = Input::get('eids');
		$userid = CurrentUserService::$user_id;
		foreach ($topicids as $topicid)
		{
			$topicid = intval($topicid);
			if ($topicid > 0)
			{
				DocumentService::DeleteFavoriteTopic($topicid, $userid);
			}
		}
		return Response::json(array('code' => 1));
	}

	/**
	 * 计算用户容量使用  Ajax
	 * @return mixed
	 */
	public function postCheckfreespace()
	{
		$userid = CurrentUserService::$user_id;
		$totalSize = DocumentService::GetTotalSize($userid);
		$limitSize = ParamsService::get('maxuploadlimit') * 1024 * 1024; // 单位为MB
		$freeSize = max(0, $limitSize - $totalSize);
		$ret = array(
			'raw' => array(
				'limit' => $limitSize,
				'used'  => $totalSize,
				'free'  => $freeSize
			),
			'format' => array(
				'limit' => \Ca\Common::format_filesize($limitSize),
				'used'  => \Ca\Common::format_filesize($totalSize),
				'free'  => \Ca\Common::format_filesize($freeSize)
			)
		);
		return Response::json($ret);
	}


	/**
	 * 附件上传处理
	 */
	public function postDocumentAttachment()
	{
//		var_dump($_FILES);
		$user_id = CurrentUserService::$user_id;
		$parentid = InputExt::getInt('documentid');
		$target_folder_name = 'documents/files/';
		$target_folder = base_path() . '/content/' . $target_folder_name;
		$target_path = $target_folder;
		$maxsize = ParamsService::get('maxuploadlimit');

		if (!empty($_FILES))
		{
			$temp_file = $_FILES['Filedata']['tmp_name'];
			$name = $_FILES['Filedata']['name'];
			$size = $_FILES['Filedata']['size'];
			$error = $_FILES['Filedata']['error'];

			if ($error > 0)
			{
				print json_encode(array(
					"status" => 2,
					"messages" => array("未知错误!")
				));
				exit;
			}
			if ($size > $maxsize * 1024 * 1024)
			{
				print json_encode(array(
					"status" => 2,
					"messages" => array("文件超过大小限制!")
				));
				exit;
			}
			$file_types = Config::get('share.allow_extension');
			$file_parts = pathinfo($name);
			$ext = strtolower($file_parts['extension']);
			$target_file_name = str_random(24) . '.' . $ext;
//			$target_file = rtrim($target_path, '/') . '/' . $target_file_name;

			$file_name = substr($name, 0, -(strlen($ext) + 1));
			if (in_array($ext, $file_types))
			{
				$status = Common::upload($temp_file, $target_file_name, $target_folder_name);
				if ($status == false)
				{
					print json_encode(array(
						"status" => 2,
						"messages" => array("未知错误!")
					));
					exit;
				}
				//存入数据库
				$type = \Ca\DocumentType::attachment;
//				$publish = \Ca\DocumentPublish::private_d;
//				$from = \Ca\DocumentSource::upload;
				$status = \Ca\DocumentStatus::normal;

				$document_id = DocumentService::create($parentid, $file_name, $user_id, $ext, $type, $status);
				DocumentService::create_documentinfo($document_id, $file_name, $size, $target_folder_name . $target_file_name);

				print json_encode(array(
					"status" => 1, "documentid" => $document_id
				));
				exit;
			}

		}

		print json_encode(array(
			"status" => 2, "messages" => array("未知错误!")
		));
		exit;
	}

	public function postDocumentDeleteAttachment()
	{
		$documentid = InputExt::getInt('documentid');
		$userid = CurrentUserService::$user_id;
		DocumentService::delete($documentid, $userid);
		return Response::json(array('status' => 1));
	}

}