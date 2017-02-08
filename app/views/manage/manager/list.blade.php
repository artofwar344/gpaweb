<script type="text/javascript">
	$(function() {
		$.backend({
			tableStructure: { eid: "managerid", struct: ["managerid", "name", "customer_name", "department_name", "role_text", "status_text", "createdate"] },
			category: "管理员",
			operators: { name: "name", departmentid: "departmentid", status: "status", role: "[role]" },
			selects: [ "departmentid" ],
			modifyStructure: { name: "name", departmentid: "departmentid", status: "status", role: "[role]" },
			modifyDialogWidth: 600,
			validateRule: {
				departmentid: {
					required: true
				},
				name: {
					required: true,
					maxlength: 64
				},
				status: {
					required: true
				},
				password: {
					required: true
				}
			},
			modifyUnvalidateRules: [ "password" ],
			validateMessages: {
				name: {
					required: "名称不能为空",
					minlength: "名称长度不得超过64"
				},
				departmentid: {
					required: "请选择所属部门"
				},
				status: {
					required: "请选择状态"
				},
				password: {
					required: "密码不能为空"
				}
			}
		});
	});
</script>
@actions (array('title' => '管理员管理', 'action' => '管理员'))
@search
	array('label' => '名称', 'type' => 'textbox', 'name' => 'name'),
	array('label' => '所属部门', 'type' => 'select', 'name' => 'departmentid'),
	array('label' => '包含权限', 'type' => 'select', 'name' => 'role', 'values' => Consts::$manager_role_texts),
	array('label' => '状态', 'type' => 'select', 'name' => 'status', 'values' => Consts::$manager_status_texts)
@endsearch

@table
	array('name' => '编号', 'css' => 'number'),
	array('name' => '名称'),
	array('name' => '所属客户'),
	array('name' => '所属部门'),
	array('name' => '权限'),
	array('name' => '状态', 'css' => 'state'),
	array('name' => '创建时间', 'css' => 'time')
@endtable

@dialog
	array('label' => '所属部门', 'type' => 'select', 'name' => 'departmentid'),
	array('label' => '名称', 'type' => 'textbox', 'name' => 'name'),
	array('label' => '密码', 'type' => 'textbox', 'name' => 'password'),
	array('label' => '权限', 'type' => 'checklist', 'name' => 'role', 'values' => Consts::$manager_role_texts),
	array('label' => '状态', 'type' => 'select', 'name' => 'status', 'values' => Consts::$manager_status_texts)
@enddialog