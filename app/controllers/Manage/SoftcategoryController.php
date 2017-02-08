<?php
namespace Manage;

use \DB,
	\Auth,
	\View,
	\Input,
	\Config,
	\Hash,
	Ca\Common,
	Ca\Data,
	Ca\Consts,
	\InputExt;

class SoftcategoryController extends BaseController {
	public $layout = 'manage/layouts/common';

	public function getIndex()
	{
		$this->layout->title = "软件分类";
		$this->layout->body = View::make('manage/soft/category');
	}

	public function postList()
	{
		$page = InputExt::getInt('page');
		$parentid = InputExt::getInt('parentid');

		$parent_category = Consts::$soft_top_categories;
		$parent_table = '(';
		$index = 0;
		$count_parent_category = count($parent_category);
		foreach ($parent_category as $id => $name)
		{
			$index++;
			$parent_table .= 'SELECT ' . $id . ' AS parentid';
			if ($index != $count_parent_category)
			{
				$parent_table .= ' UNION ALL ';
			}
		}
		$parent_table .= ') parent';

		$query = DB::table(DB::raw($parent_table))
			->select(array(DB::raw('COUNT(soft.softid) AS count'), 'parent.parentid',
				DB::raw('IFNULL(GROUP_CONCAT(DISTINCT(softcategory.`name`) separator ", "), "") AS children_name')))
			->leftJoin('softcategory', 'softcategory.parentid', '=', 'parent.parentid')
			->leftJoin('soft', 'soft.categoryid', '=', 'softcategory.categoryid')
			->orderBy('parent.parentid')
			->groupBy('parent.parentid');

		$count_query = DB::table('softcategory')->select(array(DB::raw(count($parent_category) . ' as count')));

		$ret = Data::queryList($query, $count_query, $page, array(
			array('type' => 'int', 'field' => 'parent.parentid', 'value' => $parentid)
		), array('parentid' => array(Consts::$soft_top_categories)),
			null, array(array('count', '==', '0')));
		echo json_encode($ret);
	}

	public function postEntity()
	{
		$eid = InputExt::getInt('eid');
		Common::empty_check(array('parentid', 'name'));
		Data::updateEntity('softcategory', array('categoryid', '=', $eid), array('parentid', 'name'));
	}

	public function postGet()
	{
		$eid = InputExt::getInt("eid");
		$entity = DB::table('softcategory')
			->select(array('parentid', 'name'))
			->where('categoryid', '=', $eid)->first();
		echo json_encode($entity);
	}

//	public function postDelete()
//	{
//		$eid = InputExt::getInt("eid");
//		DB::table('softcategory')->where('categoryid', '=', $eid)->delete();
//	}
}
