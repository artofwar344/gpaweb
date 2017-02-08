<script type="text/javascript">
	$(function() {
		$.backend({
			tableStructure: {
				eid: "categoryid",
				columns: [
					{ "key": "categoryid", "header": "编号", "class": "number" },
					{ "key": "name", "header": "父类名称" , "headertip": "该类别父类别名称"},
					{ "key": "children_name", "header": "包含子类", "headertip": "该类别所包含的所有子类别" },
					{ "key": "count", "header": "文档数量", "class": "count", "headertip": "该类别包含的所有文档数量<br/><span class='subtip_1'>包含所有子类别相关文档</span>" }
				]
			},
			category: "父类",
			operators: [
				{ type:'iframe', url: "/documentsubcategory?id={eid}", text: "子类别", css: "btn_view", width: "500px", height: "570px", tip: "查看并编辑该类别下的子类别" },
				{ type: "modify", tip: "文档父类是固定类别, 无法编辑", text: "编辑", css: "btn_modify", enable: false },
				{ type: "delete", tip: "文档父类是固定类别, 无法删除", enable: false, text: "删除", css: "btn_delete" }
			],
			modifyStructure: { name: "name", parentid: "parentid" },
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

@actions (array('title' => '文档分类', 'action' => '父类', 'buttons' => array()))

@search
array('label' => '父类名称', 'type' => 'textbox', 'name' => 'name', 'placeholder' => '文档父类别名称')
@endsearch