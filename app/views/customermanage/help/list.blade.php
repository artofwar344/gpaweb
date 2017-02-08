<link rel="stylesheet" href="{{ Config::get('app.asset_url') }}/scripts/kindeditor/themes/default/default.css" />
<script type="text/javascript" src="{{ Config::get('app.asset_url') }}/scripts/kindeditor/kindeditor-min.js"></script>
<script type="text/javascript" src="{{ Config::get('app.asset_url') }}/scripts/kindeditor/lang/zh_CN.js"></script>
<script type="text/javascript">
	$(function() {
		var updateStatus = function(eid) {
			$.post("/helpedit/status", { "eid": eid }, function() {
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
				return "修改帮助状态为\"禁用\"<br/><span class='subtip_1'>禁用后, 该帮助不在列表内显示</span>";
			case 2:
				return "修改帮助状态为\"可用\"<br/><span class='subtip_1'>启用后, 该帮助将在列表内显示</span>";
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
		tableStructure: {
			eid: "helpid",
			columns: [
				{ "key": "helpid", "header": "编号", "class": "number" },
				{ "key": "category_name", "header": "帮助分类" },
				{ "key": "title", "header": "帮助标题" },
				{ "key": "status_text", "header": "状态", "valueclass": statusValueClass },
				{ "key": "createdate", "header": "添加时间", "class": "time" },
				{ "key": "updatedate", "header": "更新时间", "class": "time" }
			]
		},
		category: "帮助",
		selects: [ "categoryid", "type" ],
		operators: [
			{ type: "callback", callback: updateStatus, text: getText, css: "btn_switch", tip: statusTip },
			"modify", "delete"],
		modifyStructure: { title: "title", categoryid: "categoryid", content: "content", status: "status" },
		modifyDialogWidth: 950,
		validateRule: {
			title: {
				required: true,
				maxlength: 128
			},
			categoryid: {
				required: true
			},
			content: {
				required: true
			},
			status: {
				required: true
			}
		},

		validateMessages: {
			title: {
				required: "标题不能为空",
				maxlength: "标题长度不得超过128"
			},
			categoryid: {
				required: "帮助分类不能为空"
			},
			content: {
				required: "帮助内容不能为空"
			},
			status: {
				required: "状态不能为空"
			}
		}
	});
	$("#content").width("100%").height(400);
	var editor = KindEditor.create('textarea[name="content"]', {
		allowPreviewEmoticons : false,
		allowImageUpload : true,
		allowFlashUpload : false,
		allowFileManager: true,
		fileManagerJson: "http://manage.{{ app()->env }}/filemanager",
		uploadJson: "http://manage.{{ app()->env }}/filemanager/upload",
		resizeType: 0,
		width: "100%",
		items : ["undo", "redo", "|", "preview", "print", "cut", "copy", "paste", "plainpaste",
			"wordpaste", "|", "justifyleft", "justifycenter", "justifyright", "justifyfull", "insertorderedlist",
			"insertunorderedlist", "indent", "outdent", "clearhtml", "quickformat","selectall", "fullscreen", "/",
			"formatblock", "fontname", "fontsize", "|", "forecolor", "hilitecolor", "bold", "italic", "underline",
			"strikethrough", "lineheight", "removeformat", "|", "image", "multiimage", "table", "hr", "emoticons",
			"anchor", "link", "unlink"],
		afterChange: function() {
			$("#content").val(this.html());
		}
	});
	$("#dlg_new").on("dialogopen", function(event, ui) {
		editor.html($("#content").val());
	});
});
</script>

	@actions (array('title' => '帮助内容管理', 'action' => '帮助内容'))

@search
array('label' => '名称', 'type' => 'textbox', 'name' => 'name'),
array('label' => '所属分类', 'type' => 'select', 'name' => 'categoryid'),
@endsearch

@dialog
array('label' => '帮助标题', 'type' => 'textbox', 'name' => 'title'),
array('label' => '帮助分类', 'type' => 'select', 'name' => 'categoryid'),
array('label' => '帮助内容', 'type' => 'textarea', 'name' => 'content'),
array('label' => '状态', 'type' => 'select', 'name' => 'status', 'values' => Ca\Consts::$article_status_texts)
@enddialog