<?php
namespace Ca;

use Session,
	Ca\Service\ParamsService;

class Common {

	/**密钥加密
	 * @param $password
	 * @param $data
	 * @return string
	 */
	public static function encrypt_key($password, $data)
	{
		$salted = '';
		$dx = '';
		while (strlen($salted) < 48)
		{
			$dx = md5($dx . $password, true);
			$salted .= $dx;
		}
		$key = substr($salted, 0, 32);
		$iv = substr($salted, 32, 16);
		$encrypted_data = openssl_encrypt($data, 'aes-256-cbc', $key, true, $iv);
		return base64_encode($encrypted_data);
	}

	/**
	 * 密钥解密
	 * @param $password
	 * @param $edata
	 * @return string
	 */

	public static function decrypt_key($password, $edata)
	{
		$data = base64_decode($edata);
		$salt = "";

		$rounds = 3;
		$data00 = $password . $salt;
		$md5_hash = array();
		$md5_hash[0] = md5($data00, true);

		$result = $md5_hash[0];
		for ($i = 1; $i < $rounds; $i++)
		{
			$md5_hash[$i] = md5($md5_hash[$i - 1] . $data00, true);
			$result .= $md5_hash[$i];
		}
		$key = substr($result, 0, 32);
		$iv = substr($result, 32, 16);

		$string = openssl_decrypt($data, 'aes-256-cbc', $key, true, $iv);

		return $string == false ? "" : $string;
	}

	/**
	 * 判断参数是否存在
	 * @param $fields
	 */
	public static function empty_check($fields)
	{
		if (is_array($fields))
		{
			foreach ($fields as $field)
			{
				if (!isset($_POST[$field])) exit;
				$value = $_POST[$field];
				if (is_array($value) && empty($value)) exit;
				if (!is_array($value) && self::is_empty_str($value)) exit;
			}
		}
	}

	/**
	 * 判断参数是否为空
	 * @param $str
	 * @return bool
	 */
	public static function is_empty_str($str)
	{
		return (!isset($str) || trim($str) === '');
	}

	/**
	 * 格式化大小
	 * @param $size
	 * @return string
	 */
	public static function format_filesize($size)
	{
		if ($size <= 0)
		{
			return '0B';
		}
		$base = log($size) / log(1024);
		$units = array('B', 'KB', 'MB', 'GB', 'TB');
		$suffix = $units[floor($base)];
		return round(pow(1024, $base - floor($base)), 2) . $suffix;
	}

	/**
	 * 指定字符串长度
	 * @param $string
	 * @param $length
	 * @param string $subfix
	 * @return string
	 */
	public static function cut_string($string, $length, $subfix = '…')
	{
		$ret = array();
		if (strlen($string) <= $length)
		{
			return $string;
		}
		for ($i = 0; $i < $length; $i++)
		{
			$temp_str = substr($string, 0, 1);
			if (ord($temp_str) > 127)
			{
				$i++;
				if ($i < $length)
				{
					$ret[] = substr($string, 0, 3);
					$string = substr($string, 3);
				}
			}
			else
			{
				$ret[] = substr($string, 0, 1);
				$string = substr($string, 1);
			}
		}
		return implode('', $ret) . $subfix;
	}

	/**
	 * 创建缩略图
	 * @static
	 * @param $file_name
	 * @param $max_width
	 * @param $max_height
	 * @param null $target_path
	 * @return bool
	 */
	//TODO
	public static function resize_image($file_name, $max_width, $max_height, $target_path)
	{
		if ($info = getimagesize($file_name))
		{
			list($width, $height, $type) = $info;
			if($max_width > $width && $max_height > $height)
			{
				copy($file_name, $target_path);
				return false;
			}
			$function = "imagejpeg";
			if($type == 2)
			{
				$resource = imagecreatefromjpeg($file_name);
			}
			elseif($type == 3)
			{
				$resource = imagecreatefrompng($file_name);
				$function = "imagepng";
			}
			elseif($type == 1)
			{
				$resource = imagecreatefromgif($file_name);
				$function = "imagegif";
			}
		}
		else return false;

		if ($max_width <= $width || $max_height <= $height)
		{
			$width_ratio = $max_width / $width;
			$height_ratio = $max_height / $height;

			if ($width_ratio > $height_ratio)
			{
				$new_width  = $width * $width_ratio;
				$new_height = $height * $width_ratio;
			}else
			{
				$new_width  = $width * $height_ratio;
				$new_height = $height * $height_ratio;
			}
			if (function_exists("imagecopyresampled"))
			{
				$resource2 = imagecreatetruecolor($new_width, $new_height);
				imagecopyresampled($resource2, $resource, 0, 0, 0, 0, $new_width, $new_height, $width, $height);
			} else
			{
				$resource2 = imagecreate($new_width, $new_height);
				imagecopyresized($resource2, $resource, 0, 0, 0, 0, $new_width, $new_height, $width, $height);
			}

			$width = $new_width;
			$height = $new_height;
			if ($max_width != null && $width > $max_width)
			{
				$p[0] = ($width - $max_width)/2;
				$p[1] = 0;
			}
			if ($max_height != null && $height > $max_height)
			{
				$p[0] = 0;
				$p[1] = ($height-$max_height)/2;
			}

			$new_width  = $max_width != null && $max_width < $width    ? $max_width  : $width;
			$new_height = $max_height != null && $max_height < $height ? $max_height : $height;


			$new_resource = imagecreatetruecolor($new_width, $new_height);
			@imagecopy($new_resource, $resource2, 0, 0, $p[0], $p[1], $new_width, $new_height);
			imagedestroy($resource);
			imagedestroy($resource2);
		}
		call_user_func($function, $new_resource, $target_path);
		imagedestroy($new_resource);
		return true;
	}

	/**
	 * 上传文件
	 * @param $tmp_file
	 * @param $file_name
	 * @param $path
	 * @return bool
	 */
	public static function upload($tmp_file, $file_name, $path)
	{
		try
		{
			$folder = base_path() . '/content/' . $path;
			if (!is_dir($folder))
			{
				mkdir($folder, 0700, true);
			}
			move_uploaded_file($tmp_file, $folder . $file_name);
			/*
			$ch = curl_init();
			curl_setopt($ch, CURLOPT_HEADER, 0);
			curl_setopt($ch, CURLOPT_VERBOSE, 0);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
			curl_setopt($ch, CURLOPT_USERAGENT, "Mozilla/4.0 (compatible;)");
			curl_setopt($ch, CURLOPT_URL, str_replace('content', 'upload', Config::get('application.asset_url')));
			curl_setopt($ch, CURLOPT_POST, true);
			$post = array(
				'customer' => app()->environment(),
				'customer_securekey' => App::make('customer')->securekey,
				'file' => "@" . $tmp_file,
				'folder' => $path,
				'file_name' => $file_name
			);
			curl_setopt($ch, CURLOPT_POSTFIELDS, $post);
			$response = curl_exec($ch);
			if (!empty($response))
			{
				throw new Exception('Upload failed!');
			}*/
		}
		catch(Exception $e)
		{
			return false;
		}
		return true;
	}

	/**换算时间表示方式
	 * @param $datetime
	 * @return string
	 */
	public static function time_ago($datetime)
	{
		$timestamp = strtotime($datetime);
		$ago_time = time() - $timestamp;
		if ($ago_time <= 60)
		{
			return $ago_time . '秒前';
		}
		elseif ($ago_time <= 3600 && $ago_time > 60)
		{
			return floor($ago_time / 60) . '分钟前';
		}
		elseif ($ago_time <= 3600 * 24 && $ago_time > 3600)
		{
			return floor($ago_time / 3600) . '小时前';
		}
		elseif (strtotime('-15 days') < $timestamp)
		{
			return floor((time() - $timestamp) / (3600 * 24)) . '天前';
		}
		else
		{
			return date('Y年m月d日', $timestamp);
		}
	}

	/**
	 * 转换时间格式
	 * @param $datetime
	 * @param string $format
	 * @return string
	 */
	public static function datetime_to_date($datetime, $format = 'Y年m月d日')
	{
		$timestamp = strtotime($datetime);
		return date($format, $timestamp);
	}

	/**
	 * 过滤字符串
	 * @param $string
	 * @return mixed
	 */
	public static function ubb($string)
	{
		$string = trim($string);
		$string = trim($string, "\n");
		$string = str_replace("\n","<br />", $string);
		$string = preg_replace("/\\t/is","  ", $string);

		$string = preg_replace("/\[align=(.+?)\](.+?)\[\/align\]/is", '<div class="text-align:\\1">\\2</div>', $string);
		//$string = preg_replace("/\[url=([^\[]*)\](.+?)\[\/url\]/is","<a href=\\1 target='_blank'>\\2</a>", $string);
		$string = preg_replace("/\[url\](.+?)\[\/url\]/is", "<a href=\"\\1\" target='_blank'>\\1</a>", $string);
		$string = preg_replace("/\[url=(http:\/\/.+?)\](.+?)\[\/url\]/is", "<a href='\\1' target='_blank'>\\2</a>", $string);
		//$string = preg_replace("/\[url=(.+?)\](.+?)\[\/url\]/is","<a href=\\1>\\2</a>", $string);
		$string = preg_replace("/\[img\](.+?)\[\/img\]/is", "<img src=\\1>", $string);
		$string = preg_replace("/\[img\s(.+?)\](.+?)\[\/img\]/is", "<img \\1 src=\\2>", $string);

		$string = preg_replace("/\[i\](.+?)\[\/i\]/is", "<i>\\1</i>", $string);
		$string = preg_replace("/\[s\](.+?)\[\/s\]/is", "<strike>\\1</strike>", $string);
		$string = preg_replace("/\[u\](.+?)\[\/u\]/is", "<u>\\1</u>", $string);
		$string = preg_replace("/\[b\](.+?)\[\/b\]/is", "<b>\\1</b>", $string);
		$string = preg_replace("/\[p\](.+?)\[\/p\]/is", "<div>\\1</div>", $string);
		$string = preg_replace("/\[br\]/is", "<br />", $string);
		return $string;
	}

	/**
	 * 获取用户ip
	 * @return string
	 */
	public static function client_ip()
	{
		$keys = array('HTTP_X_FORWARDED_FOR', 'HTTP_X_FORWARDED_FOR', 'HTTP_CLIENT_IP', 'REMOTE_ADDR');
		$ip = '';
		foreach ($keys as $key)
		{
			if (array_key_exists($key, $_SERVER) && !empty($_SERVER[$key]))
			{
				$ip = $_SERVER[$key];
				continue;
			}
		}
		if ($pos = strpos($ip, ","))
		{
			return substr($ip, 0, $pos);
		}
		return $ip;
	}

	/**
	 * 字符串加密
	 * @param $value
	 * @param $key
	 * @return string
	 */
	//TODO
	public static function encrypt_string($value, $key)
	{
		$ret = '';
		$vlen = strlen($value);
		$klen = strlen($key);

		$k = 0;
		$v = 0;

		for($v = 0; $v < $vlen; $v++)
		{
			$ret[$v] = chr(ord($value[$v]) ^ ord($key[$k]));
			$k = (++$k < $klen ? $k : $klen - 1);
		}
		$ret = base64_encode(implode('', $ret));
		return $ret;
	}

	/**
	 * 字符串解密
	 * @param $hash
	 * @param $key
	 * @return string
	 */
	//TODO
	public static function decrypt_string($hash, $key)
	{
		$ret = '';
		$hash = base64_decode($hash);
		$hlen = strlen($hash);
		$klen = strlen($key);
		$k = 0;

		for($v = 0; $v < $hlen; $v++)
		{
			$ret[$v] = chr(ord($hash[$v]) ^ ord($key[$k]));
			$k = (++$k < $klen ? $k : $klen - 1);
		}
		return implode('', $ret);
	}

	/**唯一表示输出
	 * @return string
	 */
	//TODO
	public static function uuid()
	{
		return sprintf( '%04x%04x-%04x-%04x-%04x-%04x%04x%04x',
			// 32 bits for "time_low"
			mt_rand(0, 0xffff), mt_rand( 0, 0xffff ),
			// 16 bits for "time_mid"
			mt_rand(0, 0xffff),
			// 16 bits for "time_hi_and_version",
			// four most significant bits holds version number 4
			mt_rand(0, 0x0fff) | 0x4000,
			// 16 bits, 8 bits for "clk_seq_hi_res",
			// 8 bits for "clk_seq_low",
			// two most significant bits holds zero and one for variant DCE1.1
			mt_rand(0, 0x3fff) | 0x8000,
			// 48 bits for "node"
			mt_rand(0, 0xffff), mt_rand(0, 0xffff), mt_rand(0, 0xffff)
		);
	}



	/**
	 * Get Login Url
	 *
	 * @param null $ret
	 * @return string
	 */
	public static function link_to_login($ret = null)
	{
		if ($ret == null)
		{
			$ret = \URL::full();
		}
		return 'http://user.' . app()->environment() . '/login?ret=' . $ret;
	}

	/**
	 * 生成随机字符串
	 * @param $length
	 * @return string
	 */
	public static function get_random_str($length)
	{
		$buffer = '~!@#$%^&*()_+}{:?><0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
		$str = '';
		for($i = 0; $i < $length; $i++)
		{
			$index = rand(0, strlen($buffer) - 1);
			$str .= $buffer[$index];
		}
		return $str;
	}
}



