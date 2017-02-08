<?php

class Wsus_Controller extends Base_Controller {
	public $layout = 'manage/layouts/common';

	public function action_index()
	{
		$this->layout->title = "WSUS产品管理";
		$this->layout->body = View::make('wsus/list');
	}

	public function action_list()
	{
		$page = InputExt::getInt('page');
		$titleen = InputExt::get('titleen');
		$filename = InputExt::get('filename');
		$productid = InputExt::getInt('productid');
		$status = InputExt::getInt('status');

		$query = DB::table('wsus')
			->select(array('wsus.wsusid', 'wsus.titleen', 'wsus.filename', 'wsus.knowledgebasearticles', 'wsus.status',
				'wsus.creationdate'))
			->left_join('wsus__wsusproduct', 'wsus.wsusid', '=', 'wsus__wsusproduct.wsusid')
			->order_by('wsusid', 'desc');

		$count_query = DB::table('wsus')
			->select(array(DB::raw('COUNT(*) as count')))
			->left_join('wsus__wsusproduct', 'wsus.wsusid', '=', 'wsus__wsusproduct.wsusid');

		$ret = Data::queryList($query, $count_query, $page, array(
			array('type' => 'string', 'field' => 'titleen', 'value' => $titleen),
			array('type' => 'string', 'field' => 'filename', 'value' => $filename),
			array('type' => 'int', 'field' => 'wsus__wsusproduct.productid', 'value' => $productid),
			array('type' => 'int', 'field' => 'status', 'value' => $status)
		), array('status' => array(Consts::$wsus_status_texts)));

		echo json_encode($ret);
	}

	public function action_entity()
	{
		$eid = InputExt::getInt('eid');
		Data::updateEntity('wsus', array('wsusid', '=', $eid), array('filename', 'status'));
	}

	public function action_get()
	{
		$eid = InputExt::getInt('eid');
		$entity = DB::table('wsus')
			->select(array('filename', 'status'))
			->where('wsusid', '=', $eid)->first();
		echo json_encode($entity);
	}

	public function action_delete()
	{
		$eid = InputExt::getInt('eid');
		DB::table('wsus')->where('wsusid', '=', $eid)->delete();
	}

	public function action_selects()
	{
		$select = DB::table('wsusproduct')
			->select(array('productid', 'name'))
			->order_by("productid", "desc")->get();
		echo json_encode(array($select));
	}
}

