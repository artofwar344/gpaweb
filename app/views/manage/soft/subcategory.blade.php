<script type="text/javascript">
	var disabledFields = [@if (Input::get("inner")) "parentid" @endif];

	$(function() {
		var deleteTip = function(row) {
			return deleteEnable(row) ?
				"删除分类" : "有软件属于该分类，无法删除";
		};

		var deleteEnable = function(row) {
			return row["count"] == 0;
		};

		$.backend({
			pageIndex: 1,
			listParams: { id: "{{ $parentid }}" },
			tableStructure: {
				eid: "categoryid",
				columns: [
					{ "key": "categoryid", "header": "编号", "class": "number" },
					{ "key": "name", "header": "名称" },
					{ "key": "count", "header": "软件数量", "class": "count" }
				]
			},
			category: "软件分类",
			operators: [
				"modify",
				{ type: "delete", tip: deleteTip, enable: deleteEnable, text: "删除", css: "btn_delete" }
			],
			modifyStructure: { parentid: "parentid", name: "name" },
			newDisabledFields: disabledFields,
			modifyDisabledFields: disabledFields,
			newDefaultValues: { "parentid": "{{ $parentid }}" },
			validateRule: {
				parentid: {
					required: true
				},
				name: {
					required: true,
					maxlength: 64
				}
			},
			validateMessages: {
				parentid: {
					required: "所属分类不能为空"
				},
				name: {
					required: "名称不能为空",
					maxlength: "名称长度不得超过64"
				}
			}
		});
	});
</script>

@actions (array('title' => $title, 'action' => '分类'))

@search
array('label' => '名称', 'type' => 'textbox', 'name' => 'name')
@endsearch

@dialog
array('label' => '所属分类', 'type' => 'select', 'name' => 'parentid', 'values' => Ca\Consts::$soft_top_categories),
array('label' => '名称', 'type' => 'textbox', 'name' => 'name')
@enddialog