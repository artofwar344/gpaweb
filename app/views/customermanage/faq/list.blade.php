{{ HTML::style(Config::get('app.asset_url') . '/scripts/kindeditor/themes/default/default.css') }}
{{ HTML::script(Config::get('app.asset_url') . '/scripts/kindeditor/kindeditor-min.js'); }}
{{ HTML::script(Config::get('app.asset_url') . '/scripts/kindeditor/lang/zh_CN.js'); }}
<script type="text/javascript">
	var visible = @if (Input::get("inner")) false @else true @endif;
	var disabledFields = [@if (Input::get("inner")) "categoryid" @endif];
	var defaultValues = {{ $category_id ? '{ "categoryid": ' . $category_id . ' }' : "{}" }};
	$(function() {
		$.backend({
			listParams: { "categoryid": "{{ $category_id }}" },
			tableStructure: {
				eid: "faqid",
				columns: [
					{ "key": "faqid", "header": "编号", "class": "number" },
					{ "key": "category_name", "header": "分类", "visible": visible },
					{ "key": "title", "header": "标题" }
				]
			},
			category: "FAQ",
			selects: [ "categoryid" ],
			modifyStructure: { title: "title", categoryid:"categoryid", content: "content" },
			operators: ["modify", "delete"],
			modifyDialogWidth: 950,
			newDisabledFields: disabledFields,
			modifyDisabledFields: disabledFields,
			actionDisabledFields: disabledFields,
			newDefaultValues: defaultValues,
			searchDefaultValues: defaultValues,
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
				}
			},

			validateMessages: {
				title: {
					required: "标题不能为空",
					maxlength: "标题长度不得超过128"
				},
				categoryid: {
					required: "分类不能为空"
				},
				content: {
					required: "内容不能为空"
				}
			}
		});
		$("#content").width("100%").height(400);
		var editor = KindEditor.create('textarea[name="content"]', {
			allowFileManager: true,
			fileManagerJson: '/filemanager',
			uploadJson: '/filemanager/upload',
			resizeType: 0,
			afterChange: function() {
				$('#content').val(this.html());
			}
		});
		$("#dlg_new").on("dialogopen", function(event, ui) {
			editor.html($("#content").val());
		});
	});
</script>

@actions (array('title' => ($category ? '分类: ' . $category->name . ' ' : 'FAQ管理'), 'action' => 'FAQ', 'tooltip' => '管理 FAQ'))

@search
array('label' => '名称', 'type' => 'textbox', 'name' => 'title', 'placeholder' => 'FAQ 标题'),
array('label' => '所属分类', 'type' => 'select', 'name' => 'categoryid')
@endsearch

@dialog
array('label' => '标题', 'type' => 'textbox', 'name' => 'title'),
array('label' => '分类', 'type' => 'select', 'name' => 'categoryid'),
array('label' => '内容', 'type' => 'textarea', 'name' => 'content')
@enddialog