<?php
namespace Ca\Service;
use Illuminate\Support\Facades\DB;
class DocumentRatingService {
	public static function add_rating($documentid, $userid, $rating)
	{
		$data = array(
			'documentid' => $documentid,
			'userid' => $userid,
			'rating' => $rating
		);
		return DB::table('documentrating')->insertGetId($data);
	}

	public static function get_score($documentid)
	{
		$result = DB::table('documentrating')
			->select(array(DB::raw('IFNULL(FORMAT(AVG(rating), 1), 0) AS score')))
			->where('documentid', '=', $documentid)
			->first();

		return $result->score;

	}

	public static function get_count($documentid)
	{
		return DB::table('documentrating')
			->where('documentid', '=', $documentid)
			->count('userid');
	}

	public static function check_rating($documentid, $userid)
	{
		return DB::table('documentrating')
			->where('documentid', '=', $documentid)
			->where('userid', '=', $userid)
			->count() > 0;
	}

	public static function rating_star_html($rating, $type = 'small')
	{
		$full_count = floor($rating);

		$string = '';

		for ($i = 1; $i <= 5; $i++)
		{
			if ($i <= $full_count)
			{
				$string .= '<b score="' . $i . '" class="icon_topics icon_score_' . $type . '_1"></b>';
			}
			elseif($rating > $full_count && $i == $full_count + 1)
			{
				$string .= '<b score="' . $i . '" class="icon_topics icon_score_' . $type . '_2"></b>';
			}
			else
			{
				$string .= '<b score="' . $i . '" class="icon_topics icon_score_' . $type . '_3"></b>';
			}

		}
		return $string;
	}
}