<?php
namespace Ca\Service;
use Illuminate\Support\Facades\DB;
class FaqService {

	public static function categories()
	{
		return DB::table('faqcategory')
			->get();
	}

	public static function get_faq_by_category($category_ids, $limit = null, $page = false)
	{
		if (!is_array($category_ids))
		{
			$category_ids = array($category_ids);
		}
		$query = DB::table('faq')
			->select(array('faq.*', 'faqcategory.name as category_name'))
			->leftJoin('faqcategory', 'faqcategory.categoryid', '=', 'faq.categoryid')
			->whereIn('faq.categoryid', $category_ids);

		if ($page)
		{
			$faqs = $query->paginate($limit);
		}
		else
		{
			if($limit == null) return $query->get();
			$faqs = $query->take($limit)->get();
		}
		return $faqs;
	}

	public static function get_faq($faqid)
	{
		return DB::table('faq')
			->select(array('faq.*', 'faqcategory.name as category_name'))
			->leftJoin('faqcategory', 'faqcategory.categoryid', '=', 'faq.categoryid')
			->where('faqid', '=', $faqid)
			->first();
	}

	public static function search($keyword, $limit = 10)
	{
		$query = DB::table('faq')
			->select(array('faq.*', 'faqcategory.name as category_name'))
			->leftJoin('faqcategory', 'faqcategory.categoryid', '=', 'faq.categoryid')
			->where('faq.title', 'like', '%' . $keyword . '%')
			->orWhere('faq.content', 'like', '%' . $keyword . '%');
		return $query->paginate($limit);
	}

	public static function get_category_all()
	{
		return DB::table('faqcategory')->get();
	}

	public static function get_category_by_id($category_id)
	{
		return DB::table('faqcategory')->where('categoryid', '=', $category_id)->first();
	}


}