<?php
namespace ClientContent;

use DB,
	Input,
	Log,
	Config,
	Response;

class UpdateController extends \Controller
{
	public function postIndex()
	{
		$customer_alias = Input::get('customer');
		$type = Input::get('type');
		$old_version = Input::get('version');

		if (!$customer_alias)
		{
			exit;
		}

		$customer_exists = DB::table('ca.customer')
			->where('alias', '=', $customer_alias)
			->where('status', '=', 1)
			->count() > 0;

		if (!$customer_exists)
		{
			exit;
		}
		$clientversionType =  $type == 'main' ? 'clientversion' : 'batchclientversion';

		$new_version = DB::table('ca_' . $customer_alias . '.params')
			->select(array('value'))
			->where('key', '=', $type == 'main' ? 'clientversion' : 'batchclientversion')
			->first();
		Log::info('#@# customer' . $customer_alias);
		Log::info('#@# new_version: ' . json_decode($new_version));
		if($clientversionType != 'batchclientversion') $new_version = $new_version->value;

		if (!$new_version)
		{
			exit;
		}

		if ($old_version && $new_version > $old_version)
		{
			DB::table('ca.clientupdatelog')
				->insert(array(
					'oldversion' => $old_version,
					'newversion' => $new_version,
					'customeralias' => $customer_alias,
					'type' => $type
				));
		}

		$proxy_server = DB::table('ca_' . $customer_alias . '.params')
			->select(array('value'))
			->where('key', '=', 'proxyserver')
			->first();
		if ($proxy_server)
		{
			$proxy_server = $proxy_server->value;
		}

		$url = ($proxy_server ? 'http://' . $proxy_server . '/' : Config::get('app.asset_url')) . 'client/update/';
		switch ($type)
		{
			case 'main':
				$url .= 'GP-lastest(' . $customer_alias . ').exe';
				break;
			case 'activation':
				$url .= 'GPActivation-lastest(' . $customer_alias . ').exe';
				break;
		}

		return Response::json(array(
			'Version' => $new_version,
			'Url' => $url
		));
	}

	public function postMain()
	{
		$old_version = Input::get('version');
		$customer_alias = Input::get('customer_alias');
		if (!$old_version || !$customer_alias) 
		{
			exit;
		}

		$new_version = DB::table('ca_' . $customer_alias . '.params')
			->select(array('value'))
			->where('key', '=', 'clientversion3')
			->first();
		if (!$new_version)
		{
				exit;
		}
		$new_version = $new_version->value;

		if ($new_version != $old_version)
		{
			DB::table('ca.clientupdatelog')
				->insert(array(
					'oldversion' => $old_version,
					'newversion' => $new_version,
					'type' => 'main'
				));
		}

		$url = Config::get('app.asset_url') . 'client/gp3/';
		return Response::json(array(
			'Version' => $new_version,
			'Url' => $url . 'GP(' . $customer_alias . ')-' . $new_version . '.exe'
		));
	}
}