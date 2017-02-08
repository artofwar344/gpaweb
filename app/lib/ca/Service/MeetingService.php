<?php
namespace Ca\Service;
use Illuminate\Support\Facades\DB;
class MeetingService {

	public static function get_meeting($meetingid)
	{
		return DB::table('meeting')
			->select(array('meeting.*', DB::raw('COUNT(meeting__user.userid) as enroll_count'),
				DB::raw('DATE_FORMAT(begindate, "%Y-%m-%d %H:%i") as begindate'),
				DB::raw('DATE_FORMAT(enddate, "%Y-%m-%d %H:%i") as enddate'),
				DB::raw('DATE_FORMAT(enrolldate, "%Y-%m-%d %H:%i") as enrolldate'),
			))
			->leftJoin('meeting__user', 'meeting.meetingid', '=', 'meeting__user.meetingid')
			->where('meeting.meetingid', '=', $meetingid)
			->groupBy('meeting.meetingid')
			->first();
	}

	/**
	 * 获取讲座
	 * @param int $limit
	 * @param bool $page 是否分页
	 * @param null $can_apply null-所有 true-正在报名  false-报名结束
	 * @return mixed
	 */
	public static function get_all_meeting($limit = 10, $page = false, $can_apply = null)
	{
		$query = DB::table('meeting')
			->select(array('*', DB::raw('(SELECT COUNT(userid) FROM meeting__user where meeting__user.meetingid = meeting.meetingid )as apply_count'),
				DB::raw('IF(enrolldate > NOW(), "0", "1") AS is_end')))
			->where('status', '=', \Ca\MeetingStatus::normal)
			->orderBy('meetingid', 'desc');
		if ($can_apply !== null)
		{
			if ($can_apply) $query->where(DB::raw('UNIX_TIMESTAMP(enrolldate)'), '>', time());
			else $query->where(DB::raw('UNIX_TIMESTAMP(enrolldate)'), '<=', time());
		}
		if ($page)
		{
			$meetings = $query->paginate($limit);
		}
		else
		{
			$meetings = $query->take($limit)->get();
		}
		if (!empty($meetings))
		{
			self::get_tag_by_meeting($meetings);
		}
		return $meetings;
	}

	public static function check_apply($meeting_id, $user_id)
	{
		$count = DB::table('meeting__user')
			->where('meetingid', '=', $meeting_id)
			->where('userid', '=', $user_id)
			->count();
		return $count > 0;
	}

	public static function apply($meeting_id, $user_id)
	{
		DB::table('meeting__user')
			->insert(array('meetingid' => $meeting_id, 'userid' => $user_id));
	}

	public static function count_apply($meeting_id)
	{
		return DB::table('meeting__user')
			->where('meetingid', '=', $meeting_id)
			->count();
	}

	public static function add_meeting($name, $intro, $begindate, $enddate, $enrolldate, $address, $contact_name, $contact_phone, $contact_email, $cost = 0)
	{
		$data = array(
			'name' => $name,
			'intro' => $intro,
			'begindate' => $begindate,
			'enddate' => $enddate,
			'enrolldate' => $enrolldate,
			'address' => $address,
			'contactname' => $contact_name,
			'contactphone' => $contact_phone,
			'contactemail' => $contact_email,
			'cost' => $cost
		);
		return DB::table('meeting')->insertGetId($data);
	}

	public static function get_tag_by_meeting(&$meetings)
	{
		$meeting_ids = array();
		if (!empty($meetings))
		{
			foreach ($meetings as $meeting)
			{
				$meeting_ids[] = $meeting->meetingid;
			}
			$tags = DB::table('meeting__tag')
				->leftJoin('tag', 'tag.tagid', '=', 'meeting__tag.tagid')
				->whereIn('meetingid', $meeting_ids)
				->get();

			$meeting_tags = array();
			foreach ($tags as $tag)
			{
				$meeting_tags[$tag->meetingid][] = $tag;
			}

			foreach ($meetings as $key => $meeting)
			{
				$meetings[$key]->tags = $meeting_tags[$meeting->meetingid];
			}
		}
	}

	public static function get_meeting_by_tag($tag_id, $limit = 10, $page = true)
	{
		$query = DB::table('meeting')
			->select(array('meeting.*', DB::raw('(SELECT COUNT(userid) FROM meeting__user where meeting__user.meetingid = meeting.meetingid )as apply_count'),
				DB::raw('IF(UNIX_TIMESTAMP(enrolldate) > ' . time() . ', "0", "1") AS is_end')))
			->leftJoin('meeting__tag', 'meeting__tag.meetingid', '=', 'meeting.meetingid')
			->where('tagid', '=', $tag_id)
			->where('meeting.status', '=', \Ca\MeetingStatus::normal)
			->orderBy('is_end')
			->orderBy('meeting.meetingid', 'desc');

		if ($page)
		{
			$meetings = $query->paginate($limit);
		}
		else
		{
			$meetings = $query->take($limit)->get();
		}
		if (count($meetings) > 0)
		{
			self::get_tag_by_meeting($meetings);
		}

		return $meetings;
	}

	public static function add_meeting_tag($meeting_id, $new_tag_names)
	{
		$tag_ids = TagService::add_tags($new_tag_names);
		DB::table('meeting__tag')->where('meetingid', '=', $meeting_id)->delete();
		if (!empty($tag_ids))
		{
			$values = array();
			foreach ($tag_ids as $tag_id)
			{
				$values[] = array('meetingid' => $meeting_id, 'tagid' => $tag_id);
			}
			DB::table('meeting__tag')->insert($values);
		}
	}

	public static function get_hot_tag($limit = 20)
	{
		return DB::table('tag')
			->select(array('tag.tagid', 'name', DB::raw('COUNT(meeting__tag.tagid) AS count_tag')))
			->leftJoin('meeting__tag', 'meeting__tag.tagid', '=', 'tag.tagid')
			->groupBy('tag.tagid')
			->having('count_tag', '>', 0)
			->orderBy('count_tag', 'DESC')
			->take($limit)
			->get();
	}

	public static function get_hot_meeting()
	{
		return DB::table('meeting')
			->select(array('meeting.meetingid', 'name', DB::raw('COUNT(meeting__user.userid) AS apply_count')))
			->leftJoin('meeting__user', 'meeting__user.meetingid', '=', 'meeting.meetingid')
			->where(DB::raw('UNIX_TIMESTAMP(enrolldate)'), '>', time())
			->where('meeting.status', '=', \Ca\MeetingStatus::normal)
			->groupBy('meeting.meetingid')
			->orderBy('apply_count', 'DESC')
			->take(10)
			->get();
	}

	public static function get_my_meeting($userid, $limit, $condition = 'all')
	{
		$query = DB::table('meeting__user')
			->select(array('meeting.*',
				DB::raw('(SELECT COUNT(userid) FROM meeting__user where meeting__user.meetingid = meeting.meetingid )as apply_count'),
				DB::raw('IF(enrolldate > NOW(), "0", "1") AS is_end')))
			->leftJoin('meeting', 'meeting.meetingid', '=', 'meeting__user.meetingid')
			->where('meeting__user.userid', '=', $userid)
			->where('meeting.status', '=', \Ca\MeetingStatus::normal)
			->orderBy('meeting.enrolldate', 'DESC');
		if ($condition == 'active')
		{
			$query->where(DB::raw('UNIX_TIMESTAMP(meeting.enrolldate)'), '>', time());
		}
		elseif ($condition == 'over')
		{
			$query->where(DB::raw('UNIX_TIMESTAMP(meeting.enrolldate)'), '<=', time());
		}
		$meetings = $query->paginate($limit);
		return $meetings;
	}

	public static function count_my_meeting($userid)
	{
		return DB::table('meeting__user')
			->leftJoin('meeting', 'meeting.meetingid', '=', 'meeting__user.meetingid')
			->where('meeting__user.userid', '=', $userid)
			->where('meeting.status', '=', \Ca\MeetingStatus::normal)
			->count();
	}


}