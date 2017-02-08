<?php
namespace Activate;
use Config,
	View,
	Ca\Service\CurrentUserService,
	Ca\Service\UserKeyService;


class RequestController extends BaseController {

	public $layout = 'activate.layouts.common';

	public function index()
	{
		$limit = Config::get('activate.page_request');
		$userid = CurrentUserService::$user_id;
		$requests = UserKeyService::get_request_by_userid($userid, $limit);
		$this->layout->title = '申请记录';
		$this->layout->nav = '申请记录';
		$this->layout->content = View::make('activate.request.index')
			->with('requests', $requests);
	}


}