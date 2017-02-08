<script type="text/javascript">
	$(function() {
		var createDatabase = function(eid) {
			$.post("/createdatabase", { "eid": eid }, function() {
				backend.list();
			});
		};

		var deleteDatabase = function(eid) {
			$.post("/deletedatabase", { "eid": eid }, function() {
				backend.list();
			});
		};

		var createEnable = function(row) {
			return row["database_status"] == "2";
		};
		var deleteEnable = function(row) {
			return row["database_status"] == "1";
		};

		var backend = $.backend({
			tableStructure: { eid: "customerid", struct: ["customerid", "name", "alias", "status_text", "database_status_text"] },
			category: "数据库",
			operators: [
				{ text: "创建数据库", type: "callback", css: "btn_modify", callback: createDatabase, enable: createEnable, confirm: true, confirmText: "你确定要创建数据库吗？"},
				{ text: "删除数据库", type: "callback", css: "btn_delete", callback: deleteDatabase, enable: deleteEnable, confirm: true, confirmText: "删除后数据将无法恢复,你确定要删除数据库吗？"}
			],
			modifyStructure: { name: "name", alias: "alias", status: "status" },
			modifyDialogWidth: 600
		});
	});

</script>
@actions ('数据库管理', '数据库', array())

@search
array('label' => '名称', 'type' => 'textbox', 'name' => 'name')
@endsearch

@table
array('name' => '编号', 'css' => 'number'),
array('name' => '客户名称'),
array('name' => '别名'),
array('name' => '客户状态', 'css' => 'state'),
array('name' => '数据库状态', 'css' => 'state')
@endtable

