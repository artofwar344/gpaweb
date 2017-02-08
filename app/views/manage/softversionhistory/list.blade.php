<script type="text/javascript">
	$(function() {
		var status_disagree = "{{ Ca\SoftVersionStatus::disagree }}";
		var softversionRecover = function(eid) {
			$.post("/softversionhistory/recover", { "eid": eid }, function(ret) {
				backend.list();
				if (ret.code != 1) {
					backend.showMessage("错误", ret.message);
				}
			});
		};

		var recoverTip = function(row) {
			return (row["status"] == status_disagree) ?  "将软件版本状态还原为\"待审核\"" : "该版本已通过审核，不能还原";
		};

		var recoverEnable = function(row) {
			return (row["status"] == status_disagree);
		};

		var backend = $.backend({
			pageIndex: 1,
			tableStructure: {
				eid: "versionid",
				columns: [
					{ "key": "versionid", "header": "编号", "class": "number" },
					{ "key": "name", "header": "软件名称" },
					{ "key": "version", "header": "当前版本" },
					{ "key": "verify_version", "header": "审核版本" },
					{ "key": "filesize_text", "header": "文件大小" },
					{ "key": "softbrief", "header": "简介", "class": "text text500" },
					{ "key": "brief", "header": "版本描述", "class": "text text500" },
					{ "key": "createdate", "header": "上传时间", "class": "time" },
					{ "key": "status_text", "header": "状态", "class": "state" }
				]
			},
			category: "软件审核历史",
			operators: [
				{ type: "callback", callback: softversionRecover, text: "恢复待审核", css: "btn_auth", enable: recoverEnable, tip: recoverTip}
			]

		});

	});

</script>
@actions (array('title' => '软件版本审核历史', 'action' => '', 'buttons' => array()))

@search
	array('label' => '软件', 'type' => 'textbox', 'name' => 'name'),
	array('label' => '状态', 'type' => 'select', 'name' => 'status', 'values' => Ca\Consts::$version_status_texts),
@endsearch
