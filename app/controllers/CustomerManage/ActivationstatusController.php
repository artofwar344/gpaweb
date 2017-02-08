<?php
namespace CustomerManage;

use DB,
	View,
	Input,
	InputExt,
	Ca\UserKeyStatus,
	Ca\Service\ProductService,
	Ca\Service\UserService;

class ActivationstatusController extends BaseController {
	public $layout = 'customermanage/layouts/common';

	public function getIndex()
	{
		$user_id = InputExt::getInt('id');
		$user = UserService::get_user_by_userid($user_id);
		$this->layout->title = "激活统计";
		$this->layout->body = View::make('customermanage/key/activationstatus')->with('user', $user)->with('user_id', $user_id);
	}

	public function postList()
	{
		$user_id = InputExt::getInt('userid');
		$products = ProductService::get_available_product($user_id);
		foreach ($products as $key => $product)
		{
			$products[$key]->available = $product->assigntotalcount - $product->used;
			$products[$key]->requesting = $product->userkey_status  == UserKeyStatus::pending ? $product->requestcount : 0;
		}

		$ret = array(
			'list' => array_values($products),
			'count' => 1,
			'entityCount' => count($products)
		);
		echo json_encode($ret);
	}
}

