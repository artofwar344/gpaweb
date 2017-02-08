<link rel="stylesheet" href="{{ Config::get('app.asset_url') }}scripts/kindeditor/themes/default/default.css" />
<script type="text/javascript" src="{{ Config::get('app.asset_url') }}scripts/kindeditor/kindeditor-min.js"></script>
<script type="text/javascript" src="{{ Config::get('app.asset_url') }}scripts/kindeditor/lang/zh_CN.js"></script>
<script type="text/javascript">
	$(function() {
		var updateStatus = function(eid) {
			$.post("/soft/status", { "eid": eid }, function() {
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

		var statusValueClass = function(row) {
			var ret = "";
			switch (parseInt(row["status"])) {
				case 1:
					ret = "green";
					break;
				case 2:
					ret = "red";
					break;
			}

			return ret;
		};

		var backend = $.backend({
			listParams: { "view_parentid": "{{ $parentid }}" },
			selectsParams: { "view_parentid": "{{ $parentid }}" },
			tableStructure: {
				eid: "softid",
				columns: [
					{ "key": "softid", "header": "编号", "class": "number" },
					{ "key": "softid_text", "header": "图标" },
					{ "key": "name", "header": "名称" },
					{ "key": "category_name", "header": "分类" },
					{ "key": "language_text", "header": "软件语言" },
					{ "key": "licensetype_text", "header": "授权类型" },
					{ "key": "platform", "header": "软件平台" },
					{ "key": "version", "header": "当前版本" },
					{ "key": "filesize_text", "header": "文件大小" },
					{ "key": "bit_text", "header": "位数" },
					{ "key": "brief", "header": "简介", "class": "text" },
					{ "key": "type_text", "header": "类型" },
					{ "key": "status_text", "header": "状态", "valueclass": statusValueClass },
					{ "key": "updatedate", "header": "更新时间", "class": "time" }
				]
			},
			category: "软件",
			selects: [ "categoryid", "type" ],
			operators: [
				{ type: "callback", callback: updateStatus, text: getText, css: "btn_switch" },
				"modify"
			],
			modifyStructure: { name: "name", productcode: "productcode", categoryid: "categoryid", language: "language", licensetype: "licensetype", platform: "platform", type: "[type]", status: "status", brief: "brief", description: "description", bit: "bit" },
			modifyUnvalidateRules: [ "icon" ],
			validateRule: {
				name: {
					required: true,
					maxlength: 64
				},
				icon: {
					required: false,
					accept: false
				},
				categoryid: {
					required: true
				},
				language: {
					required: true
				},
				licensetype: {
					required: true
				},
				platform: {
					required: true
				},
				status: {
					required: true
				},
				version: {
					required: true
				},
				bit: {
					required: true
				},
				brief: {
					required: true
				},
				description: {
					required: true
				}
			},
			validateMessages: {
				name: {
					required: "名称不能为空",
					maxlength: "名称长度不得超过64"
				},
				productcode: {
					required: "产品代码不能为空"
				},
				categoryid: { required: "类别不能为空" },
				language: { required: "软件语言不能为空" },
				licensetype: { required: "授权类型不能为空" },
				platform: { required: "软件平台不能为空" },
				status: { required: "状态不能为空" },
				bit: { required: "请选择位数" },
				version: { required: "版本不能为空" },
				brief: { required: "描述不能为空" },
				description: { required: "详细介绍不能为空" }
			}
		});

		$("#description").width("100%").height(200);
		var editor = KindEditor.create('#description', {
			allowFileManager : true,
			fileManagerJson : "/filemanager",
			uploadJson : "/filemanager/upload",
			afterChange : function() {
				$("#description").val(this.html());
			}
		});

		$("#dlg_new").on("dialogopen", function(event, ui) {
			var dlgNew = $("#dlg_new");

			var width = 920;
			dlgNew.parent(".ui-dialog").width(width).css({'left': ($(window).width() - width) / 2});
			editor.html($("#description").val());

			var softIcon = $("#icon").attr("accept", ".png");
			$("input[name=icon_data]", dlgNew).val("");
			$("img", softIcon.parent()).remove();
			var eid = $(this).attr("eid");
			if (eid) {
				var iconUrl = "{{ Config::get('app.asset_url') }}images/softicon/" + eid + ".png";
				softIcon.before($("<img />").attr("src", iconUrl).css({"width":"32px", "height":"32px"}));
			}
		});

		$("#icon").change(function(evt) {
			var file = evt.target.files[0];
			var reader = new FileReader();

			reader.file = file;
			reader.onloadend = function(evt) {
				var binaryData = evt.currentTarget.result;
				var binaryDataContainer = $("input[name=icon_data]", $("#dlg_new"));
				if (binaryDataContainer.length == 0) {
					$("#icon").after($("<input />").attr("type", "hidden").val(binaryData).attr("name", "icon_data"));
				} else {
					binaryDataContainer.val(binaryData);
				}
			};
			reader.readAsDataURL(file);
		});
	});

</script>

@actions (array('title' => $title, 'action' => '软件'))

@search
array('label' => '名称', 'type' => 'textbox', 'name' => 'name'),
array('label' => '所属分类', 'type' => 'select', 'name' => 'categoryid'),
array('label' => '类型', 'type' => 'select', 'name' => 'type')
@endsearch

@dialog
	array('label' => '名称', 'type' => 'textbox', 'name' => 'name'),
	array('label' => '图标', 'type' => 'file', 'name' => 'icon'),
	array('label' => '产品代码', 'type' => 'textbox', 'name' => 'productcode'),
	array('label' => '所属分类', 'type' => 'select', 'name' => 'categoryid'),
	array('label' => '软件语言', 'type' => 'select', 'name' => 'language', 'values' => Ca\Consts::$soft_language_texts),
	array('label' => '授权类型', 'type' => 'select', 'name' => 'licensetype', 'values' => Ca\Consts::$soft_licensetype_texts),
	array('label' => '位数', 'type' => 'select', 'name' => 'bit', 'values' => Ca\Consts::$soft_bits),
	array('label' => '软件平台', 'type' => 'textbox', 'name' => 'platform'),
	array('label' => '描述', 'type' => 'textarea', 'name' => 'brief'),
	array('label' => '详细信息', 'type' => 'textarea', 'name' => 'description'),
	array('label' => '类型', 'type' => 'checklist', 'name' => 'type', 'values' => Ca\Consts::$soft_type_texts),
	array('label' => '状态', 'type' => 'select', 'name' => 'status', 'values' => Ca\Consts::$soft_status_texts)
@enddialog