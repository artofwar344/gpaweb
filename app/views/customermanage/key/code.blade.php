<script type="text/javascript">
	$(function() {
		var topmanager = Boolean("{{ $topmanager }}");
//		var updateStatus = function(eid) {
//			$.post("/manager/status", { "eid": eid }, function() {
//				backend.list();
//			});
//		};
//		var deleteEnable = function(row) {
//
//			return topmanager;
//		};

		var statusValueClass = function(row) {
			var ret = "";
			switch (parseInt(row["status"])) {
				case 1:
					ret = "green";
					break;
				case 2:
				case 3:
					ret = "red";
					break;
			}

			return ret;
		};
		var deleteEnable = function(row) {
			return topmanager && row["status"] == "{{ \Ca\CodeStatus::unassgined }}";
		};
		var assignEnable = function(row) {
			return row["status"] == "{{ \Ca\CodeStatus::unassgined }}";
		};

		var assignTip =function(row) {
			return row["status"] == "{{ \Ca\CodeStatus::unassgined }}" ? "分配激活码" : "该激活码已分配";
		};

		var deleteTip =function(row) {
			return row["status"] == "{{ \Ca\CodeStatus::unassgined }}" ? "删除激活码" : "该激活码已分配";
		};

		var assignDialog = $("#assignDialog");
		var assignAction = function(eid) {
			$('#codeid').val(eid);
			assignDialog.dialog("open");
		};
		$(".actions .submit" , assignDialog).click(function() {
			var eid = $('#codeid').val();
			$(this).remove();
			$.post("/code/assign", { "eid": eid }, function(ret) {
				if (ret.status == 1) {
					$(".info", assignDialog).text(ret.code);
				}
			}, "json");
		});
		assignDialog.on('dialogclose', function() {
			backend.list();
		});

		var backend = $.backend({
			tableStructure: {
				eid: "codeid",
				columns: [
					{ "key": "codeid", "header": "编号", "class": "number" },
					{ "key": "productname", "header": "商品", "headertip": "该激活码可激活的商品" },
					{ "key": "keyname", "header": "密钥", "headertip": "该激活码对应的密钥" },
					{ "key": "code", "header": "激活码", "headertip": "在客户端使用该激活码可激活对应商品<br/>激活码只有分配的管理员才能查看" },
					{ "key": "managername", "header": "分配管理员", "headertip": "分配该激活码的管理员" },
					{ "key": "assigndate", "header": "分配时间", "class": "time", "headertip": "该激活码的分配时间" },
					{ "key": "status_text", "header": "状态", "class": "state", "valueclass": statusValueClass },
					{ "key": "createdate", "header": "创建时间", "class": "time", "headertip": "该激活码的创建时间" }
				]
			},
			category: "激活码",
			operators: [
				{ type: "callback", callback: assignAction, text: "分配", css: "btn_view", enable: assignEnable, "tip": assignTip },
				topmanager ? { type: "delete", tip: deleteTip, enable: deleteEnable, text: "删除", css: "btn_delete" } : ""
			],
			selects: [ 'productid' ],
			modifyStructure: { name: "name", departmentid: "departmentid", status: "status", role: "[role]" },
			validateRule: {
				productid: {
					required: true
				},
				keyid: {
					required: true
				},
				count: {
					required: true,
					min: 1
				}
			},
			validateMessages: {
				productid: {
					required: "请选择商品"
				},
				keyid: {
					required: "请选择密钥"
				},
				count: {
					required: "请填写数量"
				}
			}
		});

		var dlgNew = $("#dlg_new");
		var keyselect = $("select[name='keyid']");
		var keycount = $("input[name='count']");

		dlgNew.on("dialogopen", function() {
			$("<option />").val("").text("请选择").appendTo(keyselect.text(""));
			keyselect.prop("disabled", true);
			keycount.prop("disabled", true).addClass("disabled").val("").attr("placeholder", "");
		});


		$("select[name='productid']").change(function () {
			var productid = $(this).val();
			keyselect.prop("disabled", true).addClass("disabled").val("");
			keycount.prop("disabled", true).addClass("disabled").val("").attr("placeholder", "");
			$("option:gt(0)", keyselect).remove();
			if (productid != '') {
				keyselect.prop("disabled", false).removeClass("disabled");
				$.post("/code/key", { "productid": productid }, function (ret) {
					console.log(ret);
					$.each(ret, function(i, item) {
						$("<option />").attr("remain", item["remain"]).text(item.name).val(item.keyid).appendTo(keyselect);
					});
				}, "json");
			}
		});
		$(keyselect, dlgNew).change(function() {
			var self = $(this);
			if (self.val() == "") {
				keycount.prop("disabled", true).addClass("disabled").val("").attr("placeholder", "");
			} else {
				var remain = $(":selected", self).attr("remain") >> 0;
				keycount.prop("disabled", false)
					.removeClass("disabled")
					.attr("placeholder", "可用数量:" + remain)
					.focus()
					.rules("add", { max: remain });
			}
		});
	});

</script>

@if ($topmanager)
@actions (array('title' => ('激活码管理'), 'action' => '激活码'))
@else
@actions (array('title' => ('激活码管理'), 'action' => '激活码', 'buttons' => array()))
@endif

@search
array('label' => '状态', 'type' => 'select', 'name' => 'status', 'values' => Ca\Consts::$code_status_text)
@endsearch

@dialog
array('label' => '商品', 'type' => 'select', 'name' => 'productid'),
array('label' => '密钥', 'type' => 'select', 'name' => 'keyid'),
array('label' => '数量', 'type' => 'textbox', 'name' => 'count'),
@enddialog

<div id="assignDialog" class="dialog_1">
	<h1>分配激活码</h1>
	<p class="info">查看后该激活码将变更为<span class="red">已分配</span>状态, 确定分配该激活码？</p>
	<input type="hidden" name="codeid" id="codeid" value="">
	<div class="actions"><a href="#" class="button_1 button_1_a submit">确定</a><a href="#" class="button_1 button_1_a close">取消</a></div><a href="#" class="close header_close"></a></div>
</div>
