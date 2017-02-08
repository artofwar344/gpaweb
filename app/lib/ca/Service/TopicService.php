<?php
namespace Ca\Service;
use DB,
	DBExt;
class TopicService {

	public static function getTopic($topicid)
	{
		return DB::table('topic')
			->where('topicid', '=', $topicid)
			->where('status', '=', \Ca\TopicStatus::normal)
			->first();
	}

	/**
	 * @param $topicid
	 * @return mixed
	 * 获取专题详细信息
	 */
	public static function getTopicDetail($topicid)
	{
		$favoriteUserCountQuery = DB::table('topicfavorite')
			->select(array(DB::raw('COUNT(DISTINCT topicfavorite.userid)')))
			->where('topicfavorite.topicid', '=', DB::Raw($topicid));
		$topicRatingQuery = DB::table('topic')
			->select(array(
				'topic.topicid',
				DB::raw('IFNULL(FORMAT(AVG(documentrating.rating), 1), 0) AS topic_score'),
				DB::raw('COUNT(documentrating.userid) AS rating_user_count'),
			))
			->leftJoin('topic__document', 'topic__document.topicid', '=', 'topic.topicid')
			->leftJoin('documentrating', 'documentrating.documentid', '=', 'topic__document.documentid')
			->where('topic.topicid', '=', DB::Raw($topicid));

		$query = DB::table('topic')
			->select(array('topic.*', 'user.name AS user_name','topicrating.topic_score', 'topicrating.rating_user_count',
				DB::raw('IFNULL(topic.updatedate, topic.createdate) AS updatedate'),
				DB::raw('(' . DBExt::get_sql($favoriteUserCountQuery) . ') AS favorite_user_count')))
			->leftJoin('user', 'user.userid', '=', 'topic.userid')
			->leftJoin(
				DB::raw('(' . DBExt::get_sql($topicRatingQuery) . ') AS topicrating'),
				'topicrating.topicid', '=', 'topic.topicid'
			)
			->where('topic.topicid', '=', $topicid)
			->where('topic.status', '=', \Ca\TopicStatus::normal);
		return $query->first();
	}

	/**
	 * @param $limit
	 * @return mixed
	 * 获取专题列表
	 */
	public static function getTopics($limit)
	{
		$favoriteUserCountQuery = DB::table('topicfavorite')
			->select(array(DB::raw('COUNT(DISTINCT topicfavorite.userid)')))
			->where('topicfavorite.topicid', '=', DB::raw('topic.topicid'));
		$topicRatingQuery = DB::table('topic')
			->select(array(
				'topic.topicid',
				DB::raw('IFNULL(FORMAT(AVG(documentrating.rating), 1), 0) AS topic_score'),
				DB::raw('COUNT(documentrating.userid) AS rating_user_count'),
			))
			->leftJoin('topic__document', 'topic__document.topicid', '=', 'topic.topicid')
			->leftJoin('documentrating', 'documentrating.documentid', '=', 'topic__document.documentid')
			->groupBy('topic.topicid');

		$query = DB::table('topic')
			->select(array('topic.*', 'user.name AS user_name','topicrating.topic_score', 'topicrating.rating_user_count',
				DB::raw('IFNULL(topic.updatedate, topic.createdate) AS updatedate'),
				DB::raw('(' . DBExt::get_sql($favoriteUserCountQuery) . ') AS favorite_user_count')))
			->leftJoin('user', 'user.userid', '=', 'topic.userid')
			->leftJoin(
				DB::raw('(' . DBExt::get_sql($topicRatingQuery) . ') AS topicrating'),
				'topicrating.topicid', '=', 'topic.topicid'
			)
			->where('topic.status', '=', \Ca\TopicStatus::normal)
			->orderBy('topic.createdate', 'desc');
//		echo \DBExt::get_sql($query); exit;
		return $query->paginate($limit);

	}

	/**
	 * @param $userid
	 * @param $limit
	 * @return mixed
	 * 获取某个用户的专题列表
	 */
	public static function getTopicByUser($userid, $limit)
	{
		return DB::table('topic')
			->where('userid', '=', $userid)
			->where('status', '=', \Ca\TopicStatus::normal)
			->orderBy('createdate', 'desc')
			->paginate($limit);
	}

	/**
	 * @param $userid
	 * @param $name
	 * @param $intro
	 * @return mixed
	 * 新增专题
	 */
	public static function addTopic($userid, $name, $intro)
	{
		$data = array(
			'userid' => $userid,
			'name' => $name,
			'intro' => $intro,
		);
		return DB::table('topic')->insertGetId($data);
	}


	/**
	 * @param $topicid
	 * @param $documentIds
	 * 为专题添加文档
	 */
	public static function addTopicDocument($topicid, $documentIds)
	{
		if (!is_array($documentIds))
		{
			$documentIds = array($documentIds);
		}

		foreach ($documentIds as $documentid)
		{
			$data = array(
				'topicid' => $topicid,
				'documentid' => $documentid
			);
			DB::table('topic__document')->insert($data);
		}
	}

	/**
	 * @param $userid
	 * @param $topicIds
	 * 删除专题
	 */
	public static function deleteTopic($userid, $topicIds)
	{
		if (!is_array($topicIds))
		{
			$topicIds = array($topicIds);
		}
		DB::table('topic')
			->whereIn('topicid', $topicIds)
			->where('userid', '=', $userid)
			->delete();
	}

	/**
	 * @param $topicid
	 * @param $userid
	 * @return bool
	 * 检查专题是否属于用户
	 */
	public static function checkTopicUser($topicid, $userid)
	{
		return DB::table('topic')
			->where('topicid', '=', $topicid)
			->where('userid', '=', $userid)
			->count() > 0;
	}

	/**
	 * @param $topicid
	 * @param $documentid
	 * @return bool
	 * 检查文档是否在专题中
	 */
	public static function checkTopicDocument($topicid, $documentid)
	{
		return DB::table('topic__document')
			->where('topicid', '=', $topicid)
			->where('documentid', '=', $documentid)
			->count() > 0;
	}

	/**
	 * @param $topicid
	 * @param $limit
	 * @return mixed
	 * 根据专题获取文档
	 */
	public static function getTopicDocument($topicid, $limit)
	{
		$queryCountRating = DB::table('documentrating')
				->select(array(DB::raw('COUNT(documentrating.userid)')))
				->where('documentrating.documentid', '=', DB::raw('topic__document.documentid'))->toSql();
		$queryCountDownload = DB::table('document__download')
				->select(array(DB::raw('COUNT(document__download.userid)')))
				->where('document__download.documentid', '=', DB::raw('topic__document.documentid'))->toSql();
		$queryDocumentScore = DB::table('documentrating')
				->select(array(DB::raw('IFNULL(FORMAT(AVG(documentrating.rating), 1), 0)')))
				->where('documentrating.documentid', '=', DB::raw('topic__document.documentid'))->toSql();

		return DB::table('topic__document')
			->select(array('document.*', 'user.name as user_name',
					DB::raw('(' . $queryCountRating . ') AS count_rating'),
					DB::raw('(' . $queryCountDownload . ') AS count_download'),
					DB::raw('(' . $queryDocumentScore . ') AS document_score'))
			)
			->leftJoin('document', 'document.documentid', '=', 'topic__document.documentid')
			->leftJoin('user', 'document.userid', '=', 'user.userid')
			->leftJoin('topic', 'topic.topicid', '=', 'topic__document.topicid')
			->where('topic__document.topicid', '=', $topicid)
			->where('topic.status', '=', \Ca\TopicStatus::normal)
			->where('document.status', '=', \Ca\DocumentStatus::normal)
			->paginate($limit);
	}

	/**
	 * @param $topicid
	 * @param $documentids
	 * 删除专题内文档
	 */
	public static function deleteTopicDocument($topicid, $documentids)
	{
		if (!is_array($documentids))
		{
			$documentids = array($documentids);
		}
		DB::table('topic__document')
			->whereIn('documentid', $documentids)
			->where('topicid', '=', $topicid)
			->delete();
	}

	/**
	 * @param $topicid
	 * 专题浏览量加1
	 */
	public static function increaseTopicViews($topicid)
	{
		DB::table('topic')->where('topicid', '=', $topicid)->update(array('views' => DB::raw('views + 1')));
	}

	/**
	 * @param $userid
	 * @param $topicid
	 * @param $name
	 * @param $intro
	 * 更新专题
	 */
	public static function updateTopic($userid, $topicid, $name, $intro)
	{
		DB::table('topic')
			->where('userid', '=', $userid)
			->where('topicid', '=', $topicid)
			->update(array('name' => $name, 'intro' => $intro, 'updatedate' => date('Y-m-d H:i:s')));
	}

	/**
	 * 获取专题排行
	 */
	public static function getTopicRank($limit)
	{
		return DB::table('topic')
			->where('status', '=', \Ca\TopicStatus::normal)
			->orderBy('views', 'DESC')
			->take($limit)
			->get();
	}

	public static function checkFavorite($userid, $topicid)
	{
		return DB::table('topicfavorite')
			->where('userid', '=', $userid)
			->where('topicid', '=', $topicid)
			->count() > 0;
	}

	public static function addFavorite($userid, $topicid)
	{
		DB::table('topicfavorite')->insert(array('userid' => $userid, 'topicid' => $topicid));
	}


}