<script type="text/javascript">
$(function() {
	var deleteTip = function(row) {
		return deleteEnable(row) ? "删除该类别" : "类别下已经有提问, 无法删除";
	};

	var deleteEnable = function(row) {
		return row["count"] == 0;
	};

	$.backend({
		tableStructure: {
			eid: "categoryid",
			columns: [
				{ "key": "categoryid", "header": "编号", "class": "number" },
				{ "key": "name", "header": "父类名称", "headertip": "该父类别名称" },
				{ "key": "children_name", "header": "包含子类", "headertip": "该类别所包含的所有子类别" },
				{ "key": "count", "header": "提问数量", "class": "count", "headertip": "该类别包含的所有提问数量<br/><span class='subtip_1'>包含所有子类别相关提问</span>" }
			]
		},
		category: "父类",
		modifyStructure: { name: "name", parentid: "parentid" },
		operators: [
			{ type:'iframe', url: "/knowssubcategory?id={eid}", text: "子类别", css: "btn_view", width: "500px", height: "570px", tip: "查看并编辑该类别下的子类别" },
			{ type: "modify", tip: "编辑文档父类别信息", text: "编辑", css: "btn_modify" },
			{ type: "delete", tip: deleteTip, enable: deleteEnable, text: "删除", css: "btn_delete" }
		],
		validateRule: {
			name: {
				required: true,
				maxlength: 64
			}
		},
		validateMessages: {
			name: {
				required: "父类名称不能为空",
				minlength: "父类名称长度不得超过64"
			}
		}
	});
});
</script>

@actions (array('title' => '问答分类', 'action' => '父类'))

@search
array('label' => '父类名称', 'type' => 'textbox', 'name' => 'name', 'placeholder' => '问答父类别名称')
@endsearch

@dialog
array('label' => '父类名称', 'type' => 'textbox', 'name' => 'name', 'placeholder' => '问答父类别名称')
@enddialog