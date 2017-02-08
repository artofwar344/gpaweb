<?php

namespace ClientContent;

use DB,
	Response,
	Ca\SoftStatus,
	InputExt;


class SoftController extends \Controller
{
	public function postList()
	{
			$subquery = DB::table('ca.soft')
				->select(array(DB::raw('count(*) AS count'), 'categoryid'))
				->where('status', '=', DB::raw(SoftStatus::available))
				->whereNotNull('fileurl')
				->groupBy('categoryid');
			$subsql = $subquery->toSql();
			$query = DB::table('softcategory')
				->select('softcategory.categoryid', 'name', 'count')
				->leftJoin(DB::raw('(' . $subsql .') AS tb1'),  'tb1.categoryid', '=', 'softcategory.categoryid')
				->where('count', '>', 0)
				->get();

			$categoriesForamted = array();
			$softsFormated = array();
			
			foreach ($query as $key => $category)
			{
				$categoriesForamted[] = array(
					'Count' => $category->count,
					'Name' => $category->name,
					'CategoryID' => $category->categoryid
				);
			}

			$softs = DB::table('ca.soft')
				->select(array('softid', 'categoryid', 'name', 'productcode', 'brief', 'version', 'feature',
					'fileurl', 'filesize', DB::raw('DATE(updatedate) AS updatedate'), 'bit'))
				->where('status', '=', SoftStatus::available)
				->whereNotNull('fileurl')
				->get();

			foreach ($softs as $key => $soft)
			{
				if (filter_var($soft->fileurl, FILTER_VALIDATE_URL) == false)
				{
					$softFormated['FileUrl'] = 'http://101.4.63.133/' . $soft->fileurl;
				}
				else
				{
					$softFormated['FileUrl'] = $soft->fileurl;
				}

				$softFormated['SoftId'] = $soft->softid;
				$softFormated['CategoryId'] = $soft->categoryid;
				$softFormated['Name'] = $soft->name;
				$softFormated['ProductCode'] = $soft->productcode;
				$softFormated['Description'] = $soft->brief;
				$softFormated['Version'] = $soft->version;
				$softFormated['Feature'] = $soft->feature;
				$softFormated['FileSize'] = $soft->filesize;
				$softFormated['UpdateDate'] = $soft->updatedate;
				$softFormated['Bit'] = $soft->bit;
				$softsFormated[$key] = $softFormated;
			}

			return Response::json(
				array("Categories" => $categoriesForamted, 
					"Softs" => $softsFormated));
	}

	public function getIndex()
	{
		if (isset($_GET['category']))
		{
			$categories = DB::table('ca.softcategory')->lists('categoryid', 'name');
			$categoriesForamted['Type'] = 1;
			foreach ($categories as $name => $id)
			{
				$category["CategoryID"] = $id;
				$category["Name"] = $name;

				$categoriesForamted['Categories'][] = $category;
			}
			return Response::json($categoriesForamted);
		}
		else
		{
			$category_id = InputExt::getInt('categoryid');
			$page = InputExt::getInt('page');
			$page_size = InputExt::getInt('size');

			if ($category_id < 0 || $page <= 0 || $page_size <= 0)
			{
				exit;
			}

			$softs = DB::table('ca.soft')
				->select(array('softid', 'categoryid', 'name', 'productcode', 'brief', 'version', 'feature',
					'fileurl', 'filesize', DB::raw('DATE(updatedate) AS updatedate'), 'bit'))
				->where('status', '=', SoftStatus::available)
				->whereNotNull('fileurl');
			if ($category_id > 0)
			{
				$softs = $softs->where('categoryid', $category_id);
			}

			$softs = $softs->orderBy('order')
				->skip(($page - 1) * $page_size)
				->take($page_size)
				->get();

			foreach ($softs as $key => $soft)
			{
				if (filter_var($soft->fileurl, FILTER_VALIDATE_URL) == false)
				{
					$softFormated['FileUrl'] = 'http://101.4.63.133/' . $soft->fileurl;
				}
				else
				{
					$softFormated['FileUrl'] = $soft->fileurl;
				}

				$softFormated['SoftId'] = $soft->softid;
				$softFormated['CategoryId'] = $soft->categoryid;
				$softFormated['Name'] = $soft->name;
				$softFormated['ProductCode'] = $soft->productcode;
				$softFormated['Description'] = $soft->brief;
				$softFormated['Version'] = $soft->version;
				$softFormated['Feature'] = $soft->feature;
				$softFormated['FileSize'] = $soft->filesize;
				$softFormated['UpdateDate'] = $soft->updatedate;
				$softFormated['Bit'] = $soft->bit;
				$softs[$key] = $softFormated;
			}
			return Response::json(array("Type" => 2, "Count" => 100, "Softs" => $softs));
		}
	}
}