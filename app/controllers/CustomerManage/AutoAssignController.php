<?php
namespace CustomerManage;

use DB,
	View,
	InputExt,
	Input,
	Response,
	Ca\Consts,
	Ca\Common,
	Ca\Data,
	Ca\Service\ArticleService;

class AutoAssignController extends BaseController {
	public $layout = 'customermanage/layouts/common';

	public function getIndex()
	{
		$this->layout->title = "密钥自动分配管理";
		$this->layout->body = View::make('customermanage/autoassign/list');
	}

	public function postList()
	{
		$page = InputExt::getInt('page');
		$status = InputExt::getInt('status');

		$query = DB::table('autoassign')->select(DB::raw('"自定义分配" as assigntype'), 'autoassign.*');

		$count_query = DB::table('autoassign')->select(array(DB::raw('COUNT(autoassignid) as count')));

		$ret = Data::queryList($query, $count_query, $page, array(
			array('type' => 'int', 'field' => 'autoassign.status', 'value' => $status),
		), array('status' => array(Consts::$autoassign_status_text),));

		//$page =0 默认自动分配
		if ($page <= 1)
		{
			$autoassignkeys = DB::table('params')->where('key', '=', 'autoassignkeys')->pluck('value');
			$autoassignSwitch = DB::table('params')->where('key', '=', 'autoassignopen')->pluck('value') == 1 ? 1 : 2;
			if ($autoassignkeys != null)
			{
				$autoassign = array(
					'autoassignid' => 0,
					'assigntype' => '默认自动分配',
					'note' => '适用于所有用户(自定义分配的用户除外), 该项禁用后将关闭整个自动分配功能',
					'keyassign' => $autoassignkeys,
					'status' => $autoassignSwitch,
					'status_text' => Consts::$autoassign_status_text[$autoassignSwitch],
				);
				array_unshift($ret['list'], $autoassign);
			}
		}


		//将autoassignkeys转为 "产品名: 数量" 的格式
		foreach($ret['list'] as $index => $value)
		{
			if ($value['keyassign'] != '')
			{
				$autoassignkeys = json_decode($value['keyassign']);
				$autoassignkeys_str = '';
				foreach ($autoassignkeys as $autoassignkey)
				{
					$key = DB::table('key')
						->where('keyid', '=', $autoassignkey->keyid)
						->first();
					if($key)
					{
						$autoassignkeys_str .= $key->name . ' [' .
							Consts::$user_type_text[$autoassignkey->type]  .
							']: ' . $autoassignkey->amount . '<br/>';
					}
				}
				$ret['list'][$index]['keyassign'] = $autoassignkeys_str;
			}
		}
		echo json_encode($ret);
	}

	public function postEntity()
	{
//		var_dump($_REQUEST);exit;

		$auto_keyid = Input::get('auto_keyid');
		$auto_type = Input::get('auto_type');
		$auto_amount = Input::get('auto_amount');
		$note = Input::get('note');

		$autoassignkeys = array();
		if (is_array($auto_keyid))
		{
			foreach ($auto_keyid as $index => $keyid)
			{
				$amount = intval($auto_amount[$index]);
				$type = intval($auto_type[$index]);
				if ($amount > 0)
				{
					$autoassignkeys[] = array('keyid' => $keyid, 'type'=>$type, 'amount' => $amount);
				}
			}
			$value = json_encode($autoassignkeys);
		}
		else
		{
			$value = '[]';
		}

		if (Input::has('eid'))
		{
			$eid = InputExt::getInt('eid');
			if ($eid == 0)
			{
				DB::table('params')->where('key', '=', 'autoassignkeys')->update(array('value' => $value));
			}
			else
			{
				DB::table('autoassign')->where('autoassignid', '=', $eid)->update(array('note' => $note, 'keyassign' => $value));
			}
		}
		else
		{
			DB::table('autoassign')->insert(array('note' => $note, 'keyassign' => $value, 'status' => \Ca\AutoAssignStatus::available));
		}
	}

	public function postGet()
	{
		echo json_encode(array());
	}

	public function postDelete()
	{
		$eid = InputExt::getInt('eid');
		DB::table('autoassign')->where('autoassignid', '=', $eid)->delete();
	}


	public function postStatus()
	{
		$eid = InputExt::getInt('eid');
		if ($eid == 0)
		{
			DB::table('params')->where('key', '=', 'autoassignopen')->update(array('value' => DB::raw('abs(value - 1)')));
		}
		else
		{
			DB::table('autoassign')->where('autoassignid', '=', $eid)->update(array('status' => DB::raw('abs(status - 2) + 1')));
		}
	}

	public function postGetkeys()
	{
		$availablekeys = DB::table('key')
			->select(array('key.keyid', 'key.name', DB::raw('CONCAT(product.name, " [", product.type, "]") AS product_name')))
			->leftJoin('product', 'product.productid', '=', 'key.productid')
			->get();

		$keydata = array();
		$usertype = Consts::$user_type_text;
		foreach ($availablekeys as $availablekey)
		{
			foreach($usertype as $type_value => $type_text)
			{
				$keydata[] = array(
					'keyid' => $availablekey->keyid,
					'name' => $availablekey->name,
					'type_value' => $type_value,
					'type_text' => $type_text,
					'product_name' => $availablekey->product_name,
					'amount' => '',
				);
			}
		}
		$ret = array();
		if (Input::has('eid'))
		{
			$eid = InputExt::getInt('eid');
			if ($eid == 0)
			{
				$autoassignkeys = DB::table('params')->where('key', '=', 'autoassignkeys')->pluck('value');
			}
			else
			{
				$autoassign = DB::table('autoassign')->where('autoassignid', '=', $eid)->first();
				$autoassignkeys = $autoassign->keyassign;
				$ret['note'] = $autoassign->note;
			}
			$autoassignkeys = json_decode($autoassignkeys);
			if (is_array($autoassignkeys))
			{
				foreach ($autoassignkeys as $value)
				{
					foreach ($keydata as $index => $data)
					{
						if ($data['keyid'] == $value->keyid && $data['type_value'] == $value->type )
						{
							$keydata[$index]['amount'] = $value->amount;
						}
					}
				}
			}

		}
		$ret['autoassignkeys'] = $keydata;
		echo json_encode($ret);
		exit;
	}

}

