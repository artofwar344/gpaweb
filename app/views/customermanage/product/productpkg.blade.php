<script type="text/javascript">
	$(function() {
		var backend = $.backend({
			tableStructure: {
				eid: "pkgid",
				columns: [
					{ "key": "pkgid", "header": "编号", "class": "number" },
					{ "key": "productids_text", "header": "商品包" },
					{ "key": "note", "header": "说明" }
				]
			},
			category: "用户商品包",
			selects: [ ],
			operators: [
				"modify",
//				{ type:'iframe', url: "/productpkg/user?id={eid}", text: "查看用户名单", css: "btn_view", width: "30%", height: "570px", tip: ""  },
				{ type: "delete", tip: "删除", text: "删除", css: "btn_delete" }
			],
			modifyStructure: { productids: "[productids]", note: "note" },
			modifyDialogWidth: 450,
			validateRule: {
				"note": {
					required: true
				}
			},
			validateMessages: {
				"note": {
					required: "说明不能为空"
				}
			}
		});

	});
</script>

@actions (array('title' => '用户商品包管理', 'action' => '用户商品包'))


@dialog
array('label' => '商品', 'type' => 'checklist', 'name' => 'productids', 'values' => \Ca\Service\ProductService::all()),
array('label' => '说明', 'type' => 'textbox', 'name' => 'note')
@enddialog