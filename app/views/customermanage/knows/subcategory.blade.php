<script type="text/javascript">
	$(function() {
		var deleteTip = function(row) {
			return deleteEnable(row) ? "删除该子类别" : "子类别下已经有提问, 无法删除";
		};

		var deleteEnable = function(row) {
			return row["count"] == 0;
		};

		$.backend({
			listParams: { "id": '{{ $categoryid }}' },
			tableStructure: {
				eid: "categoryid",
				columns: [
					{ "key": "categoryid", "header": "编号", "class": "number" },
					{ "key": "name", "header": "父类名称", "headertip": "该子类别父类别名称" },
					{ "key": "count", "header": "提问数量", "class": "count", "headertip": "该子类别包含的所有提问数量" }
				]
			},
			category: "子类",
			operators: [
				{ type: "modify", tip: "编辑问答子类别信息", text: "编辑", css: "btn_modify" },
				{ type: "delete", tip: deleteTip, enable: deleteEnable, text: "删除", css: "btn_delete" }
			],
			modifyStructure: { parentid: "parentid", name: "name" },
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

@actions (array('title' => '父级分类: ' . $category->name, 'action' => '子类'))

@search
array('label' => '名称', 'type' => 'textbox', 'name' => 'name', 'placeholder' => '问答子类别名称')
@endsearch

@dialog
array('label' => '父级分类', 'type' => 'hidden', 'name' => 'parentid', 'value_hidden' => $categoryid),
array('label' => '名称', 'type' => 'textbox', 'name' => 'name', 'placeholder' => '问答子类别名称')
@enddialog