<script type="text/javascript">
$(function() {
	var backend = $.backend({
		tableStructure: {
			eid: "infoid",
			columns: [
				{ "key": "infoid", "header": "编号", "css": "number" },
				{ "key": "id", "header": "工号" },
				{ "key": "college", "header": "所在学院部处", "headertip": "所在学院部处" },
				{ "key": "grade", "header": "所在科室年级", "headertip": "所在科室年级" }
			]
		},
		category: "用户信息",
		modifyStructure: { id: "id", college: "college", grade: "grade" },
		operators: [
			{ type: "modify", tip: "编辑用户信息", text: "编辑", css: "btn_modify" },
			{ type: "delete", tip: "删除用户信息",  text: "删除", css: "btn_delete" }
		],
		validateRule: {
			id: {
				required: true,
				maxlength: 64
			},
			college: {
				required: true,
				maxlength: 128
			},
			grade: {
				required: true,
				maxlength: 128
			}
		},
		validateMessages: {
			id: {
				required: "工号不能为空",
				maxlength: "工号长度不得超过64"
			},
			college: {
				required: "学院不能为空",
				maxlength: "学院长度不得超过128"
			},
			grade: {
				required: "科室年级不能为空",
				maxlength: "科室年级长度不得超过128"
			}
		}
	});

	var dlgImport = $(".dlg_import");
	var formImport = dlgImport.find("form");
	var fileSeletor = $("tr:first", formImport);
	var preview = $(".preview_list", dlgImport).hide();
	var btnPreview = $(".preview", dlgImport);
	var btnSubmit = $(".submit", dlgImport);
	dlgImport.dialog("option", "width", 350)
		.find(".preview").click(function() {
			if (!formImport.valid()) return false;
			if ($(this).hasClass("gray")) return false;
			$(this).addClass("gray");
			var self = this;
			var file = $(".button_import", dlgImport)[0].files[0];
			$(".error_list", dlgImport).html("");
			preview.text("");
			if (file != null)
			{
				$.ajax({
					url: "/userinfo/importpreview",
					type: "POST",
					dataType: "json",
					data: file,
					processData: false,
					success: function(data) {
						$(self).removeClass("gray");
						btnPreview.hide();
						btnSubmit.show();
						var table = $("<table />");
						var form_data = $("<div />").addClass("form_data");
						$.each(data.list, function(i, item) {
							var tr = $("<tr />");
							$("<td />").text(item[0]).appendTo(tr);
							$("<td />").text(item[1]).appendTo(tr);
							$("<td />").text(item[2]).appendTo(tr);
							tr.appendTo(table);
						});
						form_data.appendTo(formImport);
						table.appendTo(preview);
						preview.show();
						fileSeletor.hide();
					}
				});
			}
			return false;
		}
	);
	btnSubmit.click(function() {
		var file = $(".button_import", dlgImport)[0].files[0];
		$(".button_import", dlgImport).val("");
		if (file != null) {
			$.ajax({
				url: "/userinfo/import",
				type: "POST",
				dataType: "json",
				data: file,
				processData: false,
				success: function(data) {
					$(self).removeClass("gray");
					if (data.errors.length > 0) {
						var ulError = $("<ul />");
						$.each(data.errors, function(i, error) {
							var errorText = '';
							switch (error.code)
							{
								case 1:
									errorText = "数据格式错误!";
									break;
								case 2:
									errorText = "工号已存在!";
									break;
								case 3:
									errorText = "未知错误!";
									break;
							}
							$("<li />").text("第 "+ error.line + " 行: " + errorText).appendTo(ulError);
						});
						if (data.errors.length != data.count) backend.list();
						$(".info", dlgImport).html(
							"导入</strong>" + data.count +
								"</strong>个用户信息" + ", <span class='red'><strong>" + data.errors.length + "</strong>个导入失败</span>! 错误如下: ");
						if (data.errors.length > 0) ulError.appendTo($(".error_list", dlgImport));
					} else {
						backend.list();
						$(".info", dlgImport).html(
							"成功导入</strong>" + data.count + "</strong>个用户!");
					}
					preview.hide();
					btnSubmit.hide();
					formImportReset();
				}
			});
		}
	});
	$(".main_actions .button_import").click(function() {
		dlgImport.find(".result").html("");
		formImportValidate.resetForm();
		$("input", formImport).removeClass("error_1");
		dlgImport.dialog("open");
		formImportReset();
		$(".info", dlgImport).html("选择用户列表文件批量导入用户!<p class='more_info'>[文件格式: <strong>csv</strong>, 文件编码: <strong>gbk</strong>] (<a href=\"{{ Config::get('app.asset_url') }}files/userinfo_import_sample.csv\">样本</a>)</p>");
		$(".error_list", dlgImport).html("");
		return false;
	});

	var formImportReset = function() {
		preview.hide();
		fileSeletor.show();
		btnSubmit.hide();
		btnPreview.show();
	};

	var formImportValidate = formImport.validate({
		errorClass: "error_1",
		errorPlacement: function(error, element) {
			error.appendTo(element.parent("td").next("td"));
		},
		rules: {
			csv_import: "required"
		},
		messages: { csv_import: "请选择一个csv文件" }
	});


});
</script>


<div class="dialog_1 dlg_import">
	<h1>导入用户信息</h1>
	<div class="info info_1"></div>
	<div class="preview_list" style="overflow-y:auto; background:white; border:1px solid #888; margin:10px; height:100px;"></div>
	<div class="error_list"></div>
	<form>
		<table>
			<tr>
				<td class="label"><label>csv文件:</label></td>
				<td><input type="file" name="csv_import" class="textbox_1 textbox_1_file button_import" required="true" accept=".csv" style="*width:248px" /></td>
				<td class="error"></td>
			</tr>
		</table>
	</form>
	<div class="actions">
		<a href="#" class="button_1 button_1_a preview">预览</a>
		<a href="#" class="button_1 button_1_a submit">提交</a>
		<a href="#" class="button_1 button_1_a close">取消</a>
	</div>
	<a href="#" class="close header_close"></a>
</div>

@actions (array('title' => '用户信息管理', 'action' => '用户信息', 'buttons' => array('add', 'import')))

@search
array('label' => '工号', 'type' => 'textbox', 'name' => 'id', 'placeholder' => '工号'),
@endsearch

@dialog
array('label' => '工号', 'type' => 'textbox', 'name' => 'id', 'placeholder' => '用户工号'),
array('label' => '学院', 'type' => 'textbox', 'name' => 'college', 'placeholder' => '所在学院部处'),
array('label' => '科室年级', 'type' => 'textbox', 'name' => 'grade', 'placeholder' => '所在科室年级'),
@enddialog