<?php
namespace CustomerManage;

use DB,
	View,
	Response,
	InputExt,
	Ca\Data,
	Ca\Service\KnowsService;

class AnswerController extends BaseController {
	public $layout = 'customermanage/layouts/common';

	public function getIndex()
	{
		$questionid = InputExt::getInt('id');
		$question = KnowsService::get_question($questionid);
		$this->layout->title = "回答管理";
		$this->layout->body = View::make('customermanage/knows/answer')->with('question', $question)->with('questionid', $questionid);
	}

	public function postList()
	{

		$id = InputExt::getInt('id');
		$content = InputExt::get('content');
		$page = InputExt::getInt('page');

		$query = DB::table('answer')
			->select(array('answer.answerid', 'question.title as question_title', 'answer.content', 'answer.createdate', DB::raw('CONCAT(user.name, "- [", username , "]") as user_name')))
			->leftJoin('user', 'user.userid', '=', 'answer.userid')
			->leftJoin('question', 'question.questionid', '=', 'answer.questionid')
			->orderBy('answer.answerid', 'desc');

		$count_query = DB::table('answer')->select(array(DB::raw('COUNT(*) as count')));

		$ret = Data::queryList($query, $count_query, $page, array(
			array('type' => 'string', 'field' => 'title', 'value' => $content),
			array('type' => 'int', 'field' => 'answer.questionid', 'value' => $id),
		), array('content' => '\Ca\Common::ubb'));

		echo json_encode($ret);
	}

	public function postGet()
	{
		$eid = InputExt::getInt("eid");
		$entity = DB::table('answer')->where('answerid', '=', $eid)->first();
		return Response::json($entity);
	}

	public function postDelete()
	{
		$eid = InputExt::getInt("eid");
		DB::table('answer')
			->where('answerid', '=', $eid)
			->delete();
	}

}