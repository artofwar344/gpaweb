<?php
namespace Share;
use \Response,
	Ca\Service\MessageService,
	Ca\Service\CurrentUserService;

/**
 * 系统消息相关
 * Class MessageController
 * @package Share
 */
class MessageController extends BaseController {

	/**
	 * ajax 获取未读消息
	 * @return mixed
	 */
	public function postNewMessage()
	{
		$user_id = CurrentUserService::$user_id;
		$messages = MessageService::count_new_messages_group_type($user_id);
		if (count($messages) > 0)
		{
			return Response::json(array('status' => 1, 'messages' => $messages));
		}
		else
		{
			return Response::json(array('status' => 2));
		}
	}

}