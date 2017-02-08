<script type="text/javascript">
var visible = @if (Input::get("inner")) false @else true @endif;
$(function() {

	var modifyTip = function(row) {
		return row["status"] != 3 ? "进行分配密钥或拒绝请求的操作" : "该请求已处理";
	};

	var modifyEnable = function(row) {
		return row["status"] != 3;
	};

	var statusalueClass = function(row) {
		var ret = "";
		switch(row["status"] >> 0) {
			case 1:
				ret = "blue";
				break;
			case 2:
				ret = "red";
				break;
			case 3:
				ret = "green";
				break;
		}

		return ret;
	};

	var authEnable = function(row) {
		return (row["status"] != <?php echo \Ca\UserKeyStatus::agree; ?>);
	};

	var checkboxEnable = function(row) {
		return authEnable(row);
	};

	var backend = $.backend({
		actionDisabledFields: visible ? [] : ["name"],
		newDefaultValues: visible ? {} : { "status": 3 },
		listParams: { "userid": "{{ $user_id }}" },
		tableStructure: {
			eid: "userkeyid",
//			checkbox: true,
			checkboxEnable: checkboxEnable,
			columns: [
				{ "key": "userkeyid", "header": "编号", "class": "number" },
				{ "key": "user_name", "header": "用户名", "headertip": "请求激活次数的用户", "visible": visible },
				{ "key": "department_name", "header": "所属部门", "headertip": "请求用户所属部门" },
				{ "key": "product_name", "header": "请求商品", "headertip": "请求激活的商品" },
				{ "key": "type", "header": "激活模式", "headertip": "<strong>定时激活</strong>: 需要在180天后再次运行客户端激活<br/><strong>永久激活</strong>: 激活后永久保持激活状态" },
				{ "key": "requestcount", "header": "请求数量", "class": "count", "headertip": "申请激活该商品的次数" },
				{ "key": "requestdate", "header": "请求时间", "class": "time", "headertip": "申请激活该商品的时间" },
				{ "key": "reason", "header": "请求理由", "class": "note" },
				{ "key": "key_name", "header": "分配密钥", "headertip": "分配给该次激活用的密钥" },
				{ "key": "assigncount", "header": "分配数量", "class": "count", "headertip": "分配给该次激活可使用数量" },
				{ "key": "assigndate", "header": "分配日期", "class": "time" },
				{ "key": "manager_name", "header": "分配管理员", "headertip": "分配该次申请的管理员" },
				{ "key": "status_text", "header": "状态", "class": "state", "headertip": "<strong>待审批</strong>: 新的申请, 管理员还未审批<br/><strong>不同意</strong>: 不同意该次申请<br/><strong>同意分配</strong>: 同意该次分配, 并且分配激活数量给申请用户", "valueclass": statusalueClass }
			]
		},
		category: "用户激活分配",
		selects: [ "productid", "keyid" ],
		modifyStructure: { keyid: "keyid", assigncount: "assigncount", status: "status", departmentid: "departmentid" },
		operators: [
			{ type: "modify", tip: modifyTip, enable: modifyEnable, text: "分配", css: "btn_modify" }
		],
		validateRule: {
			keyid: {
				required: true
			},
			status: {
				required: true
			},
			assigncount: {
				required: true,
				digits: true,
				min: 1,
				max: 0
			}
		},

		validateMessages: {
			keyid: {
				required: "密钥不能为空"
			},
			status: {
				required: "状态不能为空"
			},
			assigncount: {
				required: "分配数量不能为空",
				digits: "分配数量格式不对",
				max: "分配数量已不足",
				min: "分配数量不能低于1个"
			}
		}

	});

	var dlgNew = $("#dlg_new");
	var select = $("#keyid", dlgNew).text("");
	var assignCount = $("#assigncount");
	var eid;

	dlgNew.on("dialogopen", function() {
		eid = dlgNew.attr("eid");
		$("<option />").val("").text("请选择").appendTo(select.text(""));
		select.prop("disabled", true);
		assignCount.val("").attr("placeholder", "");
	});

	select.change(function() {
		var self = $(this);
		assignCount.rules("add", {
			max: $(":selected", self).attr("remain") >> 0
		});
	});

	$("#status", dlgNew).change(function() {
		var departmentid = $("#departmentid").val();
		var userid = $("#userid").val();
		if ($(this).val() == 3) {
			if (select.find("option").length <= 1) {
				$.post("/keyassign/modifyselects", { "eid": eid, "departmentid": departmentid, "userid": userid }, function(ret) {
					$.each(ret[0], function(i, item) {
						$("<option />").attr("remain", item["remain"]).text(item.name).val(item.keyid).appendTo(select);
					});
					assignCount.add(select).prop("disabled", false).removeClass("disabled");
				}, "json");
			} else {
				assignCount.add(select).prop("disabled", false).removeClass("disabled");
			}
		}
		else assignCount.add(select).val("").prop("disabled", true).addClass("disabled");
	});

	$("#keyid", dlgNew).change(function() {
		var self = $(this);
		var remain = $(":selected", self).attr("remain") >> 0;
		assignCount.attr("placeholder", "可分配数量:" + remain).focus().rules("add", {
			max: remain
		});
	});
});
</script>
@if (Input::get("inner"))
@actions (array('title' => '用户: ' . $user->name . ' - [' . $user->username . '] 激活分配', 'buttons' => array('add'), 'action' => '激活分配'))
@else
@actions (array('title' => '用户激活分配', 'buttons' => array()))
@endif

@search
	array('label' => '姓名', 'type' => 'textbox', 'name' => 'name', 'placeholder' => '分配用户姓名'),
	array('label' => '请求商品', 'type' => 'select', 'name' => 'productid'),
	array('label' => '分配密钥', 'type' => 'select', 'name' => 'keyid'),
	array('label' => '状态', 'type' => 'select', 'name' => 'status', 'values' => Ca\Consts::$managekey_status_texts)
@endsearch

@dialog
	array('label' => '状态', 'type' => 'select', 'name' => 'status', 'values' => Ca\Consts::$managekey_status_texts),
	array('label' => '密钥', 'type' => 'select', 'name' => 'keyid'),
	array('label' => '分配数量', 'type' => 'textbox', 'name' => 'assigncount'),
	array('label' => '', 'type' => 'hidden', 'name' => 'userid', 'value_hidden' => $user_id),
	array('label' => '', 'type' => 'hidden', 'name' => 'departmentid'),
@enddialog