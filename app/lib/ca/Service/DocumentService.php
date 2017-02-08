<?php
namespace Ca\Service;
use Config,
	Ca\DocumentStatus,
	Ca\DocumentType,
	Ca\DocumentPublish,
	Ca\DocumentSource,
	Ca\DocumentShowType,
	Ca\SplitChineseWords,
	Illuminate\Support\Facades\DB;

class DocumentService {
	public static function get_parent_id($document_id)
	{
		return DB::table('document')
			->where('documentid', '=', $document_id)
			->pluck('parentid');
	}

	public static function get_type($document_id)
	{
		return DB::table('document')
			->where('documentid', '=', $document_id)
			->pluck('type');
	}

	public static function exists($parent_id, $folder_name)
	{
		$query = empty($parent_id) ? DB::table('document')
			->whereNull('parentid') : DB::table('document')
			->where('parentid', '=', $parent_id);
		return $query->where('status', '=', DocumentStatus::normal)
			->where('name', '=', $folder_name)
			->count() > 0;
	}

	public static function delete($document_id, $user_id)
	{
		DB::table('document')
			->where('documentid', '=', $document_id)
			->where('userid', '=', $user_id)
			->update(array('status' => DocumentStatus::deleted));
	}

	public static function change_name($document_id, $user_id, $name)
	{
		DB::table('document')
			->where('documentid', '=', $document_id)
			->where('userid', '=', $user_id)
			->update(array('name' => $name));
	}

	public static function get_status($document_id)
	{
		return DB::table("document")->where('documentid', '=', $document_id)->pluck('status');
	}


	public static function document_list($parent_id, $user_id, $type = DocumentType::folder)
	{
		$query = empty($parent_id) ? DB::table('document')->whereNull('parentid') : DB::table('document')->where('parentid', '=', $parent_id);
		$folders =  $query->where('type', '=', DocumentType::folder)
			->where('userid', '=', $user_id)
			->where('status', '!=', DocumentStatus::deleted)
			->get(array('documentid', 'name'));

		$folder_ids = array_map(function($folder) { return $folder->documentid; }, $folders);
		if (empty($folder_ids))
		{
			return array();
		}

		$has_child_document_ids = DB::table('document')
			->distinct()
			->where('type', '=', $type)
			->where('userid', '=', $user_id)
			->where('status', '!=', DocumentStatus::deleted)
			->whereIn('parentid', $folder_ids)
			->get(array('parentid'));

		if (!$has_child_document_ids) $has_child_document_ids = array();
		else $has_child_document_ids = array_map(function($document) { return $document->parentid; }, $has_child_document_ids);

		$folders = array_map(function ($folder) use($has_child_document_ids) {
			$folder->has_child = in_array($folder->documentid, $has_child_document_ids);
			return $folder;
		}, $folders);

		return $folders;
	}

	public static function move($document_ids, $parent_id, $user_id)
	{
		DB::table('document')
			->whereIn('documentid', $document_ids)
			->where('userid', '=', $user_id)
			->update(array('parentid' => empty($parent_id) ? DB::raw('NULL') : $parent_id));
	}

	public static function get($document_id, $user_id, $with_info)
	{
		$query = DB::table('document')
			->where('document.documentid', '=', $document_id)
			->where('userid', '=', $user_id);
		if ($with_info)
		{
			$query->leftJoin('documentinfo', 'document.documentid', '=', 'documentinfo.documentid');
		}

		return $query->first();
	}

	public static function get_categories()
	{
		$query = DB::table('category');
		return $query->get(array('parentid', 'name', 'categoryid'));
	}

	public static function category_list($parent_id)
	{
		$query = DB::table('category');
		empty($parent_id) ? $query->whereNull('parentid') : $query->where('parentid', '=', $parent_id);
		return $query->get(array('name', 'categoryid'));
	}

	public static function category_list_by_ids($parent_ids)
	{
		return DB::table('category')
			->whereIn('parentid', $parent_ids)
			->get(array('name', 'categoryid'));
	}

	public static function get_document_category($document_id)
	{
		$query = DB::table('document__category')->where('documentid', '=', $document_id);
		return $query->first();
	}

	/**
	 * @param $category_id
	 * @return array
	 * 查询文档分类及其父级分类
	 */
	public static function document_cateory_list($category_id)
	{
		$query = DB::table('category')->where('categoryid', '=', $category_id);
		$category = $query->first();
		$categorylist[] = $category;
		if ($category->parentid != null)
		{
			$categorylist = array_merge($categorylist, self::document_cateory_list($category->parentid));
		}
		return $categorylist;
	}


	public static function create($parent_id, $folder_name, $user_id, $ext, $type, $status, $publish = 0, $from = 0)
	{
		$query = DB::table('document');
		if ($ext == 'mp4')
		{
			$status = DocumentStatus::normal;
		}
		$data = array(
			'userid' => $user_id,
			'parentid' => $parent_id,
			'name' => $folder_name,
			'extension' => $ext,
			'type' => $type,
			'publish' => $publish,
			'from' => $from,
			'status' => $status
		);
		return $query->insertGetId($data);
	}

	public static function publish_document($document_id, $name, $intro, $category_id, $tag, $publish)
	{
		$query = DB::table('document')->where('documentid', '=', $document_id);
		$data = array('name' => $name, 'publish' => $publish);
		$query->update($data);

		$query = DB::table('document__category')->where('documentid', '=', $document_id);
		$document__category = $query->first();
		if ($document__category == null)
		{
			DB::table('document__category')->insert(array('documentid' => $document_id, 'categoryid' => $category_id));
		}
		else
		{
			$query ->update(array('categoryid' => $category_id));
		}

		$query = DB::table('documentinfo')->where('documentid', '=', $document_id);
		$documentinfo = $query->first();
		$data = array('documentid' => $document_id, 'intro' => $intro, 'filename' => $name);
		if ($documentinfo == null)
		{
			DB::table('documentinfo')->insert($data);
		}
		else
		{
			$query->update($data);
		}

		$tag = explode(',', $tag);
		DB::table('document__tag')->where('documentid', '=', $document_id)->delete();
		foreach ($tag as $value)
		{
			$value = trim($value);
			if ($value == '') continue;
			$tag_M = DB::table('tag')->where('name', '=', $value)->first();
			if ($tag_M == null)
			{
				$tagid = DB::table('tag')->insertGetId(array('name' => $value));
			}
			else
			{
				$tagid = $tag_M->tagid;
			}
			$data = array('documentid' => $document_id, 'tagid' => $tagid);
			DB::table('document__tag')->insert($data);
		}
	}

	public static function get_document($document_id)
	{
		return DB::table('document')
			->select(array('document.*', 'document.createdate as createdate',
				DB::raw('DATE_FORMAT(document.createdate, "%Y-%m-%d") as date'), 'user.name as uname',
				'category.name as category_name', 'category.categoryid', 'documentinfo.*',
				DB::raw('COUNT(document__download.userid) as count_download') ))
			->where('document.documentid', '=', $document_id)
			->leftJoin('user', 'user.userid', '=', 'document.userid')
			->leftJoin('document__download', 'document__download.documentid', '=', 'document.documentid')
			->leftJoin('documentinfo', 'document.documentid', '=', 'documentinfo.documentid')
			->leftJoin('document__category', 'document.documentid', '=', 'document__category.documentid')
			->leftJoin('category', 'category.categoryid', '=', 'document__category.categoryid')
			->first();
	}

	public static function check_favorite($user_id, $from_document_id)
	{
		$count = DB::table('document')
			->where('from_documentid', '=', $from_document_id)
			->where('userid', '=', $user_id)
			->where('status', '=', DocumentStatus::normal)
			->count();
		return $count > 0;
	}

	public static function add_favorite($user_id, $from_document_id)
	{
		$from_document = self::get_document($from_document_id);
		$data = array(
			'userid' => $user_id,
			'name' => $from_document->name,
			'extension' => $from_document->extension,
			'type' => DocumentType::file,
			'publish' => DocumentPublish::submit_d,
			'from' => DocumentSource::favorite,
			'from_documentid' => $from_document_id,
			'status' => DocumentStatus::normal
		);
		DB::table('document')->insert($data);
	}

	public static function get_tags($document_id)
	{
		$tags = DB::table('document__tag')
			->where('documentid', '=', $document_id)
			->join('tag', 'tag.tagid', '=', 'document__tag.tagid')
			->get();
		$tags_str = array();
		foreach ($tags as $value)
		{
			$tags_str[] = $value->name;
		}
		return $tags_str;
	}

	/**
	 * 获取收藏文档
	 * @param $user_id
	 * @return array
	 */
	public static function get_favorite($user_id)
	{
		return DB::table('document')
			->select(array('document.name', 'user.name as user_name', 'd.createdate', 'document.documentid', 'document.from_documentid'))
			->where('document.userid', '=', $user_id)
			->where('document.from', '=', DocumentSource::favorite)
			->where('document.status', '=', DocumentStatus::normal)
			->leftJoin('document as d', 'd.documentid', '=', 'document.from_documentid')
			->leftJoin('user', 'd.userid', '=', 'user.userid')
			->paginate(10);
	}

	/**
	 * 获取下载文档
	 * @param $user_id
	 * @return array
	 */
	public static function get_downloads($user_id)
	{
		return DB::table('document')
			->select(array('document.*','d.createdate as downloaddate', 'user.name as uname'))
			->join('document__download as d', 'd.documentid', '=', 'document.documentid')
			->join('user', 'user.userid', '=', 'document.userid')
			->where('d.userid', '=', $user_id)
			->where('document.status', '=', DocumentStatus::normal)
			->paginate(10);
	}

	public static function delete_download($document_id, $user_id)
	{
		DB::table('document__download')
			->where('documentid', '=', $document_id)
			->where('userid', '=', $user_id)
			->delete();
	}

	public static function check_download($document_id, $user_id)
	{
		$count = DB::table('document__download')
			->where('documentid', '=', $document_id)
			->where('userid', '=', $user_id)
			->count();
		return $count > 0;
	}

	public static function add_download($document_id, $user_id)
	{
		DB::table('document__download')
			->insert(array('documentid' => $document_id, 'userid' => $user_id));
	}


	/**
	 * 获取分类下的文档
	 * @param $category_ids
	 * @param null $limit 取出个数
	 * @param bool $paging 是否分页
	 * @param bool $subcate 为true时包含子分类的文档
	 * @return mixed
	 */
	public static function get_documents_by_category($category_ids, $limit = null, $paging = false, $subcate = false)
	{
		if (!is_array($category_ids))
		{
			$category_ids = array($category_ids);
		}
		if (count($category_ids) == 0)
		{
			return array();
		}
		if ($subcate)
		{
			$categorys = self::category_list_by_ids($category_ids);
			foreach ($categorys as $value)
			{
				$category_ids[] = $value->categoryid;
			}
		}

		$query = DB::table('document');
		$query ->select(array('document.*', 'user.name as uname', 'documentinfo.*',
			DB::raw('(SELECT COUNT(dd.userid) FROM document__download as dd where dd.documentid = document.documentid) as download_count')))
			->leftJoin('document__category as dc', 'dc.documentid', '=', 'document.documentid')
			->leftJoin('user', 'user.userid', '=', 'document.userid')
			->leftJoin('documentinfo', 'document.documentid', '=', 'documentinfo.documentid')
			->whereIn('dc.categoryid', $category_ids)
			->where('document.status', '=', DocumentStatus::normal)
			->where('document.type', '=', DocumentType::file)
			->where('document.from', '=', DocumentSource::upload)
			->where('publish', '=', DocumentPublish::submit_d)
			->orderBy('document.documentid', 'desc');
		if ($limit == null)
		{
			return $query->get();
		}
		if ($paging)
		{
			return $query->paginate($limit);
		}
		return $query->take($limit)->get();
	}

	public static function get_category_by_name($name)
	{
		$category = DB::table('category')
			->where('name', '=', $name)->first();
		if ($category != null)
		{
			return $category;
		}
		return null;
	}
	public static function get_category_by_parentid($parent_id)
	{
		if ($parent_id == null)
		{
			return DB::table('category')
				->whereNull('parentid')
				->get();
		}
		else
		{
			return DB::table('category')
				->where('parentid', '=', $parent_id)
				->get();
		}
	}

	public static function create_documentinfo($document_id, $filename, $size, $original_file)
	{
		$data = array(
			'documentid' => $document_id,
			'filename' => $filename,
			'size' => $size,
			'originalfile' => $original_file,
		);
		DB::table('documentinfo')->insert($data);
	}

	public static function get_category($category_id)
	{
		return DB::table('category')
			->where('categoryid', '=', $category_id)
			->first();
	}


	public static function get_parent_category($category_id)
	{
		$category = self::get_category($category_id);
		if ($category == null)
		{
			return null;
		}
		return DB::table('category')
			->where('categoryid', '=', $category->parentid)
			->first();
	}

	public static function get_related_document($document_id, $limit = 10)
	{
		$tags = DB::table('document__tag')
			->where('documentid', '=', $document_id)
			->leftJoin('tag', 'tag.tagid', '=', 'document__tag.tagid')
			->get();
		$tag_ids = array();
		foreach ($tags as $value)
		{
			$tag_ids[] = $value->tagid;
		}
		if (empty($tag_ids))
		{
			return array();
		}


		return DB::table('document')
			->select(array('document.*', 'documentinfo.*', DB::raw('DATE(document.createdate) as date'), 'user.name as uname',
				DB::raw('(SELECT COUNT(dd.userid) FROM document__download as dd where dd.documentid = document.documentid) as download_count')))
			->leftJoin('document__tag as dt', 'dt.documentid', '=', 'document.documentid')
			->leftJoin('documentinfo', 'documentinfo.documentid', '=', 'document.documentid')
			->leftJoin('user', 'user.userid', '=', 'document.userid')
			->where('document.documentid', '!=', $document_id)
			->whereIn('tagid', $tag_ids)
			->where('document.type', '=', DocumentType::file)
			->where('document.status', '=', DocumentStatus::normal)
			->where('document.from', '=', DocumentSource::upload)
			->where('publish', '=', DocumentPublish::submit_d)
			->groupBy('document.documentid')
			->orderBy('document.documentid','desc')
			->take($limit)
			->get();
	}

	public static function update_views_document($document_id)
	{
		DB::table('document')->where('documentid', '=', $document_id)->update(array('views' => DB::raw('views + 1')));
	}

	public static function thumbnail($document)
	{
		if ($document->extension == 'zip')
		{
			return Config::get('app.asset_url') . 'images/zip.jpg';
		}
		return Config::get('app.asset_url') . 'documents/' . $document->thumbnailfile;
	}

	public static function get_hot_document($limit = 10, $category_ids = null)
	{
		$query = DB::table('document')
			->leftJoin('documentinfo', 'documentinfo.documentid', '=', 'document.documentid')
			->where('document.status', '=', DocumentStatus::normal)
			->where('document.from', '=', DocumentSource::upload)
			->where('publish', '=', DocumentPublish::submit_d)
			->where('document.type', '=', DocumentType::file)
			->orderBy('views', 'desc');
		if ($category_ids != null)
		{
			$query->leftJoin('document__category as dc', 'dc.documentid', '=', 'document.documentid')
				->whereIn('dc.categoryid', $category_ids);
		}
		return	$query->take($limit)->get();
	}

	public static function get_document_search($condition, $limit)
	{
		$searchNames = SplitChineseWords::splitWords($condition['name']);
		$condition_count = count($searchNames);
		if ($condition_count == 0)
		{
			return null;
		}
		//用来按相似度高的排序
		$newtable_sql = '(SELECT documentid,';
		foreach ($searchNames as $index => $words)
		{
			$newtable_sql .= ' IF(`name` LIKE "%' . $words . '%", 1, 0) AS count' . $index;
			if ($index != $condition_count - 1)
			{
				$newtable_sql .= ',';
			}
		}
		$newtable_sql .= ' FROM document) newtable';

		$orders = '(';
		for ($i = 0; $i < $condition_count; $i++)
		{
			$orders .= 'count' . $i;
			if ($i < $condition_count - 1)
			{
				$orders .= '+';
			}
		}
		$orders .= ') AS orders';

		$query = DB::table('document')
			->select(array('document.*', 'documentinfo.*', 'user.name as uname',
				DB::raw('(SELECT COUNT(dd.userid) FROM document__download as dd where dd.documentid = document.documentid) as download_count'),
				DB::raw($orders)
			))
			->leftJoin('documentinfo', 'documentinfo.documentid', '=', 'document.documentid')
			->leftJoin('user', 'user.userid', '=', 'document.userid')
			->leftJoin(DB::raw($newtable_sql), 'newtable.documentid', '=', 'document.documentid')
			->where(function($where) use ($searchNames)
			{
				foreach ($searchNames as $words)
				{
					$where->orWhere('document.name', 'LIKE', '%' . $words . '%');
				}
			})
			->where('document.status', '=', DocumentStatus::normal)
			->where('document.from', '=', DocumentSource::upload)
			->where('publish', '=', DocumentPublish::submit_d)
			->where('document.type', '=', DocumentType::file)
			->orderBy('orders', 'DESC')
			->orderBy('document.documentid', 'desc');

		if ($condition['extension'] != 'all')
		{
			if ($condition['extension'] == 'doc' || $condition['extension'] == 'ppt' )
			{
				$query->where(function($where) use ($condition)
				{
					$where->orWhere('document.extension', '=', $condition['extension'])
						->orWhere('document.extension', '=', $condition['extension'] . 'x');
				});
			}
			else
			{
				$query->where('document.extension', '=', $condition['extension']);
			}
		}
//		echo \DBExt::get_sql($query);exit;
		return	$query->paginate($limit);
	}

	public static function count_document_search($condition)
	{
		$query = DB::table('document')
			->where('document.name', 'like', '%' . $condition['name'] . '%')
			->where('document.status', '=', DocumentStatus::normal)
			->where('document.from', '=', DocumentSource::upload)
			->where('publish', '=', DocumentPublish::submit_d)
			->where('document.type', '=', DocumentType::file);
		if ($condition['extension'] != 'all') $query->where('document.extension', '=', $condition['extension']);
		return $query->count();
	}

	public static function count_user_document($user_id)
	{
		return DB::table('document')
			->where('userid', '=', $user_id)
			->where('document.from', '=', DocumentSource::upload)
			->where('status', '!=', DocumentStatus::deleted)
			->where('document.type', '=', DocumentType::file)
			->count();
	}

	/**
	 * 我的文档的被下载次数
	 * @param $user_id
	 * @return mixed
	 */
	public static function count_download_document($user_id)
	{
		$documents = DB::table('document')
			->select('documentid')
			->where('userid', '=', $user_id)
			->where('document.from', '=', DocumentSource::upload)
			->where('status', '!=', DocumentStatus::deleted)
			->where('document.type', '=', DocumentType::file)
			->get();
		$document_ids = array();
		foreach ($documents as $document)
		{
			$document_ids[] = $document->documentid;
		}
		if (count($document_ids) == 0) return 0;
		return DB::table('document__download')
			->whereIn('documentid', $document_ids)
			->count();
	}

	public static function count_user_favorite($user_id)
	{
		return DB::table('document')
			->where('userid', '=', $user_id)
			->where('document.from', '=', DocumentSource::favorite)
			->where('status', '!=', DocumentStatus::deleted)
			->count();
	}

	public static function count_user_download($user_id)
	{
		return DB::table('document__download')
			->leftJoin('document', 'document.documentid', '=', 'document__download.documentid')
			->where('document__download.userid', '=', $user_id)
			->where('document.status', '=', DocumentStatus::normal)
			->count();
	}

	public static function get_document_recommended($limit = 10)
	{
		return DB::table('documenttype')
			->select(array('document.*', 'documentinfo.*'))
			->leftJoin('document', 'document.documentid', '=', 'documenttype.documentid')
			->leftJoin('documentinfo', 'document.documentid', '=', 'documentinfo.documentid')
			->where('document.status', '=', DocumentStatus::normal)
			->where('document.from', '=', DocumentSource::upload)
			->where('publish', '=', DocumentPublish::submit_d)
			->where('document.type', '=', DocumentType::file)
			->where('documenttype.type', '=', DocumentShowType::Commended)
			->orderBy('document.documentid', 'desc')
			->take($limit)
			->get();
	}


	public static function set_document_type($document_id, $types)
	{
		if (empty($types))
		{
			DB::table('documenttype')->where('documentid', '=', $document_id)->delete();
		}
		else
		{
			$rows = DB::table('documenttype')->select(array('type'))->where('documentid', '=', $document_id)->get();
			$current_types = array();
			foreach ($rows as $current_type)
			{
				$current_types[] = $current_type->type;
				if (!in_array($current_type->type, $types))
				{
					DB::table('documenttype')
						->where('documentid', '=', $document_id)
						->where('type', '=', $current_type->type)
						->delete();
				}
			}
			foreach ($types as $type)
			{
				if (!in_array($type, $current_types))
				{
					DB::table('documenttype')
						->insert(array(
							'documentid' => $document_id,
							'type' => $type,
						));
				}
			}
		}
	}

	public static function check_document($documentid)
	{
		return DB::table('document')
			->where('documentid', '=', $documentid)
			->where('status', '=', DocumentStatus::normal)
			->where('type', '=', DocumentType::file)
			->where('document.from', '=', DocumentSource::upload)
			->where('publish', '=', DocumentPublish::submit_d)
			->count() > 0;
	}

	public static function getFavoriteTopic($userid, $limit)
	{
		return DB::table('topicfavorite')
			->leftJoin('topic', 'topic.topicid', '=', 'topicfavorite.topicid')
			->where('topicfavorite.userid', '=', $userid)
			->where('topic.status', '=', \Ca\TopicStatus::normal)
			->paginate($limit);
	}
	public static function DeleteFavoriteTopic($topicid, $userid)
	{
		DB::table('topicfavorite')
			->where('topicfavorite.topicid', '=', $topicid)
			->where('topicfavorite.userid', '=', $userid)
			->delete();
	}

	/**
	 * 获取用户上传文档的总大小
	 * @param $userid
	 * @return mixed
	 */
	public static function GetTotalSize($userid)
	{
		$totalsize = DB::table('document')
			->select(DB::raw('SUM(documentinfo.size) as totalsize'))
			->leftJoin('documentinfo', 'document.documentid', '=', 'documentinfo.documentid')
			->where('userid', '=', $userid)
			->where('status', '!=', DocumentStatus::deleted)
			->where('type', '=', DocumentType::file)
			->where('document.from', '=', DocumentSource::upload)
			->pluck('totalsize');
//		var_dump($totalsize); exit;
		$totalsize = $totalsize == null ? 0 : $totalsize;
		return $totalsize;
	}

	public static function getAttachments($parentid, $userid = null)
	{
		$query = DB::table('document')
			->select(array('document.documentid', 'document.name','document.extension', 'documentinfo.size', 'documentinfo.originalfile'))
			->leftJoin('documentinfo', 'document.documentid', '=', 'documentinfo.documentid')
			->where('parentid', '=', $parentid)
			->where('document.type', '=', DocumentType::attachment)
			->where('document.status', '=', DocumentStatus::normal);
		if($userid != null)
		{
			$query->where('userid', '=', $userid);
		}
		return $query->get();
	}

}