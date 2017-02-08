<link rel="stylesheet" href="{{ Config::get('app.asset_url') }}/scripts/kindeditor/themes/default/default.css" />
<script type="text/javascript" src="{{ Config::get('app.asset_url') }}/scripts/kindeditor/kindeditor-min.js"></script>
<script type="text/javascript" src="{{ Config::get('app.asset_url') }}/scripts/kindeditor/lang/zh_CN.js"></script>
<script type="text/javascript">
$(function() {
	var updateStatus = function(eid) {
		$.post("/article/status", { "eid": eid }, function() {
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
				return "修改文章状态为\"禁用\"<br/><span class='subtip_1'>禁用后, 该文章不在列表内显示</span>";
			case 2:
				return "修改文章状态为\"可用\"<br/><span class='subtip_1'>启用后, 该文章将在列表内显示</span>";
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
			eid: "articleid",
			columns: [
				{ "key": "articleid", "header": "编号", "class": "number" },
				{ "key": "module_text", "header": "站点" },
				{ "key": "category_name", "header": "文章分类" },
				{ "key": "title", "header": "文章标题" },
				{ "key": "type_text", "header": "类型" },
				{ "key": "status_text", "header": "状态", "valueclass": statusValueClass },
				{ "key": "createdate", "header": "添加时间", "class": "time" },
				{ "key": "updatedate", "header": "更新时间", "class": "time" }
			]
		},
		category: "文章",
		selects: [ "categoryid", "type" ],
		operators: [
			{ type: "callback", callback: updateStatus, text: getText, css: "btn_switch", tip: statusTip },
			"modify", "delete"],
		modifyStructure: { module: "module", title: "title", categoryid: "categoryid", content: "content", type: "[type]", status: "status" },
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
				required: "文章分类不能为空"
			},
			content: {
				required: "文章内容不能为空"
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

@actions (array('title' => '文章管理', 'action' => '文章'))

@search
array('label' => '名称', 'type' => 'textbox', 'name' => 'name'),
array('label' => '所属分类', 'type' => 'select', 'name' => 'categoryid'),
array('label' => '类型', 'type' => 'select', 'name' => 'type'),
@endsearch

@dialog
array('label' => '站点', 'type' => 'select', 'name' => 'module', 'values' => Ca\Consts::$module_texts),
array('label' => '文章标题', 'type' => 'textbox', 'name' => 'title'),
array('label' => '文章分类', 'type' => 'select', 'name' => 'categoryid'),
array('label' => '文章内容', 'type' => 'textarea', 'name' => 'content'),
array('label' => '类型', 'type' => 'checklist', 'name' => 'type', 'values' => Ca\Consts::$article_type_texts),
array('label' => '状态', 'type' => 'select', 'name' => 'status', 'values' => Ca\Consts::$article_status_texts)
@enddialog