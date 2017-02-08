<?php
namespace CustomerManage;

use DB,
	View,
	Input,
	InputExt,
	Ca\Data,
	Ca\Consts,
	Ca\Common,
	Ca\Service\MeetingService;

class MeetingController extends BaseController {
	public $layout = 'customermanage/layouts/common';

	public function getIndex()
	{
		$this->layout->title = "讲座列表";
		$this->layout->body = View::make('customermanage/meeting/list');
	}

	public function postList()
	{
		$name = InputExt::get('name');
		$page = InputExt::getInt('page');

		$query = DB::table('meeting')
			->select(
				array(
					'meeting.meetingid', 'name', 'status',
					DB::raw('CONCAT(DATE_FORMAT(begindate, "%Y-%m-%d %H:%i"), " ~ ", DATE_FORMAT(enddate, "%Y-%m-%d %H:%i")) as meeting_date'),
					DB::raw('NOW() < IFNULL(enddate, 0) as active'),
					'address', 'createdate', DB::raw('COUNT(meeting__user.userid) as count')
				)
			)
			->leftJoin('meeting__user', 'meeting__user.meetingid', '=', 'meeting.meetingid')
			->groupBy('meeting.meetingid')
			->orderBy('meeting.meetingid', 'desc');
		$count_query = DB::table('meeting')->select(array(DB::raw('COUNT(meetingid) as count')));
		$ret = Data::queryList($query, $count_query, $page,
			array(
				array('type' => 'string', 'field' => 'name', 'value' => $name),
			),
			array(
				'status' => array(Consts::$meeting_status_texts),
				'active' => array(Consts::$meeting_active_texts)
			));
		echo json_encode($ret);
	}

	public function postEntity()
	{
		$eid = InputExt::getInt('eid');
		$fields = array('name', 'intro', 'begindate', 'enddate', 'enrolldate', 'address', 'contactname', 'contactphone', 'contactemail', 'cost');
		if ($eid)
		{
			$fields[] = 'status';
			$_POST['status'] = 1;
		}
		Common::empty_check(array('name', 'intro', 'begindate', 'enddate', 'enrolldate', 'address', 'contactname', 'contactphone', 'contactemail'));
		Data::updateEntity('meeting', array('meetingid', '=', $eid), $fields, null, null, $eid);
		$tags = Input::get('tag');
		MeetingService::add_meeting_tag($eid, $tags);EXIT;
	}

	public function postGet()
	{
		$eid = InputExt::getInt('eid');
		$entity = MeetingService::get_meeting($eid);
		$tags = DB::table('meeting__tag')
			->select(array('tag.name'))
			->leftJoin('tag', 'tag.tagid', '=', 'meeting__tag.tagid')
			->where('meetingid', '=', $eid)
			->get();
		$tags_str = array();
		foreach ($tags as $tag) {
			$tags_str[] = $tag->name;
		}
		$tags_str = implode(',', $tags_str);
		$entity->tag = $tags_str;
		echo json_encode($entity);
	}

	public function postDelete()
	{
		$eid = InputExt::getInt('eid');
		DB::table('meeting')->where('meetingid', '=', $eid)->delete();
	}

	public function postStatus()
	{
		$eid = InputExt::getInt('eid');
		DB::table('meeting')->where('meetingid', '=', $eid)->update(array('status' => DB::raw('abs(status - 2) + 1')));
	}

}