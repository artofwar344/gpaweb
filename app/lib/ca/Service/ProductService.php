<?php
namespace Ca\Service;

use DB,
	Ca\KeyUsageStatus,
	Ca\UserKeyStatus,
	Ca\ProductStatus;

class ProductService {

	public static function check_product($productid)
	{
		return DB::table('product')->where('productid', '=', $productid)->count() > 0;
	}

	public static function get_available_product($userid)
	{

//		$usagecount_query = DB::table('keyusage')
//			->select(array(DB::raw('COUNT(keyusage.userid) as count'), 'key.productid'))
//			->leftJoin('key', 'key.keyid', '=', 'keyusage.keyid')
//			->where('keyusage.userid', '=', DB::raw($userid))
//			->whereNotIn('keyusage.status', array(KeyUsageStatus::activation_failed, KeyUsageStatus::activation_reset))
//			->groupBy('key.productid')->toSql();
		$usagecount_query = 'select COUNT(keyusage.userid) as count, `key`.`productid` from `keyusage`
		left join `key` on `key`.`keyid` = `keyusage`.`keyid`
		where `keyusage`.`userid` = '.DB::raw($userid).' and `keyusage`.`status` not in ('.KeyUsageStatus::activation_failed . ',' . KeyUsageStatus::activation_reset.')
		 group by `key`.`productid`';

		$keycount_query = DB::table('userkey')
			->select(array(DB::raw('SUM(assigncount) as count'), 'userkey.productid',DB::raw('SUM(requestcount) as requestcount')))
//			->select(array(DB::raw('SUM(requestcount) as requestcount'), 'userkey.productid'))
			->where('userkey.userid', '=', DB::raw($userid))
			->where('userkey.status', '=', DB::raw(UserKeyStatus::agree))
			->groupBy('userkey.productid')->toSql();

		$deniedcount_query = DB::table('userkey')
			->select(array(DB::raw('SUM(CAST(requestcount AS SIGNED) - CAST(assigncount AS SIGNED)) AS count'), 'userkey.productid'))
			->where('userkey.userid', '=', DB::raw($userid))
			->where('userkey.status', '=', DB::raw(UserKeyStatus::agree))
			->groupBy('userkey.productid')->toSql();

		$products = DB::table('product')
			->select(array('product.productid', 'product.name', 'product.aliasname', 'product.type', 'userkey.userkeyid',
				DB::raw('IFNULL(userkeycount.requestcount, 0) as requestcount'),
				DB::raw('IF(deniedcount.count < 0, 0, deniedcount.count) as denied'),
//				DB::raw('IFNULL(deniedcount.count, 0) as denied'),
//				DB::raw('IFNULL(assigncount, 0) as assigncount'),
				DB::raw('IFNULL(keyusagecount.count, 0) as used'),
				DB::raw('IFNULL(userkeycount.count, 0) as assigntotalcount'),
				'userkey.status AS userkey_status' ))
			->leftJoin('key', 'key.productid', '=', 'product.productid')
			->leftJoin(DB::raw("({$usagecount_query}) AS keyusagecount"), 'keyusagecount.productid', '=', 'product.productid')
			->leftJoin(DB::raw("({$keycount_query}) AS userkeycount"), 'userkeycount.productid', '=', 'product.productid')
			->leftJoin(DB::raw("({$deniedcount_query}) AS deniedcount"), 'deniedcount.productid', '=', 'product.productid')
			->leftJoin('userkey', function($join) use ($userid)
			{
				$join->on('userkey.productid', '=', 'product.productid');
				$join->on('userkey.userid', '=', DB::raw($userid));
			})
			->where('product.status', '=', ProductStatus::available)
			->groupBy('product.productid')
			->groupBy('userkeyid')
			->get();
		self::get_latest_products($products);
		return $products;
	}

	/**
	 * 剔除不是最新的激活申请
	 * @param $products
	 */
	public static function get_latest_products(&$products)
	{
		$products_new = array();
		foreach ($products as $product)
		{
			if (array_key_exists($product->productid, $products_new) )
			{
				if($product->userkeyid > $products_new[$product->productid]->userkeyid)
				{
					$products_new[$product->productid] = $product;
				}
			}
			else
			{
				$products_new[$product->productid] = $product;
			}
		}
		$products = $products_new;
	}


	//获取商品 用于显示商品checklist
	public static function all()
	{
		$products = DB::table('product')->where('status', '=', ProductStatus::available)->get();
		$productlists = array();
		foreach($products as $product)
		{
			$productlists[$product->type]['name'] = $product->type;
			$productlists[$product->type]['list'][$product->productid] = $product->name . ' [' . $product->type . ']';
		}
		return $productlists;
	}

}