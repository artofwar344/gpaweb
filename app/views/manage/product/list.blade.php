<script type="text/javascript">
	$(function() {
		var updateStatus = function(eid) {
			$.post("/product/status", { "eid": eid }, function() {
				backend.list();
			});
		};

		var getText = function(row) {
			switch (row["status"] >> 0) {
				case 1:
					return [ "禁用", true ];
				case 2:
					return [ "启用", false ];
			}
			return "";
		};
		var backend = $.backend({
			pageIndex: 1,
			tableStructure: { eid: "productid", struct: ["productid", "name", "intro", "type", "status_text"]  },
			category: "商品",
			operators: [ { type: "callback", callback: updateStatus, text: getText, css: "btn_switch" } ],
			modifyStructure: { status: "status" }
		});
	});
</script>

@actions ('商品管理', '', array())

@search
array('label' => '名称', 'type' => 'textbox', 'name' => 'name')
@endsearch

@table
array('name' => '编号', 'css' => 'number'),
array('name' => '名称'),
array('name' => '描述', 'css' => 'text'),
array('name' => '激活模式', 'css' => 'state'),
array('name' => '状态', 'css' => 'state')
@endtable