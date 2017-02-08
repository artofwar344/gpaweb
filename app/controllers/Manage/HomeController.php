<?php
namespace Manage;
use Illuminate\Support\Facades\DB,
	Illuminate\Support\Facades\Auth,
	Illuminate\Support\Facades\View,
	Illuminate\Support\Facades\Input,
	Ca\Service\ManagerService,
	Ca\Common;
class HomeController extends \BaseController {

	public function getIndex()
	{
		if (!Auth::guest())
		{
			return \Redirect::to('/home/welcome');
		}
		else
		{
			return View::make('manage.home.index');
		}
	}

	public function postLogin()
	{
		$input = Input::all();
		$login = $input["name"];
		$password = $input["password"];
		$captcha = $input["captcha"];
		/*if (!\Ca\Captcha::check(null, $captcha))
		{
			echo -1;
			exit;
		}*/

		$credentials = array('name' => $login, 'password' => $password, 'provider' => 'manager');

		if (Auth::attempt($credentials))
		{
			if (Auth::user()->status != 1)
			{
				Auth::logout();
				echo -3;
				exit;
			}
			/*$manager = new \stdClass();
			$manager->id = Auth::user()->adminerid;
			$manager->adminerid = Auth::user()->adminerid;
			$manager->name = Auth::user()->name;
			$manager->role = Auth::user()->role;

			ManagerService::putCurrentUser($manager);*/
			echo 1;
		}
		else
		{
			echo -2;
		}
	}


	public function postUpdatepassword()
	{
		if (\Request::ajax() && !Auth::guest())
		{
			$oldpassword = Input::get('oldpassword');
			$password = Input::get('password');
			if (\Hash::check($oldpassword, Auth::user()->password))
			{
				DB::table('adminer')
					->where('adminerid', '=', Auth::user()->adminerid)
					->update(array('password' => \Hash::make($password)));
			}
		}
	}

	public function getLogout()
	{
		\Session::flush();
		return \Redirect::to("/");
	}

	public function getWelcome()
	{
		if (\Auth::guest())
		{
			return \Redirect::to("/");
		}
		$page = max(1, \InputExt::getInt('page'));
		$perpage = 4;
		$offset = ($page - 1) * $perpage;
		$query = DB::table('customer')
			->select(array('customer.customerid', 'customer.name', 'customer.alias', 'customer.module',
				DB::raw('IFNULL((SELECT "1" FROM information_schema.schemata WHERE schema_name = (SELECT CONCAT("ca_", customer.alias))), "2") AS database_status'),
				'customer.status', 'customer.createdate'));

		$count_query = Clone $query;
		$query->orderBy('customerid', 'desc')
			->groupBy('customer.customerid')
			->having('database_status', '=', 1)
			->skip($offset)
			->take($perpage);
		$customers = $query->get();
		$count = $count_query->select(array(DB::raw('count(customer.customerid) as count'),
			DB::raw('IFNULL((SELECT "1" FROM information_schema.schemata WHERE schema_name = (SELECT CONCAT("ca_", customer.alias))), "2") AS database_status'),
			'customer.status', 'customer.createdate'))
			->groupBy('database_status')
			->having('database_status', '=', 1)->first();

		return View::make('manage.layouts.welcome')
			->with('customers', $customers)
			->with('customers_count', $count->count)
			->with('page', $page)
			->with('pages', ceil($count->count / $perpage));
	}

	public function getStatus()
	{
		$data = null;// = unserialize(\Cache::get('customer_actived'));
		if (empty($data) || $data['lastupdate'] < time() - 3600 * 8)
		{
			$query = DB::table('customer')
				->select(array('customer.customerid', 'customer.name', 'customer.alias', 'customer.module',
					DB::raw('IFNULL((SELECT "1" FROM information_schema.schemata WHERE schema_name = (SELECT CONCAT("ca_", customer.alias))), "2") AS database_status'),
					'customer.status', 'customer.createdate'))
				->orderBy('customerid', 'desc')
				->groupBy('customer.customerid')
				->having('database_status', '=', 1);
			$customers = $query->get();
			$ret = array();
			foreach ($customers as $customer)
			{
				$database = 'ca_' . $customer->alias;
				$sql = "select '" . $customer->alias . "' as alias,
max(case `name` when 'user_count'   then (count) else 0 end) as 'user_count',
max(case `name` when 'weblogin_count' then (count) else 0 end) as 'weblogin_count',
max(case `name` when 'clientlogin_count' then (count) else 0 end) as 'clientlogin_count',
max(case `name` when 'requestkey_count' then (count) else 0 end) as 'requestkey_count',
max(case `name` when 'assignkey_count' then (count) else 0 end) as 'assignkey_count',
max(case `name` when 'keyusage_count' then (count) else 0 end) as 'keyusage_count'
					from  (select count(*) as count, 'user_count' as name from " . $database . ".user where createdate between ? and ?
					union select count(*) as count, 'weblogin_count' as name from " . $database . ".useraccesslog where createdate between ? and ?
					union select count(*) as count, 'clientlogin_count' as name from " . $database . ".userthread where logindate between ? and ?
					union select count(*) as count, 'requestkey_count' as name from " . $database . ".userkey where requestdate between ? and ?
					union select count(*) as count, 'assignkey_count' as name from " . $database . ".userkey where assigndate between ? and ?
					union select count(*) as count, 'keyusage_count' as name from " . $database . ".keyusage where status=2 and begindate between ? and ?) tb1";
				$start = date('Y-m-d', strtotime('-1 week'));
				$end   = date('Y-m-d');
				$query = \DB::select($sql, array(
					$start,
					$end,
					$start,
					$end,
					$start,
					$end,
					$start,
					$end,
					$start,
					$end,
					$start,
					$end
				));
				$ret[] = $query[0];
			}
			$data = array(
				'info' => $ret,
				'lastupdate' => time()
			);
			\Cache::put('customer_actived', serialize($data), 8 * 60);
		}

		print json_encode($data['info']);
		exit;
	}

}