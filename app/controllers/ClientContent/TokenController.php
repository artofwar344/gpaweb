<?php
namespace ClientContent;

use DB,
	Redis;

class TokenController extends \Controller
{
	public function postIndex()
	{
		$token = str_random(32);
		$redis = Redis::connection();
		$redis->set($token, 1);
		// 30秒过期
		$redis->expire($token, 30);

		echo json_encode(array('token' => $token));
	}
}