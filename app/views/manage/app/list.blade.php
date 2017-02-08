<script type="text/javascript">
	$(function() {
		$.backend({
			pageIndex: 1,
			tableStructure: { eid: "appid", struct: ["appid", "name", "guid", "category_name", "type_text", "color_text", "params", "version", "description", "createdate"] },
			category: "应用",
			operators: [ "modify", "delete" ],
			selects: [ "categoryid" ],
			modifyStructure: { name: "name", guid: "guid", categoryid: "categoryid", type: "type", color: "color", params: "params", version: "version", description: "description" },
			validateRule: {
				name: {
					required: true,
					maxlength: 64
				},
				guid: {
					required: true,
					maxlength: 36
				},
				categoryid: {
					required: true
				},
				type: {
					required: true
				},
				color: {
					required: true
				},
				params: {
					required: true
				},
				version: {
					required: true
				}
			},
			validateMessages: {
				name: {
					required: "名称不能为空",
					maxlength: "名称长度不得超过64"
				},
				guid: {
					required: "GUID不能为空",
					maxlength: "GUID长度不得超过36"
				},
				categoryid: { required: "类别不能为空" },
				type: { required: "类型不能为空" },
				color: { required: "颜色不能为空" },
				params: { required: "参数不能为空" },
				version: { required: "版本不能为空" }
			},
			modifyLoad: function(eid, func) {
				var dlgNew = $("#dlg_new");
				var colorInput = $("#color");
				var colorSelector = $("input[type=color]", dlgNew);
				if (colorSelector[0] == null) {
					colorSelector = $("<input />").attr("type", "color").val(" ");
					if (colorSelector.val() != " ") {
						colorInput.hide();
						colorInput.after(colorSelector.css({"border":"none", "width":"32px", "height":"32px"}));
						colorSelector.change(function() {
							colorInput.val($(this).val().replace("#", ""));
						});

					} else colorSelector = null;
				}

				$(document).ajaxStop(function() {
					if (dlgNew.is(":visible")) {
						colorSelector.val("#" + colorInput.val());
					}
				});
				func();
			}
		});
	});
</script>

@actions ('应用管理', '应用')

@search
	array('label' => '名称', 'type' => 'textbox', 'name' => 'name'),
	array('label' => '所属分类', 'type' => 'select', 'name' => 'categoryid')
@endsearch

@table
	array('name' => '编号', 'css' => 'number'),
	array('name' => '名称'),
	array('name' => 'GUID'),
	array('name' => '类别'),
	array('name' => '类型', 'css' => 'state'),
	array('name' => '颜色'),
	array('name' => '参量'),
	array('name' => '版本'),
	array('name' => '描述', 'css' => 'text'),
	array('name' => '创建时间', 'css' => 'time')
@endtable

@dialog
	array('label' => '名称', 'type' => 'textbox', 'name' => 'name'),
	array('label' => 'GUID', 'type' => 'textbox', 'name' => 'guid'),
	array('label' => '所属分类', 'type' => 'select', 'name' => 'categoryid'),
	array('label' => '类型', 'type' => 'select', 'name' => 'type', 'values' => Consts::$app_type_texts),
	array('label' => '颜色', 'type' => 'textbox', 'name' => 'color'),
	array('label' => '版本', 'type' => 'textbox', 'name' => 'version'),
	array('label' => '参数', 'type' => 'textbox', 'name' => 'params'),
	array('label' => '描述', 'type' => 'textarea', 'name' => 'description')
@enddialog