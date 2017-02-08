<script type="text/javascript">
	$(function() {
		$.backend({
			pageIndex: 1,
			tableStructure: { eid: "productid", struct: ["productid", "name", "count"] },
			category: "更新产品",
			operators: [ "modify", "delete" ],
			modifyStructure: { name: "name", status: "status" },
			validateRule: {
				name: {
					required: true,
					maxlength: 128
				}
			},
			validateMessages: {
				name: {
					required: "文件名不能为空",
					minlength: "文件名长度不得超过128"
				}
			}
		});
	});
</script>

@actions ('更新产品管理', '更新产品')

@search
	array('label' => '名称', 'type' => 'textbox', 'name' => 'name')
@endsearch

@table
	array('name' => '编号', 'css' => 'number'),
	array('name' => '名称'),
	array('name' => '更新数量', 'css' => 'count')
@endtable

@dialog
	array('label' => '名称', 'type' => 'textbox', 'name' => 'name')
@enddialog