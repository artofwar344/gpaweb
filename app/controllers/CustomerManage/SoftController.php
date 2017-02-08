<?php
namespace CustomerManage;

use DB,
	View,
	Input,
	Config,
	InputExt,
	Ca\Consts,
	Ca\Common,
	Ca\Data,
	Ca\Service\SoftService;

class SoftController extends BaseController {
	public $layout = 'customermanage/layouts/common';

	public function getIndex()
	{
		$this->layout->title = "软件管理";
		$this->layout->body = View::make('customermanage/soft/list');
	}

	public function postList()
	{
		$name = InputExt::get('name');
		$category_id = InputExt::getInt('categoryid');
		$page = InputExt::getInt('page');
		$type = InputExt::getInt('type');

		$query = DB::table('soft')
			->select(array('soft.softid', 'soft.name', 'soft.productcode', 'softcategory.name as category_name',
				'language', 'licensetype', 'platform', 'bit', 'soft.brief',
				'soft.description', 'soft.version', 'soft.feature', 'soft.fileurl', 'soft.filesize', 'soft.order', 'soft.status', 'soft.createdate', 'soft.updatedate', DB::raw('GROUP_CONCAT(softtype.type) as type')))
			->orderBy('softid', 'desc')
			->leftJoin('softcategory', 'softcategory.categoryid', '=', 'soft.categoryid')
			->leftJoin('softtype', 'softtype.softid', '=', 'soft.softid')
			->groupBy('softid');

		$count_query = DB::table('soft')
			->select(array(DB::raw('COUNT(distinct soft.softid) as count')))
			->leftJoin('softtype', 'softtype.softid', '=', 'soft.softid');

		$ret = Data::queryList($query, $count_query, $page, array(
			array('type' => 'string', 'field' => 'soft.name', 'value' => $name),
			array('type' => 'int', 'field' => 'soft.categoryid', 'value' => $category_id),
			array('type' => 'int', 'field' => 'softtype.type', 'value' => $type)
		), array('status' => array(Consts::$soft_status_texts),
			'softid' => function($value) {
				return '<img style="width:16px;height:16px;" src="' . Config::get('app.asset_url') . 'images/softicon/' . $value . '.png" />';
			},
			'filesize' => '\Ca\Common::format_filesize',
			'language' => array(Consts::$soft_language_texts),
			'bit' => array(Consts::$soft_bits),
			'licensetype' => array(Consts::$soft_licensetype_texts),
			'type' => array(Consts::$soft_type_texts, 'array')));

		echo json_encode($ret);
	}

	public function postEntity()
	{
		$eid = InputExt::getInt('eid');
		Common::empty_check(array('status'));
		$eid = Data::updateEntity('soft', array('softid', '=', $eid), array('status'), null, null, $eid);
		SoftService::setSoftType($eid, Input::get('type'));
	}

	public function postGet()
	{
		$eid = InputExt::getInt('eid');
		$entity = DB::table('soft')->select(array('soft.softid', 'soft.name', 'soft.productcode', 'soft.categoryid',
			'language', 'licensetype', 'platform', 'soft.brief',
			'soft.description', 'soft.version', 'soft.feature', 'soft.fileurl', 'soft.filesize', 'soft.order', 'soft.status', 'soft.createdate', 'soft.updatedate', DB::raw('GROUP_CONCAT(softtype.type) as type')))
			->leftJoin('softtype', 'softtype.softid', '=', 'soft.softid')
			->where('soft.softid', '=', $eid)->groupBy('softid')->first();
		echo json_encode($entity);
	}

	public function postDelete()
	{
		$eid = InputExt::getInt('eid');
		DB::table('soft')->where('softid', '=', $eid)->delete();
	}

	public function postSelects()
	{
		$select_1 = DB::table('softcategory')
			->select(array('categoryid', 'name'))
			->orderBy("categoryid", "desc")
			->get();

		$select_2 = array();
		foreach (Consts::$soft_type_texts as $key => $type)
		{
			$select_2[] = array(
				'type' => $key,
				'name' => $type
			);
		}
		echo json_encode(array($select_1, $select_2));
	}
}

