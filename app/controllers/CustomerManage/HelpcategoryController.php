<?php
/**
 * Created by JetBrains PhpStorm.
 * User: Administrator
 * Date: 14-7-30
 * Time: 上午10:53
 * To change this template use File | Settings | File Templates.
 */

namespace CustomerManage;

use DB,
	View,
	InputExt,
	Input,
	Response,
	Ca\Common,
	Ca\Data;

class HelpcategoryController extends BaseController {

	public $layout = 'customermanage/layouts/common';

	public function getIndex()
	{
		$this->layout->title = "帮助中心分类";
		$this->layout->body = View::make('customermanage/help/category');
	}

	public function postList()
	{
		$name = InputExt::get('name');
		$page = InputExt::getInt('page');

		$query = DB::table('helpcategory')
			->select(array(DB::raw('COUNT(help.helpid) as count'), 'helpcategory.categoryid', 'helpcategory.name'))
			->orderBy('categoryid', 'desc')
			->groupBy('helpcategory.categoryid')
			->leftJoin('help', 'helpcategory.categoryid', '=', 'help.categoryid');

		$count_query = DB::table('helpcategory')->select(array(DB::raw('COUNT(*) as count')));

		$ret = Data::queryList($query, $count_query, $page, array(
			array('type' => 'string', 'field' => 'helpcategory.name', 'value' => $name)
		), null, null, array(array('count', '==', '0')));

		echo json_encode($ret);
	}

	public function postEntity()
	{
		$eid = InputExt::getInt('eid');
		Common::empty_check(array('name'));
		Data::updateEntity('helpcategory', array('categoryid', '=', $eid), array('name'));
	}

	public function postGet()
	{
		$eid = InputExt::getInt("eid");
		$entity = DB::table('helpcategory')
			->select(array('name'))
			->where('categoryid', '=', $eid)->first();
		echo json_encode($entity);
	}

	public function postDelete()
	{
		$eid = InputExt::getInt("eid");
		DB::table('helpcategory')->where('categoryid', '=', $eid)->delete();
	}
}