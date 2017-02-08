<script type="text/javascript">
	$(function() {
		$.backend({
			pageIndex: 1,
			tableStructure: { eid: "wsusid", struct: ["wsusid", "titleen", "filename", "knowledgebasearticles", "status_text", "creationdate"] },
			category: "更新",
			selects: [ "productid" ],
			operators: [ "modify", "delete" ],
			modifyStructure: { filename: "filename", status: "status" },
			validateRule: {
				filename: {
					required: true,
					maxlength: 128
				}
			},
			validateMessages: {
				filename: {
					required: "文件名不能为空",
					minlength: "文件名长度不得超过128"
				}
			}
		});
	});
</script>

@actions ('更新管理', '更新')

@search
	array('label' => '英文名称', 'type' => 'textbox', 'name' => 'titleen'),
	array('label' => '文件名', 'type' => 'textbox', 'name' => 'filename'),
	array('label' => '所属产品', 'type' => 'select', 'name' => 'productid'),
	array('label' => '所属产品', 'type' => 'select', 'name' => 'status', 'values' => Consts::$wsus_status_texts)
@endsearch

@table
	array('name' => '编号', 'css' => 'number'),
	array('name' => '名称'),
	array('name' => '文件'),
	array('name' => 'KB包', 'css' => 'state'),
	array('name' => '状态', 'css' => 'state'),
	array('name' => '发布时间', 'css' => 'time')
@endtable

@dialog
	array('label' => '文件名', 'type' => 'textbox', 'name' => 'filename'),
	array('label' => '审批结果', 'type' => 'select', 'name' => 'status', 'values' => Consts::$wsus_status_texts)
@enddialog