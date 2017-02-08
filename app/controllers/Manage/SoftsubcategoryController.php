<?php
namespace Manage;

use \DB,
	\Auth,
	\View,
	\Input,
	\Config,
	\Hash,
	\Response,
	Ca\Common,
	Ca\Consts,
	Ca\Data,
	\InputExt;

class SoftsubcategoryController extends BaseController {
	public $layout = 'manage/layouts/common';

	public function getIndex()
	{
		$categoryid_top = InputExt::getInt('id');
		$title = '子类管理';
		if ($categoryid_top > 0)
		{
			$title = '分类: ' . Consts::$soft_top_categories[$categoryid_top];
		}
		$this->layout->title = "子类管理";
		$this->layout->body = View::make('manage/soft/subcategory')->with('parentid', $categoryid_top)->with('title', $title);
	}

	public function postList()
	{
		$parentid = InputExt::getInt('id');
		$name = InputExt::get('name');
		$page = InputExt::getInt('page');

		$query = DB::table('softcategory')
			->select(array(DB::raw('COUNT(soft.softid) as count'), 'softcategory.categoryid', 'softcategory.name'))
			->leftJoin('soft', 'softcategory.categoryid', '=', 'soft.categoryid')
			->orderBy('softcategory.categoryid', 'desc')
			->groupBy('softcategory.categoryid');

		$count_query = DB::table('softcategory')->select(array(DB::raw('COUNT(*) as count')));

		$ret = Data::queryList($query, $count_query, $page, array(
			array('type' => 'string', 'field' => 'softcategory.name', 'value' => $name),
			array('type' => 'int', 'field' => 'softcategory.parentid', 'value' => $parentid),
		), null, null, array(array('count', '==', '0')));

		echo json_encode($ret);
	}

	public function postGet()
	{
		$eid = InputExt::getInt("eid");
		$entity = DB::table('softcategory')->where('categoryid', '=', $eid)->first();
		return Response::json($entity);
	}

	public function postEntity()
	{
		$eid = InputExt::getInt('eid');
		$fields = array('name', 'parentid');
		Common::empty_check(array('name', 'parentid'));
		Data::updateEntity('softcategory', array('categoryid', '=', $eid), $fields);
	}

	public function postDelete()
	{
		$eid = InputExt::getInt("eid");
		DB::table('softcategory')
			->where('categoryid', '=', $eid)
			->delete();
	}



}

