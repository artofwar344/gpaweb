<script type="text/javascript">
	var faqRole @if (ManagerService::check_role('faq')) = true @endif;
	$(function() {
		var deleteEnable = function(row) {
			return row["count"] == 0;
		};
		$.backend({
			tableStructure: {
				eid: "categoryid",
					columns: [
					{ "key": "categoryid", "header": "编号", "class": "number" },
					{ "key": "name", "header": "名称" },
					{ "key": "count", "header": "FAQ数量", "class": "count" }
				]
			},
			category: "FAQ分类",
			selects: [],
			modifyStructure: { name: "name" },
			operators: [
				faqRole ? { type: "iframe", url: "/faq?id={eid}", text: "FAQ列表", css: "btn_view", width: "80%", height: "700px" , tip: "查看该部门的激活分配信息" } : "",
				{ type: "modify", tip: "编辑FAQ分类", text: "编辑", css: "btn_modify" },
				{ type: "delete", tip: "删除FAQ分类", enable: deleteEnable, text: "删除", css: "btn_delete" }
			],
			validateRule: {
				name: {
					required: true,
					maxlength: 64
				}
			},
			validateMessages: {
				name: {
					required: "名称不能为空",
					maxlength: "名称长度不得超过64"
				}
			}
		});
	});
</script>

@actions (array('title' => 'FAQ分类', 'action' => '分类', 'tooltip' => '管理 FAQ 分类'))

@search
array('label' => '名称', 'type' => 'textbox', 'name' => 'name')
@endsearch

@dialog
array('label' => '名称', 'type' => 'textbox', 'name' => 'name')
@enddialog