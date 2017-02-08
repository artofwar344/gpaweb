<?php
namespace CustomerManage;

use DB,
	View,
	Input,
	Response,
	InputExt,
	Ca\Data,
	Ca\Service\KnowsService;
use FastDFS\Exception;

class AutoAssignUserController  extends BaseController {
	public $layout = 'customermanage/layouts/common';

	public function getIndex()
	{
		$autoassignid = InputExt::getInt('id');
		$this->layout->title = "分配名单管理";
		$this->layout->body = View::make('customermanage/autoassign/user')
			->with('autoassignid', $autoassignid);
	}

	public function postList()
	{
		$autoassignid = InputExt::getInt('autoassignid');
		$page = InputExt::getInt('page');

		$query = DB::table('autoassign__user')->where('autoassignid', '=', $autoassignid);

		$count_query = DB::table('autoassign__user')->where('autoassignid', '=', $autoassignid)->select(array(DB::raw('COUNT(*) as count')));

		$ret = Data::queryList($query, $count_query, $page, array());

		echo json_encode($ret);
	}

	public function  postEntity()
	{
		$autoassignid = InputExt::getInt('autoassignid');
		$username = Input::get('username');
		if (DB::table('autoassign__user')->where('username', '=', $username)->count() == 0)
		{
			if (Input::has('eid'))
			{
				$eid = Input::get('eid');
				DB::table('autoassign__user')
					->where('username', '=', $eid)
					->where('autoassignid', '=', $autoassignid)
					->update(array('username' => $username));
			}
			else
			{
				DB::table('autoassign__user')->insert(array('autoassignid' => $autoassignid, 'username' => $username));
			}
		}
	}

	public function postGet()
	{
		$autoassignid = InputExt::getInt('autoassignid');
		$eid = Input::get("eid");
		$entity = DB::table('autoassign__user')->where('autoassignid', '=', $autoassignid)->where('username', '=', $eid)->first();
		return Response::json($entity);
	}

	public function postDelete()
	{
		$autoassignid = InputExt::getInt('autoassignid');
		$username = Input::get("eid");
		DB::table('autoassign__user')
			->where('username', '=', $username)
			->where('autoassignid', '=', $autoassignid)
			->delete();
	}

}