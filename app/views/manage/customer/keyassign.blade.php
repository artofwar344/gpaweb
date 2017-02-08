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
	var dialogwidth = 400;
	var edit = function(eid) {
//		console.log(eid);
		$.post("/keyassign/keyinfo", { "eid": eid, "customerid": "{{ $customerid }}" }, function(ret) {
			console.log(ret);
			var key = ret.key;
			switch (key.type) {
				case "定时激活":
				case "永久激活":
					$("form :hidden[name='eid']", dialogEditMicrosoft).val(eid);
//					console.log(ret.key);
					$.each(key, function(index, value) {
						$("form :hidden[name='" + index + "']", dialogEditMicrosoft).val(value);
					});
					dialogEditMicrosoft.dialog("open");
					break;
				case "手动激活":
				case "零售激活":
					$("form :hidden[name='eid']", dialogEditAdobe).val(eid);
					$.each(key, function(index, value) {
						$("form :hidden[name='" + index + "']", dialogEditAdobe).val(value);
					});
					dialogEditAdobe.dialog("open");
					break;
			}
		}, "json");
	};

	var backend = $.backend({
		listParams: { "id": '{{ $customerid }}' },
		getParams: { "id": '{{ $customerid }}' },
		deleteParams: { "id": '{{ $customerid }}' },
		selectsParams: { "id": '{{ $customerid }}' },
		tableStructure: {
			eid: "keyid",
			columns: [
				{ "key": "keyid", "header": "编号", "class": "number" },
				{ "key": "name", "header": "名称" },
				{ "key": "section", "header": "密钥片段" },
				{ "key": "product_name", "header": "所属商品" },
				{ "key": "product_type", "header": "激活模式" },
				{ "key": "server", "header": "激活服务器" },
				{ "key": "count", "header": "激活总量", "class": "number" },
				{ "key": "note", "header": "备注", "class": "text" },
				{ "key": "createdate", "header": "分配日期", "class": "time" }
			]
		},
		category: "密钥",
		selects: [ "productid" ],
		operators: [
//			"modify",
			{ type: "callback", callback: edit, text: "编辑", css: "btn_modify" },
			"delete"
		],
		modifyStructure: { productid:"productid", name: "name", product_name: "product_name", count: "count", key: "key", note: "note", server: "server" },
		modifyDialogWidth: dialogwidth,
		validateRule: {
			productid: {
				required: true
			},
			name: {
				required: true,
				maxlength: 64
			},
			count: {
				required: true,
				number: true,
				min: 0
			},
			key: {
				required: true
			},
			section: {
				required: true
			},
			note: {
				required: true
			}
		},
		validateMessages: {
			productid: {
				required: "请选择产品"
			},
			name: {
				required: "名称不能为空",
				maxlength: "名称长度不得超过64"
			},
			count: {
				required: "数量不能为空",
				number: "数量必须为正整数",
				min: "数量必须为正整数"
			},
			key: {
				required: "key不能为空"
			},
			section: {
				required: "section不能为空"
			},
			note: {
				required: "描述不能为空"
			}
		}

	});


	var table = $("#dlg_new table");
	var form = $("#dlg_new form");
	var adobeinput = $(".adobeinput tr");
	var microsoftinput = $(".microsoftinput tr");

	var actions = $("#dlg_new .actions");
	var importview = $('<a id="import" class="button_1 button_1_a import" href="#">确定</a>');

	var dialogEditMicrosoft = $("#dialogEditMicrosoft");
	var dialogEditAdobe = $("#dialogEditAdobe");
	var dialogParams = { autoOpen: false, modal: true, resizable: false, width:600, height: "auto", minHeight: 0 };
	dialogEditMicrosoft.dialog(dialogParams);
	dialogEditAdobe.dialog(dialogParams);

	actions.prepend(importview.hide());
	var submit = $(".submit", actions);

	$(".button_add").click(function() {
		$("tr:gt(3)", table).remove();
	});

	$("#productid").change(function() {
		$("tr:gt(3)", table).remove();
		var productName = $("option:selected").text();
		if (productName.endWith("[手动激活]")|| productName.endWith("[零售激活]")) {
			table.append(adobeinput);
			submit.hide();
			importview.show();
		} else if (productName.endWith("[定时激活]") || productName.endWith("[永久激活]")) {
			table.append(microsoftinput);
			submit.show();
			importview.hide();
		}
	});

	$(".button_import").change(function() {
		var error = $(".error", $(this).closest("tr"));
		if (!$(this).val().endWith('.csv')) {
			error.html('<label class="error_1" for="name">文件格式错误, 请选择csv格式的文件！</label>');
			importview.addClass("button_1_disabled");
		} else {
			error.html("");
			importview.removeClass("button_1_disabled");
		}
		return false;
	});

	importview.click(function() {
		if ($(this).hasClass("button_1_disabled")) return false;
		var formData = new FormData(form[0]);
		$.ajax({
			url: "/keyassign/newadobe",
			type: 'POST',
			dataType: "json",
			data: formData,
			contentType: false,
			processData: false,
			success: function (data) {
//				console.log(data);
				backend.list();
				$("#dlg_new").dialog("close");
			}
		});

	});

	dialogEditMicrosoft.dialog("option", "width", dialogwidth);
	dialogEditAdobe.dialog("option", "width", dialogwidth);

	$('.actions .submit', dialogEditMicrosoft).click(function() {
		$.post("/keyassign/updatemicrosoft", $("form", dialogEditMicrosoft).serialize(), function(ret) {
			dialogEditMicrosoft.dialog("close");
			backend.list();
		}, "json");
		return false;
	});

	$('.actions .submit', dialogEditAdobe).click(function() {
		$.post("/keyassign/updateadobe", $("form", dialogEditAdobe).serialize(), function(ret) {
			dialogEditAdobe.dialog("close");
			backend.list();
		}, "json");
		return false;
	});


});
</script>
<table style="display:none">
	<tbody class="adobeinput">
		<tr>
			<td colspan="3" class="info">
				选择密钥列表文件批量导入密钥!
				<p class="more_info">
					[文件格式:<strong>csv</strong>, 文件编码:<strong>gbk</strong>] (<a href="{{ \Config::get('app.asset_url') }}files/key_import_sample.csv">样本</a>)
				</p>
			</td>
		</tr>
		<tr>
			<td class="label"><label for="">密钥文件:</label></td>
			<td colspan="1"><input class="textbox_1 textbox_1_file button_import" name="importfile"  type="file" style="*width:248px" accept=".csv" required="true"></td>
			<td class="error"></td>
		</tr>
	</tbody>

	<tbody class="microsoftinput">
		<tr>
			<td class="label"><label for="">密钥：</label></td>
			<td colspan="1"><input class="textbox_1" type="text" placeholder="" name="key"></td>
			<td class="error"></td>
		</tr>
		<tr>
			<td class="label"><label for="">激活服务器：</label></td>
			<td colspan="1"><input class="textbox_1" type="text" placeholder="" name="server"></td>
			<td class="error"></td>
		</tr>
		<tr>
			<td class="label"><label for="">激活总量：</label></td>
			<td colspan="1"><input class="textbox_1" type="text" placeholder="" name="count"></td>
			<td class="error"></td>
		</tr>
	</tbody>
</table>

<div class="dialog_1" id="dialogEditMicrosoft">
	<h1>编辑密钥</h1>
	<form>
		<input type="hidden" name="eid" />
		<input name="customerid" type="hidden" value="{{ $customerid }}" />
		<table>
			<tr>
				<td class="label" style="width:60px"><label for="">名称：</label></td>
				<td colspan="1"><input name="name" class="textbox_1" type="text" /> </td>
				<td class="error"></td>
			</tr>
			<tr>
				<td class="label"><label for="">所属商品：</label></td>
				<td colspan="1"><input disabled="disabled" name="product_name" class="textbox_1" type="text" /> </td>
				<td class="error"></td>
			</tr>
			<tr>
				<td class="label"><label for="">备注：</label></td>
				<td colspan="1"><input name="note" class="textbox_1" type="text" /> </td>
				<td class="error"></td>
			</tr>
			<tr>
				<td class="label"><label for="">密钥：</label></td>
				<td colspan="1"><input name="key" class="textbox_1" type="text" /> </td>
				<td class="error"></td>
			</tr>
			<tr>
				<td class="label"><label for="">激活服务器：</label></td>
				<td colspan="1"><input name="server" class="textbox_1" type="text" /> </td>
				<td class="error"></td>
			</tr>
			<tr>
				<td class="label"><label for="">激活总量：</label></td>
				<td colspan="1"><input name="count" class="textbox_1" type="text" /> </td>
				<td class="error"></td>
			</tr>
		</table>
	</form>
	<div class="actions">
		<a href="#" class="button_1 button_1_a submit"><span>确定</span></a>
		<a class="button_1 button_1_a close" href="#">取消</a>
	</div>
	<a class="close header_close" href="#"></a>
</div>

<div class="dialog_1" id="dialogEditAdobe">
	<h1>编辑密钥</h1>
	<form>
		<input type="hidden" name="eid" />
		<input name="customerid" type="hidden" value="{{ $customerid }}" />
		<table>
			<tr>
				<td class="label" style="width:60px"><label for="">名称：</label></td>
				<td colspan="1"><input name="name" class="textbox_1" type="text" /> </td>
				<td class="error"></td>
			</tr>
			<tr>
				<td class="label"><label for="">所属商品：</label></td>
				<td colspan="1"><input disabled="disabled" name="product_name" class="textbox_1" type="text" /> </td>
				<td class="error"></td>
			</tr>
			<tr>
				<td class="label"><label for="">备注：</label></td>
				<td colspan="1"><input name="note" class="textbox_1" type="text" /> </td>
				<td class="error"></td>
			</tr>
			<tr>
				<td class="label"><label for="">激活总量：</label></td>
				<td colspan="1"><input disabled="disabled" name="count" class="textbox_1" type="text" /> </td>
				<td class="error"></td>
			</tr>
		</table>
	</form>
	<div class="actions">
		<a href="#" class="button_1 button_1_a submit"><span>确定</span></a>
		<a class="button_1 button_1_a close" href="#">取消</a>
	</div>
	<a class="close header_close" href="#"></a>
</div>

@actions (array('title' => '客户: ' . $customer->name, 'action' => '密钥' ))

@search
array('label' => '名称', 'type' => 'textbox', 'name' => 'name')
@endsearch

@dialog
array('label' => '', 'type' => 'hidden', 'name' => 'customerid', 'value_hidden' => $customerid),
array('label' => '名称', 'type' => 'textbox', 'name' => 'name'),
array('label' => '所属商品', 'type' => 'select', 'name' => 'productid'),
array('label' => '备注', 'type' => 'textbox', 'name' => 'note')
@enddialog
