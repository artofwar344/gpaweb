@if (Auth::check())
<script type="text/javascript">
$(function() {
	var reportUrl = "{{ $reportUrl }}";
	var dialogReport = $("div#dialogReport");
	var dialogReported = $("div#dialogReported");
	dialogReported.dialog(dialogParams);
	dialogParams.width = 350;
	dialogReport.dialog(dialogParams);

	var targetId;
	var reportType;
	var reportBtn;
	$(".report").click(function() {
		reportBtn = $(this);
		if (reportBtn.hasClass("disabled")) {
			return false;
		}
		targetId = reportBtn.attr("tid");
		reportType = reportBtn.attr("rtype");
		reportBtn.addClass("disabled");
		$(".submit", dialogReport).removeClass("button_1_disabled");
		$(".submit span", dialogReport).text("确定");
		$("input[name='report_type']:checked", dialogReport).prop("checked", false);
		$(".messages", dialogReport).hide();
		dialogReport.dialog("open");
		return false;
	});

	$("input[name='report_type']", dialogReport).click(function() {
		$(".messages", dialogReport).hide();
	});

	$(".submit", dialogReport).click(function() {
		var reason = $("input[name='report_type']:checked", dialogReport).val();
		if (!reason) {
			$(".messages", dialogReport).html("").append($("<li />").html("请选择举报类型")).show();
			return false;
		}
		$(this).addClass("button_1_disabled");
		$("span", this).text("处理中");
		$.post(reportUrl, { "id": targetId, "reason": reason, "type": reportType }, function(ret) {
			if (ret.status == 1) {
				reportBtn.text("已举报");
				dialogReport.dialog("close");
				dialogReported.dialog("open");
			} else {
				reportBtn.removeClass("disabled");
			}
			return false;
		}, "json");
		return false;
	});

	$(".close", dialogReport).click(function() {
		reportBtn.removeClass("disabled");
	});

	$(".submit", dialogReported).click(function() {
		dialogReported.dialog("close");
		return false;
	});

});
</script>

<div class="dialog_1 dialogReport" id="dialogReport">
	<div class="header"><span>{{ $dialogTitle }}</span><a class="close"></a></div>
	<ul class="messages"></ul>
	<div class="content">
		<ul>
			@foreach ($data as $value => $text)
			<li><label><input name="report_type" value="{{ $value }}" type="radio" /><span> {{ $text }} </span></label></li>
			@endforeach
		</ul>
	</div>
	<div class="actions">
		<a href="#" class="button_1 submit"><span>确定</span></a>
		<a class="close" href="#">取消</a>
	</div>
</div>
<div class="dialog_1" id="dialogReported">
	<div class="header"><span>举报问答</span><a class="close"></a></div>
	<div class="content">
		<p class="confirm">举报信息已发送成功, 请等待管理员处理。</p>
	</div>
	<div class="actions">
		<a href="#" class="button_1 submit"><span>确定</span></a>
	</div>
</div>
@endif