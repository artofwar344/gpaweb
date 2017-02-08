<?php
namespace CustomerManage;

use DB,
	View,
	Response,
	InputExt,
	Ca\Data,
	Ca\Common,
	Ca\UserKeyStatus,
	Ca\ProductStatus,
	Ca\Service\KeyUsageService,
	Ca\Service\DepartmentService,
	Ca\Service\ManagerService,
	Ca\Service\KeyService;

class KeyController extends BaseController {
	public $layout = 'customermanage/layouts/common';

	public function getIndex()
	{
		$this->layout->title = "密钥管理";
		$this->layout->body = View::make('customermanage/key/list');
	}

	public function postList()
	{
		$page = InputExt::getInt('page');
		$name = InputExt::get('name');
		$productid = InputExt::getInt('productid');
		$departmentid = InputExt::getInt('departmentid');

		// 已经使用
		$usagecount_query = 'select COUNT(userid) as count, keyid from keyusage where status in (1, 2, 5) group by keyid';

		//下一级部门分配
		$department_assigncount_query = DB::table('department__key')
			->select(array(DB::raw('SUM(department__key.count) as count'), 'keyid'))
			->leftJoin('department', 'department__key.departmentid', '=', 'department.departmentid')
			->where('department.parentid', '=', DB::raw($this->manager->departmentid))
			->where('department__key.status', '=', DB::raw(1))
			->groupBy('keyid')->toSql();

		//用户分配
		$userkeycount_query = DB::table('userkey')
			->select(array(DB::raw('SUM(assigncount) as count'), 'keyid'))
			->leftJoin('user', 'user.userid', '=', 'userkey.userid')
			->where('userkey.status', '=', DB::raw(UserKeyStatus::agree))
			->where('user.departmentid', '=', DB::raw($this->manager->departmentid))
			->groupBy('keyid')->toSql();


		if ($this->manager->top)
		{
			$query = DB::table('key')
				->select(array('key.keyid',
					'key.name as name', 'section', 'product.name as product_name', 'product.type',
					'server', 'key.count', 'note', 'key.createdate',
					DB::raw('IFNULL(keyusagecount.count, 0) as used'),
					DB::raw('IFNULL(departmentassigncount.count, 0) as departmentassigncount'),
					DB::raw('IFNULL(userkeycount.count, 0) as assigncount'),
					DB::raw('IFNULL(userkeycount.count, 0) + IFNULL(departmentassigncount.count, 0) as count2')
				))
				->orderBy('keyid', 'desc')
				->leftJoin(DB::raw("({$usagecount_query}) AS keyusagecount"), 'keyusagecount.keyid', '=', 'key.keyid')
				->leftJoin(DB::raw("({$userkeycount_query}) AS userkeycount"), 'userkeycount.keyid', '=', 'key.keyid')
				->leftJoin(DB::raw("({$department_assigncount_query}) AS departmentassigncount"), 'departmentassigncount.keyid', '=', 'key.keyid')
				->leftJoin('product', 'product.productid', '=', 'key.productid');
		}
		else
		{
			$query = DB::table('key')
				->select(array('key.keyid', 'key.name as name', 'section', 'product.name as product_name',
					'product.type', 'department__key.departmentid',
					'server', DB::raw('SUM(department__key.count) as count'), 'note', 'key.createdate',
					DB::raw('IFNULL(keyusagecount.count, 0) as used'),
					DB::raw('IFNULL(departmentassigncount.count, 0) as departmentassigncount'),
					DB::raw('IFNULL(userkeycount.count, 0) as assigncount'),
					DB::raw('IFNULL(userkeycount.count, 0) + IFNULL(departmentassigncount.count, 0) as count2')
				))
				->orderBy('keyid', 'desc')
				->groupBy('keyid')
				->leftJoin('department__key', 'department__key.keyid', '=', 'key.keyid')
				->leftJoin(DB::raw("({$usagecount_query}) AS keyusagecount"), 'keyusagecount.keyid', '=', 'key.keyid')
				->leftJoin(DB::raw("({$userkeycount_query}) AS userkeycount"), 'userkeycount.keyid', '=', 'key.keyid')
				->leftJoin(DB::raw("({$department_assigncount_query}) AS departmentassigncount"), 'departmentassigncount.keyid', '=', 'key.keyid')
				->leftJoin('product', 'product.productid', '=', 'key.productid')
				->where('department__key.departmentid', '=', $this->manager->departmentid)
				->where('department__key.status', '=', 1);
		}

		$count_query = DB::table('key')->select(array(DB::raw('COUNT(*) as count')));

		$query_list_conditions = array(
			array('type' => 'string', 'field' => 'key.name', 'value' => $name),
			array('type' => 'int', 'field' => 'key.productid', 'value' => $productid),
		);

		if (!$this->manager->top)
		{
			//$count_query->leftJoin('department__key', 'department__key.keyid', '=', 'key.keyid');
//			$query_list_conditions[] = array('type' => 'int', 'field' => 'department__key.count', 'operator' => '>', 'value' => 0);
		}

		$ret = Data::queryList($query, $count_query, $page, $query_list_conditions,
			array('server' => function($value) {
				if (empty($value))
				{
					return null;
				}
				$ret = explode('.', $value);
				$ret[0] = $ret[1] = '*';
				return implode('.', $ret);
			}),
			null, array(array('count2', '==', '0')));

		foreach ($ret['list'] as $key => $list)
		{
			if (ManagerService::check_role('key.modify') != true)
			{
				$ret['list'][$key]['_can_modify'] = false;
			}
			if (ManagerService::check_role('key.delete') != true)
			{
				$ret['list'][$key]['_can_delete'] = false;
			}
			unset($ret['list'][$key]['server']);
			if ($this->manager->top)
			{
				$topDepartment = DepartmentService::getTopDepartment();
				$ret['list'][$key]['department_name'] = $topDepartment->name;
			}
			else
			{
				$ret['list'][$key]['department_name'] = DepartmentService::getFullName($list['departmentid']);
			}

		}

		return Response::json($ret);
	}

	public function postEntity()
	{
		return;
		$eid = InputExt::getInt('eid');
		$key = InputExt::get('key');
		$modify = $eid > 0;

		if ($modify && !KeyService::check_customer($eid))
		{
			return;
		}

		if (!$modify)
		{
			if (!$this->manager->top && ManagerService::check_role('key.new') != true)
			{
				return;
			}
		}
		else
		{
			if (!$this->manager->top && ManagerService::check_role('key.modify') != true)
			{
				return;
			}
		}

		$fields = array('name', 'note');

		if (!KeyUsageService::key_inuse($eid))
		{
			$fields[] = 'productid';
			$fields[] = 'departmentid';
			$fields[] = 'server';
			$fields[] = 'count';
		}

		if (!Common::is_empty_str($key))
		{
			$section = explode('-', $_POST['key']);
			$_POST['section'] = $section[0];
			$fields[] = 'section';

			$_POST['key'] = Common::encrypt_key("12345", $key);
			$fields[] = 'key';

		}
		Data::updateEntity('key', array('keyid', '=', $eid), $fields);
	}

	public function postGet()
	{
		return;
		$eid = InputExt::getInt("eid");

		if(!KeyService::check_customer($eid))
		{
			return;
		}

		$entity = DB::table('key')
			->select(array('key.name', 'key.keyid', 'key.productid', 'key.departmentid', 'key.server', 'key.count', 'key.note'))
			->where('keyid', '=', $eid)
			->first();

		if (KeyUsageService::key_inuse($eid))
		{
			$entity->_disable_fields = array('productid', 'departmentid', 'key', 'server', 'count');
		}
		return Response::json($entity);
	}

	public function postDelete()
	{
		return;
		$eid = InputExt::getInt('eid');

		// 密钥没有被分配，可以删除
		if (KeyService::check_customer($eid) && !KeyUsageService::key_inuse($eid) && ManagerService::check_role('key.delete') == true)
		{
			DB::table('key')->where('keyid', '=', $eid)->delete();
		}
	}

	public function postSelects()
	{
		$select_1 = DB::table('product')->select(array('productid', DB::raw('CONCAT(name, " [", type, "]") as name')))
			->where('status', '=', ProductStatus::available)
			->orderBy("productid", "desc")->get();

		$select_2 = DB::table('department')->select(array('departmentid', 'name'))
			->orderBy("departmentid", "desc");


		$select_2 = $select_2->get();

		return Response::json(array($select_1, $select_2));
	}
}

