<?php
include_once(dirname(__FILE__).'/../sso/client/Local.php');

class LocalValidate implements Local {
	public function doLocal() {
		$username = $_REQUEST["username"];
		$password = $_REQUEST["password"];
		if ($username=="admin" && $password=="pass")
		{
			return "{\"result\":\"Success\",\"msg\":\"" . "验证成功！" . "\"}";
		}
		else
		{
			return "{\"result\":\"Failure\",\"msg\":\"" . "用户名或密码错误！" . "\"}";
		}
	}
}?>