<?php
namespace CustomerManage;

use Ca\Service\DepartmentService;
use DB,
	View,
	Response,
	InputExt,
	Ca\ProductStatus,
	Ca\Data,
	Ca\Consts,
	Ca\Service\UserService,
	Ca\Service\KeyUsageService;

class KeyusageController extends BaseController {
	public $layout = 'customermanage/layouts/common';

	public function getIndex()
	{
		$this->layout->title = "密钥使用情况";
		$user_id = InputExt::getInt('id');
		$user = null;
		if ($user_id)
		{
			$user = UserService::get_user_by_userid($user_id);
		}
		$this->layout->body = View::make('customermanage/key/keyusage')->with('user', $user)->with('user_id', $user_id);
	}

	public function postList()
	{
		$page = InputExt::getInt('page');
		$name = InputExt::get('name');
		$keyid = InputExt::getInt('keyid');
		$status = InputExt::getInt('status');
		$productid = InputExt::getInt('productid');
		$userid = InputExt::getInt('userid');

		$query = DB::table('keyusage')
			->select(array('keyusage.usageid', DB::raw('CONCAT(user.name, " - [", username , "]") as user_name'), 'product.name as product_name', 'product.type', 'productname',
				'user.departmentid',
				DB::raw('CONCAT(key.name, IF(key.section IS NULL, "", CONCAT(" - [", key.section, "]"))) as key_name'), 'keyusage.status as status',
				'ip', 'computerid', 'errorcode', 'begindate', 'enddate'))
			->orderBy('usageid', 'desc')
			->leftJoin('key', 'key.keyid', '=', 'keyusage.keyid')
			->leftJoin('user', 'keyusage.userid', '=', 'user.userid')
			->leftJoin('product', 'key.productid', '=', 'product.productid');

		$count_query = DB::table('keyusage')->select(array(DB::raw('COUNT(*) as count')))
			->leftJoin('key', 'key.keyid', '=', 'keyusage.keyid')
			->leftJoin('user', 'keyusage.userid', '=', 'user.userid')
			->leftJoin('product', 'key.productid', '=', 'product.productid');

		$query_list_conditions = array(
			array('type' => 'string', 'field' => 'user.name', 'value' => $name),
			array('type' => 'int', 'field' => 'key.keyid', 'value' => $keyid),
			array('type' => 'int', 'field' => 'keyusage.status', 'value' => $status),
			array('type' => 'int', 'field' => 'key.productid', 'value' => $productid),
			array('type' => 'int', 'field' => 'user.userid', 'value' => $userid)
		);

		if (!$this->manager->top)
		{
			DepartmentService::getChildDepartments($this->manager->departmentid, $departmentids);
			$query_list_conditions[] = array('type' => 'int', 'field' => 'user.departmentid', 'operator' => 'in', 'value' => $departmentids);
		}
		$ret = Data::queryList($query, $count_query, $page, $query_list_conditions,
			array('status' => array(Consts::$keyusage_status_texts), 'ip' => 'long2ip'),
			array(array('status', '==', 1))
		);
		foreach ($ret['list'] as $key => $item)
		{
			$ret['list'][$key]['department_name'] = DepartmentService::getFullName($item['departmentid']);
		}
		return Response::json($ret);
	}


	public function postGet()
	{
		$eid = InputExt::getInt("eid");

		if(!KeyUsageService::check_customer($eid))
		{
			return;
		}

		$entity = DB::table('keyusage')->select(array('usageid', 'status'))
			->where('usageid', '=', $eid)
			->first();
		return Response::json($entity);
	}

	public function postSelects()
	{
		if (!$this->manager->top)
		{
			$query = DB::table('key')
				->select(array('key.keyid', DB::raw('CONCAT(key.name, IF(key.section is null, "", CONCAT(" - [", key.section, "]"))) as name')))
//				->leftJoin('department__key', 'department__key.keyid', '=', 'key.keyid')
				->orderBy("key.keyid", "desc");
//				->where('department__key.departmentid', '=', $this->manager->departmentid)
//				->where('department__key.status', '=', 1);
		}
		else
		{
			$query = DB::table('key')
				->select(array('keyid', DB::raw('CONCAT(key.name, " - [", key.section, "]") as name')))
				->orderBy("keyid", "desc");
		}
		$select_1 = $query->get();

		$select_2 = DB::table('product')->select(array('productid', DB::raw('CONCAT(name, " [", type, "]") as name')))
			->where('status', '=', ProductStatus::available)
			->get();
		return Response::json(array($select_1, $select_2));
	}
}