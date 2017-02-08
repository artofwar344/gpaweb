<?php
namespace CustomerManage;

use DB,
	View,
	Input,
	InputExt,
	Ca\Data,
	Ca\Service\TreeService,
	Ca\Service\MeetingService,
	Ca\Service\DepartmentService;

class MeetingenrollController extends BaseController {
	public $layout = 'customermanage/layouts/common';

	public function getIndex()
	{
		$meetingid = InputExt::getInt('id');
		$this->layout->title = "报名用户列表";
		$meeting = MeetingService::get_meeting($meetingid);
		$this->layout->body = View::make('customermanage/meeting/meetingenroll')->with('meeting', $meeting)->with('meetingid', $meetingid);
	}

	public function postList()
	{
		$meetingid = InputExt::getInt('id');
		$page = InputExt::getInt('page');

		$query = DB::table('meeting__user')
			->select(array('meeting.name as meeting_name', 'user.userid', 'user.username', 'user.email', 'user.name as user_name', 'departmentid'))
			->leftJoin('user', 'meeting__user.userid', '=', 'user.userid')
			->leftJoin('meeting', 'meeting.meetingid', '=', 'meeting__user.meetingid')
			->where('meeting__user.meetingid', '=', $meetingid);
		$count_query = DB::table('meeting__user')
			->select(array(DB::raw('COUNT(userid) as count')))
			->where('meeting__user.meetingid', '=', $meetingid);

		//部门
		$departments = DepartmentService::departments();
		foreach ($departments as $key => $row)
		{
			if (is_object($row))
			{
				$departments[$key] = (array)$row;
			}
		}
		$tree_service = new TreeService($departments, array(
			'_id' => 'departmentid',
			'_pid' => 'parentid',
			'_default_pid' => 1
		));
		$ret = Data::queryList($query, $count_query, $page, array(),
		array('departmentid' => function($value) use($tree_service) {
			$parents = $tree_service->get_parents($value);
			$parents = array_reverse($parents);
			$ret = array();
			foreach ($parents as $parent)
			{
				$ret[] = $parent['name'];
			}
			return join(' > ', $ret);
		}));
		echo json_encode($ret);
	}


	public function postDelete()
	{
		$eid = InputExt::getInt('eid');
		DB::table('meeting__user')->where('userid', '=', $eid)->delete();
	}

}