<?php
namespace Manage;
use Illuminate\Support\Facades\DB,
	Illuminate\Support\Facades\View,
	Illuminate\Support\Facades\Input,
	Illuminate\Support\Facades\Hash,
	Ca\Common,
	Ca\Consts,
	Ca\Service,
	Ca\Data,
	\InputExt,
	\Ca\Service\PermissionService;
class CustomerController extends BaseController {
	public $layout = 'manage/layouts/common';

	public function getIndex()
	{
		$this->layout->title = "客户管理";
		$this->layout->body = View::make('manage/customer/list');
	}

	public function postList()
	{
		$name = \InputExt::get('name');
		$page = \InputExt::getInt('page');

		$query = DB::table('customer')
			->select(array('customer.customerid', 'customer.name', 'customer.alias', 'customer.module',
				DB::raw('IFNULL((SELECT "1" FROM information_schema.schemata WHERE schema_name = (SELECT CONCAT("ca_", customer.alias))), "2") AS database_status'),
				'customer.status', 'customer.createdate'))
			->orderBy('customerid', 'desc')
			->groupBy('customer.customerid');

		$count_query = DB::table('customer')->select(array(DB::raw('COUNT(*) as count')));

		$ret = Data::queryList($query, $count_query, $page, array(
			array('type' => 'string', 'field' => 'customer.name', 'value' => $name),
			array('type' => 'int', 'field' => 'customer.adminerid', 'value' => $this->manager->adminerid)
		), array('status' => array(Consts::$customer_status_texts), 'module' => array(Consts::$module_texts, 'array'), 'database_status' => array(Consts::$database_status_texts)));

		echo json_encode($ret);
		exit;
	}

	public function postEntity()
	{
		$adminerid = $this->manager->adminerid;
		$eid = \InputExt::getInt('eid');
		$modify = $eid > 0;
		$_POST['adminerid'] = $adminerid;

		if ($modify && !Service\CustomerService::checkAdminer($eid))
		{
			return;
		}
//		if ($modify)
//		{
//			DB::table('dns.records')
//				->where('host', '=', Input::get('alias'))
//				->where('type', '=', 'A')->update(array(
//					'data' => Input::get('ip', '117.79.83.204'),
//					'view' => Input::get('view', 'CER'),
//				));
//		}
//		else
//		{
//			DB::table('dns.records')
//				->insert(array(
//					'zone' => 'gpa.edu.cn',
//					'type' => 'A',
//					'host' => Input::get('alias'),
//					'data' => Input::get('ip', '117.79.83.204'),
//					'view' => Input::get('view', 'CER'),
//				));
//		}

		Common::empty_check(array('name', 'alias'));
		$_POST['module'] = isset($_POST['module']) ? implode(',', $_POST['module']) : '';
		Data::updateEntity('customer', array('customerid', '=', $eid), array('adminerid', 'organizeid', 'name', 'alias', 'module', 'status'));
	}

	public function postGet()
	{
		$eid = \InputExt::getInt("eid");
		if (!Service\CustomerService::checkAdminer($eid))
		{
			return;
		}
		$entity = DB::table('customer')
			->select(array('name', 'alias', 'module', 'status', 'organizeid'))
			->where('customerid', '=', $eid)->first();
//		$entity2 = DB::table('dns.records')
//			->select(array('view', 'data'))
//			->where('host', '=', $entity->alias)
//			->first();
//		if (!empty($entity2))
//		{
//			$entity->view = $entity2->view;
//			$entity->ip   = $entity2->data;
//		}
		echo json_encode($entity);
	}

	public function anyDelete()
	{
		$eid = \InputExt::getInt("eid");

		if (Service\CustomerService::checkAdminer($eid))
		{
			DB::table('customer')->where('customerid', '=', $eid)->delete();
		}
	}

	public function anyStatus()
	{
		$eid = \InputExt::getInt('eid');
		DB::table('customer')->where('customerid', '=', $eid)->update(array('status' => DB::raw('abs(status - 2) + 1')));
	}

	/**
	 * 获取顶级管理员
	 */
	public function postGettopmanager()
	{
		$customerid = \InputExt::getInt('eid');
		$customer = DB::table('customer')->where('customerid', '=', $customerid)->first();
		if ($customer == null)
		{
			return;
		}

		$database = 'ca_' . $customer->alias;

		$manger = DB::table($database . '.department')
			->select(array('managerid', 'department.name as department_name', 'manager.name as manager_name', 'role', 'status'))
			->leftJoin($database . '.manager', 'manager.departmentid', '=', 'department.departmentid')
			->whereNull('department.parentid')
			->first();

		$modules_alias = array();
		if (!empty($customer->module))
		{
			$modules = explode(',', $customer->module);
			foreach ($modules as $module)
			{
				$modules_alias[] = Consts::$module_alias[$module];
			}
		}
//		$roles = Consts::$manager_role_texts;
		$roles = PermissionService::all($customer->alias);
		foreach ($roles as $key => $value)
		{
			if (is_array($value) && !in_array($key, $modules_alias))
			{
				if ($key == 'soft' || $key == 'share' || $key == 'activate')
				{
					unset($roles[$key]);
				}
			}
		}
		if ($manger == null)
		{
			$ret = array('status' => 2, 'roles' => $roles);
		}
		elseif ($manger->managerid == null)
		{
			$ret = array('status' => 3, 'department_name'=> $manger->department_name, 'roles' => $roles);
		}
		else
		{
			$ret = array('status' => 1, 'manager' => $manger, 'roles' => $roles);
		}

		echo json_encode($ret);
	}

	/**
	 * 更新顶级管理员
	 */
	public function postUpdatetopmanager()
	{
		$customerid = InputExt::getInt('eid');
		$managerid = InputExt::getInt('managerid');
		$department_name = Input::get('department_name');
		$name = Input::get('name');
		$password = Input::get('password');
		$roles = Input::get('role') == '' ? '' : implode(',', Input::get('role'));
		$status = 1;

		$customer = DB::table('customer')->where('customerid', '=', $customerid)->first();
		if ($customer == null)
		{
			return;
		}
		$database = 'ca_' . $customer->alias;
		if ($managerid > 0)
		{
			$data = array('name' => $name, 'role' => $roles);
			if ($password != '')
			{
				$data['password'] = Hash::make($password);
			}
			DB::table($database . '.manager')
				->where('managerid', '=', $managerid)
				->update($data);
		}
		else
		{
			$department = DB::table($database . '.department')->whereNull('parentid')->first();
			if ($department == null)
			{
				$departmentid = DB::table($database . '.department')
					->insertGetId(array('parentid' => null, 'name' => $department_name));
			}
			else
			{
				$departmentid = $department->departmentid;
			}
			DB::table($database . '.manager')
				->insert(array('departmentid'=>$departmentid, 'name' => $name, 'password' => Hash::make($password), 'role' => $roles, 'status'=> $status));

		}
	}

	public function postSelects()
	{
		$select = DB::table('organize')
			->select(array('organizeid', 'name'))
			->orderBy(DB::raw('convert(name using gbk)'))
			->get();
		echo json_encode(array($select));
	}
}