<script type="text/javascript">
$(function() {
	var viewTarget = function(eid) {
		$.post("/reportquestion/gettarget", { "eid": eid }, function(targetid) {
			window.open("{{ 'http://share.' . app()->env . '/knows/question?id=' }}" + targetid, '_blank');
		});
		return false;
	};

	var disableTarget = function(eid) {
		$.post("/reportquestion/disabletarget", { "eid": eid }, function(ret) {
			backend.list();
		});
		return false;
	};

	var rejectReport = function(eid) {
		$.post("/reportquestion/rejectreport", { "eid": eid }, function(ret) {
			backend.list();
		});
		return false;
	};

	var dealEnable = function(row) {
			return row["status"] == {{ Ca\ReportStatus::pending }};
	};

	var viewEnable = function(row) {
		return row["status"] != {{ Ca\ReportStatus::disabled }};
	};
	var checkboxEnable = function(row) {
		return dealEnable(row);
	};

	var backend = $.backend({
		tableStructure: {
			eid: "reportid",
			checkbox: true,
			checkboxEnable: checkboxEnable,
			columns: [
				{ "key": "reportid", "header": "编号", "class": "number" },
				{ "key": "report_content", "header": "举报内容", "class": "text" },
				{ "key": "type_text", "header": "内容类型" },
				{ "key": "reason_text", "header": "举报原因" },
				{ "key": "reporter_name", "header": "举报人" },
				{ "key": "status_text", "header": "处理情况", "class": "state" },
				{ "key": "createdate", "header": "举报时间", "class": "time" }
			]
		},
		category: "举报状态",
		operators: [
			{ type: "callback", callback: rejectReport, enable: dealEnable, tip: "将该提问的举报驳回", text: "驳回举报", css: "btn_auth" },
			{ type: "callback", callback: disableTarget, enable: dealEnable, tip: "将该提问设为禁用状态", text: "禁用提问", css: "btn_delete" },
			{ type: "callback", callback: viewTarget, enable: viewEnable, tip: "打开目标页面，查看具体内容", text: "查看详情", css: "btn_view" }
		],
		modifyStructure: { status: "status" },
		validateRule: {},
		validateMessages: {}
	});

	$(".multi_actions .button_auth").click(function() {
		if ($(this).hasClass("button_1_disabled")) return false;
		$(this).addClass("button_1_disabled");
		$.post("/reportquestion/rejectreportmulti", { eids: backend.checkedRow() }, function() {
			backend.clearCheckedRow();
			backend.list();
		});
		return false;
	});

	$(".multi_actions .button_disagree").click(function() {
		if ($(this).hasClass("button_1_disabled")) return false;
		$(this).addClass("button_1_disabled");
		$.post("/reportquestion/disabletargetmulti", { eids: backend.checkedRow() }, function() {
			backend.clearCheckedRow();
			backend.list();
		});
		return false;
	});

});
</script>

@actions (array('title' => '举报管理', 'buttons' => array()))

@search
array('label' => '处理情况', 'type' => 'select', 'name' => 'status', 'values' =>  Ca\Consts::$report_status_texts),
@endsearch

<div class="multi_actions">
	<span class="selected"><span class="tip_1" title="选择多条记录批量操作<br/><span class='subtip_1'>可以在多页同时选择</span>"></span> 批量操作: <strong>0</strong> 条</span>
	<a href="#" class="button_1 button_1_disabled button_auth">驳回举报</a>
	<a href="#" class="button_1 button_1_disabled button_disagree">禁用提问</a>
</div>

