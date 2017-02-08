<link rel="stylesheet" href="{{ Config::get('app.asset_url') }}/scripts/kindeditor/themes/default/default.css" />
<script type="text/javascript" src="{{ Config::get('app.asset_url') }}/scripts/kindeditor/kindeditor-min.js"></script>
<script type="text/javascript" src="{{ Config::get('app.asset_url') }}/scripts/kindeditor/lang/zh_CN.js"></script>
<script type="text/javascript">
	$(function() {
		$.backend({
			tableStructure: {
				eid: "errorid",
				columns: [
					{ "key": "errorid", "header": "编号", "class": "number" },
					{ "key": "code", "header": "错误代码", "headertip": "" },
					{ "key": "message", "header": "错误信息", "headertip": "" },
					{ "key": "solution", "header": "解决办法", "class": "text" },
				]
			},
			category: "错误代码",
			operators: [ "modify", "delete" ],
			modifyStructure: { code: "code", message: "message", solution: "solution" },
			validateRule: {
				code: {
					required: true
				},
				message: {
					required: true
				},
				solution: {
					required: true
				}
			},
			validateMessages: {
				code: {
					required: "错误代码不能为空"
				},
				message: {
					required: "文章分类不能为空"
				},
				solution: {
					required: "文章内容不能为空"
				}
			}
		});

		$("#solution").width("100%").height(400);
		var editor = KindEditor.create('textarea[name="solution"]', {
			allowPreviewEmoticons : false,
			allowImageUpload : true,
			allowFlashUpload : false,
			allowFileManager: true,
			fileManagerJson: "/filemanager",
			uploadJson: "/filemanager/upload",
			resizeType: 0,
			afterChange: function() {
				$('#solution').val(this.html());
			},
			width: "100%",
			items : ["undo", "redo", "|", "preview", "print", "cut", "copy", "paste", "plainpaste",
				"wordpaste", "|", "justifyleft", "justifycenter", "justifyright", "justifyfull", "insertorderedlist",
				"insertunorderedlist", "indent", "outdent", "clearhtml", "quickformat","selectall", "fullscreen", "/",
				"formatblock", "fontname", "fontsize", "|", "forecolor", "hilitecolor", "bold", "italic", "underline",
				"strikethrough", "lineheight", "removeformat", "|", "image", "multiimage", "table", "hr", "emoticons",
				"anchor", "link", "unlink"],
		});
		$("#dlg_new").on("dialogopen", function(event, ui) {
			var width = 920;
			$("#dlg_new").parent(".ui-dialog").width(width).css({'left': ($(window).width() - width) / 2});
			editor.html($("#solution").val());
		});

	});

</script>

@actions (array('title' => '错误代码', 'action' => '错误代码'))

@search
array('label' => '错误代码', 'type' => 'textbox', 'name' => 'code'),
array('label' => '错误信息', 'type' => 'textbox', 'name' => 'message'),
@endsearch

@dialog
array('label' => '错误代码', 'type' => 'textbox', 'name' => 'code'),
array('label' => '错误信息', 'type' => 'textarea', 'name' => 'message'),
array('label' => '解决办法', 'type' => 'textarea', 'name' => 'solution'),
@enddialog