<?php
namespace CustomerManage;

use DB,
	View,
	Input,
	Response,
	InputExt,
	Ca\Data;

class TagController extends BaseController {
	public $layout = 'customermanage/layouts/common';

	public function getIndex()
	{
		$this->layout->title = "标签管理";
		$this->layout->body = View::make('customermanage/tag/list');
	}

	public function postList()
	{
		$title = InputExt::get('title');
		$page = InputExt::getInt('page');

		$document_tag_count = DB::table('document__tag')
			->select(array(DB::raw('COUNT(document__tag.tagid) as count'), 'tagid'))
			->groupBy('tagid')->toSql();
		$meeting_tag_count = DB::table('meeting__tag')
			->select(array(DB::raw('COUNT(meeting__tag.tagid) as count'), 'tagid'))
			->groupBy('tagid')->toSql();
		$question_tag_count = DB::table('question__tag')
			->select(array(DB::raw('COUNT(question__tag.tagid) as count'), 'tagid'))
			->groupBy('tagid')->toSql();

		$query = DB::table('tag')
			->select(array('tag.tagid', 'name', DB::raw('IFNULL(documenttagcount.count, 0) as document_tagcount'),
				DB::raw('IFNULL(meetingtagcount.count, 0) as meeting_tagcount'), DB::raw('IFNULL(questiontagcount.count, 0) as question_tagcount')
			))
			->leftJoin(DB::raw("({$document_tag_count}) AS documenttagcount"), 'documenttagcount.tagid', '=', 'tag.tagid')
			->leftJoin(DB::raw("({$meeting_tag_count}) AS meetingtagcount"), 'meetingtagcount.tagid', '=', 'tag.tagid')
			->leftJoin(DB::raw("({$question_tag_count}) AS questiontagcount"), 'questiontagcount.tagid', '=', 'tag.tagid')
			->orderBy('tag.tagid', 'desc')
			->groupBy('tag.tagid');
		$count_query = DB::table('tag')->select(array(DB::raw('COUNT(*) as count')));
		$ret = Data::queryList($query, $count_query, $page, array(
			array('type' => 'string', 'field' => 'name', 'value' => $title),
		));
		echo json_encode($ret);
	}

	public function postEntity()
	{
		$eid = InputExt::getInt("eid");
		Data::updateEntity('tag', array('tagid', '=', $eid), array('name'));
	}

	public function postGet()
	{
		$eid = InputExt::getInt("eid");
		$entity = DB::table('tag')
			->select(array('name'))
			->where('tagid', '=', $eid)->first();

		return Response::json($entity);
	}

	public function postDelete()
	{
		$eid = InputExt::getInt("eid");
		DB::table('tag')
			->where('tagid', '=', $eid)
			->delete();
	}

	public function postSelects()
	{

	}

}