<?php
namespace CustomerManage;

use \View,
	\DB,
	\Input,
	\Response,
	\InputExt,
	Ca\Data,
	Ca\Service\ParamsService,
	Ca\Service\DepartmentService;


class UserinfoController extends BaseController {
	public $layout = 'customermanage/layouts/common';

	public function getIndex()
	{
		$this->layout->title = "用户信息管理";
		$this->layout->body = View::make("customermanage/userinfo/list");
	}

	public function postList()
	{
		$id = Input::get('id');
		$page = InputExt::getInt('page');

		$query = DB::table('userinfo1');
		$count_query =  DB::table('userinfo1')->select(array(DB::raw('COUNT(*) as count')));

		$ret = Data::queryList($query, $count_query, $page, array(array('type' => 'string', 'field' => 'userinfo1.id', 'value' => $id)));
		return Response::json($ret);
	}

	public function postEntity()
	{
		$eid = Input::get('eid');
		$fields = array('id', 'college', 'grade');

		try
		{
			Data::updateEntity('userinfo1', array('infoid', '=', $eid), $fields);
		}
		catch (\Exception $e)
		{
			$message = $e->getMessage();
			if (strpos($message, 'un_id') !== false)
			{
				return Response::json(array('code' => 2, 'id' => 'id', 'message' => '工号已存在'));
			}
			return;
		}
	}

	public function postGet()
	{
		$eid = Input::get("eid");
		$entity = DB::table('userinfo1')->where('infoid', '=', $eid)->first();
		return Response::json($entity);
	}

	public function postDelete()
	{
		$eid = Input::get("eid");
		DB::table('userinfo1')->where('infoid', '=', $eid)->delete();
		return Response::json(array('status'=>'1'));
	}

	public function postImportpreview()
	{
		$fp = fopen('php://input', 'r');
		$i = 1;
		$error = array();
		$list = array();
		while ($data = fgetcsv($fp))
		{
			if (count($data) >= 3)
			{
				list($id, $college, $grade) = array(trim($data[0]), trim($data[1]), trim($data[2]));
				$id = @iconv('gb2312', 'utf-8', $id);
				$college = @iconv('gb2312', 'utf-8', $college);
				$grade = @iconv('gb2312', 'utf-8', $grade);
				if (empty($id) || empty($college) || empty($grade))
				{
					$error[] = array(
						'line' => $i,
						'code' => 1 // 数据格式不对
					);
				}
				else
				{
					$list[] = array($id, $college, $grade);
				}
			}
			else
			{
				$error[] = array(
					'line' => $i,
					'code' => 1
				);
			}
			$i++;
		}
		fclose($fp);
		return Response::json(array('count' => $i - 1, 'list' => $list, 'errors' => $error));
	}

	public function anyImport()
	{
		set_time_limit(0);

		$fp = fopen('php://input', 'r');
		$i = 1;
		$error = array();
		while ($data = fgetcsv($fp))
		{
			if (count($data) >= 3)
			{
				list($id, $college, $grade) = array(trim($data[0]), trim($data[1]), trim($data[2]));
				$id = @iconv('gb2312', 'utf-8', $id);
				$college = @iconv('gb2312', 'utf-8', $college);
				$grade = @iconv('gb2312', 'utf-8', $grade);
				if (empty($id) || empty($college) || empty($grade))
				{
					$error[] = array(
						'line' => $i,
						'code' => 1 // 数据格式不对
					);
				}
				else
				{
					try
					{
						$user_id = DB::table('userinfo1')
							->insertGetId(array(
								'id' => $id,
								'college' => $college,
								'grade' => $grade
							));
					}
					catch (\Exception $e)
					{
						$message = $e->getMessage();
						if (strpos($message, 'un_id') !== false)
						{
							$code = 2; // 工号重复
						}
						else
						{
							$code = 3; // 其他错误
						}

						$error[] = array(
							'line' => $i,
							'code' => $code,
//							'message' => $message,
						);
					}
				}
			}
			else
			{
				$error[] = array(
					'line' => $i,
					'code' => 1
				);
			}
			$i++;
		}
		fclose($fp);
		return Response::json(array('count' => $i - 1, 'errors' => $error));

	}

}
