<?php
namespace Ca\Service;
use Ca\SplitChineseWords,
	DBExt,
	Illuminate\Support\Facades\DB;
class KnowsService {

	public static function add_question($user_id, $category_id, $title, $content, $tag_names)
	{
		$data = array(
			'userid' => $user_id,
			'categoryid' => $category_id,
			'title' => $title,
			'content' => $content
		);
		$question_id = DB::table('question')->insertGetId($data);

		$tag_ids = TagService::add_tags($tag_names);
		if (!empty($tag_ids))
		{
			$values = array();
			foreach ($tag_ids as $tag_id)
			{
				$values[] = array('questionid' => $question_id, 'tagid' => $tag_id);
			}
			DB::table('question__tag')->insert($values);
		}
		return $question_id;
	}

	public static function check_question_tag($question_id, $tag_id)
	{
		$count = DB::table('question__tag')
			->where('questionid', '=', $question_id)
			->where('tagid', '=', $tag_id)
			->first();
		return $count > 0;
	}

	public static function add_answer($user_id, $question_id, $content, $parentid = null, $type = \Ca\AnswerType::normal)
	{
		$data = array(
			'userid' => $user_id,
			'questionid' => $question_id,
			'content' => $content,
			'parentid' => $parentid,
			'type' => $type
		);
		return DB::table('answer')->insertGetId($data);
	}

	public static function update_views_question($question_id)
	{
		DB::table('question')
			->where('questionid', '=', $question_id)
			->update(array('views' => DB::raw('views + 1')));
	}

	public static function get_question($question_id)
	{
		$question = DB::table('question')
			->select(array('question.*', 'user.name as user_name', 'questioncategory.name as category_name',
				DB::raw('(SELECT COUNT(*) FROM answer WHERE questionid = ' . $question_id .' AND status = ' . \Ca\AnswerStatus::normal . ' AND answer.type = ' . \CA\AnswerType::normal . ') as answer_count '),
				DB::raw('DATE(question.createdate) as date')))
			->leftJoin('user', 'user.userid', '=', 'question.userid')
			->leftJoin('questioncategory', 'questioncategory.categoryid', '=', 'question.categoryid')
			->where('question.questionid', '=', $question_id)
			->where('question.status', '=', \Ca\QuestionStatus::normal)
			->first();
		if ($question != null) self::get_tag_by_question($question);
		return $question;
	}

	/**
	 * 根据分类id获取问题
	 * @param $category_ids
	 * @param null $limit 查询个数
	 * @param bool $page 表示是否分页
	 * @param bool subcate 是否获取其子类的问题
	 * @return mixed
	 */
	public static function get_question_by_category($category_ids, $limit = null, $page = false, $subcate = false)
	{
		if (!is_array($category_ids))
		{
			$category_ids = array($category_ids);
		}
		if ($subcate)
		{
			$categorys = DB::table('questioncategory')
				->whereIn('parentid', $category_ids)
				->get(array('name', 'categoryid'));
			foreach ($categorys as $value)
			{
				$category_ids[] = $value->categoryid;
			}
		}
		$query = DB::table('question')
			->select(array('question.*', 'user.name as user_name', 'questioncategory.name as category_name', 'question.createdate as date',
				DB::raw('(SELECT COUNT(*) FROM `answer` WHERE `answer`.`questionid` = `question`.`questionid` AND answer.type = ' . \CA\AnswerType::normal . ' AND answer.status != ' . \Ca\AnswerStatus::deleted . ') as answer_count'),
				DB::raw('(SELECT COUNT(*) FROM `answer` WHERE `answer`.`questionid` = `question`.`questionid` AND answer.status = ' . \Ca\AnswerStatus::best . ') as best_answer_count'),
//				DB::raw('(SELECT * FROM question__tag WHERE question__tag.questionid = question.questionid) as tags')
				))
			->leftJoin('user', 'user.userid', '=', 'question.userid')
			->leftJoin('questioncategory', 'questioncategory.categoryid', '=', 'question.categoryid')
			->whereIn('question.categoryid', $category_ids)
			->where('question.status', '=', \Ca\QuestionStatus::normal)
			->orderBy('question.questionid', 'DESC');
		if($page)
		{
			$questions = $query->paginate($limit);
		}
		else
		{
			if($limit == null) return $query->get();
			$questions = $query->take($limit)->get();
		}
		self::get_tag_by_question($questions);

		return $questions;
	}

	public static function get_tag_by_question(&$questions)
	{
		$question_ids = array();
		if (isset($questions->questionid))
		{
			$question_ids[] = $questions->questionid;
		}
		else
		{
			foreach ($questions as $question)
			{
				$question_ids[] = $question->questionid;
			}
		}

		$tags = array();
		if (!empty($question_ids))
		{
			$tags = DB::table('question__tag')
				->leftJoin('tag', 'tag.tagid', '=', 'question__tag.tagid')
				->whereIn('questionid', $question_ids)
				->get();
		}
		if (isset($questions->questionid))
		{
			$questions->tags = $tags;
		}
		else
		{
			foreach ($questions as $key => $question)
			{
				$question->tags = array();
				foreach ($tags as $tag)
				{
					if ($question->questionid == $tag->questionid)
					{
						$question->tags[] = $tag;
					}
				}
			}
		}
	}

	/**
	 * 检测是否有最佳回答
	 */
	public static function check_best_answer($question_id)
	{
		$count = DB::table('answer')
			->where('questionid', '=', $question_id)
			->where('answer.status', '=', \Ca\AnswerStatus::best)
			->count();
		return $count > 0 ;
	}

	public static function get_best_answer($question_id)
	{
		$answer = DB::table('answer')
			->select(array('answer.*', 'user.name as user_name'))
			->join('user', 'user.userid', '=', 'answer.userid')
			->where('questionid', '=', $question_id)
			->where('answer.status', '=', \Ca\AnswerStatus::best)
			->where('answer.type', '=', \Ca\AnswerType::normal)
			->first();
		if ($answer != null)
		{
			$answer->answermore = self::getAnswerMore($answer->answerid);
		}
		return $answer;
	}

	public static function get_answer_by_question($question_id, $limit = null)
	{
		$answers = DB::table('answer')
			->select(array('answer.*', 'user.name as user_name'))
			->join('user', 'user.userid', '=', 'answer.userid')
			->where('questionid', '=', $question_id)
			->where('answer.status', '=', \Ca\AnswerStatus::normal)
			->where('answer.type', '=', \Ca\AnswerType::normal)
			->orderBy('answer.answerid', 'desc')
			->paginate($limit);
		foreach ($answers as $index => $answer)
		{
			$answers[$index]->answermore = KnowsService::getAnswerMore($answer->answerid);
		}
		return $answers;
	}

	public static function count_answer($question_id)
	{
		return DB::table('answer')
			->where('questionid', '=', $question_id)
			->where('answer.status', '=', AnswerStatus::normal)
			->where('answer.type', '=', \Ca\AnswerType::normal)
			->count();
	}

	public static function update_status_question($question_id, $status)
	{
		DB::table('question')
			->where('questionid', '=', $question_id)
			->update(array('status' => $status));
	}

	public static function update_status_answer($answer_id, $status)
	{
		DB::table('answer')
			->where('answerid', '=', $answer_id)
			->update(array('status' => $status));
	}

	public static function get_category($category_id)
	{
		return DB::table('questioncategory')
			->select(array('questioncategory.*', 'parent.name as parent_name'))
			->leftJoin('questioncategory as parent', 'parent.categoryid', '=', 'questioncategory.parentid')
			->where('questioncategory.categoryid', '=', $category_id)
			->first();
	}

	public static function get_category_by_parentid($parent_id)
	{
		if ($parent_id == null)
		{
			return DB::table('questioncategory')
				->whereNull('parentid')
				->get();
		}
		else
		{
			return DB::table('questioncategory')
				->where('parentid', '=', $parent_id)
				->get();
		}
	}

	public static function get_category_all()
	{
		return DB::table('questioncategory')->get();
	}

	public static function getSubCategories()
	{
		return DB::table('questioncategory')
			->whereNotNull('parentid')
			->get();
	}

	/**
	 * 检验是否是二级分类
	 * @param $categoryid
	 * @return bool
	 */
	public static function checkSubCategory($categoryid)
	{
		return DB::table('questioncategory')
			->where('categoryid', '=', $categoryid)
			->whereNotNull('parentid')
			->count() > 0;
	}

	public static function get_hot_question($limit = 10)
	{
		return DB::table('question')
			->select(array('question.*', DB::raw('(SELECT COUNT(*) FROM answer WHERE answer.questionid = question.questionid AND answer.type = ' . \CA\AnswerType::normal . ' AND answer.status != ' . \Ca\AnswerStatus::deleted . ') as answer_count')))
			->orderBy('views', 'desc')
			->take($limit)
			->get();
	}

	public static function lastpage_answer($question_id, $limit)
	{
		return DB::table('answer')
			->select(array('answer.*', 'user.name as user_name'))
			->join('user', 'user.userid', '=', 'answer.userid')
			->where('questionid', '=', $question_id)
			->paginate($limit)
			->last;
	}

	public static function get_question_by_tag($tag_id, $limit = 10, $page = true)
	{
		$query = DB::table('question')
			->select(array('question.*', 'user.name as user_name', 'questioncategory.name as category_name', 'question.createdate as date',
				DB::raw('(SELECT COUNT(*) FROM `answer` WHERE `answer`.`questionid` = `question`.`questionid` AND answer.type = ' . \CA\AnswerType::normal . ' AND answer.status != ' . \Ca\AnswerStatus::deleted . ') as answer_count'),
				DB::raw('(SELECT COUNT(*) FROM `answer` WHERE `answer`.`questionid` = `question`.`questionid` AND answer.status = ' . \Ca\AnswerStatus::best . ') as best_answer_count'),
			))
			->leftJoin('user', 'user.userid', '=', 'question.userid')
			->leftJoin('questioncategory', 'questioncategory.categoryid', '=', 'question.categoryid')
			->leftJoin('question__tag', 'question__tag.questionid', '=', 'question.questionid')
			->where('tagid', '=', $tag_id)
			->where('question.status', '=', \Ca\QuestionStatus::normal)
			->orderBy('question.questionid', 'DESC');
		if($page)
		{
			$questions = $query->paginate($limit);
		}
		else
		{
			$questions = $query->take($limit)->get();
		}
		self::get_tag_by_question($questions);
		return $questions;
	}

	public static function get_question_by_answer_id($answer_id)
	{
		return DB::table('answer')
			->select(array('question.*'))
			->leftJoin('question', 'question.questionid', '=', 'answer.questionid')
			->where('answerid', '=', $answer_id)
			->where('question.status', '=', \Ca\QuestionStatus::normal)
			->first();
	}

	public static function get_hot_tag($limit = 20)
	{
		return DB::table('tag')
			->select(array('tag.tagid', 'name', DB::raw('COUNT(question__tag.tagid) AS count_tag')))
			->leftJoin('question__tag', 'question__tag.tagid', '=', 'tag.tagid')
			->groupBy('tag.tagid')
			->having('count_tag', '>', 0)
			->orderBy('count_tag', 'DESC')
			->take($limit)
			->get();
	}

//	public static function get_related_question($questionid)
//	{
//		return DB::table('question__tag')
//			->select(array('question__tag.questionid', 'title',
////				DB::raw('COUNT(answerid) AS answer_count'),
//				DB::raw('COUNT(question__tag.questionid) AS count_question')))
//			->where('tagid', 'IN', DB::raw('(SELECT tagid FROM question__tag WHERE questionid = ' . $questionid . ')'))
//			->leftJoin('question', 'question.questionid', '=', 'question__tag.questionid')
//			->leftJoin('answer', 'question__tag.questionid', '=', 'answer.questionid')
//			->groupBy('question__tag.questionid')
//			->having('question__tag.questionid', '!=', $questionid)
//			->orderBy('count_question', 'DESC')
//			->take(10)
//			->get();
//	}

	public static function check_favorite($user_id, $question_id)
	{
		$count = DB::table('questionfavorite')
			->where('questionid', '=', $question_id)
			->where('userid', '=', $user_id)
			->count();
		return $count > 0;
	}

	public static function add_favorite($user_id, $question_id)
	{
		$data = array(
			'userid' => $user_id,
			'questionid' => $question_id,
		);
		DB::table('questionfavorite')->insert($data);
	}

	public static function get_favorite($user_id, $limit = 10, $condition = 'all')
	{
		$query = DB::table('questionfavorite')
			->select(array('question.*', 'user.name as user_name', 'questioncategory.name as category_name', 'question.createdate as date',
				DB::raw('(SELECT COUNT(*) FROM `answer` WHERE `answer`.`questionid` = `question`.`questionid` AND answer.type = ' . \CA\AnswerType::normal . ' AND answer.status != ' . \Ca\AnswerStatus::deleted . ') as answer_count'),
				DB::raw('(SELECT COUNT(*) FROM `answer` WHERE `answer`.`questionid` = `question`.`questionid` AND answer.status = ' . \Ca\AnswerStatus::best . ') as best_answer_count')
			))
			->leftJoin('question', 'question.questionid', '=', 'questionfavorite.questionid')
			->leftJoin('user', 'user.userid', '=', 'question.userid')
			->leftJoin('questioncategory', 'questioncategory.categoryid', '=', 'question.categoryid')
			->where('questionfavorite.userid', '=', $user_id)
			->where('question.status', '=', \Ca\QuestionStatus::normal)
			->groupBy('question.questionid')
			->orderBy('question.createdate', 'DESC');
		if ($condition == 'answered')
		{
			$query->having('best_answer_count', '>=', 1);
		}
		else if ($condition == 'unanswered')
		{
			$query->having('best_answer_count', '<', 1);
		}
		$questions = $query->paginate($limit);
		self::get_tag_by_question($questions);
		return $questions;
	}


	public static function count_favorite($user_id)
	{
		return DB::table('questionfavorite')
			->leftJoin('question', 'question.questionid', '=', 'questionfavorite.questionid')
			->where('questionfavorite.userid', '=', $user_id)
			->where('question.status', '=', \Ca\QuestionStatus::normal)
			->count();
	}

	public static function get_similarquestion($title, $page = false, $bestonly = false, $exceptionid = null)
	{
		$searchNames = SplitChineseWords::splitWords($title);
		$condition_count = count($searchNames);
		if ($condition_count == 0)
		{
			return array();
		}
		//用来按相似度高的排序
		$newtable_sql = '(SELECT questionid,';
			foreach ($searchNames as $index => $words)
			{
			$newtable_sql .= ' IF(`title` LIKE "%' . $words . '%", 1, 0) AS count' . $index;
			if ($index != $condition_count - 1)
			{
				$newtable_sql .= ',';
			}
		}
		$newtable_sql .= ' FROM question) newtable';

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

		$query = DB::table('question')
			->select(array(
				'question.*', DB::raw('IFNULL(best.content, 0) AS best_answer'),
				'questioncategory.name as category_name', 'user.name as user_name', 'question.createdate as date',
				DB::raw('(SELECT COUNT(*) FROM `answer` WHERE `answer`.`questionid` = `question`.`questionid` AND answer.type = ' . \CA\AnswerType::normal . ' AND answer.status != ' . \Ca\AnswerStatus::deleted . ') as answer_count'),
				DB::raw('(SELECT COUNT(*) FROM `answer` WHERE `answer`.`questionid` = `question`.`questionid` AND answer.status = ' . \Ca\AnswerStatus::best . ') as best_answer_count'),
//				DB::raw('COUNT(answer.answerid) AS answer_count'),
				DB::raw($orders)
			))
			->leftJoin('questioncategory', 'questioncategory.categoryid', '=', 'question.categoryid')
			->leftJoin('user', 'user.userid', '=', 'question.userid')
			->leftJoin('answer', 'answer.questionid', '=', 'question.questionid')
			->leftJoin('answer AS best', function($join)
			{
				$join->on('best.questionid', '=', 'question.questionid');
				$join->on('best.status', '=', DB::raw(\Ca\AnswerStatus::best));
			})

			->leftJoin(DB::raw($newtable_sql), 'newtable.questionid', '=', 'question.questionid')
			->where('question.status', '=', \Ca\QuestionStatus::normal)
			->where(function($where) use ($searchNames)
			{
				foreach ($searchNames as $words)
				{
					$where->orWhere('question.title', 'LIKE', '%' . $words . '%');
				}
			})
			->groupBy('question.questionid')
			->orderBy('orders', 'DESC')
			->orderBy('question.createdate', 'DESC');
		if ($exceptionid !== null)
		{
			$query->where('question.questionid', '!=', $exceptionid);
		}
		if ($bestonly)
		{
			$query->having('best_answer', '!=', '0');
		}
		if ($page !== false)
		{
			$questions = $query->paginate($page);
			self::get_tag_by_question($questions);
		}
		else
		{
			$questions = $query->get();
		}
		return $questions;

	}

	public static function get_question_by_user($user_id, $limit, $condition = 'all')
	{
		$query = DB::table('question')
			->select(array('question.*', 'user.name as user_name', 'questioncategory.name as category_name', 'question.createdate as date',
				DB::raw('(SELECT COUNT(*) FROM `answer` WHERE `answer`.`questionid` = `question`.`questionid` AND answer.type = ' . \CA\AnswerType::normal . ' AND answer.status != ' . \Ca\AnswerStatus::deleted . ') as answer_count'),
//				DB::raw('(SELECT COUNT(*) FROM `answer` WHERE `answer`.`questionid` = `question`.`questionid`) as answer_count'),
				DB::raw('(SELECT COUNT(*) FROM `answer` WHERE `answer`.`questionid` = `question`.`questionid` AND answer.status = ' . \Ca\AnswerStatus::best . ') as best_answer_count')
			))
			->leftJoin('user', 'user.userid', '=', 'question.userid')
			->leftJoin('questioncategory', 'questioncategory.categoryid', '=', 'question.categoryid')
			->where('question.userid', '=', $user_id)
			->where('question.status', '=', \Ca\QuestionStatus::normal)
			->groupBy('question.questionid')
			->orderBy('question.createdate', 'DESC');
		if ($condition == 'answered')
		{
			$query->having('best_answer_count', '>=', 1);
		}
		else if ($condition == 'unanswered')
		{
			$query->having('best_answer_count', '<', 1);
		}
		$questions = $query->paginate($limit);

		self::get_tag_by_question($questions);
		return $questions;
	}

	public static function count_question_by_user($user_id)
	{
		return DB::table('question')
			->where('question.userid', '=', $user_id)
			->where('question.status', '=', \Ca\QuestionStatus::normal)
			->count();
	}

	/**
	 * 根据用户回答查找提问
	 */
	public static function get_question_by_answer_user($user_id, $limit, $condition = 'all')
	{
		$query = DB::table('question')
			->select(array('question.*', 'user.name as user_name', 'questioncategory.name as category_name', 'question.createdate as date',
				DB::raw('(SELECT COUNT(*) FROM `answer` WHERE `answer`.`questionid` = `question`.`questionid` AND answer.type = ' . \CA\AnswerType::normal . ' AND answer.status != ' . \Ca\AnswerStatus::deleted . ') as answer_count'),
//				DB::raw('(SELECT COUNT(*) FROM `answer` WHERE `answer`.`questionid` = `question`.`questionid`) as answer_count'),
				DB::raw('(SELECT COUNT(*) FROM `answer` WHERE `answer`.`questionid` = `question`.`questionid` AND answer.status = ' . \Ca\AnswerStatus::best . ') as best_answer_count')
			))
			->leftJoin('user', 'user.userid', '=', 'question.userid')
			->leftJoin('questioncategory', 'questioncategory.categoryid', '=', 'question.categoryid')
			->whereIn('question.questionid', function($query) use ($user_id) {
				$query->select(array(DB::raw('DISTINCT answer.questionid')))
					->from('answer')
					->where('answer.userid', '=', $user_id)
					->where('answer.type', '=', \Ca\AnswerType::normal);
			})
			->where('question.status', '=', \Ca\QuestionStatus::normal)
			->groupBy('question.questionid')
			->orderBy('question.createdate', 'DESC');
		if ($condition == 'answered')
		{
			$query->having('best_answer_count', '>=', 1);
		}
		else if ($condition == 'unanswered')
		{
			$query->having('best_answer_count', '<', 1);
		}
		$questions = $query->paginate($limit);
//		self::get_tag_by_question($questions);
//		echo DBExt::get_sql($questions); exit;
		return $questions;
	}

	public static function delete_favorite($questionids, $user_id)
	{
		if (!is_array($questionids))
		{
			$questionids = array($questionids);
		}
		DB::table('questionfavorite')
			->where('userid', '=', $user_id)
			->whereIn('questionid', $questionids)
			->delete();
	}

//	public static function search($keyword, $limit = 10)
//	{
//		$query = DB::table('question')
//			->select(array('question.*', 'user.name as user_name', 'questioncategory.name as category_name', 'question.createdate as date',
//				DB::raw('(SELECT COUNT(*) FROM `answer` WHERE `answer`.`questionid` = `question`.`questionid` AND answer.type = ' . \CA\AnswerType::normal . ' AND answer.status != ' . \Ca\AnswerStatus::deleted . ') as answer_count'),
////				DB::raw('(SELECT COUNT(*) FROM `answer` WHERE `answer`.`questionid` = `question`.`questionid`) as answer_count'),
//				DB::raw('(SELECT COUNT(*) FROM `answer` WHERE `answer`.`questionid` = `question`.`questionid` AND answer.status = ' . \Ca\AnswerStatus::best . ') as best_answer_count'),
//			))
//			->leftJoin('user', 'user.userid', '=', 'question.userid')
//			->leftJoin('questioncategory', 'questioncategory.categoryid', '=', 'question.categoryid')
//			->where('question.title', 'like', '%' . $keyword . '%')
//			->orWhere('question.content', 'like', '%' . $keyword . '%');
//		$questions = $query->paginate($limit);
//		self::get_tag_by_question($questions);
//		return $questions;
//	}

	/**
	 * @param $answerid
	 * 获取追问和追加问答
	 */
	public static function getAnswerMore($answerid)
	{
		return DB::table('answer')
			->select(array('answer.*', 'user.name as user_name'))
			->join('user', 'user.userid', '=', 'answer.userid')
			->where('parentid', '=', $answerid)
			->where('answer.status', '=', \Ca\AnswerStatus::normal)
			->whereIn('answer.type', array(\Ca\AnswerType::answerMore, \Ca\AnswerType::askMore))
			->orderBy('answer.createdate')
			->get();
	}

	/**
	 * @param $userid
	 * @param $questionid
	 * @return bool
	 * 检测用户是否回答过该问题
	 */
	public static function checkAnswered($userid, $questionid)
	{
		return DB::table('answer')
			->where('questionid', '=', $questionid)
			->where('userid', '=', $userid)
			->where('status', '=', \Ca\AnswerStatus::normal)
			->where('type', '=', \Ca\AnswerStatus::normal)
			->count() > 0;
	}

	/**
	 * @param $userid
	 * @param $questionid
	 * @param $content
	 * @return bool
	 * 修改提问
	 */
	public static function updateQuestion($userid, $questionid, $content)
	{
		return DB::table('question')
			->where('questionid', '=', $questionid)
			->where('userid', '=', $userid)
			->where('status', '=', \Ca\QuestionStatus::normal)
			->update(array('content' => $content));
	}

	/**
	 * @param $userid
	 * @param $limit
	 * @param $tagid
	 * @return mixed
	 * 根据用户关注关键词获取问题
	 */
	public static function getQuestionByAttentionTag($userid, $limit, $tagid = null)
	{
		$query = DB::table('question')
			->select(array('question.*', 'questioncategory.name AS category_name',
				DB::raw('(SELECT COUNT(*) FROM `answer` WHERE `answer`.`questionid` = `question`.`questionid` AND answer.type = ' . \CA\AnswerType::normal . ' AND answer.status != ' . \Ca\AnswerStatus::deleted . ') as answer_count'),
//				DB::raw('COUNT(answer.answerid) AS answer_count')
			))
			->leftJoin('answer', function($join) {
				$join->on('answer.type', '=', DB::raw(\Ca\AnswerType::normal))
					->on('answer.questionid', '=', 'question.questionid');
			})
			->leftJoin('questioncategory', 'questioncategory.categoryid', '=', 'question.categoryid')
			->whereIn('question.questionid', function($query) use ($userid, $tagid) {
				$query->select(array(DB::raw('DISTINCT question__tag.questionid')))
					->from('question__tag')
					->whereIn('question__tag.tagid', function($query) use ($userid, $tagid) {
						$query->select(array('attentiontag.tagid'))
							->from('attentiontag')
							->where('attentiontag.userid', '=', $userid);
						if ($tagid !== null)
						{
							$query->where('attentiontag.tagid', '=', $tagid);
						}
					});
			})
			->leftJoin('answer as solved', function($join) {
				$join->on('solved.status', '=', DB::raw(\Ca\AnswerStatus::best))
					->on('solved.questionid', '=', 'question.questionid');
			})
			->whereNull('solved.questionid')
			->where('question.userid', '!=', $userid)
			->where('question.status', '=', \Ca\QuestionStatus::normal)
			->groupBy('question.questionid')
			->orderBy('question.createdate', 'desc');
		return $query->paginate($limit);
	}

	/**
	 * @param $userid
	 * @param $limit
	 * @param $categoryid
	 * @return mixed
	 * 根据用户关注分类获取问题
	 */
	public static function getQuestionByAttentionCategory($userid, $limit, $categoryid = null)
	{
		$query = DB::table('attentioncategory')
			->select(array('question.*', 'questioncategory.name AS category_name',
				DB::raw('(SELECT COUNT(*) FROM `answer` WHERE `answer`.`questionid` = `question`.`questionid` AND answer.type = ' . \CA\AnswerType::normal . ' AND answer.status != ' . \Ca\AnswerStatus::deleted . ') as answer_count'),
//				DB::raw('COUNT(answer.`answerid`) AS answer_count')
			))
			->leftJoin('question', 'question.categoryid', '=', 'attentioncategory.categoryid')
			->leftJoin('answer', function($join){
				$join->on('answer.type', '=', DB::raw(\Ca\AnswerType::normal))
					->on('answer.questionid', '=', 'question.questionid');
			})
			->leftJoin('questioncategory', 'questioncategory.categoryid', '=', 'question.categoryid')
			->leftJoin('answer as solved', function($join) {
				$join->on('solved.status', '=', DB::raw(\Ca\AnswerStatus::best))
					->on('solved.questionid', '=', 'question.questionid');
			})
			->whereNull('solved.questionid')
			->where('attentioncategory.userid', '=', $userid)
			->where('question.userid', '!=', $userid)
			->where('question.status', '=', \Ca\QuestionStatus::normal)
			->groupBy('question.questionid')
			->orderBy('question.createdate', 'desc');
		if ($categoryid !== null)
		{
			$query->where('attentioncategory.categoryid', '=', $categoryid);
		}
		return $query->paginate($limit);
	}

	/**
	 * @param $userid
	 * @param $questionid
	 * @return bool
	 * 检查问题与用户是否相符
	 */
	public static function checkUserQuestion($userid, $questionid)
	{
		return DB::table('question')
			->where('userid', '=', $userid)
			->where('questionid', '=', $questionid)
			->count() > 0;
	}
	/**
	 * @param $questionid
	 * @param $categoryid
	 * 修改问题分类
	 */
	public static function updateCategory($questionid, $categoryid)
	{
		DB::table('question')
			->where('questionid', '=', $questionid)
			->update(array("categoryid" => $categoryid));
	}

	/**
	 * 检查是否满足添加的追问和回答
	 * @param $userid
	 * @param $questionid
	 * @param $type
	 * @param $answerid
	 * @return bool
	 */
	public static function checkAskMore($userid, $questionid, $type, $answerid)
	{
		if ($type != \Ca\AnswerType::answerMore && $type != \Ca\AnswerType::askMore )
		{
			return false;
		}
		$lastAnswerType = DB::table('answer')
			->where('questionid', '=', $questionid)
			->orderBy('createDate', 'desc')
			->take(1)
			->pluck('type');
		echo $lastAnswerType;
		if ($type == $lastAnswerType || $lastAnswerType == \Ca\AnswerType::normal && $type == \Ca\AnswerType::answerMore)
		{
			return false;
		}
		if ($type == \Ca\AnswerType::askMore && !self::checkUserQuestion($userid, $questionid))
		{
			return false;
		}
		$count = DB::table('answer')
			->where('answerid', '=', $answerid)
			->where('questionid', '=', $questionid)
			->where('type', '=', \Ca\AnswerType::normal)
			->count();
		if ($count <= 0)
		{
			return false;
		}
		return true;
	}

}