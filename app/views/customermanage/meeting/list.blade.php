<link rel="stylesheet" href="{{ Config::get('app.asset_url') }}scripts/kindeditor/themes/default/default.css" />
<script type="text/javascript" src="{{ Config::get('app.asset_url') }}scripts/kindeditor/kindeditor-min.js"></script>
<script type="text/javascript" src="{{ Config::get('app.asset_url') }}scripts/kindeditor/lang/zh_CN.js"></script>
<script type="text/javascript">
	$(function() {
		var updateStatus = function(eid) {
			$.post("/meeting/status", { "eid": eid }, function() {
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
					return "修改讲座状态为\"禁用\"<br/><span class='subtip_1'>禁用后, 用户不能报名, 并且不可见</span>";
				case 2:
					return "修改讲座状态为\"可用\"<br/><span class='subtip_1'>启用后, 用户可以报名</span>";
			}
			return "";
		};

		var enrollEnable = function(row) {
			return row["count"] > 0;
		};

		var enrollTip = function(row) {
			return enrollEnable(row) ? "查看并编辑讲座的报名者" : "该讲座暂无人报名";
		};

		var deleteTip = function(row) {
			return deleteEnable(row) ? "删除讲座" : "讲座已有人报名, 无法删除";
		};

		var deleteEnable = function(row) {
			return row["count"] == 0;
		};

		var statusActiveClass = function(row) {
			var ret = "";
			switch (parseInt(row["active"])) {
				case 1:
					ret = "green";
					break;
				case 0:
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
				eid: "meetingid",
				columns: [
					{ "key": "meetingid", "header": "编号", "class": "number" },
					{ "key": "name", "header": "标题" },
					{ "key": "address", "header": "地址", "class": "text", "headertip": "讲座举办地址" },
					{ "key": "count", "header": "报名人数", "class": "count", "headertip": "参加该讲座的人数" },
					{ "key": "active_text", "header": "进行状态", "class": "state", "valueclass": statusActiveClass },
					{ "key": "meeting_date", "header": "开始~结束时间", "class": "time_2", "headertip": "讲座开始~结束时间" },
					{ "key": "status_text", "header": "状态", "class": "state", "valueclass": statusValueClass },
					{ "key": "createdate", "header": "发布时间", "class": "time", "headertip": "发布该讲座的时间" }
				]
			},
			category: "讲座",
			operators: [
				{ type: "callback", callback: updateStatus, text: getText, css: "btn_switch", tip: statusTip },
				{ type:'iframe', url: "/meetingenroll?id={eid}", text: "报名者", css: "btn_view", width: "750px", height: "570px", enable: enrollEnable, tip: enrollTip },
				{ type: "modify", tip: "编辑讲座", text: "编辑", css: "btn_modify" },
				{ type: "delete", tip: deleteTip, enable: deleteEnable, text: "删除", css: "btn_delete" }
			],
			selects: [],
			modifyStructure: {
				name: "name",
				intro: "intro",
				tag: "tag",
				begindate: "begindate",
				enddate: "enddate",
				enrolldate: "enrolldate",
				address: "address",
				contactname: "contactname",
				contactphone: "contactphone",
				contactemail: "contactemail",
				cost: "cost"
			},
			modifyDialogWidth: 650,

			validateRule: {
				name: {
					required: true,
					maxlength: 128
				},
				intro: {
					required: true
				},
				tag: {
					required: true
				},
				begindate: {
					required: true
				},
				enddate: {
					required: true
				},
				enrolldate: {
					required: true
				},
				address: {
					required: true
				},
				contactname: {
					required: true
				},
				contactphone: {
					required: true
				},
				contactemail: {
					required: true,
					email: true
				},
				cost: {
					required: true
				}

			},

			validateMessages: {
				name: {
					required: "标题不能为空",
					maxlength: "标题长度不得超过128"
				},
				intro: {
					required: "内容不能为空"
				},
				tag: {
					required: "标签不能为空"
				},
				begindate: {
					required: "讲座开始时间不能为空"
				},
				enddate: {
					required: "讲座结束时间不能为空"
				},
				enrolldate: {
					required: "报名结束时间不能为空"
				},
				address: {
					required: "地址不能为空"
				},
				cost: {
					required: "费用不能为空"
				},
				contactname: {
					required: "联系人不能为空"
				},
				contactphone: {
					required: "联系电话不能为空"
				},
				contactemail: {
					required: "联系邮箱不能为空",
					email: "邮箱格式不正确"
				}
			}
		});
		var editor = KindEditor.create('textarea[name="intro"]', {
			allowPreviewEmoticons : false,
			allowImageUpload : true,
			allowFlashUpload : false,
			allowFileManager: true,
			fileManagerJson: "http://manage.{{ app()->environment() }}/filemanager",
			uploadJson: "http://manage.{{ app()->environment() }}/filemanager/upload",
			resizeType: 0,
			afterChange: function() {
				$('#intro').val(this.html());
			},
			width: "100%",
			items : ["undo", "redo", "|", "preview", "print", "cut", "copy", "paste", "plainpaste",
				"wordpaste", "|", "justifyleft", "justifycenter", "justifyright", "justifyfull", "insertorderedlist",
				"insertunorderedlist", "indent", "outdent", "clearhtml", "quickformat","selectall", "fullscreen", "/",
				"formatblock", "fontname", "fontsize", "|", "forecolor", "hilitecolor", "bold", "italic", "underline",
				"strikethrough", "lineheight", "removeformat", "|", "image", "multiimage", "table", "hr", "emoticons",
				"anchor", "link", "unlink"]
		});
		$("#dlg_new").on("dialogopen", function(event, ui) {
			editor.html($("#intro").val());
		});
	});

</script>

@actions (array('title' => '讲座管理', 'action' => '讲座'))

@search
array('label' => '标题', 'type' => 'textbox', 'name' => 'name', 'placeholder' => '讲座标题')
@endsearch

@dialog
array('label' => '讲座标题', 'type' => 'textbox', 'name' => 'name', 'placeholder' => '讲座标题'),
array('label' => '讲座介绍', 'type' => 'textarea', 'name' => 'intro'),
array('label' => '标签', 'type' => 'textbox', 'name' => 'tag', 'placeholder' => '讲座标签, 用逗号隔开'),
array('label' => '讲座开始时间', 'type' => 'datetime', 'name' => 'begindate', 'placeholder' => '讲座开始时间'),
array('label' => '讲座结束时间', 'type' => 'datetime', 'name' => 'enddate', 'placeholder' => '讲座结束时间'),
array('label' => '报名结束时间', 'type' => 'datetime', 'name' => 'enrolldate', 'placeholder' => '讲座报名结束时间'),
array('label' => '讲座地点', 'type' => 'textbox', 'name' => 'address', 'placeholder' => '讲座地点'),
array('label' => '联系人', 'type' => 'textbox', 'name' => 'contactname', 'placeholder' => '讲座联系人'),
array('label' => '联系电话', 'type' => 'textbox', 'name' => 'contactphone', 'placeholder' => '讲座联系人电话'),
array('label' => '联系邮箱', 'type' => 'textbox', 'name' => 'contactemail', 'placeholder' => '讲座联系电子邮箱'),
array('label' => '费用', 'type' => 'textbox', 'name' => 'cost', 'placeholder' => '参加讲座所需费用')
@enddialog

