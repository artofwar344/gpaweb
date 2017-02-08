<link rel="stylesheet" href="{{ Config::get('app.asset_url') }}/scripts/kindeditor/themes/default/default.css" />
<script type="text/javascript" src="{{ Config::get('app.asset_url') }}/scripts/kindeditor/kindeditor-min.js"></script>
<script type="text/javascript" src="{{ Config::get('app.asset_url') }}/scripts/kindeditor/lang/zh_CN.js"></script>
<script type="text/javascript">
	$(function() {
		$.backend({
			pageIndex: 1,
			tableStructure: { eid: "adid", struct: ["adid", "name", "link", "blank_text"] },
			category: "广告",
			operators: [ "modify", "delete" ],
			modifyStructure: { name: "name", image: "image", link: "link", blank: "blank"},
			validateRule: {
				name: {
					required: true,
					maxlength: 64
				},
				image: {
					required: true,
					url: true
				},
				link: {
					required: true,
					url: true
				},
				blank: {
					required: true
				}
			},
			validateMessages: {
				name: {
					required: "名称不能为空",
					maxlength: "名称长度不得超过64"
				},
				image: {
					required: "图片不能为空",
					url: "图片必须是一个URL地址"
				},
				link: {
					required: "链接不能为空",
					url: "必须是一个URL地址"
				},
				blank: {
					required: "不能为空"
				}
			}
		});

		var editor = KindEditor.editor({
			allowFileManager : true,
			fileManagerJson : '/filemanager',
			uploadJson : '/filemanager/upload'
		});
		KindEditor('#image').click(function() {
			editor.loadPlugin('image', function() {
				editor.plugin.imageDialog({
					imageUrl : KindEditor('#image').val(),
					clickFn : function(url, title, width, height, border, align) {
						KindEditor('#image').val(url);
						editor.hideDialog();
					}
				});
			});
		});

	});

</script>

@actions ('广告管理', '广告')

@search
	array('label' => '名称', 'type' => 'textbox', 'name' => 'name')
@endsearch

@table
	array('name' => '编号', 'css' => 'number'),
	array('name' => '名称'),
	array('name' => '链接'),
	array('name' => '新窗口打开')
@endtable

@dialog
	array('label' => '名称', 'type' => 'textbox', 'name' => 'name'),
	array('label' => '图片', 'type' => 'textbox', 'name' => 'image'),
	array('label' => '链接', 'type' => 'textbox', 'name' => 'link'),
	array('label' => '新窗口打开', 'type' => 'select', 'name' => 'blank', 'values' => Consts::$ad_targets)
@enddialog