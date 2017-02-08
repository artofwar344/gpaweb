<script type="text/javascript">
	$(function() {
		var updateStatus = function(eid) {
			$.post("/app/status", { "eid": eid }, function() {
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

		var statusTip = function(row) {
			switch (row["status"] >> 0) {
				case 1:
					return "修改应用状态为\"禁用\"<br/><span class='subtip_1'>禁用后, 客户端不允许下载并使用该应用, 并且不可见</span>";
				case 2:
					return "修改应用状态为\"可用\"<br/><span class='subtip_1'>启用后, 客户端可以下载并使用该应用</span>";
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

		var backend = $.backend({
			tableStructure : {
				eid: "appid",
				columns: [
					{ "key": "appid", "header": "编号", "class": "number" },
					{ "key": "name", "header": "名称", "headertip": "客户端应用名称" },
					{ "key": "category_name", "header": "类别", "headertip": "客户端应用所属类别" },
					{ "key": "type_text", "header": "类型", "class": "state", "headertip": "<strong>执行文件</strong>: 客户端嵌入可执行应用" },
					{ "key": "version", "header": "版本", "headertip": "客户端应用版本" },
					{ "key": "description", "header": "描述", "class": "text", "headertip": "客户端应用简单描述, <span class='subtip_1'>对应客户端应用提示信息</span>" },
					{ "key": "status_text", "header": "状态", "class": "state", "headertip": "<strong>可用</strong>: 客户端可以下载并使用该应用<br/><strong>禁用</strong>: 客户端不允许下载并使用该应用, 并且不可见", "valueclass": statusValueClass },
					{ "key": "createdate", "header": "创建时间", "class": "time" }
				]
			},
			selects : [ "categoryid" ],
			operators : [ { type: "callback", callback: updateStatus, text: getText, css: "btn_switch", tip: statusTip } ]
		});
	});
</script>

@actions (array('title' => '客户端应用管理', 'action' => '应用', 'buttons' => array()))

@search
	array('label' => '名称', 'type' => 'textbox', 'name' => 'name', 'placeholder' => '应用名称'),
	array('label' => '所属分类', 'type' => 'select', 'name' => 'categoryid')
@endsearch