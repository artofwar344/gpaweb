<?php
use Illuminate\Support\Facades\HTML,
	Illuminate\Support\Facades\Request,
	Illuminate\Support\Facades\Auth,
	Ca\Service\ParamsService;
class HtmlExt  {
	public static function link($url, $title = null, $attributes = array(), $https = null)  
	{
		//$url = URL::to($url, $https);

		if (is_null($title)) $title = $url;

		return '<a href="'.$url.'"'. HTML::attributes($attributes).'>' . HTML::entities($title).'</a>';
	}

	public static function html_options($params, $selected = '')
	{
		$ret = '';
		foreach ($params as $value => $text)
		{
			if (is_array($text) && array_key_exists('list', $text))
			{
				foreach ($text['list'] as $value2 => $text2)
				{
					$ret .= '<option value="' . $value2 . '"' . ($selected == $value2 ? ' selected' : '') . '>' . (is_array($text2) ? $text2[0] : $text2) . '</option>';
				}
			}
			else
			{
				$ret .= '<option value="' . $value . '"' . ($selected == $value ? ' selected' : '') . '>' . (is_array($text) ? $text[0] : $text) . '</option>';
			}
		}
		return $ret;
	}

	public static function html_checklist($name, $params, $checked = '')
	{
		$ret = '<ul class="checklist">';
		/*foreach ($params as $key => $value)
		{
			if (is_array($value))
			{
				//echo $key;continue;
				$ret .= '<fieldset><legend><label><input type="checkbox" /><span>' . Consts::$permission_group_texts[$key] . '</span></label></legend>';
				foreach ($value as $key2 => $value2)
				{
					$ret .= '<li><label><input type="checkbox" name="' . $name . '[]" class="checkbox" value="' . $key2 . '"'. ($checked == $key2 ? " checked" : "") .'><span>' . (is_array($value2) ? $value2[0] : $value2) . '</span></label></li>';
				}
				$ret .= '</fieldset>';
			}
			else
			{
				$ret .= '<li><label><input type="checkbox" name="' . $name . '[]" class="checkbox" value="' . $key . '"'. ($checked == $key ? " checked" : "") .'><span>' . (is_array($value) ? $value[0] : $value) . '</span></label></li>';
			}
		}*/

		foreach ($params as $value => $text)
		{
			if (is_array($text) && array_key_exists('name', $text))
			{
				$ret .= '<fieldset><legend><label><input type="checkbox" /><span>' . $text['name'] . '</span></label></legend>';
				foreach ($text['list'] as $value2 => $text2)
				{
					$ret .= '<li><label><input type="checkbox" name="' . $name . '[]" class="checkbox" value="' . $value2 . '"'. ($checked == $value2 ? " checked" : "") .'><span>' . (is_array($text2) ? $text2[0] : $text2) . '</span></label></li>';
				}
				$ret .= '</fieldset>';
			}
			else
			{
				$ret .= '<li><label><input type="checkbox" name="' . $name . '[]" class="checkbox" value="' . $value . '"'. ($checked == $value ? " checked" : "") .'><span>' . (is_array($text) ? $text[0] : $text) . '</span></label></li>';
			}
		}
		$ret .= '<li class="clear"></li></ul>';
		return $ret;
	}

	public static function htmlMainSearch($options)
	{
		$ret = '<div class="main_search" style="display:none"><form>';

		foreach ($options as $option)
		{
			$ret .= '<label for="search_' . $option['name'] . '" class="label_text">' . $option['label'] . '：</label>';
			$placeholder = isset($option['placeholder']) ? $option['placeholder'] : '';
			switch ($option['type'])
			{
				case 'textbox':
					$ret .= '<input class="textbox_1" type="text" name="' . $option['name'] . '" id="search_' . $option['name'] . '" placeholder="' . $placeholder . '"/>';
					break;
				case 'select':
					$ret .= '<select id="search_' . $option['name'] . '" name="' . $option['name'] . '" class="select_1">';
					if (array_key_exists('default', $option))
					{
						if (is_array($option['default']))
						{
							$ret .= '<option value="' . $option['default']['value'] . '" selected>' . $option['default']['name'] . '</option>';
						}
					}
					else
					{
						$ret .= '<option value="" selected>全部</option>';
					}
					if (array_key_exists('values', $option)) $ret .= self::html_options($option['values']);
					$ret .= '</select>';
					break;
			}
		}

		$ret .= '<a href="#" id="search" class="button_1 button_search">搜索 </a><a href="#" id="clear" class="button_1 button_clear">清除</a><div class="clear"></div></form></div>';

		return $ret;
	}

	public static function htmlTable1($columns, $action = 2)
	{
		$ret = '<div class="table_1"><table style="display:none"><tr>';
		foreach ($columns as $column)
		{
			$css = array_key_exists('css', $column) ? $column['css'] : '';
			$title = array_key_exists('title', $column) ? '<span class="tip_1" title="' . $column['title'] . '"></span>' : '';
			$ret .= '<th class="' . $css . '">' . $column['name'] . $title . '</th>';
		}
		if ($action) $ret .= '<th class="action">动作</th>';
		$ret .= '</tr></table><div class="paging"></div></div>';
		return $ret;
	}

	public static function htmlDialogNew($fields, $css = '')
	{
		$ret = '<div id="dlg_new" class="dialog_1 ' . $css . '"><h1></h1><form><table>';

		foreach ($fields as $field)
		{
			$values = array_key_exists('values', $field) ? $field['values'] : array();
			$newline = array_key_exists('newline', $field) ? $field['newline'] : true;
			$selected = array_key_exists('selected', $field) ? $field['selected'] : '';
			$placeholder = isset($field['placeholder']) ? $field['placeholder'] : '';

			if ($newline) $ret .= '<tr' . ($field['type'] == 'hidden' ? ' style="display:none"' : '') . '>';

			$ret .= '<td class="label"><label for="">' . $field['label'] . '：</label></td>';
			$ret .= '<td colspan="1">';
			switch ($field['type'])
			{
				case 'datetime':
					$ret .= '<input class="textbox_1 datetime_picker" type="text" name="' . $field['name'] . '" id="' . $field['name'] . '" placeholder="' . $placeholder . '"/>';
					break;
				case 'textbox':
					$ret .= '<input class="textbox_1" type="text" name="' . $field['name'] . '" id="' . $field['name'] . '" placeholder="' . $placeholder . '"/>';
					break;
				case 'password':
					$ret .= '<input class="textbox_1" type="password" name="' . $field['name'] . '" id="' . $field['name'] . '" placeholder="' . $placeholder . '"/>';
					break;
				case 'select':
					$ret .= '<select default="' . $selected . '" id="' . $field['name'] . '" name="' . $field['name'] . '" class="select_1"><option value="">请选择</option>';
					$ret .= self::html_options($values, $selected);
					$ret .= '</select>';
					break;
				case 'textarea':
					$ret .= '<textarea class="textbox_1" name="' . $field['name'] . '" id="' . $field['name'] . '" placeholder="' . $placeholder . '"/></textarea>';
					break;
				case 'checklist':
					$ret .= self::html_checklist($field['name'], $values);
					break;
				case 'file':
					$ret .= '<input class="textbox_1" type="file" name="' . $field['name'] . '" id="' . $field['name'] . '"/>';
					break;
				case 'date':
					$ret .= '<input class="textbox_1" type="date" name="' . $field['name'] . '" id="' . $field['name'] . '"/>';
					break;
				case 'radio':
					foreach ($field['radio_data'] as $value)
					{
						$ret .= $value['text'] . ' <input type="radio" name="' . $field['name'] . '" value="' . $value['value'] . '" id="' . $field['name'] . '">  ';
					}
					break;
				case 'empty':
					$ret .= '<div id="'. $field['name'] . '" class="empty"></div>';
					break;
				case 'hidden':
					$ret .= '<input id="'. $field['name'] . '" class="hidden" type="hidden" name="' . $field['name'] . '" value="' . (isset($field['value_hidden']) ? $field['value_hidden'] : '') . '" >';
					break;
			}
			$ret .= '</td><td class="error"></td>';

			if (!$newline) $ret .= '</tr>';
		}

		$ret .= '</table></form><div class="actions"><a href="#" id="submit" class="button_1 button_1_a submit">确定</a><a href="#" class="button_1 button_1_a close">取消</a></div><a href="#" class="close header_close"></a></div>';

		return $ret;
	}

	public static function htmlActions($options)
	{
		$title   = array_key_exists('title', $options) ? $options['title'] : '';
		$tooltip = array_key_exists('tooltip', $options) ? $options['tooltip'] : '';
		$action  = array_key_exists('action', $options) ? $options['action'] : '';
		$buttons = array_key_exists('buttons', $options) ? $options['buttons'] : array('add');

		$ret = '<div class="main_actions" style="display:none"><h1 class="header_1">' . $title;
		if (!empty($tooltip))
		{
			$ret .= '<span class="tip_1" title="' . $tooltip . '"></span>';
		}
		$ret .= '</h1>';

		foreach ($buttons as $button)
		{
			switch ($button)
			{
				case 'add':
					$ret .= '<a href="#" id="create" class="button_1 button_add">新建' . $action . '</a>';
					break;
				case 'import':
					$ret .= '<a href="#" id="create" class="button_1 button_import">导入' . $action . '</a>';
					break;
				case 'exportpdf':
					$ret .= '<a href="#" id="create" class="button_1 button_export button_exportpdf">导出数据(PDF)</a>';
					break;
				case 'export':
					$ret .= '<a href="#" id="create" class="button_1 button_export">导出数据(Excel)</a>';
					break;
			}
		}
		$ret .= '<div class="clear"></div></div>';
		return $ret;
	}

	public static function htmlNavigate($params)
	{
		$paths = explode('/', Request::path());
		$controller = $paths[0];

		$ret = '';

		foreach ($params as $param)
		{
			$role_text = $param[0];
			$roles = explode(' ', $role_text);
			$current = in_array($controller, $roles);

			$title = $param[1];
			$menus = $param[2];

			$adminer_roles = explode(',', Auth::user()->role);
			$has_menu = false;
			$dl = '<ul class="main ' . $role_text . ($current  ? ' current' : '') . '"><li><span>' . $title . '</span>';
			$dl .= '<ul class="popup">';
			foreach ($menus as $name => $href)
			{
				if (is_array($href))
				{
					$roles = explode(' ', $href[0]);
					$in_menu = false;
					foreach($adminer_roles as $role)
					{
						$role = str_replace(array('[', ']'), '', $role);
						if (in_array($role, $roles))
						{
							$in_menu = true;
						}
					}
					if ($in_menu == false)
					{
						continue;
					}

					$title = $href[1];
					$sub_menus = $href[2];
					$sub_dl = '<li class="haschild"><a>'.$title.'</a><ul class="popup subnav">';
					foreach ($sub_menus as $key2 => $href2)
					{
						$current_role = explode('/', $href2);
						$current_role = $current_role[1];
						$current_role = '[' . $current_role . ']';
						if (in_array($current_role, $adminer_roles))
						{
							$sub_dl .= '<li><a href="' . $href2 . '">' . $key2 . '</a></li>';
							$has_menu = true;
						}
					}
					$sub_dl .= '</ul></li>';
					if ($has_menu)
					{
						$dl .= $sub_dl;
					}
				}
				else
				{
					$current_role = explode('/', $href);
					$current_role = $current_role[1];
					$current_role = '[' . $current_role . ']';
					if (in_array($current_role, $adminer_roles))
					{
						$dl .= '<li><a href="' . $href . '">' . $name . '</a></li>';
						$has_menu = true;
					}
				}
			}
			$dl .= '</ul>';
			if ($has_menu) $ret .= $dl . '</ul></li>';
		}

		return $ret;
	}

	public static function headerLogin()
	{
		$ret_url = URL::full();
		if (starts_with(parse_url($ret_url, PHP_URL_HOST), 'user'))
		{
			$ret_url = '';
		}
		if (Auth::guest())
		{
			$html = '您好, 欢迎来到GP平台!&nbsp;&nbsp;&nbsp;<a href="http://user.' . app()->environment() . '/login?ret=' . $ret_url . '">登录</a>';
			if (ParamsService::get('register') == 1)
			{
				if (ParamsService::get('registerurl'))
				{
					$html .= ' | <a href="' . ParamsService::get('registerurl') . '" target="_blank">注册</a>';
				}
				else
				{
					$html .= ' | <a href="http://user.' . app()->environment() . '/register">注册</a>';
				}
			}
			return $html;
		}
		else
		{
			return Auth::user()->name . ', 欢迎您回来!&nbsp;&nbsp;&nbsp;' . ' <a href="http://user.' . app()->environment() . '/profile">用户信息</a> | <a href="http://user.' . app()->environment() . '/logout?ret=' . $ret_url . '">注销</a>';
		}
	}
}