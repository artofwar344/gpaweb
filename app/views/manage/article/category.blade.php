<script type="text/javascript">
	$(function() {
		$.backend({
			pageIndex: 1,
			tableStructure: { eid: "categoryid", struct: ["categoryid", "name", "count"] },
			category: "文章分类",
			operators: [ "modify", "delete" ],
			modifyStructure: { name: "name" },
			validateRule: {
				name: {
					required: true,
					maxlength: 64
				}
			},
			validateMessages: {
				name: {
					required: "名称不能为空",
					minlength: "名称长度不得超过64"
				}
			}
		});
	});
</script>

@actions ('文章分类', '分类')

@search
	array('label' => '名称', 'type' => 'textbox', 'name' => 'name')
@endsearch

@table
	array('name' => '编号', 'css' => 'number'),
	array('name' => '名称'),
	array('name' => '文章数量', 'css' => 'count')
@endtable

@dialog
	array('label' => '名称', 'type' => 'textbox', 'name' => 'name')
@enddialog