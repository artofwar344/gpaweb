<?php
namespace Api;

use \Illuminate\Support\Facades\Request,
	\Illuminate\Support\Facades\Input,
	\Illuminate\Support\Facades\Validator,
	Ca\UserType,
	Ca\Common,
	Ca\Consts,
	Ca\Service\UserService,
	Ca\Service\ManagerService,
	Ca\Service\ParamsService,
	Ca\Service\KeyService,
	Ca\Service\LicenseService,
	Ca\Service\DesService,
	Ca\Logger;

class LicenseController extends BaseController {

	public function license()
	{
		$number = Input::get('number');
		$hash = Input::get('hash');
		$type = Input::get('type');
		if (ParamsService::get('apislicenseserver') && $hash == 'ay7TZnpElKxWO76fEe8ehB2BzuWfa2bnS02z')
		{
			$license = LicenseService::get($number,$type,'1','');
			if($license == null){
				print json_encode(array('status' => 0));
				exit;
			}else if($license->status == 1)
			{
				print json_encode(array('status' => 2));
				exit;
			}else
			{
				$licenseEncrypt = DesService::encrypt($license->license);
				print json_encode(array('status' => 1, 'license' => $licenseEncrypt));
				exit;
			}
		}
	}
	public function licenseNew()
	{
		$number = Input::get('number');
		$hash = Input::get('hash');
		$type = Input::get('type');
		$status = Input::get('status');
		$error = Input::get('error');
		$computerId = Input::get('computerId');
		if (ParamsService::get('apislicenseserver') && $hash == 'ay7TZnpElKxWO76fEe8ehB2BzuWfa2bnS02z')
		{
			if($status == 1)
			{
				LicenseService::updateNumber($number,'1',$error,$computerId);
				print json_encode(array('status' => 1));
				exit;
			}
			if($status == 2)
			{
				LicenseService::updateNumber($number,'0',$error,$computerId);
				print json_encode(array('status' => 1));
				exit;
			}
			$license = LicenseService::get($number,$type,'2',$computerId);
			if($license == null){
				print json_encode(array('status' => 0));
				exit;
			}else if($license->status == 1 || $license->status == 2)
			{
				if($license->status == 1)
				{
					if($license->computerid == $computerId)
					{
						print json_encode(array('status' => 3));
						exit;
					}
				}
				print json_encode(array('status' => 2));
				exit;
			}else
			{
				$licenseEncrypt = DesService::encrypt($license->license);
				print json_encode(array('status' => 1, 'license' => $licenseEncrypt));
				exit;
			}
		}
	}
	public function code()
	{
		$str = null;
		$length = 10;
		$strPol = "ABCDEFGHJKLMNPQRSTUVWXYZ23456789";
		$max = strlen($strPol)-1;
		for($i=0;$i<$length;$i++){
		    $str.=$strPol[rand(0,$max)];//rand($min,$max)生成介于min和max两个数之间的一个随机整数
		}
		echo $str;
		exit();
	}
	public function test(){
		
		echo Common::encrypt_key("12345","GMKPB-HBVV4-T99VG-KK2KX-WXWY6")."<br/>";
		echo Common::decrypt_key("12345","yLRqWwflFgcJZlKyeqGJKJQ83TCn3I08bu3rbNfhnz4=");
		exit();
		//echo Common::encrypt_key("12345","");
	}
}