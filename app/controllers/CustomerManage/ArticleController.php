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

class ArticleController extends BaseController {
	public $layout = 'customermanage/layouts/common';

	public function getIndex()
	{
		$this->layout->title = "文章管理";
		$this->layout->body = View::make('customermanage/article/list');
	}

	public function postList()
	{
		$name = InputExt::get('name');
		$category_id = InputExt::getInt('categoryid');
		$page = InputExt::getInt('page');
		$type = InputExt::getInt('type');

		$query = DB::table('article')
			->select(array('article.articleid', 'article.module', 'articlecategory.name as category_name', 'article.title', 'createdate',
				'updatedate', 'status', DB::raw('GROUP_CONCAT(articletype.type) as type')))
			->leftJoin('articlecategory', 'article.categoryid', '=', 'articlecategory.categoryid')
			->leftJoin('articletype', 'articletype.articleid', '=', 'article.articleid')
			->groupBy('article.articleid')
			->orderBy('articleid', 'desc');

		$count_query = DB::table('article')->select(array(DB::raw('COUNT(distinct article.articleid) as count')))
			->leftJoin('articletype', 'articletype.articleid', '=', 'article.articleid');

		$ret = Data::queryList($query, $count_query, $page, array(
			array('type' => 'string', 'field' => 'article.title', 'value' => $name),
			array('type' => 'string', 'field' => 'article.categoryid', 'value' => $category_id),
			array('type' => 'int', 'field' => 'articletype.type', 'value' => $type),
		), array('status' => array(Consts::$article_status_texts), 'module' => array(Consts::$module_texts),
			'type' => array(Consts::$article_type_texts, 'array')));

		echo json_encode($ret);
	}

	public function postEntity()
	{
		$eid = InputExt::getInt('eid');
		$manager_id = $this->manager->managerid;
		$modify = $eid > 0;
		$fields = array('module', 'title', 'categoryid', 'managerid', 'content', 'status');
		if ($modify)
		{
			$fields[] = 'updatedate';
			$_POST['updatedate'] = DB::raw('NOW()');
		}

		$_POST['managerid'] = $manager_id;
		Common::empty_check(array('module', 'title', 'categoryid', 'managerid', 'status'));
		$eid = Data::updateEntity('article', array('articleid', '=', $eid), $fields, null, null);

		ArticleService::set_article_type($eid, Input::get('type'));
	}

	public function postGet()
	{
		$eid = InputExt::getInt('eid');
		$entity = DB::table('article')
			->select(array('article.articleid', 'article.module', 'article.categoryid', 'article.title', 'article.content', 'createdate',
				'updatedate', 'status', DB::raw('GROUP_CONCAT(articletype.type) as type')))
			->where('article.articleid', '=', $eid)
			->leftJoin('articletype', 'articletype.articleid', '=', 'article.articleid')
			->groupBy('articleid')
			->first();
		echo json_encode($entity);
	}

	public function postDelete()
	{
		$eid = InputExt::getInt('eid');
		DB::table('article')->where('articleid', '=', $eid)->delete();
	}

	public function postSelects()
	{
		$select_1 = DB::table('articlecategory')
			->select(array('categoryid', 'name'))
			->orderBy("categoryid", "desc")
			->get();
		$select_2 = array();
		foreach (Consts::$article_type_texts as $key => $type)
		{
			$select_2[] = array(
				'type' => $key,
				'name' => $type
			);
		}
		echo json_encode(array($select_1, $select_2));
	}

	public function postStatus()
	{
		$eid = InputExt::getInt('eid');
		DB::table('article')->where('articleid', '=', $eid)->update(array('status' => DB::raw('abs(status - 2) + 1')));
	}
}

