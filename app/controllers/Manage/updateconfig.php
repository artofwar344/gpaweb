<?php

class  Updateconfig_Controller extends Base_Controller {

	public function action_index()
	{
		$customer_id = InputExt::getInt('eid');
		$customer = DB::table('customer')->where('customerid', '=', $customer_id)->first();
		if ($customer == null)
		{
			exit;
		}

		$this->generate_config_file('generate', 'user', $customer->alias);
		$this->generate_config_file('generate', 'customer', $customer->alias, array('main_domain' => 'gp.test'));
		$this->generate_config_file('generate', 'customer.manage', $customer->alias);
//		$this->generate_config_file('generate', 'client.content', $customer->alias);
		$this->generate_config_file('generate', 'api', $customer->alias);

		$modules = Consts::$module_texts;
		foreach ($modules as $module => $name)
		{
			if (strpos($customer->module, $module) !== false)
			{
				$this->update_config_file('generate', $module, $customer->alias);
			}
			else
			{
				$this->update_config_file('delete', $module, $customer->alias);
			}
		}

		return Response::make('配置文件更新成功');
	}

	private function update_config_file($act, $module, $alias, $data = array())
	{
		$sitename = $alias . '.gp.test';
		$databasename = 'ca_' . $alias;
		$appdir = 'app_' . $module;
		$config_path = '../' . $appdir . '/application/config/' . $sitename;

		if ($act == 'delete')
		{
			if (is_dir($config_path))
			{
				$this->deldir($config_path);
			}
			return;
		}

		if (!is_dir($config_path))
		{
			mkdir($config_path);
		}

		$tempdir = 'config.template/' . $module . '/';
		if (is_file($tempdir . 'application.cfg.tpl'))
		{

			$str = file_get_contents($tempdir . 'application.cfg.tpl');
			$str = str_replace('{{ sitename }}', $sitename, $str);
			if (isset($data['main_domain']))
			{
				$main_domain = $data['main_domain'];
				$str = str_replace('{{ main_domain }}', $main_domain, $str);
			}
			file_put_contents($config_path . '/application.php', $str);
		}

		if (is_file($tempdir . 'database.cfg.tpl'))
		{
			$str = file_get_contents($tempdir . 'database.cfg.tpl');
			$str = str_replace('{{ database }}', $databasename, $str);
			file_put_contents($config_path . '/database.php', $str);
		}

		if (is_file($tempdir . 'session.cfg.tpl'))
		{
			$str = file_get_contents($tempdir . 'session.cfg.tpl');
			$str = str_replace('{{ domain }}', $sitename, $str);
			file_put_contents($config_path . '/session.php', $str);
		}

		if (is_file($tempdir . 'error.cfg.tpl'))
		{
			$str = file_get_contents($tempdir . 'error.cfg.tpl');
			file_put_contents($config_path . '/error.php', $str);
		}

	}

	/**
	 * 删除文件夹及其子文件(夹)
	 * param $path
	 */
	private function deldir($path)
	{
		if (substr($path,0,-1) != '/')
		{
			$path = $path . '/';
		}
		$_dir = dir($path);
		while (false !== ($fileName = $_dir->read())) {
			if (is_file($path . $fileName))
			{
				unlink($path . $fileName);
			}

			if (is_dir($path . $fileName) &&  $fileName != '.' && $fileName != '..')
			{
				$this->deldir($path . $fileName);
				rmdir($path . $fileName);
			}
		}
		$_dir->close();
		rmdir($path);
	}


}

