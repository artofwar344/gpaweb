<script type="text/javascript">
	$(function() {

		var dlgTopManager =  $("#dlg_topmanager");
		var topManagerValidate = $("form", dlgTopManager).validate({
			errorClass: "error_1",
			errorPlacement: function(error, element) {
				error.appendTo(element.parent("td").next("td"));
			},
			rules: {
				name: {
					required: true
				}
			},
			messages: {
				name: {
					required: "账号不能为空"
				}
			}
		});

		dlgTopManager.on("dialogclose", function() {
			topManagerValidate.resetForm();
			$(".error_1", dlgTopManager).removeClass("error_1");
			$("#retrievecount", dlgTopManager).val("");
		});



		var getTopManager = function(eid) {
			$.post("/customer/gettopmanager", {"eid": eid}, function(ret) {
				$("#eid", dlgTopManager).val(eid);
				var customerRoles = "";
				switch (ret.status) {
					case 1:
						var manager = ret.manager;
						customerRoles = manager.role;
						$("#managerid", dlgTopManager).val(manager.managerid);
						$("#department", dlgTopManager).prop("disabled", true).val(manager.department_name);
						$("#name", dlgTopManager).val(manager.manager_name);
						$("#password", dlgTopManager).val("");
						break;
					case 2:
						$("#managerid", dlgTopManager).val("");
						$("#department", dlgTopManager).prop("disabled", false).val("");
						$("#name", dlgTopManager).val("");
						$("#password", dlgTopManager).val("");
						break;
					case 3:
						$("#managerid", dlgTopManager).val("");
						$("#department", dlgTopManager).prop("disabled", true).val(ret.department_name);
						$("#name", dlgTopManager).val("");
						$("#password", dlgTopManager).val("");
						break;
				}

				$("#roles", dlgTopManager).html("");

				$.each(ret.roles, function(index, value){
					if (jQuery.type(value) == "string") {

						var checkbox = $("<input/>")
							.attr({ "type": "checkbox", "name": "role[]" })
							.addClass("checkbox")
							.val(index)
							.click(function() {
								if ($(this).is(":checked")) $(this).next().addClass("checked");
								else $(this).next().removeClass("checked");
							});
						var span_roleName = $("<span/>").text(value);
						if (customerRoles.indexOf(index) >= 0){
							checkbox.prop("checked", true);
							span_roleName.addClass("checked");
						}

						var li = $("<li/>").append(
							$("<label/>")
								.append(checkbox)
								.append(span_roleName)
						);
						$("#roles", dlgTopManager).append(li);
					}
					else {
						var fieldset = $("<fieldset/>");
						var topcheckbox = $("<input/>")
							.attr({ "type": "checkbox" })
							.click(function() {
								var options = $(this).closest("fieldset").find("li");
								if ($(this).is(":checked")) {
									options.find("input").prop("checked", true);
									options.find("span").addClass("checked");
								}
								else {
									options.find("input").prop("checked", false);
									options.find("span").removeClass("checked");
								}
							});
						var legend = $("<legend/>")
							.append(
								$("<label/>")
									.append(topcheckbox)
									.append($("<span/>").text(value.name))
							)
						fieldset.append(legend);
						$.each(value.list, function(role, roleName){
							var checkbox = $("<input/>")
								.attr({ "type": "checkbox", "name": "role[]" })
								.addClass("checkbox")
								.val(role)
								.click(function() {
									if ($(this).is(":checked")) $(this).next().addClass("checked");
									else $(this).next().removeClass("checked");
								});
							var span_roleName = $("<span/>").text(roleName)

							if (customerRoles.indexOf(role) >= 0){
								checkbox.prop("checked", true);
								span_roleName.addClass("checked");
							}
							var li = $("<li/>")
								.append(
									$("<label/>")
										.append(checkbox)
										.append(span_roleName)
								);
							fieldset.append(li);
						});
						$("#roles", dlgTopManager).append(fieldset);
					}
				});
				$("#roles", dlgTopManager).append($("<li/>").addClass("clear"));
				dlgTopManager.dialog({ width: '650px' })
				dlgTopManager.dialog("open");
			}, "json");
		};

		$(".submit", dlgTopManager).click(function() {
			var data = $("#form_topmanager", dlgTopManager).serializeArray();
			if ($("form", dlgTopManager).valid()) {
				$.post("/customer/updatetopmanager", data, function(ret) {
					dlgTopManager.dialog("close");
					backend.list();
				});
			}
		});

		var updateStatus = function(eid) {
			$.post("/customer/status", { "eid": eid }, function() {
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

		var createDatabase = function(eid) {
			$.post("/createdatabase", { "eid": eid }, function() {
				backend.list();
			});
		};

		var createEnable = function(row) {
			return row["database_status"] == "2";
		};
		var settingEnable = function(row) {
			return row["database_status"] == "1";
		};

		var databaseStatusValueClass = function(row) {
			var ret = "";
			switch (parseInt(row["database_status"])) {
				case 1:
					ret = "green";
					break;
				case 2:
					ret = "red";
					break;
			}

			return ret;
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

		var backend = $.backend({
			tableStructure: {
				eid: "customerid",
				columns: [
					{ "key": "customerid", "header": "编号", "class": "number" },
					{ "key": "name", "header": "名称" },
					{ "key": "alias", "header": "别名" },
					{ "key": "module_text", "header": "模块" },
					{ "key": "database_status_text", "header": "数据库状态", "class": "state", "valueclass": databaseStatusValueClass },
					{ "key": "status_text", "header": "客户状态", "class": "state", "valueclass": statusValueClass },
					{ "key": "createdate", "header": "创建日期", "class": "time" }
				]
			},
			selects: [ "organizeid" ],
			category: "客户",
			operators: [
				"modify",
				{ text: "创建数据库", type: "callback", css: "btn_modify", callback: createDatabase, enable: createEnable, confirm: true, confirmText: "你确定要创建数据库吗？" },
				{ type:'iframe', url: "/customersetting?id={eid}", text: "参数设置", enable: settingEnable, css: "btn_view", width: "800px", height: "650px" },
				{ type:'iframe', url: "/keyassign?id={eid}", text: "密钥管理", enable: settingEnable, css: "btn_view", width: "90%", height: "650px" },
				{ type: "callback", callback: getTopManager, text: "顶级管理员", css: "btn_modify", enable: settingEnable, "tip": "编辑查看顶级管理员信息" },
				{ type: "callback", callback: updateStatus, text: getText, css: "btn_switch" },
				"delete"
			],
			modifyStructure: { name: "name", alias: "alias", module: "[module]", status: "status" },
			modifyDialogWidth: 400,
			validateRule: {
				name: {
					required: true,
					maxlength: 64
				},
				alias: {
					required: true,
					maxlength: 64,
					lettersonly: true
				},
				view:
				{
					required: true
				},
				organizeid:
				{
					required: true
				},
				status: {
					required: true
				}
			},
			validateMessages: {
				name: {
					required: "名称不能为空",
					maxlength: "名称长度不得超过64"
				},
				alias: {
					required: "别名不能为空",
					maxlength: "别名长度不得超过64",
					lettersonly: "别名只能是英文字母"
				},
				view: {
					required: "运营商不能为空"
				},
				organizeid: {
					required: "机构不能为空"
				},
				status: {
					required: "状态不能为空"
				}
			}
		});
		jQuery.validator.addMethod("lettersonly", function(value, element) {
			return this.optional(element) || /^[a-zA-Z]+$/i.test(jQuery.trim(value));
		}, "只能是英文字母");

	});
</script>

@actions (array('title' => '客户管理', 'action' => '客户'))

@search
	array('label' => '名称', 'type' => 'textbox', 'name' => 'name')
@endsearch



@dialog
	array('label' => '名称', 'type' => 'textbox', 'name' => 'name'),
	array('label' => '别名', 'type' => 'textbox', 'name' => 'alias'),
	array('label' => '模块', 'type' => 'checklist', 'name' => 'module', 'values' => Ca\Consts::$module_texts),
	array('label' => '机构', 'type' => 'select', 'name' => 'organizeid'),
	array('label' => '状态', 'type' => 'select', 'name' => 'status', 'values' => Ca\Consts::$customer_status_texts)
@enddialog


<div id="dlg_topmanager" class="dialog_1">
	<h1>顶级管理员</h1>
	<form id="form_topmanager">
		<input type="hidden" id="eid" name="eid">
		<input type="hidden" id="managerid" name="managerid">
		<table>
			<tr>
				<td class="label"><label for="">部门：</label></td>
				<td colspan="1"><input type="text" class="textbox_1 disabled" id="department" name="department_name" placeholder="顶级部门名称" disabled /></td>
				<td class="error"></td>
			</tr>
			<tr>
				<td class="label"><label for="">账号：</label></td>
				<td colspan="1"><input type="text" class="textbox_1 disabled" id="name" name="name" placeholder="登录账号"/></td>
				<td class="error"></td>
			</tr>
			<tr>
				<td class="label"><label for="">密码：</label></td>
				<td colspan="1"><input class="textbox_1" type="text" id="password" name="password" placeholder="密码"/></td>
				<td class="error"></td>
			</tr>
			<tr>
				<td class="label"><label for="">权限：</label></td>
				<td colspan="1"><ul id="roles" class="checklist"><ul/></td>
				<td class="error"></td>
			</tr>
		</table>
	</form>
	<div class="actions"><a href="#" class="button_1 button_1_a submit">确定</a><a href="#" class="button_1 button_1_a close">取消</a></div><a href="#" class="close header_close"></a></div>
</div>