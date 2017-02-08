<?php
include_once(dirname(__FILE__).'/../sso/client/Local.php');

class LocalLogout implements Local {
	public function doLocal() {
		$_SESSION["LocalSession_LoginUserName"] = null;
		
		return "";
	}
}
?>