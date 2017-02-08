<?php
namespace Ca;

use Config,
	App;

class Filemanager {

	static function index()
	{
		$php_path = base_path() . '/content/';
		$php_url = Config::get('app.asset_url');
		$alias = App::make('customer')->alias;
		if ($alias)
		{
			$root_path = $php_path . 'upload/' . $alias . '/';
			$root_url = $php_url . 'upload/' . $alias . '/';
		}
		else
		{
			$root_path = $php_path . 'upload/';
			$root_url = $php_url . 'upload/';
		}
		$ext_arr = array('gif', 'jpg', 'jpeg', 'png', 'bmp');

		$dir_name = empty($_GET['dir']) ? '' : trim($_GET['dir']);
		if (!in_array($dir_name, array('', 'image', 'flash', 'media', 'file')))
		{
			echo "Invalid Directory name.";
			exit;
		}
		if ($dir_name !== '')
		{
			$root_path .= $dir_name . "/";
			$root_url .= $dir_name . "/";
			if (!file_exists($root_path))
			{
				mkdir($root_path, 0700, true);
			}
		}

		if (empty($_GET['path']))
		{
			$current_path = realpath($root_path) . '/';
			$current_url = $root_url;
			$current_dir_path = '';
			$moveup_dir_path = '';
		}
		else
		{
			$current_path = realpath($root_path) . '/' . $_GET['path'];
			$current_url = $root_url . $_GET['path'];
			$current_dir_path = $_GET['path'];
			$moveup_dir_path = preg_replace('/(.*?)[^\/]+\/$/', '$1', $current_dir_path);
		}
		$order = empty($_GET['order']) ? 'name' : strtolower($_GET['order']);

		if (preg_match('/\.\./', $current_path))
		{
			echo 'Access is not allowed.';
			exit;
		}
		if (!preg_match('/\/$/', $current_path))
		{
			echo 'Parameter is not valid.';
			exit;
		}
		if (!file_exists($current_path) || !is_dir($current_path))
		{
			echo 'Directory does not exist.';
			exit;
		}

		$file_list = array();
		if ($handle = opendir($current_path))
		{
			$i = 0;
			while (false !== ($filename = readdir($handle)))
			{
				if ($filename{0} == '.')
				{
					continue;
				}
				$file = $current_path . $filename;
				if (is_dir($file))
				{
					$file_list[$i]['is_dir'] = true;
					$file_list[$i]['has_file'] = (count(scandir($file)) > 2);
					$file_list[$i]['filesize'] = 0;
					$file_list[$i]['is_photo'] = false;
					$file_list[$i]['filetype'] = '';
				}
				else
				{
					$file_list[$i]['is_dir'] = false;
					$file_list[$i]['has_file'] = false;
					$file_list[$i]['filesize'] = filesize($file);
					$file_list[$i]['dir_path'] = '';
					$file_ext = strtolower(pathinfo($file, PATHINFO_EXTENSION));
					$file_list[$i]['is_photo'] = in_array($file_ext, $ext_arr);
					$file_list[$i]['filetype'] = $file_ext;
				}
				$file_list[$i]['filename'] = $filename;
				$file_list[$i]['datetime'] = date('Y-m-d H:i:s', filemtime($file));
				$i++;
			}
			closedir($handle);
		}

		usort($file_list, function ($a, $b) use ($order)
		{
			if ($a['is_dir'] && !$b['is_dir'])
			{
				return -1;
			}
			else if (!$a['is_dir'] && $b['is_dir'])
			{
				return 1;
			}
			else
			{
				if ($order == 'size')
				{
					if ($a['filesize'] > $b['filesize'])
					{
						return 1;
					}
					else if ($a['filesize'] < $b['filesize'])
					{
						return -1;
					}
					else
					{
						return 0;
					}
				}
				else if ($order == 'type')
				{
					return strcmp($a['filetype'], $b['filetype']);
				}
				else
				{
					return strcmp($a['filename'], $b['filename']);
				}
			}
		});

		$result = array();
		$result['moveup_dir_path'] = $moveup_dir_path;
		$result['current_dir_path'] = $current_dir_path;
		$result['current_url'] = $current_url;
		$result['total_count'] = count($file_list);
		$result['file_list'] = $file_list;

		header('Content-type: application/json; charset=UTF-8');
		echo json_encode($result);
		exit;

	}

	static public function upload()
	{
		$max_size = 200 * 1024;
		$ext_arr = array(
			'image' => array('gif', 'jpg', 'jpeg', 'png', 'bmp'),
			'flash' => array('swf', 'flv'),
			'media' => array('swf', 'flv', 'mp3', 'wav', 'wma', 'wmv', 'mid', 'avi', 'mpg', 'asf', 'rm', 'rmvb'),
			'file' => array('doc', 'docx', 'xls', 'xlsx', 'ppt', 'htm', 'html', 'txt', 'zip', 'rar', 'gz', 'bz2'),
		);

		if (!empty($_FILES['imgFile']['error']))
		{
			switch ($_FILES['imgFile']['error'])
			{
				case '1':
					$error = '超过php.ini允许的大小。';
					break;
				case '2':
					$error = '超过表单允许的大小。';
					break;
				case '3':
					$error = '图片只有部分被上传。';
					break;
				case '4':
					$error = '请选择图片。';
					break;
				case '6':
					$error = '找不到临时目录。';
					break;
				case '7':
					$error = '写文件到硬盘出错。';
					break;
				case '8':
					$error = 'File upload stopped by extension。';
					break;
				case '999':
				default:
					$error = '未知错误。';
			}
			header('Content-type: text/html; charset=UTF-8');
			echo json_encode(array('error' => 1, 'message' => $error));
			exit;
		}

		if (empty($_FILES) === false)
		{
			//原文件名
			$file_name = $_FILES['imgFile']['name'];
			//服务器上临时文件名
			$tmp_name = $_FILES['imgFile']['tmp_name'];
			//文件大小
			$file_size = $_FILES['imgFile']['size'];
			//检查文件名
			if (!$file_name)
			{
				echo json_encode(array('error' => 1, 'message' => "请选择文件。"));
				exit;
			}

			//检查是否已上传
			if (@is_uploaded_file($tmp_name) === false)
			{
				echo json_encode(array('error' => 1, 'message' => "上传失败。"));
				exit;
			}
			//检查文件大小
			if ($file_size > $max_size)
			{
				echo json_encode(array('error' => 1, 'message' => "上传文件大小超过限制。"));
				exit;
			}
			//检查目录名
			$dir_name = empty($_GET['dir']) ? 'image' : trim($_GET['dir']);
			if (empty($ext_arr[$dir_name]))
			{
				echo json_encode(array('error' => 1, 'message' => "目录名不正确。"));
				exit;
			}
			//获得文件扩展名
			$temp_arr = explode(".", $file_name);
			$file_ext = array_pop($temp_arr);
			$file_ext = trim($file_ext);
			$file_ext = strtolower($file_ext);
			//检查扩展名
			if (in_array($file_ext, $ext_arr[$dir_name]) === false)
			{
				echo json_encode(array('error' => 1, 'message' => "上传文件扩展名是不允许的扩展名。\n只允许" . implode(",", $ext_arr[$dir_name]) . "格式。"));
				exit;
			}

			$time = explode(" ", microtime());
			$ext = substr($_FILES['imgFile']['name'], -4);
			$sub_path = date('Y-m-d') . '/';

			$alias = App::make('customer')->alias;
			if ($alias)
			{
				$new_path = base_path() . '/content/upload/' . $alias . '/image/' . $sub_path . date('H') . str_replace('.', '', $time[0]) . $ext;
			}
			else
			{
				$new_path = base_path() . '/content/upload/image/' . $sub_path . date('H') . str_replace('.', '', $time[0]) . $ext;
			}
			if (!is_dir(dirname($new_path)))
			{
				mkdir(dirname($new_path), 0700, true);
			}
			move_uploaded_file($_FILES['imgFile']['tmp_name'], $new_path);

			header('Content-type: text/html; charset=UTF-8');
			echo json_encode(array('error' => 0, 'url' => Config::get('app.asset_url') . "upload/" . $alias . '/' . $dir_name . "/" . $sub_path . "/" . basename($new_path)));
			exit;
		}

		exit;
	}
}