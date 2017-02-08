<?php

class Article_Controller extends Base_Controller {
	public $layout = 'manage/layouts/common';

	public function action_index()
	{
		$this->layout->title = "文章管理";
		$this->layout->body = new View('article/list');
	}

	public function action_list()
	{
		$name = InputExt::get('name');
		$category_id = InputExt::getInt('categoryid');
		$page = InputExt::getInt('page');
		$type = InputExt::getInt('type');

		$query = DB::table('article')
			->select(array('article.articleid', 'articlecategory.name as category_name', 'article.title', 'createdate',
				'updatedate', 'status', DB::raw('GROUP_CONCAT(articletype.type) as type')))
			->left_join('articlecategory', 'article.categoryid', '=', 'articlecategory.categoryid')
			->left_join('articletype', 'articletype.articleid', '=', 'article.articleid')
			->group_by('article.articleid')
			->order_by('articleid', 'desc');

		$count_query = DB::table('article')->select(array(DB::raw('COUNT(distinct article.articleid) as count')))
			->left_join('articletype', 'articletype.articleid', '=', 'articletype.articleid');

		$ret = Data::queryList($query, $count_query, $page, array(
			array('type' => 'string', 'field' => 'article.name', 'value' => $name),
			array('type' => 'string', 'field' => 'article.categoryid', 'value' => $category_id),
			array('type' => 'int', 'field' => 'articletype.type', 'value' => $type),
		), array('status' => array(Consts::$article_status_texts),
			'type' => array(Consts::$article_type_texts, 'array')));

		echo json_encode($ret);
	}

	public function action_entity()
	{
		$eid = InputExt::getInt('eid');
		$manager_id = $this->manager->id;
		$modify = $eid > 0;
		$fields = array('title', 'categoryid', 'managerid', 'content', 'status');
		if ($modify)
		{
			$fields[] = 'updatedate';
			$_POST['updatedate'] = DB::raw('NOW()');
		}

		$_POST['managerid'] = $manager_id;
		Common::empty_check(array('title', 'categoryid', 'managerid', 'status'));
		Data::updateEntity('article', array('articleid', '=', $eid), $fields, null, null);
		if (!empty($_POST['type']))
		{
			ArticleService::set_article_type($eid, $_POST['type']);
		}
	}

	public function action_get()
	{
		$eid = InputExt::getInt('eid');
		$entity = DB::table('article')
			->select(array('article.articleid', 'article.categoryid', 'article.title', 'article.content', 'createdate',
				'updatedate', 'status', DB::raw('GROUP_CONCAT(articletype.type) as type')))
			->where('article.articleid', '=', $eid)
			->left_join('articletype', 'articletype.articleid', '=', 'articletype.articleid')
			->group_by('articleid')
			->first();
		echo json_encode($entity);
	}

	public function action_delete()
	{
		$eid = InputExt::getInt('eid');
		DB::table('article')->where('articleid', '=', $eid)->delete();
	}

	public function action_selects()
	{
		$select_1 = DB::table('articlecategory')
			->select(array('categoryid', 'name'))
			->order_by("categoryid", "desc")
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
}

