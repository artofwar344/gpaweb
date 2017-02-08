<?php
namespace Activate;
use Config,
	View,
	Ca\Service\CurrentUserService,
	Ca\Service\KeyUsageService;
class UsageController extends BaseController {

	public $layout = 'activate.layouts.common';

	public function index()
	{
		$limit = Config::get('activate.page_key');
		$userid = CurrentUserService::$user_id;
		$keys = KeyUsageService::get_usage_keys_by_userid($userid, $limit);
		$this->layout->title = '激活记录';
		$this->layout->nav = '激活记录';
		$this->layout->content = View::make('activate.usage.index')
			->with('keys', $keys);
	}


}