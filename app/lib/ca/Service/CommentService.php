<?php
namespace Ca\Service;

use \DB;

class CommentService {
	public static function getCommentByTargetId($targetid, $type, $limit)
	{
		return DB::table('comment')
			->select(array('comment.*', 'user.name as userName'))
			->leftJoin('user', 'user.userid', '=', 'comment.userid')
			->where('comment.targetid', '=', $targetid)
			->where('comment.type', '=', $type)
			->where('comment.status', '=', \Ca\CommentStatus::normal)
			->orderBy('comment.createdate', 'desc')
			->paginate($limit);
	}

	public static function addComment($userid, $targetid, $content, $type)
	{
		$data = array(
			'userid' => $userid,
			'targetid' => $targetid,
			'content' => $content,
			'type' => $type,
			'status' => \Ca\commentStatus::normal,
		);
		return DB::table('comment')->insertGetId($data);
	}

	public static function checkExists($commentid)
	{
		return DB::table('comment')
			->where('commentid', '=', $commentid)
			->count() > 0;
	}
}