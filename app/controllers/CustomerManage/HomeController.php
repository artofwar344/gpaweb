<?php
namespace CustomerManage;

use
	\Route,
	\Auth,
	\View,
	\Redirect,
	\Input,
	\DB,
	\Request,
	\Hash,
	\Session,
	\DBExt,
	Ca\UserKeyStatus,
	Ca\Data,
	Ca\Consts,
	Ca\Service\ManagerService,
	Ca\Common;

class HomeController extends BaseController {

	public function getIndex()
	{
		if (Auth::guest())
		{

			return View::make('customermanage.home.index')->with('title', '首页');
		}
		else
		{
			return Redirect::to('/user');
		}
	}

	public function postLogin()
	{
		$input = Input::all();
		$login = $input["name"];
		$password = $input["password"];
		$captcha = $input["captcha"];
		if (!\Ca\Captcha::check(null, $captcha))
		{
			echo -1;
			exit;
		}

		$credentials = array('name' => $login, 'password' => $password, 'provider' => 'customer.manager');
		if (Auth::attempt($credentials))
		{
			if (Auth::user()->status != 1)
			{
				Auth::logout();
				echo -3;
				exit;
			}

			echo 1;
		}
		else
		{
			echo -2;
		}
	}

	public function postUpdatepassword()
	{
		if (Request::ajax() && !Auth::guest())
		{
			$oldpassword = Input::get('oldpassword');
			$password = Input::get('password');
			if (Hash::check($oldpassword, Auth::user()->password))
			{
				DB::table('manager')
					->where('managerid', '=', Auth::user()->managerid)
					->update(array('password' => Hash::make($password)));
			}
		}
	}

	public function getLogout()
	{
		Session::flush();
		return Redirect::to("/");
	}

	public function getWelcome()
	{
		if (Auth::guest())
		{
			return Redirect::to("/");
		}
		$manager = Auth::user();
		//$manager->top = true;
		// echo '<pre>';
		// print_r($manager->departmentid);
		// echo '</pre>';
		// exit;
		$count_query = null;

		$department_count = DB::table('department')
			->select(array(DB::raw('COUNT(*) as count')))
			->where('department.parentid', '=', $manager->departmentid)
			->first();

		// 用户已经分配密钥数量
		$keycount_query = DB::table('userkey')
			->select(array(DB::raw('SUM(assigncount) as count'), 'keyid'))
			->leftJoin('user', 'user.userid', '=', 'userkey.userid')
			->where('userkey.status', '=', DB::raw(UserKeyStatus::agree))
			->where('user.departmentid', '=', DB::raw($manager->departmentid))
			->groupBy('keyid')->toSql();

		//下一级部门分配
		$department_assigncount_query = DB::table('department__key')
			->select(array(DB::raw('SUM(department__key.count) as count'), 'keyid'))
			->leftJoin('department', 'department__key.departmentid', '=', 'department.departmentid')
			->where('department.parentid', '=', DB::raw($manager->departmentid))
			->where('department__key.status', '=', DB::raw(1))
			->groupBy('keyid')->toSql();

		if ($manager->top)
		{
			$query = DB::table('product')
				->select(array('product.productid', 'product.name', DB::raw('SUM(key.count) as keycount'), DB::raw('IFNULL(userkeycount.count, 0) as assigncount'), DB::raw('IFNULL(departmentassigncount.count, 0) as departmentassigncount')))
				->leftJoin('key', 'key.productid', '=', 'product.productid')
				->leftJoin(DB::raw("({$keycount_query}) AS userkeycount"), 'userkeycount.keyid', '=', 'key.keyid')
				->leftJoin(DB::raw("({$department_assigncount_query}) AS departmentassigncount"), 'departmentassigncount.keyid', '=', 'key.keyid')
				->groupBy('product.productid');
		}
		else
		{
			$query = DB::table('product')
				->select(array('product.productid', 'product.name', DB::raw('SUM(department__key.count) as keycount'), DB::raw('IFNULL(userkeycount.count, 0) as assigncount'),
					DB::raw('IFNULL(departmentassigncount.count, 0) as departmentassigncount')))
				->leftJoin('key', 'key.productid', '=', 'product.productid')
				->leftJoin('department__key', 'department__key.keyid', '=', 'key.keyid')
				->leftJoin(DB::raw("({$keycount_query}) AS userkeycount"), 'userkeycount.keyid', '=', 'key.keyid')
				->leftJoin(DB::raw("({$department_assigncount_query}) AS departmentassigncount"), 'departmentassigncount.keyid', '=', 'key.keyid')
				->where('department__key.departmentid', '=', $manager->departmentid)
				->where('department__key.status', '=', 1)
				->groupBy('product.productid');
		}
		$query_list_conditions = array();
		$query_list_conditions[] = array('type' => 'int', 'field' => 'product.status', 'operator' => '!=', 'value' => 3);

		$products = Data::queryList($query, $count_query, 1, $query_list_conditions, null, null);

//var_dump($query->tosql());
//		exit;

		$query_list_conditions = array();
		if ($manager->top)
		{
			$query = DB::table('key')
				->select(array('key.keyid', DB::raw('CONCAT(product.name, " - [", key.name, "]") as name'), DB::raw('SUM(key.count) as keycount'), DB::raw('IFNULL(userkeycount.count, 0) as assigncount'),
					DB::raw('IFNULL(departmentassigncount.count, 0) as departmentassigncount')))
				->leftJoin('product', 'product.productid', '=', 'key.productid')
				->leftJoin(DB::raw("({$keycount_query}) AS userkeycount"), 'userkeycount.keyid', '=', 'key.keyid')
				->leftJoin(DB::raw("({$department_assigncount_query}) AS departmentassigncount"), 'departmentassigncount.keyid', '=', 'key.keyid')
				->groupBy('key.keyid');
		}
		else
		{
			$query = DB::table('key')
				->select(array('key.keyid', DB::raw('CONCAT(product.name, " - [", key.name, "]") as name'), DB::raw('SUM(department__key.count) as keycount'),
					DB::raw('IFNULL(userkeycount.count, 0) as assigncount'), DB::raw('IFNULL(departmentassigncount.count, 0) as departmentassigncount')))
				->leftJoin('product', 'product.productid', '=', 'key.productid')
				->leftJoin('department__key', 'department__key.keyid', '=', 'key.keyid')
				->leftJoin(DB::raw("({$keycount_query}) AS userkeycount"), 'userkeycount.keyid', '=', 'key.keyid')
				->leftJoin(DB::raw("({$department_assigncount_query}) AS departmentassigncount"), 'departmentassigncount.keyid', '=', 'key.keyid')
				->where('department__key.departmentid', '=', $manager->departmentid)
				->where('department__key.status', '=', 1)
				->groupBy('key.keyid');
		}
		$keys = Data::queryList($query, $count_query, 1, $query_list_conditions, null, null);

		//最新激活记录
		$query = DB::table('keyusage')
			->select(array('usageid', 'user.name', DB::raw('CONCAT(key.name, " - [", key.section, "]") as key_name'), 'product.name as product_name', 'ip', 'computerid', 'errorcode', 'begindate', 'enddate', 'keyusage.status'))
			->orderBy('usageid', 'desc')
			->leftJoin('key', 'key.keyid', '=', 'keyusage.keyid')
			->leftJoin('user', 'keyusage.userid', '=', 'user.userid')
			->leftJoin('department', 'department.departmentid', '=', 'user.departmentid')
			->leftJoin('product', 'key.productid', '=', 'product.productid');

		if (!$manager->top)
		{
			$query_list_conditions[] = array('type' => 'int', 'field' => 'user.departmentid', 'value' => $manager->departmentid);
		}
		$usages = Data::queryList($query, $count_query, 1, $query_list_conditions,
			array('status' => array(Consts::$keyusage_status_texts), 'ip' => 'long2ip')
		);


		$keyassigns = array(
			'list' => array()
		);
		if (!$manager->top)
		{
			$query = DB::table('userkey')
				->select(array('userkeyid', 'user.name as user_name', 'product.name as product_name',
					'requestcount', 'requestdate', 'reason', DB::raw('CONCAT(key.name, " - [", key.section, "]") as key_name'),
					'assigncount', 'assigndate', 'manager.name as manager_name', 'userkey.status'))
				->orderBy('userkey.userkeyid', 'desc')
				->leftJoin('key', 'userkey.keyid', '=', 'key.keyid')
				->leftJoin('product', 'userkey.productid', '=', 'product.productid')
				->leftJoin('user', 'userkey.userid', '=', 'user.userid')
				->leftJoin('manager', 'userkey.managerid', '=', 'manager.managerid')
				->leftJoin('department', 'user.departmentid', '=', 'department.departmentid');

			$keyassigns = Data::queryList($query, $count_query, 1, array(
				array('type' => 'int', 'field' => 'userkey.status', 'value' => 1),
				array('type' => 'int', 'field' => 'department.departmentid', 'value' => $manager->departmentid),
			), array('status' => array(Consts::$managekey_status_texts)));
		}

		return View::make('customermanage.home.welcome')
			->with('department_count', $department_count->count)
			->with('products', $products['list'])
			->with('keys', $keys['list'])
			->with('usages', $usages['list'])
			->with('keyassigns', $keyassigns['list']);
	}
}