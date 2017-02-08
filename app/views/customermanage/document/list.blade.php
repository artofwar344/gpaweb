<script type="text/javascript">
var visible = @if (Input::get("inner")) false @else true @endif;
$(function() {
	var statusValueClass = function(row) {
		var ret = "";
		switch (parseInt(row["status"])) {
			case 1:
				ret = "green";
				break;
			case 2:
				ret = "red";
				break;
			case 3:
				ret = "blue";
				break;
			case 4:
				ret = "red";
				break;
		}

		return ret;
	};
	var pass = function(eid) {
		$.post("/document/pass", { "eid": eid }, function(ret) {
			backend.list();
		});
		return false;
	};

	var reject = function(eid) {
		$.post("/document/reject", { "eid": eid }, function(ret) {
			backend.list();
		});
		return false;
	};

	var dealEnable = function(row) {
		return row["publish"] == {{ Ca\DocumentPublish::public_d }};
	};

	var checkboxEnable = function(row) {
		return dealEnable(row);
	};

	var backend = $.backend({
		tableStructure: {
			eid: "documentid",
			checkbox: true,
			checkboxEnable: checkboxEnable,
			columns: [
				{ "key": "documentid", "header": "编号", "class": "number" },
				{ "key": "name", "header": "标题" },
				{ "key": "category_name", "header": "所属分类", "visible": visible },
				{ "key": "extension", "header": "文件类型" },
				{ "key": "user_name", "header": "上传用户" },
				{ "key": "createdate", "header": "上传时间", "class": "time" },
				{ "key": "status_text", "header": "文档状态", "valueclass": statusValueClass, "headertip": "<strong>成功</strong>: 文档转换为网页可浏览文件已成功<br/><strong>删除</strong>: 文档已删除<br/><strong>处理中</strong>: 正在转换文档为网页可浏览文件<br/><strong>处理失败</strong>: 转换文档为网页可浏览文件失败" },
				{ "key": "publish_text", "header": "发布状态", "headertip": "<strong>私有</strong>: 该文档只能发布者查看<br/><strong>等待审核</strong>: 发布者将公开该文档, 等待管理员审核<br/><strong>审核通过</strong>: 文档已通过管理员审核, 已为公开文档" },
				{ "key": "type_text", "header": "所属板块", "headertip": "文档在哪些板块中显示" },
				{ "key": "views", "header": "浏览量", "class": "count", "headertip": "该文档在网站上浏览总数" }
			]
		},
		category: "文档",
		selects: [ "categoryid", "type" ],
		modifyStructure: { name: "name", publish: "publish", type: "[type]" },
		operators: [
			{ type: "callback", callback: pass, enable: dealEnable, tip: "将该文档发布状态设为已发布", text: "审核通过", css: "btn_auth" },
			{ type: "callback", callback: reject, enable: dealEnable, tip: "将该文档发布状态设为私有", text: "驳回", css: "btn_delete" },
			{ type: "modify", tip: "编辑文档信息", text: "编辑", css: "btn_modify" },
			{ type: "delete", tip: "删除文档", text: "删除", css: "btn_delete" }
		],
		validateRule: {
			name: {
				required: true,
				maxlength: 128
			},
			customerid: {
				required: true
			}
		},
		validateMessages: {
			name: {
				required: "名称不能为空",
				maxlength: "名称长度不得超过128"
			},
			customerid: {
				required: "请选择所属客户"
			}
		}
	});

	$(".multi_actions .button_auth").click(function() {
		if ($(this).hasClass("button_1_disabled")) return false;
		$(this).addClass("button_1_disabled");
		$.post("/document/passmulti", { eids: backend.checkedRow() }, function() {
			backend.clearCheckedRow();
			backend.list();
		});
		return false;
	});

	$(".multi_actions .button_disagree").click(function() {
		if ($(this).hasClass("button_1_disabled")) return false;
		$(this).addClass("button_1_disabled");
		$.post("/document/rejectmulti", { eids: backend.checkedRow() }, function() {
			backend.clearCheckedRow();
			backend.list();
		});
		return false;
	});

});
</script>

@actions (array('title' => '文档管理', 'action' => '文档', 'buttons' => array()))

@search
	array('label' => '标题', 'type' => 'textbox', 'name' => 'name', 'placeholder' => '文档标题'),
	array('label' => '所属分类', 'type' => 'select', 'name' => 'categoryid'),
	array('label' => '类型', 'type' => 'select', 'name' => 'type'),
	array('label' => '文档状态', 'type' => 'select', 'name' => 'status', 'values' => Ca\Consts::$document_status_texts),
	array('label' => '发布状态', 'type' => 'select', 'name' => 'publish', 'values' => Ca\Consts::$document_publish_texts)
@endsearch
<div class="multi_actions">
	<span class="selected"><span class="tip_1" title="选择多条记录批量操作<br/><span class='subtip_1'>可以在多页同时选择</span>"></span> 批量操作: <strong>0</strong> 条</span>
	<a href="#" class="button_1 button_1_disabled button_auth">通过</a>
	<a href="#" class="button_1 button_1_disabled button_disagree">驳回</a>
</div>
@dialog
	array('label' => '标题', 'type' => 'textbox', 'name' => 'name', 'placeholder' => '文档标题'),
	array('label' => '发布状态', 'type' => 'select', 'name' => 'publish', 'values' => Ca\Consts::$document_publish_texts),
	array('label' => '所属板块', 'type' => 'checklist', 'name' => 'type', 'values' => Ca\Consts::$document_type_texts)
@enddialog