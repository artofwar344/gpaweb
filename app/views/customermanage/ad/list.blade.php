<link rel="stylesheet" href="{{ Config::get('app.asset_url') }}/scripts/kindeditor/themes/default/default.css" />
<script type="text/javascript" src="{{ Config::get('app.asset_url') }}/scripts/kindeditor/kindeditor-min.js"></script>
<script type="text/javascript" src="{{ Config::get('app.asset_url') }}/scripts/kindeditor/lang/zh_CN.js"></script>
<script type="text/javascript">
$(function() {
	var updateStatus = function(eid) {
		$.post("/ad/status", { "eid": eid }, function() {
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

	var statusTip = function(row) {
		switch (row["status"] >> 0) {
			case 1:
				return "修改广告状态为\"禁用\"<br/><span class='subtip_1'>禁用后, 该广告不在网站内显示</span>";
			case 2:
				return "修改广告状态为\"可用\"<br/><span class='subtip_1'>启用后, 该广告将在网站内显示</span>";
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
		tableStructure : {
			eid: "adid",
			struct: ["adid","module_text", "name", "link", "target_text", "status_text"],
			columns: [
				{ "key": "adid", "header": "编号", "class": "number" },
				{ "key": "module_text", "header": "站点", "headertip": "广告隶属网站名称" },
				{ "key": "name", "header": "名称", "headertip": "广告在网站上对应位置的名称" },
				{ "key": "link", "header": "链接", "headertip": "广告点击后打开连接" },
				{ "key": "target_text", "header": "打开方式", "headertip": "广告点击后打开方式<br/><strong>新窗口</strong>: 打开新窗口显示广告<br/><strong>当前窗口</strong>: 当前窗口跳转显示广告" },
				{ "key": "status_text", "header": "状态", "class": "state", "headertip": "<strong>可用</strong>: 在网站页面上显示该广告位<br/><strong>禁用</strong>: 在网站页面上不显示该广告位", "valueclass": statusValueClass }
			]
		},
		category : "广告",
		modifyStructure : { module:"module", name: "name", image: "image", link: "link", target: "target", status: "status" },
		modifyDialogWidth: 400,
		operators: [
			{ type: "callback", callback: updateStatus, text: getText, css: "btn_switch", tip: statusTip },
			{ type: "modify", tip: "编辑广告信息", text: "编辑", css: "btn_modify" }
		],
		validateRule : {
			module: {
				required: true
			},
			name: {
				required: true,
				maxlength: 64
			},
			image: {
				required: true,
				url: true
			},
			link: {
				required: true,
				url: true
			},
			target: {
				required: true
			}
		},

		validateMessages : {
			module: {
				required: "请选择站点"
			},
			name: {
				required: "名称不能为空",
				maxlength: "名称长度不得超过64"
			},
			image: {
				required: "图片不能为空",
				url: "图片必须是一个URL地址"
			},
			link: {
				required: "链接不能为空",
				url: "必须是一个URL地址"
			},
			target: {
				required: "不能为空"
			}
		}
	});

	var editor = KindEditor.editor({
		allowFileManager : true,
		fileManagerJson : '/filemanager',
		uploadJson : '/filemanager/upload'
	});
	KindEditor('#image').click(function() {
		editor.loadPlugin('image', function() {
			editor.plugin.imageDialog({
				imageUrl : KindEditor('#image').val(),
				clickFn : function(url, title, width, height, border, align) {
					KindEditor('#image').val(url);
					editor.hideDialog();
				}
			});
		});
	});
});

</script>

@actions (array('title' => '广告管理', 'action' => '广告', 'buttons' => array()))

@search
	array('label' => '名称', 'type' => 'textbox', 'name' => 'name')
@endsearch

@dialog
	array('label' => '站点', 'type' => 'select', 'name' => 'module', 'values' => Ca\Consts::$module_texts),
	array('label' => '名称', 'type' => 'textbox', 'name' => 'name'),
	array('label' => '图片', 'type' => 'textbox', 'name' => 'image'),
	array('label' => '链接', 'type' => 'textbox', 'name' => 'link'),
	array('label' => '打开方式', 'type' => 'select', 'name' => 'target', 'values' => Ca\Consts::$anchor_target),
	array('label' => '状态', 'type' => 'select', 'name' => 'status', 'values' => Ca\Consts::$ad_status_text)
@enddialog