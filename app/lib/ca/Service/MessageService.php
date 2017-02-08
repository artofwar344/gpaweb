<?php
namespace Ca\Service;
use Illuminate\Support\Facades\DB;
class MessageService {

	/**
	 * 创建系统消息
	 * @param $toid
	 * @param $content
	 * @param $type
	 * @return mixed
	 */

	public static function create_system_message($toid, $content, $type)
	{
		$fromid = null;
		$data = array(
			'fromid' => $fromid,
			'toid' => $toid,
			'content' => $content,
			'type' => $type,
			'status' => \Ca\MessageStatus::unread,
		);
		return DB::table('message')->insertGetId($data);
	}

	/**
	 * 创建用户消息
	 * @param $fromid
	 * @param $toid
	 * @param $content
	 * @param $type
	 * @return bool
	 */

	public static function create_user_message($fromid, $toid, $content, $type)
	{
		if ($fromid == null)
		{
			return false;
		}
		$data = array(
			'fromid' => $fromid,
			'toid' => $toid,
			'content' => $content,
			'type' => $type,
			'status' => \Ca\MessageStatus::unread,
		);
		return DB::table('message')->insertGetId($data);
	}

	/**
	 * 获取未读消息个数
	 * @param $userid
	 * @return bool
	 */
	public static function count_system_new($userid)
	{
		if ($userid <= 0)
		{
			return false;
		}
		return DB::table('message')
			->where('toid', '=', $userid)
			->where('status', '=', \Ca\MessageStatus::unread)
			->whereNull('fromid')
			->count();
	}


	/**
	 * @param $userid
	 * @return bool
	 * 按种类获取消息条数
	 */
	public static function count_new_messages_group_type($userid)
	{
		if ($userid <= 0)
		{
			return false;
		}
		return DB::table('message')
			->select(array('type', DB::raw('COUNT(messageid) AS message_count')))
			->where('toid', '=', $userid)
			->where('status', '=', \Ca\MessageStatus::unread)
			->groupBy('type')
			->get();
	}

	/**
	 * @param $messageid
	 * @param $userid
	 * @return bool
	 * 获取单条消息
	 */
	public static function get_message($messageid, $userid)
	{
		if ($userid <= 0)
		{
			return false;
		}
		return DB::table('message')
			->where('messageid', '=', $messageid)
			->where('toid', '=', $userid)
			->first();
	}

	/**
	 * @param $userid
	 * @param null $new 为null时返回所有消息，true时只返回未读消息, false返回已读消息
	 * @param null $type
	 * @param null $limit
	 * @return array | object
	 * 获取用户系统消息
	 */
	public static function get_messages($userid, $new = null, $type = null, $limit = null)
	{
		if ($userid <= 0)
		{
			return array();
		}

		$query = DB::table('message')
			->where('toid', '=', $userid)
			->orderBy('status')
			->orderBy('createdate', 'desc');

		if($new !== null)
		{
			if ($new)
			{
				$query->where('status', '=', \Ca\MessageStatus::unread);
			}
			else
			{
				$query->where('status', '=', \Ca\MessageStatus::read);
			}
		}
		if ($type != null)
		{
			if (!is_array($type))
			{
				$type = array($type);
			}
			$query->whereIn('type', $type);
		}

		if ($limit != null)
		{
			return $query->paginate($limit);
		}
		else
		{
			return $query->get();
		}
	}

	/**
	 * @param $questionid
	 * @param $answerid
	 * @return bool
	 *
	 */
	public static function create_system_message_new_answer($questionid, $answerid)
	{
		$question = DB::table('question')
			->where('questionid', '=', $questionid)
			->first();
		$answer = DB::table('answer')
			->select(array('answer.*','user.name'))
			->leftJoin('user', 'user.userid', '=', 'answer.userid')
			->where('answerid', '=', $answerid)
			->first();
		if ($question == null || $answer == null)
		{
			return false;
		}
		$toid = $question->userid;
		$type = \Ca\MessageType::getNewAnswer;
		$content_data = array(
			'questionid' => $question->questionid,
			'question_title' => $question->title,
			'answer_userid' => $answer->userid,
			'answer_username' => $answer->name,
			'answer_date' => $answer->createdate,
		);
		$content = json_encode($content_data);
		self::create_system_message($toid, $content, $type);
	}

	public static function create_system_message_accept_answer($questionid, $answerid)
	{
		$question = DB::table('question')
			->where('questionid', '=', $questionid)
			->first();
		$answer = DB::table('answer')
			->select(array('answer.userid'))
			->where('answerid', '=', $answerid)
			->first();
//		$users = DB::table('answer')
//			->select(array(DB::raw('DISTINCT(userid)')))
//			->where('questionid', '=', $questionid)
//			->get();
		if ($question == null || $answer == null)
		{
			return false;
		}
		$toid = $answer->userid;
		$type = \Ca\MessageType::acceptAnswer;
		$content_data = array(
			'questionid' => $question->questionid,
			'question_title' => $question->title,
		);
		$content = json_encode($content_data);
		self::create_system_message($toid, $content, $type);
	}

	/**
	 * 将未读信息标记为已读
	 */
	public static function set_message_read($userid, $messageids = null)
	{
		if ($userid <= 0)
		{
			return false;
		}
		$query = DB::table('message')
			->where('toid', '=', $userid)
			->where('status', '=', \Ca\MessageStatus::unread);
		if ($messageids != null)
		{
			if (!is_array($messageids))
			{
				$messageids = array($messageids);
			}
			$query->whereIn('messageid', $messageids);
		}
		$query->update(array('status' => \Ca\MessageStatus::read));
	}


	public static function delete_message($userid, $messageids)
	{
		DB::table('message')
			->where('toid', '=', $userid)
			->whereIn('messageid', $messageids)
			->delete();
	}

	public static function createSystemMessageAskMore($questionid, $answerid)
	{
		$question = DB::table('question')
			->where('questionid', '=', $questionid)
			->where('status', '=', \Ca\QuestionStatus::normal)
			->first();
		$answer = DB::table('answer')
			->select(array('answer.userid'))
			->where('answerid', '=', $answerid)
			->where('status', '=', \Ca\AnswerStatus::normal)
			->first();
		if ($question == null || $answer == null)
		{
			return false;
		}
		$toid = $answer->userid;
		$type = \Ca\MessageType::moreAnswer;
		$content_data = array(
			'questionid' => $question->questionid,
			'question_title' => $question->title,
		);
		$content = json_encode($content_data);
		self::create_system_message($toid, $content, $type);
	}

	public static function createSystemMessageUpdateQuestion($questionid)
	{
		$question = DB::table('question')
			->where('questionid', '=', $questionid)
			->where('status', '=', \Ca\QuestionStatus::normal)
			->first();
		if ($question == null)
		{
			return false;
		}
		$answers = DB::table('answer')
			->select(array(DB::raw('distinct answer.userid')))
			->where('questionid', '=', $questionid)
			->where('status', '=', \Ca\AnswerStatus::normal)
			->where('type', '=', \Ca\AnswerType::normal)
			->get();
		foreach ($answers as $answer)
		{
			$toid = $answer->userid;
			$type = \Ca\MessageType::updateQuestion;
			$content_data = array(
				'questionid' => $question->questionid,
				'question_title' => $question->title,
			);
			$content = json_encode($content_data);
			$message = DB::table('message')
				->where('toid', '=', $toid)
				->where('status', '=', \Ca\MessageStatus::unread)
				->where('type', '=', \Ca\MessageType::updateQuestion)
				->where('content', '=', $content)
				->first();
			if ($message != null)
			{
				self::updateSystemMessage($message->messageid, array('createdate' => date('Y-m-d H:i:s')));
			}
			else
			{
				self::create_system_message($toid, $content, $type);
			}
		}
	}

	public static function updateSystemMessage($messageid, $field)
	{
		DB::table('message')
			->where('messageid', '=', $messageid)
			->update($field);
	}
}