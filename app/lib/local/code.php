<?php

include_once(dirname(__FILE__).'/../sso/client/Local.php');

class LocalCode implements Local {
	public function doLocal() {
		$sysCode = $_SESSION["sysCode"] == null ? "" : $_SESSION["sysCode"].ToString();

		if (empty($sysCode) || $sysCode=="")
			return "true";

		$inputCode = $_REQUEST["code"];
		if (empty($inputCode) || $inputCode=="")
			return "false";

		if (strtolower($inputCode) == strtolower($sysCode))
			return "true";
		else
			return "false";
	}
}

?>