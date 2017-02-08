<?php
namespace Share;
use View,
	Config,
	InputExt,
	Ca\Common,
	Redirect,
	Response,
	Ca\Service\DocumentService,
	Ca\Service\KnowsService,
	Ca\Service\CurrentUserService;

class SearchController extends BaseController {

	public $layout = 'share.layouts.common';

	/**
	 * 查询页面
	 */
	public function anyIndex()
	{
		$extension = InputExt::get('ext');
		$name = InputExt::xss_clean(trim(InputExt::get('text_search')));
		if ($name == '')
		{
			return Redirect::to('/');
		}
		if ($extension != 'all' && !in_array($extension, Config::get('share.allow_extension')))
		{
			$extension = 'all';
		}
		$condition = array('name' =>  $name, 'extension' => $extension);
		$limit = Config::get('share.page_document_search');
		$documents = DocumentService::get_document_search($condition, $limit);

		$data = array(
			'title' => '查询"' . $name .'", 共有' . $documents->getTotal() . '条结果',
			'documents' => $documents
		);
		$hot_document = DocumentService::get_hot_document(); //文档排行
		$this->layout->title = '查找资源';
		$this->layout->condition = $condition;
		$this->layout->content = View::make('share.search.index')
			->with('condition', $condition)
			->with('hot_document', $hot_document)
			->with('data', $data);
		$documents->appends(array('text_search' => $name, 'ext' => $extension));
	}
}