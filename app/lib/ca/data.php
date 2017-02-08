<?php
namespace Ca;
use \Config,
	\DB;
class Data {
	public static function queryList(&$query, &$count_query, $page, $conditions, $text_fields = null, $modify_conditions = null, $delete_conditions = null, $page_size = null)
	{
		$page_size = Config::get('app.page_size', $page_size);
		$page_size = $page_size == 0 ? 10 : $page_size;
		foreach ($conditions as $condition)
		{
			switch ($condition['type'])
			{
				case 'string':
					if ($condition['value'] == '') break;
					$query->where($condition['field'], 'like', '%' . $condition['value'] . '%');
					if (!is_null($count_query))
					{
						$count_query->where($condition['field'], 'like', '%' . $condition['value'] . '%');
					}
					break;
				case 'int':
				case 'int(0)':
					$operator = isset($condition['operator']) ? $condition['operator'] : '=';
					if ($condition['type'] == 'int' && $condition['value'] === 0 && $operator == '=') break;
					if (strtolower($operator) == 'in')
					{
						$query->whereIn($condition['field'], $condition['value']);
						if (!is_null($count_query))
						{
							$count_query->whereIn($condition['field'], $condition['value']);
						}
					}
					else
					{
						$query->where($condition['field'], $operator, $condition['value']);
						if (!is_null($count_query))
						{
							$count_query->where($condition['field'], $operator, $condition['value']);
						}
					}
					break;
				case 'null':
					if ($condition['operator'] == 'NOT')
					{
						$query->whereNotNull($condition['field']);
						if (!is_null($count_query))
						{
							$count_query->whereNotNull($condition['field']);
						}
					}
					else
					{
						$query->whereNull($condition['field']);
						if (!is_null($count_query))
						{
							$count_query->whereNull($condition['field']);
						}
					}
					break;
			}
		}

		$query->skip($page_size * ($page - 1))->take($page_size);

		$list = $query->get();
		foreach ($list as $key => $entity)
			if (!is_array($entity)) $list[$key] = $entity = (array)$entity;

		if ($text_fields)
		{
			foreach ($list as $key => $entity)
			{
				foreach ($text_fields as $field => $values)
				{
					if (is_callable($values))
						$list[$key][$field . "_text"] = call_user_func($values, $entity[$field]);
					else if (sizeof($values) == 1)
					{
						if(array_key_exists($entity[$field],$values[0]))
						{
							$list[$key][$field . "_text"] = $values[0][$entity[$field]];
						}
					}
					else
					{
						$vals = explode(',', $entity[$field]);
						$val_text = '';
						foreach ($vals as $val)
						{
							if (array_key_exists($val, $values[0]))
							{
								$val_text .= ($val ? $values[0][$val] : '') . ', ';
							}
						}
						$list[$key][$field . "_text"] = trim($val_text, ', ');
					}
				}
			}
		}

		if ($modify_conditions)
		{
			foreach ($list as $key => $entity)
			{
				foreach ($modify_conditions as $modify_condition)
				{
					$list[$key]['_can_modify'] = false;
					$field = $modify_condition[0];
					$operator = strtolower($modify_condition[1]);
					$value = $modify_condition[2];

					switch ($operator)
					{
						case '!=':
							if ($entity[$field] != $value) $list[$key]['_can_modify'] = true;
							break;
						case '==':
							if ($entity[$field] == $value) $list[$key]['_can_modify'] = true;
							break;
						case 'in':
							if (!is_array($value)) $value = array($value);
							if (in_array($entity[$field], $value)) $list[$key]['_can_modify'] = true;
							break;
					}
				}
			}
		}

		if ($delete_conditions)
		{
			foreach ($list as $key => $entity)
			{
				foreach ($delete_conditions as $delete_condition)
				{
					$list[$key]['_can_delete'] = false;
					$field = $delete_condition[0];
					$operator = strtolower($delete_condition[1]);
					$value = $delete_condition[2];

					switch ($operator)
					{
						case '!=':
							if ($entity[$field] != $value) $list[$key]['_can_delete'] = true;
							break;
						case '==':
							if ($entity[$field] == $value) $list[$key]['_can_delete'] = true;
							break;
						case 'in':
							if (!is_array($value)) $value = array($value);
							if (in_array($entity[$field], $value)) $list[$key]['_can_delete'] = true;
							break;
					}
				}
			}
		}
		$count = 0;
		$page_count = 0;
		$entity_count = 0;
		if (!is_null($count_query))
		{
			$count = $count_query->first();
			$page_count = ceil($count->count / $page_size);
			$entity_count = $count->count;
		}

		return array('list' => $list, 'count' => $page_count, 'entityCount' => $entity_count);
	}

	public static function updateEntity($table, $where, $fields, $values = array(), $conditions = null, &$entityid = null)
	{
		$post = $_POST;
		if (!empty($where) && (is_numeric($where[2]) && $where[2] > 0 || is_string($where[2]) && $where[2] != '') )
		{
			$query = DB::table($table);
			$set = array();
			foreach ($fields as $key => $field)
			{
				if (!$values) $set[$field] = $post[$field];
				else $set[$field] = $values[$key];
			}
			$query->where($where[0], $where[1], $where[2]);
			if ($conditions)
			{
				foreach ($conditions as $condition)
				{
					if (strtoupper($condition[1]) == 'IN')
					{
						$query->whereIn($condition[0], $condition[2]);
					}
					else
					{
						$query->where($condition[0], $condition[1], $condition[2]);
					}
				}
			}
			$query->update($set);
			$entityid = $where[2];
		}
		else
		{
			//TODO 暂时全部用POST
			if (!$values) foreach ($fields as $field) $values[$field] = $post[$field];
			$entityid = DB::table($table)->insertGetId($values);
		}
		return $entityid;
	}
}