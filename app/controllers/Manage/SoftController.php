<?php
namespace Manage;

use \DB,
	\View,
	\Input,
	\Config,
	Ca\Consts,
	Ca\Data,
	Ca\Service,
	Ca\Common,
	\InputExt;

class SoftController extends BaseController {
	public $layout = 'manage/layouts/common';

	public function getIndex()
	{
		$categoryid_top = InputExt::getInt('id');
		$title = '软件管理';
		if ($categoryid_top > 0)
		{
			$title = '分类: ' . Consts::$soft_top_categories[$categoryid_top];
		}
		$this->layout->title = "软件管理";
		$this->layout->body = View::make('manage/soft/list')->with('parentid', $categoryid_top)->with('title', $title);
	}

	public function postList()
	{
		$name = InputExt::get('name');
		$category_id = InputExt::getInt('categoryid');
		$parent_id = InputExt::getInt('view_parentid');
		$page = InputExt::getInt('page');
		$type = InputExt::getInt('type');

		$query = DB::table('soft')
			->select(array('soft.softid', 'soft.name', 'soft.productcode', 'softcategory.name as category_name',
				'language', 'licensetype', 'platform', 'bit', 'soft.brief',
				'soft.description', 'soft.version', 'soft.feature', 'soft.fileurl', 'soft.filesize', 'soft.order',
				'soft.status', 'soft.createdate', 'soft.updatedate', DB::raw('GROUP_CONCAT(softtype.type) as type')))
			->orderBy('softid', 'desc')
			->leftJoin('softcategory', 'softcategory.categoryid', '=', 'soft.categoryid')
			->leftJoin('softtype', 'softtype.softid', '=', 'soft.softid')
			->groupBy('softid');


		$count_query = DB::table('soft')
			->select(array(DB::raw('COUNT(distinct soft.softid) as count')));

		if ($parent_id > 0)
		{
			$query->where('softcategory.parentid', '=', $parent_id);
			$count_query->leftJoin('softcategory', 'softcategory.categoryid', '=', 'soft.categoryid')
				->where('softcategory.parentid', '=', $parent_id);
		}

		$ret = Data::queryList($query, $count_query, $page, array(
			array('type' => 'string', 'field' => 'soft.name', 'value' => $name),
			array('type' => 'int', 'field' => 'soft.categoryid', 'value' => $category_id),
			array('type' => 'int', 'field' => 'softtype.type', 'value' => $type)
		), array('status' => array(Consts::$soft_status_texts),
			'softid' => function($value) {
					return '<img style="width:16px;height:16px" src="' . Config::get('app.asset_url') . 'images/softicon/' . $value . '.png" />';
				},
			'filesize' => 'Ca\Common::format_filesize',
			'language' => array(Consts::$soft_language_texts),
			'bit' => array(Consts::$soft_bits),
			'licensetype' => array(Consts::$soft_licensetype_texts),
			'type' => array(Consts::$soft_type_texts, 'array')));

		echo json_encode($ret);
	}

	public function postEntity()
	{
		$eid = InputExt::getInt('eid');
//		echo 2222222;
//		echo $eid;
		Common::empty_check(array('name', 'language', 'licensetype', 'platform', 'categoryid', 'brief', 'status', 'bit'));
		$eid = Data::updateEntity('soft', array('softid', '=', $eid), array('name', 'productcode', 'language', 'licensetype', 'platform', 'categoryid', 'brief', 'description', 'status', 'bit'), null, null, $eid);
		if (!empty($_POST['type']))
		{
			Service\SoftService::setSoftType($eid, $_POST['type']);
		}
		if (isset($_POST["icon_data"]) && $eid)
		{
			$icon_binary_data = explode("base64,", $_POST["icon_data"], 2);
			$icon_binary_data = base64_decode($icon_binary_data[1]);
			file_put_contents(base_path() . "/content/images/softicon/" . $eid . ".png", $icon_binary_data);
		}
	}

	public function postGet()
	{
		$eid = InputExt::getInt('eid');
		$entity = DB::table('soft')->select(array('soft.softid', 'soft.name', 'soft.productcode', 'soft.categoryid',
			'language', 'licensetype', 'platform', 'soft.brief', 'soft.bit',
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
		$parent_id = InputExt::getInt('view_parentid');
		if ($parent_id > 0)
		{
			$select_1 = DB::table('softcategory')
				->select(array('categoryid', 'name'))
				->where('parentid', '=', $parent_id)
				->orderBy("categoryid", "desc")
				->get();
		}
		else
		{
			$select_1 = DB::table('softcategory')
				->select(array('categoryid', 'name'))
				->orderBy("categoryid", "desc")
				->get();
		}


		$select_2 = array();
		foreach (Consts::$soft_type_texts as $key => $type)
		{
			$select_2[] = array(
				'type' => $key,
				'name' => $type
			);
		}

		$select_3 = array();
		foreach (Consts::$soft_bits as $key => $type)
		{
			$select_3[] = array(
				'type' => $key,
				'name' => $type
			);
		}


		echo json_encode(array($select_1, $select_2));
	}

	public function postStatus()
	{
		$eid = InputExt::getInt('eid');
		DB::table('soft')->where('softid', '=', $eid)->update(array('status' => DB::raw('abs(status - 2) + 1')));
	}
}

