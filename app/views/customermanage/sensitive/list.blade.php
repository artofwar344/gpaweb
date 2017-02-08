<script type="text/javascript">

String.prototype.endWith = function(str) {
	if (str == null || str == "" || this.length == 0 || str.length > this.length)
		return false;
	if (this.substring(this.length - str.length) == str)
		return true;
	else
		return false;
	return true;
};

$(function() {
	var backend = $.backend({
		tableStructure: {
			eid: "wordid",
			columns: [
				{ "key": "wordid", "header": "编号", "class": "number" },
				{ "key": "word", "header": "敏感词" }
			]
		},
		category: "敏感词",
		operators: [
			{ type: "modify", tip: "编辑敏感词", text: "编辑", css: "btn_modify" },
			{ type: "delete", tip: "删除敏感词", text: "删除", css: "btn_delete" }
		],
		modifyStructure: { word: "word" },
		modifyDialogWidth: 280,
		validateRule: {
			word: { required: true, maxlength:32 }
		},
		validateMessages: {
			word: {
				required: "敏感词不能为空",
				maxlength: "敏感词长度不能超过32个字符"
			}
		}

	});

	//导入敏感词
	var dlgImport = $(".dlg_import");
	var formImport = dlgImport.find("form");
	var btnSubmit = $(".submit", dlgImport);
	var fileButton = $(":file[name='importfile']" ,formImport)

	dlgImport.dialog("option", "width", 350);

	$(".main_actions .button_import").click(function() {
		$("input", formImport).removeClass("error_1");
		$(".info", dlgImport).html("选择文件批量导入敏感词!<p class='more_info'>[文件格式: <strong>txt</strong>, 文件编码: <strong>gbk</strong>] (<a href=\"{{ Config::get('app.asset_url') }}files/sensitive_import_sample.rar\">样本</a>)</p>");
		$(".error_list", dlgImport).html("");
		dlgImport.dialog("open");
		return false;
	});
	fileButton.change(function() {
		if (!$(this).val().endWith('.txt')) {
			$(".error_list", dlgImport).html('<span class="red">文件格式错误, 请选择txt格式的文件！</span>');
			btnSubmit.addClass("button_1_disabled");
		} else {
			$(".error_list", dlgImport).html("");
			btnSubmit.removeClass("button_1_disabled");
		}
		return false;
	});
	btnSubmit.click(function() {
		if ($(this).hasClass("button_1_disabled")) return false;
		$(this).addClass("button_1_disabled");
		var file = fileButton[0].files[0];
		$(".info", dlgImport).html("正在导入...");
		$.ajax({
			url: "/sensitive/import",
			type: "POST",
			dataType: "json",
			data: file,
			processData: false,
			success: function(data) {
				console.log(data);
				$(".info", dlgImport).html("敏感词导入结束, 已存在" + data['existcount'] + "个, 成功导入" + data['newcount'] + "个");
				backend.list();
			}
		});
		return false;
	});

});

</script>

<div class="dialog_1 dlg_import">
	<h1>导入敏感词</h1>
	<div class="info info_1"></div>
	<div class="error_list"></div>
	<form>
		<table>
			<tr>
				<td class="label"><label>txt文件:</label></td>
				<td><input type="file" name="importfile" class="textbox_1 textbox_1_file button_file" required="true" accept=".txt" style="*width:248px" /></td>
				<td class="error"></td>
			</tr>
		</table>
	</form>
	<div class="actions">
		<a href="#" class="button_1 button_1_a submit button_1_disabled">导入</a>
		<a href="#" class="button_1 button_1_a close">取消</a>
	</div>
	<a href="#" class="close header_close"></a>
</div>
@actions (array('title' => '敏感词管理', 'action' => '敏感词', 'buttons' => array('add', 'import')))

@search
array('label' => '名称', 'type' => 'textbox', 'name' => 'name')
@endsearch

@dialog
array('label' => '敏感词', 'type' => 'textbox', 'name' => 'word')
@enddialog