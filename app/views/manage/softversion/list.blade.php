<script type="text/javascript">
	$(function() {
		var status_pending = "{{ Ca\SoftVersionStatus::pending }}";
		var softversionAuth = function(eid) {
			$.post("/softversion/auth", { "eid": eid }, function(ret) {
				backend.list();
				if (ret.code != 1) {
					backend.showMessage("错误", ret.message);
				}
			});
		};

		var softversionDisagree = function(eid) {
			$.post("/softversion/disagree", { "eid": eid }, function(ret) {
				backend.list();
				if (ret.code != 1) {
					backend.showMessage("错误", ret.message);
				}
			});
		};

		var authEnable = function(row) {
			return (row["status"] == status_pending);
		};

		var backend = $.backend({
			pageIndex: 1,
			tableStructure: {
				eid: "versionid",
				checkbox: true,
				columns: [
					{ "key": "versionid", "header": "编号", "class": "number" },
					{ "key": "name", "header": "软件名称" },
					{ "key": "version", "header": "当前版本" },
					{ "key": "verify_version", "header": "待审核版本" },
					{ "key": "filesize_text", "header": "文件大小" },
					{ "key": "softbrief", "header": "简介", "class": "text text500" },
					{ "key": "brief", "header": "版本描述", "class": "text text500" },
					{ "key": "createdate", "header": "上传时间", "class": "time" },
					{ "key": "status_text", "header": "状态", "class": "state" }
				]
			},
			category: "软件审核",
			operators: [
				{ type: "callback", callback: softversionAuth, text: "通过", css: "btn_auth", enable: authEnable, tip: "修改软件版本状态为\"通过审核\"" },
				{ type: "callback", callback: softversionDisagree, text: "拒绝", css: "btn_disagree", enable: authEnable, tip: "修改软件版本状态为\"未通过审核\"" }
			],
			validateRule: {
				status: {
					required: true
				}
			},
			validateMessages: {
				status: { required: "状态不能为空" }
			}
		});

		$(".multi_actions .button_auth").click(function() {
			if ($(this).hasClass("button_1_disabled")) return false;
			$(this).addClass("button_1_disabled");

			$.post("/softversion/authmulti", { eids: backend.checkedRow() }, function() {
				backend.clearCheckedRow();
				backend.list();
			});

			return false;
		});

		$(".multi_actions .button_disagree").click(function() {
			if ($(this).hasClass("button_1_disabled")) return false;
			$(this).addClass("button_1_disabled");

			$.post("/softversion/disagreemulti", { eids: backend.checkedRow() }, function() {
				backend.clearCheckedRow();
				backend.list();
			});

			return false;
		});

	});

</script>
@actions (array('title' => '软件版本审核', 'action' => '', 'buttons' => array()))

@search
	array('label' => '软件', 'type' => 'textbox', 'name' => 'name')
@endsearch

<div class="multi_actions">
	<span class="selected">批量操作: <strong>0</strong> 条</span>
	<a href="#" class="button_1 button_1_disabled button_auth">通过审核</a>
	<a href="#" class="button_1 button_1_disabled button_disagree">拒绝通过</a>
</div>

@dialog
	array('label' => '状态', 'type' => 'select', 'name' => 'status', 'values' => Ca\Consts::$version_status_texts)
@enddialog