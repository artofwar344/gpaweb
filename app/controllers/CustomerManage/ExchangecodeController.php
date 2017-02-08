<?php
namespace CustomerManage;

use \View,
	\DB,
	\Input,
	\Response,
	\InputExt,
	Ca\Data,
	Ca\Common,
	Ca\Consts,
	Ca\ExchangecodeStatus,
	Ca\ProductStatus,
	Ca\Service\CustomerService,
	Ca\Service\DepartmentService,
	Ca\Service\ExchangeCodeService,
	Ca\Service\KeyService;

class ExchangecodeController extends BaseController {
	public $layout = 'customermanage/layouts/common';

	public function getIndex()
	{
//		KeyService::check_remains(1);exit;
		$this->layout->title = "激活码管理";
		$this->layout->body = View::make("customermanage/key/exchangecode")
			->with('topmanager', $this->manager->top);
	}

	public function postList()
	{
		$status = InputExt::getInt('status');
		$page = InputExt::getInt('page');
		$managerid = $this->manager->managerid;
		$query = DB::table('exchangecode')
			->select(array('exchangecode.*', 'manager.name as managername',
					DB::raw('IF(exchangecode.status = ' . ExchangecodeStatus::unassgined . ' or exchangecode.managerid != ' . $managerid . ' ,"", exchangecode.code) as code'),
					DB::raw('CONCAT(key.name, IF(key.section IS NULL, "", CONCAT(" - [", key.section, "]"))) as keyname'),
					DB::raw('CONCAT(product.name, " [", product.type, "]") as productname')
				))
			->leftJoin('key', 'key.keyid', '=', 'exchangecode.keyid')
			->leftJoin('product', 'product.productid', '=', 'key.productid')
			->leftJoin('manager', 'manager.managerid', '=', 'exchangecode.managerid')
			->orderBy('exchangecode.codeid', 'desc');

		$count_query = DB::table('exchangecode')->select(array(DB::raw('COUNT(*) as count')));

		$ret = Data::queryList(
			$query, $count_query, $page,
			array(array('type' => 'int', 'field' => 'exchangecode.status', 'value' => $status)),
			array('status' => array(Consts::$exchangecode_status_text)), null,
			array(array('status', '!=', ExchangecodeStatus::used))
		);

		return Response::json($ret);
	}

	public function postEntity($customer)
	{
		if (!$this->manager->top)
		{
			return;
		}
		$customerid = CustomerService::getCustomeridByAlias($customer);

		$count = InputExt::getInt('count');
		$keyid = InputExt::getInt('keyid');

		for ($i = 0; $i < $count; $i++)
		{
			DB::table('exchangecode')->insert(array(
				'code' => ExchangeCodeService::exchangeCode($customerid),
				'keyid' => $keyid,
				'status' => ExchangecodeStatus::unassgined
			));
		}
	}

	public function postDelete()
	{
		if (!$this->manager->top)
		{
			return;
		}
		$eid = InputExt::getInt("eid");
		DB::table('exchangecode')
			->where('codeid', '=', $eid)
			->where('status', '!=', ExchangecodeStatus::used)
			->delete();
	}

	public function postSelects()
	{
		$select_1 = DB::table('product')->select(array('productid', DB::raw('CONCAT(name, " [", type, "]") as name')))
			->where('status', '=', ProductStatus::available)
			->whereIn('type', array('永久激活', '定时激活'))
			->orderBy("productid", "desc")
			->get();

		return Response::json(array($select_1));
	}

	public function postKey()
	{
		$productid = InputExt::getInt('productid');
		$remains = KeyService::check_remains($this->manager->departmentid);
//		return Response::json($remains);
		$ret = array();
		foreach ($remains as $item)
		{
			if ($item->productid == $productid)
			{
				$ret[] = $item;
			}
		}
		return Response::json($ret);
	}

	public function postAssign()
	{
		$eid = InputExt::getInt('eid');
		$code = DB::table('exchangecode')->where('codeid', '=', $eid)->first();
		if ($code == null)
		{
			return Response::json(array('status' => 3));
		}
		if ($code->status != ExchangecodeStatus::unassgined)
		{
			return Response::json(array('status' => 2));
		}
		DB::table('exchangecode')->where('codeid', '=', $eid)
		->where('status', '=', ExchangecodeStatus::unassgined)
		->update(array('managerid' => $this->manager->managerid, 'assigndate' => date('Y-m-d H:i:s'), 'status' => ExchangecodeStatus::assgined));
		return Response::json(array('status' => 1, 'code' => $code->code));
	}


}

