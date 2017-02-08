<script type="text/javascript">
	var _pageIndex = 1;
	var _tableStructure = { eid: "departmentid", struct: ["departmentid", "name", "customer_name", "count", "createdate"]  };
	var _category = "部门";
	var _selects = [ "customerid" ];
	var _modifyStructure = { name: "name", customerid: "customerid" };
	var _validateRule = {
		name: {
			required: true,
			maxlength: 64
		},
		customerid: {
			required: true
		}
	};

	var _validateMessages = {
		name: {
			required: "名称不能为空",
			minlength: "名称长度不得超过64"
		},
		customerid: {
			required: "请选择所属客户"
		}
	};
</script>

@actions ('部门管理', '部门')

@search
	array('label' => '名称', 'type' => 'textbox', 'name' => 'name'),
	array('label' => '所属客户', 'type' => 'select', 'name' => 'customerid')
@endsearch

@table
	array('name' => '编号', 'css' => 'number'),
	array('name' => '名称'),
	array('name' => '所属客户'),
	array('name' => '管理员量', 'css' => 'count'),
	array('name' => '创建时间', 'css' => 'time')
@endtable

@dialog
	array('label' => '名称', 'type' => 'textbox', 'name' => 'name'),
	array('label' => '所属客户', 'type' => 'select', 'name' => 'customerid', 'values' => array())
@enddialog