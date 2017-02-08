<link rel="stylesheet" href="{{ Config::get('app.asset_url') }}/scripts/kindeditor/themes/default/default.css" />
<script type="text/javascript" src="{{ Config::get('app.asset_url') }}/scripts/kindeditor/kindeditor-min.js"></script>
<script type="text/javascript" src="{{ Config::get('app.asset_url') }}/scripts/kindeditor/lang/zh_CN.js"></script>
<script type="text/javascript">
	$(function() {
		$.backend({
			pageIndex: 1,
			tableStructure: { eid: "articleid", struct: ["articleid", "category_name", "title", "type_text", "createdate", "updatedate"] },
			category: "文章",
			selects: [ "categoryid", "type" ],
			operators: [ "modify", "delete" ],
			modifyStructure: { title: "title", categoryid:"categoryid", content: "content", type:"[type]", status: "status" },
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
		var editor;
		editor = KindEditor.create('textarea[name="content"]', {
			allowFileManager : true,
			fileManagerJson : '/filemanager',
			uploadJson : '/filemanager/upload',
			afterChange : function() {
				$('#content').val(this.html());
			}
		});
		$("#dlg_new").on("dialogopen", function(event, ui) {
			var width = 920;
			$("#dlg_new").parent(".ui-dialog").width(width).css({'left': ($(window).width() - width) / 2});
			editor.html($("#content").val());
		});
	});

</script>

@actions ('文章管理', '文章')

@search
array('label' => '名称', 'type' => 'textbox', 'name' => 'name'),
array('label' => '所属分类', 'type' => 'select', 'name' => 'categoryid'),
array('label' => '类型', 'type' => 'select', 'name' => 'type'),
@endsearch

@table
array('name' => '编号', 'css' => 'number'),
array('name' => '文章分类'),
array('name' => '文章标题'),
array('name' => '类型'),
array('name' => '添加时间', 'css' => 'time'),
array('name' => '更新时间', 'css' => 'time')
@endtable

@dialog
array('label' => '文章标题', 'type' => 'textbox', 'name' => 'title'),
array('label' => '文章分类', 'type' => 'select', 'name' => 'categoryid'),
array('label' => '文章内容', 'type' => 'textarea', 'name' => 'content'),
array('label' => '类型', 'type' => 'checklist', 'name' => 'type', 'values' => Consts::$article_type_texts),
array('label' => '状态', 'type' => 'select', 'name' => 'status', 'values' => Consts::$article_status_texts)
@enddialog