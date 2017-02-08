<?php
namespace Ca\Service;

class FastdfsService
{
	public static function gen_download_url($file_name)
	{
		$time_stamp = time();
		list($group, $file_id) = explode('/', $file_name, 2);
		if (function_exists('fastdfs_http_gen_token'))
		{
			$token = fastdfs_http_gen_token($file_id, $time_stamp);
			return 'http://101.4.63.133/' . $file_name . '?token=' . $token . '&ts=' . $time_stamp;
		}
		else
		{
			return 'http://101.4.63.133/' . $file_name;
		}
	}

}