<script type="text/javascript">
	$(function() {
		var deleteEnable = function(entry) {
			return entry.count == 0;
		}
		$.backend({
			tableStructure: {
				eid: "categoryid",
				columns: [
					{ "key": "categoryid", "header": "编号", "class": "number" },
					{ "key": "name", "header": "名称" },
					{ "key": "count", "header": "文章数量", "class": "count" }
				]
			},
			category: "文章分类",
			operators: [
				"modify",
				{ type: "delete", tip: "删除文章分类", enable: deleteEnable, text: "删除", css: "btn_delete" }],
			selects: [],
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

@actions (array('title' => '文章分类', 'action' => '分类'))

@search
	array('label' => '名称', 'type' => 'textbox', 'name' => 'name')
@endsearch

@dialog
	array('label' => '名称', 'type' => 'textbox', 'name' => 'name')
@enddialog


