<?php
namespace CustomerManage;

use View,
	DB,
	Input,
	InputExt,
	Response,
	\Ca\Consts,
	\Ca\Common,
	\Ca\Data;

class ProductpkgController extends BaseController {
	public $layout = 'customermanage/layouts/common';

	public function getIndex()
	{
		$this->layout->title = "用户商品管理";
		$this->layout->body = View::make('customermanage/product/productpkg');
	}

	public function postList()
	{
		$page = InputExt::getInt('page');

		$query = DB::table('productpkg')
			->select(array('productpkg.*'));

		$count_query = DB::table('productpkg')->select(array(DB::raw('COUNT(*) as count')));

		$ret = Data::queryList($query, $count_query, $page, array(),
			array('productids' => function($productids) {
			$productnames = array();
			$productids = explode(',', $productids);
			foreach ($productids as $productid)
			{
				$productnames[] = DB::table('product')->where('productid', '=', $productid)->pluck('name');
			}
			return implode(', ', $productnames);
		}));
		echo json_encode($ret);
	}

	public function postEntity()
	{
		$eid = InputExt::getInt('eid');
		$productids = Input::get('productids');
		$note = Input::get('note');
		if (is_array($productids))
		{
			$productids = implode(',', $productids);
		}
		else
		{
			$productids = '';
		}

		Data::updateEntity('productpkg', array('pkgid', '=', $eid), array('productids' => 'productids', 'note' => 'note'),
			array('productids' => $productids, 'note' => $note)
		);
	}

	public function postGet()
	{
		$eid = InputExt::getInt('eid');
		$entity = DB::table('productpkg')
			->select(array('productpkg.pkgid', 'productpkg.productids', 'productpkg.note'))
			->where('pkgid', '=', $eid)->first();
		return Response::json($entity);
	}

	public function postDelete()
	{
		$eid = InputExt::getInt("eid");
		DB::table('product')->where('productid', '=', $eid)->delete();
	}


}

