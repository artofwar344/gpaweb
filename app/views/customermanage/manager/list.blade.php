<script type="text/javascript">
//	$(function() {
//		$(document).on("list", function(e, rows) {
//			var step;
//			if (rows.entityCount == 0) {
//				step = $(".button_add").attr("data-intro", "点击这里添加管理员")
//			} else {
//				for (i in rows) {
//					if (rows[i].manager_count == 0 && rows == 1) {
//						step = $(".btn_view:eq(0)").attr("data-intro", "点击这里添加管理员");
//					}
//				}
//			}
//			if (step) {
//				step.attr("data-step", "1").attr("data-position", "right");
//				step.click(function() {
//					intro.exit();
//				});
//				var intro = introJs().setOptions({"skipLabel":"确定", "showStepNumbers":false}).start();
//			}
//		});
//	});
</script>
<script type="text/javascript">
	var visible = @if (Input::get("inner")) false @else true @endif ;
	var disabledFields = [@if (Input::get("inner")) "departmentid" @endif];
	var defaultValues = {{ $department_id ? '{ "departmentid": ' . $department_id . ' }' : "{}" }};
	$(function() {
		var updateStatus = function(eid) {
			$.post("/manager/status", { "eid": eid }, function() {
				backend.list();
			});
		};

		var getText = function(row) {
			switch (row["status"] >> 0) {
				case 1:
					return [ "禁用", true ];
				case 2:
					return [ "启用", false ];
			}
			return "";
		};

		var updateStatusTip = function(row) {
			switch (row["status"] >> 0) {
				case 1:
					return "修改管理员状态为\"禁用\"<br/><span class='subtip_1'>禁用后, 该管理员将不能登录管理后台</span>";
				case 2:
					return "修改管理员状态为\"正常\"<br/><span class='subtip_1'>启用后, 管理员可以正常登录后台, 并且拥有分配权限</span>";
			}
			return "";
		};

		var statusValueClass = function(row) {
			var ret = "";
			switch (parseInt(row["status"])) {
				case 1:
					ret = "green";
					break;
				case 2:
					ret = "red";
					break;
			}
			return ret;
		};
		var deleteEnable = function(row) {
			return row["assign_count"] == 0;
		};

		var backend = $.backend({
			listParams: { "departmentid": "{{ $department_id }}" },
			tableStructure: {
				eid: "managerid",
				columns: [
					{ "key": "managerid", "header": "编号", "class": "number" },
					{ "key": "name", "header": "账号", "headertip": "管理员登录账号" },
					{ "key": "departmentid_text", "header": "所属部门", "headertip": "管理员所属部门<br/><span class='subtip_1'>该管理员只能管理该部门相关信息</span>" },
					{ "key": "role_text", "header": "权限", "headertip": "管理员所有权限" },
					{ "key": "assign_count", "header": "分配用户激活", "class": "count", "headertip": "该管理员分配给直属部门所有用户激活数总量" },
					{ "key": "status_text", "header": "状态", "class": "state", "headertip": "<strong>正常</strong>: 管理员可以正常后台管理<br/><strong>锁定</strong>: 管理员不能登录后台管理", "valueclass": statusValueClass },
					{ "key": "createdate", "header": "创建时间", "class": "time" }
				]
			},
			category: "管理员",
			operators: [
				{ type: "callback", callback: updateStatus, text: getText, css: "btn_switch", tip: updateStatusTip },
				{ type: "modify", tip: "编辑管理员信息", text: "编辑", css: "btn_modify" },
				{ type: "delete", tip: "删除管理员", enable: deleteEnable, text: "删除", css: "btn_delete" }
			],
			selects: [ "departmentid" ],
			modifyStructure: { name: "name", departmentid: "departmentid", status: "status", role: "[role]" },
			modifyDialogWidth: 700,
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
			newDisabledFields: disabledFields,
			modifyDisabledFields: disabledFields,
			actionDisabledFields: disabledFields,
			newDefaultValues: defaultValues,
			searchDefaultValues: defaultValues,
			validateMessages: {
				name: {
					required: "名称不能为空",
					maxlength: "名称长度不得超过64"
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
@actions (array('title' => (empty($department_name) ? '管理员管理' : '部门: ' . $department_name ), 'action' => '管理员'))

@search
	array('label' => '账号', 'type' => 'textbox', 'name' => 'name', 'placeholder' => '管理员账号'),
	array('label' => '所属部门', 'type' => 'select', 'name' => 'departmentid'),
	array('label' => '包含权限', 'type' => 'select', 'name' => 'role', 'values' => Ca\Service\PermissionService::all()),
	array('label' => '状态', 'type' => 'select', 'name' => 'status', 'values' => Ca\Consts::$manager_status_texts)
@endsearch

@dialog
	array('label' => '所属部门', 'type' => 'select', 'name' => 'departmentid'),
	array('label' => '账号', 'type' => 'textbox', 'name' => 'name', 'placeholder' => '管理员登录账号'),
	array('label' => '密码', 'type' => 'password', 'name' => 'password', 'placeholder' => '管理员登录密码'),
	array('label' => '权限', 'type' => 'checklist', 'name' => 'role', 'values' => Ca\Service\PermissionService::all()),
	array('label' => '状态', 'type' => 'select', 'name' => 'status', 'values' => Ca\Consts::$manager_status_texts)
@enddialog