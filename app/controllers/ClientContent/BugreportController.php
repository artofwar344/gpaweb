<?php

namespace ClientContent;

use DB,
	Input;

class BugreportController extends \Controller {
	public function postIndex()
	{
		$params = file_get_contents("php://input");
		parse_str($params, $output);

		$version = $output['version'];
		$osversion = $output['osversion'];
		$info = $output['info'];
		$errorlog = pack('H*', $output['log']);

		$filename = date('YmdHis') . str_random(6) . '.dmp';
		$logpath = base_path() . '/content/client/errorlog/' . $filename;
		file_put_contents($logpath, $errorlog);

		if (empty($email)) $email = null;
		if (empty($info)) $info = null;

		DB::table('ca.exception3')->insert(
			array(
				'version' => $version,
				'osversion' => $osversion,
				'email' => $email,
				'info' => $info,
				'errorlog' => $filename
			)
		);
	}
} 