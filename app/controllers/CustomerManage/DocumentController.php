<?php
namespace CustomerManage;

use DB,
	View,
	Input,
	Response,
	InputExt,
	Ca\Data,
	Ca\Consts,
	Ca\DocumentSource,
	Ca\DocumentType,
	Ca\DocumentPublish,
	Ca\Service\DocumentService;

class DocumentController extends BaseController {
	public $layout = 'customermanage/layouts/common';

	public function getIndex()
	{
		$this->layout->title = "文档管理";
		$this->layout->body = View::make('customermanage/document/list');
	}

	public function postList()
	{
		$name = InputExt::get('name');
		$status = InputExt::getInt('status');
		$publish = InputExt::getInt('publish');
		$category_id = InputExt::getInt('categoryid');
		$page = InputExt::getInt('page');
		$type = InputExt::getInt('type');


		$query = DB::table('document')
			->select(array('document.documentid',
				DB::raw('CONCAT(parent.name, " > ", category.name) as category_name'),
				'document.name', 'document.extension', 'document.views', 'document.publish',
				'document.status', 'document.createdate',
				DB::raw('CONCAT(user.name, " - [", user.username, "]") as user_name'),
				DB::raw('GROUP_CONCAT(documenttype.type) as type')))
			->leftJoin('user', 'user.userid', '=', 'document.userid')
			->leftJoin('document__category', 'document__category.documentid', '=', 'document.documentid')
			->leftJoin('category', 'document__category.categoryid', '=', 'category.categoryid')
			->leftJoin('category as parent', 'parent.categoryid', '=', 'category.parentid')
			->leftJoin('documenttype', 'documenttype.documentid', '=', 'document.documentid')
			->groupBy('document.documentid')
			->orderBy('document.documentid', 'desc');

		$count_query = DB::table('document')
			->select(array(DB::raw('COUNT(distinct document.documentid) as count')))
			->leftJoin('document__category', 'document__category.documentid', '=', 'document.documentid')
			->leftJoin('documenttype', 'documenttype.documentid', '=', 'document.documentid');

		$ret = Data::queryList($query, $count_query, $page, array(
			array('type' => 'int', 'field' => 'document.from', 'value' => DocumentSource::upload),
			array('type' => 'int', 'field' => 'document.type', 'value' => DocumentType::file),
			array('type' => 'int', 'field' => 'document__category.categoryid', 'value' => $category_id),
			array('type' => 'int', 'field' => 'documenttype.type', 'value' => $type),
			array('type' => 'int', 'field' => 'document.status', 'value' => $status),
			array('type' => 'int', 'field' => 'publish', 'value' => $publish),
			array('type' => 'string', 'field' => 'document.name', 'value' => $name),
		), array('publish' => array(Consts::$document_publish_texts), 'type' => array(Consts::$document_type_texts), 'status' => array(Consts::$document_status_texts)));

		echo json_encode($ret);
	}

	public function postEntity()
	{
		$eid = InputExt::getInt("eid");
		Data::updateEntity('document', array('documentid', '=', $eid), array('name', 'publish'));
		DocumentService::set_document_type($eid, Input::get('type'));

	}

	public function postGet()
	{
		$eid = InputExt::getInt("eid");

		$entity = DB::table('document')
			->select(array('document.name', 'document.publish',  DB::raw('GROUP_CONCAT(documenttype.type) as type')))
			->leftJoin('documenttype', 'documenttype.documentid', '=', 'document.documentid')
			->where('document.documentid', '=', $eid)->first();

		return Response::json($entity);
	}

	public function postDelete()
	{
		$eid = InputExt::getInt("eid");

		DB::table('document')
			->where('from_documentid', '=', $eid)
			->delete();

		DB::table('document')
			->where('documentid', '=', $eid)
			->delete();

	}

	public function postSelects()
	{
		$select_1 = DB::table('category')
			->select(array('category.categoryid', DB::raw('concat(parent.name , " > ", category.name) as name')))
			->leftJoin('category as parent', 'parent.categoryid', '=', 'category.parentid')
			->whereNotNull('category.parentid')
			->orderBy("categoryid", "desc")
			->get();

		$select_2 = array();
		foreach (Consts::$document_type_texts as $key => $type)
		{
			$select_2[] = array(
				'type' => $key,
				'name' => $type
			);
		}
		echo json_encode(array($select_1, $select_2));
	}


	public function postPass()
	{
		$eid = InputExt::getInt("eid");
		DB::table('document')->where('documentid', '=', $eid)->update(array('publish'=> DocumentPublish::submit_d));
		return Response::json(array('status' => 1));
	}

	public function postReject()
	{
		$eid = InputExt::getInt("eid");
		DB::table('document')->where('documentid', '=', $eid)->update(array('publish'=> DocumentPublish::private_d));
		return Response::json(array('status' => 1));
	}

//	private function changePublishMulti($eids, $publish)
//	{
//		if (is_array($eids) && !empty($eids))
//		{
//			$query = DB::table('document')
//				->whereIn('documentid', $eids)
//				->where('publish', '=', DocumentPublish::public_d)
//				->update(array('publish' => $publish));
//		}
//	}

	public function postPassmulti()
	{
		$eids = InputExt::get('eids');
		if (is_array($eids) && !empty($eids))
		{
			$query = DB::table('document')
				->whereIn('documentid', $eids)
				->where('publish', '=', DocumentPublish::public_d)
				->update(array('publish' => DocumentPublish::submit_d));
		}
		return Response::json(array('code' => 1));
	}

	public function postRejectmulti()
	{
		$eids = InputExt::get('eids');
		if (is_array($eids) && !empty($eids))
		{
			$query = DB::table('document')
				->whereIn('documentid', $eids)
				->where('publish', '=', DocumentPublish::public_d)
				->update(array('publish' => DocumentPublish::private_d));
		}
		return Response::json(array('code' => 1));
	}


}