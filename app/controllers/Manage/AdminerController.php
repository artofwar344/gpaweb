<?php
namespace Manage;

use \DB,
	\Auth,
	\View,
	\Input,
	\Config,
	\Hash,
	Ca\Common,
	Ca\Consts,
	Ca\Data,
	\InputExt;
class AdminerController extends BaseController {
	public $layout = 'manage/layouts/common';

	public function getIndex()
	{
		$this->layout->title = "高级管理员管理";
		$this->layout->body = View::make('manage/adminer/list');
	}

	public function postList()
	{
		$name = InputExt::get('name');
		$page = InputExt::getInt('page');

		$query = DB::table('adminer')
			->select(array('adminer.adminerid', 'adminer.name', 'adminer.role', 'adminer.status', 'adminer.createdate'))
			->orderBy('adminerid', 'desc');

		$count_query = DB::table('adminer')->select(array(DB::raw('COUNT(*) as count')));

		$ret = Data::queryList($query, $count_query, $page, array(
			array('type' => 'string', 'field' => 'adminer.name', 'value' => $name)
		), array('status' => array(Consts::$adminer_status_texts),
			'role' => function($value) {
					$available_roles = Config::get('app.adminer_roles', Consts::$adminer_role_texts);
					$roles = array();
					foreach ($available_roles as $role_name => $role)
					{
						if (is_array($role) && array_key_exists('list', $role) && is_array($role['list']))
						{
							$roles += $role['list'];
						}
						else
						{
							$roles[$role_name] = $role;
						}
					}
					$vals = explode(',', $value);
					$val_text = '';
					foreach ($vals as $val)
					{
						if (array_key_exists($val, $roles))
						{
							$role_text = is_array($roles[$val]) ? $roles[$val][0] : $roles[$val];
							$val_text .= ($val ? $role_text : '') . ', ';
						}
					}
					return trim($val_text, ', ');
				}
		));

		echo json_encode($ret);
	}

	public function postEntity()
	{
		$eid = InputExt::getInt('eid');
		Common::empty_check(array('name'));
		$fields = array('name', 'role', 'status');
		$_POST['role'] = isset($_POST['role']) ? implode(',', $_POST['role']) : '';
		if (trim(InputExt::get('password')))
		{
			$fields[] = 'password';
			$_POST['password'] = Hash::make($_POST['password']);
		}
		Data::updateEntity('adminer', array('adminerid', '=', $eid), $fields);
	}

	public function postGet()
	{
		$eid = InputExt::getInt('eid');
		$entity = DB::table('adminer')
			->select(array('name', 'role', 'status'))
			->where('adminerid', '=', $eid)->first();
		echo json_encode($entity);
	}

	public function postDelete()
	{
		$eid = InputExt::getInt('eid');
		DB::table('adminer')->where('adminerid', '=', $eid)->delete();
	}

	public function postStatus()
	{
		$eid = InputExt::getInt('eid');
		DB::table('adminer')->where('adminerid', '=', $eid)->update(array('status' => DB::raw('abs(status - 2) + 1')));
	}
}

