<?php
namespace Manage;

use DB,
	View,
	Input,
	InputExt,
	Config,
	Response,
	Ca\Common,
	Ca\Data,
	Ca\Service\SensitiveService;

class SensitiveController extends BaseController {
	public $layout = 'manage/layouts/common';

	public function getIndex()
	{
		$this->layout->title = "敏感词管理";
		$this->layout->body = View::make('manage/sensitive/list');
	}

	public function postList()
	{
		$name = InputExt::get('name');
		$page = InputExt::getInt('page');

		$query = DB::table('sensitive')->orderBy('wordid', 'desc');

		$count_query = DB::table('sensitive')->select(array(DB::raw('COUNT(*) as count')));

		$ret = Data::queryList($query, $count_query, $page, array(
			array('type' => 'string', 'field' => 'word', 'value' => $name)
		), array());
		echo json_encode($ret);
	}

	public function postEntity()
	{
		$eid = InputExt::getInt('eid');
		Common::empty_check(array('word'));

		$word = Input::get('word');
		$word = DB::table('sensitive')->where('word', '=', $word)->first();
		if ($word != null)
		{
			exit;
		}
		$fields = array('word');
		Data::updateEntity('sensitive', array('wordid', '=', $eid), $fields);
	}

	public function postGet()
	{
		$eid = InputExt::getInt('eid');
		$entity = DB::table('sensitive')
			->where('wordid', '=', $eid)->first();
		echo json_encode($entity);
	}

	public function postDelete()
	{
		$eid = InputExt::getInt('eid');
		DB::table('sensitive')->where('wordid', '=', $eid)->delete();
	}

	public function anyImport()
	{
		set_time_limit(0);

		$file = trim(file_get_contents('php://input', 'r'));
		$file = mb_convert_encoding($file, 'utf8', 'gbk');
		$file = str_replace(array("\r\n", "\n"), "\r", $file);
		$data = explode("\r", $file);
		$wordNames = array_map(function($str) {
			$word = explode("=", $str);
			return trim($word[0]);
		}, $data);

		$newIds = SensitiveService::add($wordNames);
		$newcount = count($newIds);
		$existcount = count($wordNames) - $newcount;
		return Response::json(array('newcount' => $newcount, 'existcount' => $existcount));
	}

}

