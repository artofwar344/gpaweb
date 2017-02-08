<?php
namespace CustomerManage;

use \View,
	\DB,
	\Input,
	\Auth,
	\Response,
	\InputExt,
	Ca\Data,
	Ca\Consts,
	Ca\UserStatus,
	Ca\Service\ParamsService,
	Ca\Service\UserKeyService,
	Ca\Service\KeyService,
	Ca\Service\DepartmentService,
	Ca\Service\UserService,
	Ca\Service\ManagerService,
	Ca\Service\TreeService,
	Ca\UserKeyStatus;
use Whoops\Example\Exception;


class UserController extends BaseController {
	public $layout = 'customermanage/layouts/common';

	public function getIndex()
	{
		$this->layout->title = "用户管理";
		$department_id = InputExt::getInt('id');

		$departmentName = DepartmentService::getFullName($department_id);
		$this->layout->body = View::make("customermanage/user/list")
			->with('manager_department_id', $this->manager->departmentid)
			->with('department_id', $department_id)
			->with('department_name', $departmentName);
	}

	public function postList()
	{
		$name = Input::get('name');
		$username = Input::get('username');
		$type = InputExt::getInt('type');
		$department_id = InputExt::getInt('departmentid');
		if (empty($department_id))
		{
			$department_id = $this->manager->departmentid;
		}
		$status = InputExt::getInt('status');
		$page = InputExt::getInt('page');

		$keycount_query = DB::table('userkey')
			->select(array(DB::raw('COUNT(userid) as count'), 'userid'))
			->where('status', '!=', DB::raw(1))
			->groupBy('userid')->toSql();

		$accesslogcount_query = DB::table('useraccesslog')
			->select(array(DB::raw('COUNT(userid) as count'), 'userid'))
			->groupBy('userid')->toSql();

		$usagecount_query = DB::table('keyusage')
			->select(array(DB::raw('COUNT(userid) as count'), 'userid'))
			->groupBy('userid')->toSql();

		$query = DB::table('user')->select(array('user.userid', 'user.username', 'user.name', 'user.email', 'user.type',
			'department.name as department_name', 'department.departmentid',
			'user.status', 'user.createdate',
			DB::raw('IFNULL(userkeycount.count, 0) as userkeycount'),
			DB::raw('IFNULL(useraccesslogcount.count, 0) as useraccesslogcount'),
			DB::raw('IFNULL(keyusagecount.count, 0) as keyusagecount')))
			->orderBy('userid', 'desc')
			->leftJoin(DB::raw("({$keycount_query}) as userkeycount"), 'userkeycount.userid', '=', 'user.userid')
			->leftJoin(DB::raw("({$accesslogcount_query}) as useraccesslogcount"), 'useraccesslogcount.userid', '=', 'user.userid')
			->leftJoin(DB::raw("({$usagecount_query}) as keyusagecount"), 'keyusagecount.userid', '=', 'user.userid')
			->leftJoin('department', 'department.departmentid', '=', 'user.departmentid');

		$count_query = DB::table('user')->select(array(DB::raw('COUNT(*) as count')))
			->leftJoin('department', 'department.departmentid', '=', 'user.departmentid');

		$query_list_conditions = array(
			array('type' => 'string', 'field' => 'user.name', 'value' => $name),
			array('type' => 'int', 'field' => 'user.type', 'value' => $type),
			array('type' => 'string', 'field' => 'user.username', 'value' => $username),
			array('type' => 'int', 'field' => 'user.status', 'value' => $status)
		);

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

		$children = $tree_service->get_children($department_id);
		$department_ids = array_map(function($child) { return $child['departmentid']; }, $children);
		$query_list_conditions[] = array('type' => 'int', 'field' => 'user.departmentid', 'operator' => 'in', 'value' => $department_ids);

		$ret = Data::queryList($query, $count_query, $page, $query_list_conditions,
			array(
				'status' => array(Consts::$user_status_texts),
				'type' => array(Consts::$user_type_text),
				'departmentid' => function($value) use($tree_service) {
					$parents = $tree_service->get_parents($value);
					$parents = array_reverse($parents);
					$ret = array();
					foreach ($parents as $parent)
					{
						$ret[] = $parent['name'];
					}
					return join(' > ', $ret);
				}
			),
			null,
			array(array('userkeycount', '==', '0'))
		);
		return Response::json($ret);
	}

	public function postEntity()
	{
		$eid = InputExt::getInt('eid');
		$fields = array('name', 'username', 'email', 'status', 'type');
		$modify = $eid > 0;

		if($modify && !UserService::check_customer($eid))
		{
			return;
		}

		// 非高级管理员, 并且没有添加用户权限
		if (!$modify && !$this->manager->top && ManagerService::check_role('user.new') != true)
		{
			return;
		}

		// 新添加的和没有处理过分配密钥的管理员才可以修改部门
		if (empty($eid) || !UserService::key_assigned($eid))
		{
			$fields[] = 'departmentid';
		}
		if (Input::get('password', null))
		{
			$fields[] = 'password';
			$_POST['password'] = md5(Input::get('password'));
		}

		try
		{
			$departmentid = $this->manager->departmentid;
			$parentid = InputExt::getInt('departmentid');

			if(DepartmentService::check_departmentid($departmentid, $parentid))
			{
				$user_id = Data::updateEntity('user', array('userid', '=', $eid), $fields, array(), null, $eid);
			}
		}
		catch (\Exception $e)
		{
			$message = $e->getMessage();
			$name = '';
			if (strpos($message, 'un_username') !== false)
			{
				$code = 2; // 用户名重复
				$name = 'username';
				$message = '该登录帐号已经存在';
			}
			elseif (strpos($message, 'un_email') !== false)
			{
				$code = 2; // email重复
				$name = 'email';
				$message = '该邮箱已经存在';
			}
			else
			{
				$code = 2; // 其他错误
				$message = '未知错误';
			}
			return Response::json(array('code' => $code, 'id' => $name, 'message' => $message));
		}
		if (!empty($user_id))
		{
			if (Input::get('password'))
			{
				$fields[] = 'password';
				UserService::save_password($user_id, Input::get('password'));
			}

			if ($_POST['status'] == UserStatus::normal)
			{
				if (ParamsService::get('autoassignopen'))
				{
					$reason = '通过审核后自动分配';
					$manage_id = Auth::user()->managerid;
					KeyService::auto_assign($user_id, $manage_id, $reason);
				}
			}
		}
		return Response::json(array('code' => 1));
	}

	public function postGet()
	{
		$eid = InputExt::getInt("eid");

		if(!UserService::check_customer($eid))
		{
			return;
		}

		$entity = DB::table('user')
			->select(array('user.name', 'user.username', 'user.email', 'user.departmentid', 'user.status', 'user.type'))
			->leftJoin('department', 'department.departmentid', '=', 'user.departmentid')
			->where('userid', '=', $eid)->first();

		if (UserService::key_assigned($eid))
		{
			$entity->_disable_fields = array('departmentid');
		}

		return Response::json($entity);
	}

	public function postDelete()
	{
		$eid = InputExt::getInt("eid");

		if (UserService::check_customer($eid) && !UserService::key_assigned($eid))
		{
			// 删除该用户的相关记录
			try
			{
				DB::table('userkey')
					->where('userid', '=', $eid)
					->where('status', '=', UserKeyStatus::pending)
					->delete();
				DB::table('document')->where('userid', '=', $eid)->orderBy('parentid', 'DESC')->delete();
				DB::table('question')->where('userid', '=', $eid)->delete();
				DB::table('topic')->where('userid', '=', $eid)->delete();
				DB::table('user')->where('userid', '=', $eid)->delete();
			}
			catch(Exception $e)
			{
				return Response::json(array('status'=>'2', 'error' => $e));
			}
			return Response::json(array('status'=>'1'));
		}
	}

	public function postSelects()
	{
		$query = DB::table('department')
			->select(array('departmentid', 'name'))
			->whereNotNull('department.parentid')
			->orderBy("departmentid", "desc");
		if (!$this->manager->top)
		{
			DepartmentService::getChildDepartments($this->manager->departmentid, $departmentids);
			$query->whereIn('departmentid', $departmentids);
		}
		$select_1 = $query->get();


		return Response::json(array($select_1));
	}

	public function postImportpreview()
	{
		if (!$this->manager->top)
		{
			$fp = fopen('php://input', 'r');
			$i = 1;
			$error = array();
			$list = array();
			while ($data = fgetcsv($fp))
			{
				if (count($data) >= 4)
				{
					list($usermame, $password, $email, $name) = array(trim($data[0]), trim($data[1]), trim($data[2]), trim($data[3]));

					$name = @iconv('gb2312', 'utf-8', $name);
					$usermame = @iconv('gb2312', 'utf-8', $usermame);
					$email = @iconv('gb2312', 'utf-8', $email);
					if (empty($usermame) || empty($password) || empty($name) || empty($email))
					{
						$error[] = array(
							'line' => $i,
							'code' => 1 // 数据格式不对
						);
					}
					else if (!filter_var($email, FILTER_VALIDATE_EMAIL))
					{
						$error[] = array(
							'line' => $i,
							'code' => 2 //  email格式不对
						);
					}
					else
					{
						$list[] = array($usermame, $password, $email, $name);
					}
				}
				else
				{
					$error[] = array(
						'line' => $i,
						'code' => 1
					);
				}
				$i++;
			}
			fclose($fp);
			return Response::json(array('count' => $i - 1, 'list' => $list, 'errors' => $error));
		}
	}

	public function anyImport()
	{
		set_time_limit(0);
		if (!$this->manager->top)
		{
			$fp = fopen('php://input', 'r');
			$i = 1;
			$error = array();
			while ($data = fgetcsv($fp))
			{
				if (count($data) >= 4)
				{
					list($usermame, $password, $email, $name) = array(trim($data[0]), trim($data[1]), trim($data[2]), trim($data[3]));
					$name = @iconv('gb2312', 'utf-8', $name);
					$usermame = @iconv('gb2312', 'utf-8', $usermame);
					$email = @iconv('gb2312', 'utf-8', $email);
					if (empty($usermame) || empty($password) || empty($name) || empty($email))
					{
						$error[] = array(
							'line' => $i,
							'code' => 1 // 数据格式不对
						);
					}
					else if (!filter_var($email, FILTER_VALIDATE_EMAIL))
					{
						$error[] = array(
							'line' => $i,
							'code' => 2 //  email格式不对
						);
					}
					else
					{
						try
						{
							$user_id = DB::table('user')
								->insertGetId(array(
									'username' => $usermame,
									'password' => md5($password),
									'name' => $name,
									'email' => $email,
									'departmentid' => $this->manager->departmentid,
									'status' => 1
								));

							UserService::save_password($user_id, $password);
							if (ParamsService::get('autoassignopen'))
							{
								$reason = '导入用户自动分配';
								$manage_id = Auth::user()->managerid;
								KeyService::auto_assign($user_id, $manage_id, $reason);
							}
						} catch (\Exception $e)
						{
							$message = $e->getMessage();
							if (strpos($message, 'un_username') !== false)
							{
								$code = 3; // 用户名重复
							}
							elseif (strpos($message, 'un_email') !== false)
							{
								$code = 4; // email重复
							}
							else
							{
								$code = 5; // 其他错误
							}

							$error[] = array(
								'line' => $i,
								'code' => $code
							);
						}
					}

				}
				else
				{
					$error[] = array(
						'line' => $i,
						'code' => 1
					);
				}
				$i++;
			}
			fclose($fp);
			return Response::json(array('count' => $i - 1, 'errors' => $error));
		}
	}

	public function postAuth()
	{
		$eid = InputExt::getInt('eid');
		if (!$this->manager->top)
		{
			// 判断是否和当前管理员同一部门
			if (DB::table('user')
				->where('userid', '=', $eid)
				->where('departmentid', '=', Auth::user()->departmentid)
				->count() <= 0)
			{
				return Response::json(array('code' => 2, 'message' => '你无权修改该用户状态'));
			}
		}
		$user_id = $eid;
		if (ParamsService::get('autoassignopen'))
		{
			$reason = '通过审核后自动分配';
			$manage_id = Auth::user()->managerid;
			KeyService::auto_assign($user_id, $manage_id, $reason);
		}

		DB::table('user')->where('userid', '=', $eid)->update(array('status' => UserStatus::normal));
		return Response::json(array('code' => 1));
	}

	public function postAuthmulti()
	{
		set_time_limit(60);
		$eids = InputExt::get('eids');
		if (is_array($eids) && !empty($eids))
		{
			$query = DB::table('user')->whereIn('userid', $eids);
			if (!$this->manager->top)
			{
				$query->where('departmentid', '=', Auth::user()->departmentid);
			}

			if (ParamsService::get('autoassignopen'))
			{
				$reason = '通过审核后自动分配';
				$manage_id = Auth::user()->managerid;
				foreach ($eids as $user_id)
				{
					try
					{
						KeyService::auto_assign($user_id, $manage_id, $reason);
					}
					catch (\Exception $e) {}
				}
			}
			$query->update(array('status' => UserStatus::normal));
			return Response::json(array('code' => 1));
		}
	}
}
