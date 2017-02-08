<?php
namespace Ca\Service;

class TreeService {
	private $_id  = 'id';
	private $_pid = 'pid';
	private $_default_pid = '1';
	private $_objects = array();
	private $_tree = array();
	function __construct($objects, $config = array())
	{
		foreach ($config as $k=>$v)
		{
			if (isset($this->$k))
			{
				$this->$k = $v;
			}
		}
		foreach ($objects as $object)
		{
			$this->_objects[$object[$this->_id]] = $object;
		}
		$this->_initialize();
	}

	function get_tree()
	{
		return $this->_tree;
	}

	function _initialize($id = null)
	{
		$id = $id == null ? $this->_default_pid : $id;
		$tree = array();
		$rootArray = $this->_search_pid($this->_objects, $id);
		foreach ($rootArray as $value )
		{
			$value['child'] = $this->_initialize($value[$this->_id]);
			$tree[] = $value;
		}
		$this->_tree = $tree;
		return $tree;
	}

	function _search_pid(& $data, $pid)
	{
		$rs  = array();
		foreach ($data as $key => $value )
		{
			if ($value[$this->_pid] == $pid )
			{
				$rs[] = $value ;
			}
		}
		return $rs;
	}

	public function get_children($id = 0, &$children = array())
	{
		foreach ($this->_objects as $v)
		{
			if ($v[$this->_id] == $id)
			{
				if (empty($children))
				{
					$children[] = $v;
				}
			}
			elseif ($v[$this->_pid] == $id)
			{
				//$children[] = $v[$this->_id];
				$children[] = $v;
				$this->get_children($v[$this->_id], $children);
			}
		}
		return $children;
	}

	public function get_parents($id, &$parents = array())
	{
		foreach ($this->_objects as $v)
		{
			if ($v[$this->_id] == $id && $v[$this->_id] != $this->_default_pid)
			{
				$parents[] = $v;
				$this->get_parents($v[$this->_pid], $parents);
			}
		}
		return $parents;
	}

	public function render($selected = '', $prefix_orig = '-', $prefix_curr = '', $prefix_name = '└', $o = null, & $ret = array())
	{
		if ($o != null)
		{
			$tmp['field'] = $o;
			//unset($tmp['field']['child']);
			if ($o[$this->_id] == $selected)
			{
				$tmp['extra']['selected'] = true;
			}
			$tmp['extra']['prefix'] = $prefix_curr;
			if ($o[$this->_pid] != $this->_default_pid)
			{
				$tmp['extra']['prefix_name'] = $prefix_name;
			}
			else
			{
				$tmp['extra']['prefix_name'] = '';
			}
			$prefix_curr .= $prefix_orig;
			$ret[] = $tmp;

			if ($o['child'])
			{
				foreach ($o['child'] as $val)
				{
					$this->render($selected, $prefix_orig, $prefix_curr, $prefix_name, $val, $ret);
				}
			}
		}
		else
		{
			foreach ($this->_tree as $v)
			{
				$this->render($selected, $prefix_orig, $prefix_curr, $prefix_name, $v, $ret);
			}
		}
		return $ret;
	}
}