<script type="text/javascript">
	$(function() {
		var updateConfig = function(eid) {
			$.post("/updateconfig", { "eid": eid }, function(ret) {

			});
		};
		$.backend({
			tableStructure: { eid: "customerid", struct: ["customerid", "name", "alias", "status_text", "module_text"] },
			category: "模块",
			operators: [
				"modify" ,
				{
					text: "更新配置文件", type: "callback", css: "btn_modify", callback: updateConfig,
					enable: function(){return true;}, confirm: true, confirmText: "你确定要更新配置文件吗？"
				}
			],
			modifyStructure: { name: "name", module: "[module]" },
			modifyDialogWidth: 600,
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
@actions ('模块管理', '', array())

@search
array('label' => '名称', 'type' => 'textbox', 'name' => 'name')
@endsearch

@table
array('name' => '编号', 'css' => 'number'),
array('name' => '客户名称'),
array('name' => '别名'),
array('name' => '客户状态', 'css' => 'state'),
array('name' => '模块')
@endtable

@dialog
array('label' => '名称', 'type' => 'textbox', 'name' => 'name'),
array('label' => '模块', 'type' => 'checklist', 'name' => 'module', 'values' => Consts::$module_texts)
@enddialog
