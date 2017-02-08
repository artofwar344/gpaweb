<script type="text/javascript">
	$(function() {
		$.backend({
			pageIndex: 1,
			tableStructure: { eid: "categoryid", struct: ["categoryid", "name", "count"] },
			category: "应用分类",
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

@actions ('应用分类', '分类')

@search
	array('label' => '名称', 'type' => 'textbox', 'name' => 'name')
@endsearch

@table
	array('name' => '编号', 'css' => 'number'),
	array('name' => '名称'),
	array('name' => '应用数量', 'css' => 'count')
@endtable

@dialog
	array('label' => '名称', 'type' => 'textbox', 'name' => 'name')
@enddialog