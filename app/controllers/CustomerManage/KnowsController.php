<?php
namespace CustomerManage;

use DB,
	View,
	Input,
	Response,
	InputExt,
	Ca\Data,
	Ca\Consts;

class KnowsController extends BaseController {
	public $layout = 'customermanage/layouts/common';

	public function getIndex()
	{
		$this->layout->title = "问答列表";
		$this->layout->body = View::make('customermanage/knows/list');
	}

	public function postList()
	{
		$title = InputExt::get('title');
		$categoryid = InputExt::getInt('categoryid');
		$page = InputExt::getInt('page');

		$query = DB::table('question')
			->select(array('question.questionid', 'question.title', 'question.views', 'question.status', 'question.createdate',
				DB::raw('CONCAT(user.name, " - [", user.username, "]") as user_name'),
				DB::raw('COUNT(answer.answerid) as answer_count'),
				DB::raw('IF(COUNT(accept.answerid) > 0, "是", "否" )  as accepted'),
				DB::raw('CASE WHEN parent.name IS NOT NULL THEN CONCAT(parent.name, " > ", questioncategory.name) ELSE questioncategory.name END as category_name')))
			->leftJoin('user', 'user.userid', '=', 'question.userid')
			->leftJoin('answer', 'question.questionid', '=', 'answer.questionid')
			->leftJoin('answer as accept', function($join) {
				$join->on('question.questionid', '=', 'accept.questionid');
				$join->on('accept.status', '=', DB::raw(\Ca\AnswerStatus::best));
			})
			->leftJoin('questioncategory', 'question.categoryid', '=', 'questioncategory.categoryid')
			->leftJoin('questioncategory as parent', 'parent.categoryid', '=', 'questioncategory.parentid')
			->groupBy('question.questionid')
			->orderBy('question.questionid', 'desc');

		$count_query = DB::table('question')->select(array(DB::raw('COUNT(*) as count')));

		$ret = Data::queryList($query, $count_query, $page, array(
			array('type' => 'int', 'field' => 'question.categoryid', 'value' => $categoryid),
			array('type' => 'string', 'field' => 'title', 'value' => $title),
		), array('status' => array(Consts::$question_status_texts)));

		echo json_encode($ret);
	}

	public function postEntity()
	{
		$eid = InputExt::getInt("eid");
		Data::updateEntity('knows', array('knowsid', '=', $eid), array('name', 'publish'));
	}

	public function postGet()
	{
		$eid = InputExt::getInt("eid");

		$entity = DB::table('question')
			->select(array('title'))
			->where('questionid', '=', $eid)->first();

		return Response::json($entity);
	}

	public function postDelete()
	{
		$eid = InputExt::getInt("eid");

		DB::table('question')
			->where('questionid', '=', $eid)
			->delete();
		DB::table('answer')
			->where('questionid', '=', $eid)
			->delete();
	}

	public function postSelects()
	{
		$select_1 = DB::table('questioncategory')
			->select(array('categoryid', 'name'))
			//->where('questioncategory.parentid', 'IS', DB::raw('NULL'))
			->orderBy("categoryid", "desc")
			->get();
		echo json_encode(array($select_1));
	}

	public function postStatus()
	{
		$eid = InputExt::getInt('eid');
		DB::table('question')->where('questionid', '=', $eid)->update(array('status' => DB::raw('abs(status - 2) + 1')));
	}
}