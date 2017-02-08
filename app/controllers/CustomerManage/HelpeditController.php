<?php
namespace CustomerManage;

use DB,
	View,
	InputExt,
	Input,
	Response,
	Ca\Consts,
	Ca\Common,
	Ca\Data,
	Ca\Service\ArticleService;

class HelpeditController extends BaseController {
	public $layout = 'customermanage/layouts/common';

	public function getIndex()
	{
		$this->layout->title = "帮助中心管理";
		$this->layout->body = View::make('customermanage/help/list');
	}

	public function postList()
	{
		$name = InputExt::get('name');
		$category_id = InputExt::getInt('categoryid');
		$page = InputExt::getInt('page');
		$status = InputExt::getInt('status');

		$query = DB::table('help')
			->select(array('help.helpid', 'helpcategory.name as category_name', 'help.title', 'createdate',
				'updatedate', 'status'))
			->leftJoin('helpcategory', 'help.categoryid', '=', 'helpcategory.categoryid')
			->groupBy('help.helpid')
			->orderBy('helpid', 'desc');

		$count_query = DB::table('help')->select(array(DB::raw('COUNT(distinct help.helpid) as count')));

		$ret = Data::queryList($query, $count_query, $page, array(
			array('type' => 'string', 'field' => 'help.title', 'value' => $name),
			array('type' => 'int', 'field' => 'help.status', 'value' => $status),
			array('type' => 'string', 'field' => 'help.categoryid', 'value' => $category_id),
		));

		echo json_encode($ret);
	}

	public function postEntity()
	{
		$eid = InputExt::getInt('eid');
		$manager_id = $this->manager->managerid;
		$modify = $eid > 0;
		$fields = array('title', 'categoryid', 'managerid', 'content', 'status');
		if ($modify)
		{
			$fields[] = 'updatedate';
			$_POST['updatedate'] = DB::raw('NOW()');
		}

		$_POST['managerid'] = $manager_id;
		Common::empty_check(array('title', 'categoryid', 'managerid', 'status'));
		Data::updateEntity('help', array('helpid', '=', $eid), $fields, null, null);

	}

	public function postGet()
	{
		$eid = InputExt::getInt('eid');
		$entity = DB::table('help')
			->select(array('help.helpid', 'help.categoryid', 'help.title', 'help.content', 'createdate',
				'updatedate', 'status'))
			->where('help.helpid', '=', $eid)
			->groupBy('helpid')
			->first();
		echo json_encode($entity);
	}

	public function postDelete()
	{
		$eid = InputExt::getInt('eid');
		DB::table('help')->where('helpid', '=', $eid)->delete();
	}

	public function postSelects()
	{
		$select_1 = DB::table('helpcategory')
			->select(array('categoryid', 'name'))
			->orderBy("categoryid", "desc")
			->get();
		echo json_encode(array($select_1));
	}
	public function postStatus()
	{
		$eid = InputExt::getInt('eid');
		DB::table('help')->where('helpid', '=', $eid)->update(array('status' => DB::raw('abs(status - 2) + 1')));
	}
}

