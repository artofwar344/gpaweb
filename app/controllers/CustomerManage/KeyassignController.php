<?php
namespace CustomerManage;

use Redirect,
	View,
	DB,
	InputExt,
	Response,
	Ca\Data,
	Ca\Consts,
	Ca\UserKeyStatus,
	Ca\Service\UserService,
	Ca\Service\DepartmentService,
	Ca\Service\UserKeyService,
	Ca\Service\KeyService;

class keyassignController extends BaseController {
	public $layout = 'customermanage/layouts/common';

	public function getIndex()
	{
		$user_id = InputExt::getInt('id');
		$user = null;
		if ($user_id)
		{
			$user = UserService::get_user_by_userid($user_id);
		}
		$this->layout->title = "用户激活分配";
		$this->layout->body = View::make('customermanage/key/keyassign')->with('user_id', $user_id)->with('user', $user);
	}

	public function postList()
	{
		$page = InputExt::getInt('page');
		$name = InputExt::get('name');
		$productid = InputExt::getInt('productid');
		$keyid = InputExt::getInt('keyid');
		$status = InputExt::getInt('status');
		$userid = InputExt::getInt('userid');

		$query = DB::table('userkey')
			->select(array('userkeyid', DB::raw('CONCAT(user.name, " - [", user.username, "]") as user_name'), 'product.name as product_name', 'product.type',
				'requestcount', 'requestdate', 'reason', DB::raw('CONCAT(key.name, IF(key.section is null, "", CONCAT(" - [", key.section, "]"))) as key_name'),
				'user.departmentid',
				'assigncount', 'assigndate', 'manager.name as manager_name', 'userkey.status'))
			->orderBy('userkey.userkeyid', 'desc')
			->leftJoin('key', 'userkey.keyid', '=', 'key.keyid')
			->leftJoin('product', 'userkey.productid', '=', 'product.productid')
			->leftJoin('user', 'userkey.userid', '=', 'user.userid')
			->leftJoin('manager', 'userkey.managerid', '=', 'manager.managerid')
			->leftJoin('department', 'user.departmentid', '=', 'department.departmentid');

		$count_query = DB::table('userkey')
			->select(array(DB::raw('COUNT(*) AS count')))
			->leftJoin('user', 'userkey.userid', '=', 'user.userid')
			->leftJoin('manager', 'userkey.managerid', '=', 'manager.managerid')
			->leftJoin('department', 'user.departmentid', '=', 'department.departmentid');

		DepartmentService::getChildDepartments($this->manager->departmentid, $departmentids);
		$ret = Data::queryList($query, $count_query, $page, array(
			array('type' => 'string', 'field' => 'user.name', 'value' => $name),
			array('type' => 'int', 'field' => 'userkey.status', 'value' => $status),
			array('type' => 'int', 'field' => 'userkey.productid', 'value' => $productid),
			array('type' => 'int', 'field' => 'userkey.keyid', 'value' => $keyid),
			array('type' => 'int', 'field' => 'userkey.userid', 'value' => $userid),
			array('type' => 'int', 'field' => 'department.departmentid', 'operator' => 'in', 'value' => $departmentids),
		), array('status' => array(Consts::$managekey_status_texts)), array(array('status', '==', UserKeyStatus::pending)));
		foreach ($ret['list'] as $key => $item)
		{
			$ret['list'][$key]['department_name'] = DepartmentService::getFullName($item['departmentid']);
		}
		return Response::json($ret);
	}

	public function postEntity()
	{
		$eid = InputExt::getInt('eid');
		$status = InputExt::getInt('status');
		$managerid = $this->manager->managerid;
		$keyid = InputExt::getInt('keyid');
		$userid = InputExt::getInt('userid');
		$assigncount = InputExt::getInt('assigncount');
		$departmentid = InputExt::getInt('departmentid');

		$modify = $eid > 0;

		if ($modify && !UserKeyService::check_customer($eid))
		{
			return;
		}

		$fields = array('managerid', 'status');
		$values = array($managerid, $status);

		$key = DB::table('key')
			->where('keyid', '=', $keyid)
			->first();

		// 分配激活次数
		if (!$modify && !empty($userid))
		{

			$fields[] = 'userid';
			$fields[] = 'requestcount';
			$fields[] = 'productid';
			$fields[] = 'reason';

			$_POST['managerid'] = $managerid;
			$_POST['requestcount'] = $assigncount;
			$_POST['assigndate'] = DB::raw('NOW()');
			$_POST['productid'] = $key->productid;
			$_POST['reason'] = '管理员分配';
		}

		switch ($status)
		{
			case 1: // 待审批, 不进行修改
				exit;
			case 2: // 不同意, 修改
				$fields[] = 'assigndate';
				$values[] = DB::raw('NOW()');
				break;
			case 3: // 同意, 修改数据库
				$fields[] = 'keyid';
				$fields[] = 'assigncount';
				$fields[] = 'assigndate';

				$values[] = InputExt::getInt('keyid');
				$values[] = InputExt::getInt('assigncount');
				$values[] = DB::raw('NOW()');
				break;
		}
		if (!$modify)
		{
			$values = array();
		}

		$remain = KeyService::check_remain($status, $keyid, $assigncount, $departmentid);
		// 可分配
		if ($remain >= 0)
		{
			Data::updateEntity('userkey', array('userkeyid', '=', $eid), $fields, $values,
				array(array('status', 'in', array(UserKeyStatus::pending, UserKeyStatus::disagree)))
			);
			//分配subkey
			if ($key->section == null)
			{
				$userkey = DB::table('userkey')
					->where('userkeyid', '=', $eid)
					->first();
				DB::table('subkey')
					->whereNull('userid')
					->take($assigncount)
					->update(array('userid' => $userkey->userid, 'outdate' => DB::raw('NOW()')));
			}
		}
	}

	public function postGet()
	{
		$eid = InputExt::getInt('eid');

		if (!UserKeyService::check_customer($eid))
		{
			return;
		}

		$entity = DB::table('userkey')
			->select(array('userkey.status', 'user.departmentid'))
			->leftJoin('user', 'user.userid', '=', 'userkey.userid')
			->where('userkeyid', '=', $eid)
			->first();

		$entity->_disable_fields = array('assigncount', 'keyid');

		return Response::json($entity);
	}

	public function postSelects()
	{
		$select_1 = DB::table('product')
			->select(array('productid', DB::raw('CONCAT(name, " [", type, "]") as name')))
			->where('status', '=', 1)
			->orderBy("productid", "desc")->get();
		$select_2 = DB::table('key')->select(array('key.keyid', DB::raw('CONCAT(key.name, IF(key.section is null, "", CONCAT(" - [", key.section, "]"))) as name')))
			->orderBy('key.keyid', 'desc')
			->groupBy('key.keyid')
			->get();
		return Response::json(array($select_1, $select_2));
	}

	public function postModifyselects()
	{
		$eid = InputExt::getInt('eid');
		$departmentid = InputExt::getInt('departmentid');
		$userid = InputExt::getInt('userid');
		$user = UserService::get_user_by_userid($userid);
		if ($user != null)
		{
			$departmentid = $user->departmentid;
		}
		$remains = KeyService::check_remains($departmentid);
		if ($eid)
		{
			$keys = DB::table('userkey')
				->leftJoin('key', 'key.productid', '=', 'userkey.productid')
				->where('userkeyid', '=', $eid)->get();
			$key_ids = array();
			foreach ($keys as $key)
			{
				$key_ids[] = $key->keyid;
			}

			$ret = array();
			foreach ($remains as $remain)
			{
				if (in_array($remain->keyid, $key_ids))
				{
					$ret[] = $remain;
				}
			}
			return Response::json(array($ret));
		}
		return Response::json(array($remains));
	}

	public function postAssignmulti()
	{
		$eids = InputExt::get('eids');
		$ret = 0;
		if (!empty($eids))
		{
			$requests = DB::table('userkey')
				->whereIn('userkeyid', InputExt::get('eids'))
				->where('status', '!=', UserKeyStatus::agree)
				->get();
			$remains = KeyService::check_remains($this->manager->departmentid);

			$inassgins = array();
			foreach ($requests as $request)
			{
				if (!array_key_exists($request->productid, $inassgins))
				{
					$inassgins[$request->productid] = 0;
				}
				$inassgins[$request->productid] += $request->requestcount;
			}

			$assigns = array();
			foreach ($remains as $remain)
			{
				foreach ($requests as $request)
				{
					if ($remain->productid == $request->productid)
					{
						//激活量够用
						if ($remain->remain >= $inassgins[$request->productid])
						{
							$assigns[$request->userkeyid] = $remain->keyid;
							continue;
						}
					}
				}
			}
			if (count($assigns) == count($requests))
			{
				DB::transaction(function() use ($assigns, $requests) {
					foreach ($assigns as $userkeyid => $assign)
					{
						$data = array (
							'managerid' => $this->manager->managerid,
							'status' => UserKeyStatus::agree,
							'keyid' => $assign,
							'assigncount' => DB::raw('requestcount'),
							'assigndate' => DB::raw('NOW()')
						);
						DB::table('userkey')
							->where('userkeyid', '=', $userkeyid)
							->update($data);
						//分配subkey
						$key = DB::table('key')
							->where('keyid', '=', $assign)
							->first();
						if ($key->section == null)
						{
							foreach ($requests as $request)
							{
								if ($request->userkeyid == $userkeyid)
								{
									$userkey = $request;
									break;
								}
							}
							DB::table('subkey')
								->whereNull('userid')
								->take($userkey->requestcount)
								->update(array('userid' => $userkey->userid, 'outdate' => DB::raw('NOW()')));
						}

					}
				});
				$ret = 1;
			}

		}
		return Response::json(array('status' => $ret));
	}
}