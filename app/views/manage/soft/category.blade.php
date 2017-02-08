<script type="text/javascript">
	$(function() {
		var viewSoftEnable = function(row) {
			return row["count"] > 0;
		};
		$.backend({
			pageIndex: 1,
			tableStructure: {
				eid: "parentid",
				columns: [
					{ "key": "parentid", "header": "编号", "class": "number" },
					{ "key": "parentid_text", "header": "父类名称", "headertip": "该父类别名称" },
					{ "key": "children_name", "header": "包含子类", "headertip": "该类别所包含的所有子类别" },
					{ "key": "count", "header": "软件数量", "class": "count", "headertip": "该类别包含的所有软件数量<br/><span class='subtip_1'>包含所有子类别软件</span>" }
				]
			},
			category: "软件分类",
			operators: [
				{ type:'iframe', url: "/soft?id={eid}", text: "查看软件", enable: viewSoftEnable, css: "btn_view", width: "90%", height: "570px", tip: "查看并编辑该类别及其子类下的软件"  },
				{ type:'iframe', url: "/softsubcategory?id={eid}", text: "子类别", css: "btn_view", width: "500px", height: "570px", tip: "查看并编辑该类别下的子类别" }
			],
			modifyStructure: { parentid: "parentid", name: "name" },
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
@actions (array('title' => '软件分类', 'action' => '分类', 'buttons' => array()))



@dialog
array('label' => '所属分类', 'type' => 'select', 'name' => 'parentid', 'values' => Ca\Consts::$soft_top_categories),
array('label' => '名称', 'type' => 'textbox', 'name' => 'name')
@enddialog