<link href="{{ Config::get('app.asset_url') . 'scripts/Validation-Engine/validationEngine.jquery.css?' . Ca\Consts::$ca_version }}" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="{{ Config::get('app.asset_url') }}scripts/Validation-Engine/jquery.validationEngine-zh_CN.js"></script>
<script type="text/javascript" src="{{ Config::get('app.asset_url') }}scripts/Validation-Engine/jquery.validationEngine.js"></script>

@if (Auth::check())
<script type="text/javascript">
	$(function() {
		$(".actions .btn_apply").click(function() {
			var btnApply = $(this);
			if (btnApply.hasClass("button_2_disabled")) {
				return false;
			}
			btnApply.text("正在报名").addClass("button_2_disabled").removeClass("btn_apply");
			$.post("/meeting/apply", { "id": '{{ $meeting->meetingid }}' }, function() {
				btnApply.text("报名成功").removeAttr("href");
			}, "json");
			return false;
		});

		var commentForm = $("form#commentForm");
		commentForm.validationEngine({
			validationEventTrigger: '', //触发验证的事件,提交时验证
			"scroll": false,
			"autoHidePrompt": true,
			"autoHideDelay": 5000,
			"custom_error_messages": {
				"#content": {
					"required":   { "message": "请填写评论内容" }
				}
			}
		});
		$(".actions .btn_submit", commentForm).click(function() {
			if ($(this).hasClass("button_1_disabled")) {
				return false;
			}
			if (commentForm.validationEngine("validate")) {
				$(this).addClass("button_1_disabled");
				$("span", $(this)).text("正在提交");
				commentForm.validationEngine("detach");
				commentForm.submit();
			}
			return false;
		});
	});
</script>
@endif

@include('share.partials.common.report_block', array('data' => Ca\Consts::$report_reason_text, 'dialogTitle' => '举报评论', 'reportUrl' => '/meeting/report'))
<div class="spacer_1"></div>
<div class="frame_1 home_content">
	<div class="frame_1_l">
		<div class="meeting_page">
			<div class="header_5"><a href="{{ url('meeting/detail?id=' . $meeting->meetingid) }}">{{ $meeting->name }}</a></div>
			<div class="detail">
				<table class="info">
					<tr><td><label>费用: </label></td><td><span>{{ $meeting->cost == 0 ? '免费' : $meeting->cost . '元' }}</span></td></tr>
					<tr><td><label>地点: </label></td><td><span>{{ $meeting->address }}</span></td></tr>
					<tr><td><label>报名结束: </label></td><td><span>{{ $meeting->enrolldate }}</span></td></tr>
					<tr><td><label>开始: </label></td><td><span>{{ $meeting->begindate }}</span></td></tr>
					<tr><td><label>联系人: </label></td><td><span>{{ $meeting->contactname }}</span></td></tr>
					<tr><td><label>联系电话: </label></td><td><span>{{ $meeting->contactphone }}</span></td></tr>
					<tr><td><label>联系邮箱: </label></td><td><span>{{ $meeting->contactemail }}</span></td></tr>
				</table>
				<div class="actions">
					@if ($applied)
						<a class="button_1 button_1_disabled">已报名</a>
					@elseif (strtotime($meeting->enrolldate) < time())
						<a class="button_1 button_1_disabled">已结束</a>
					@else
						<a href="{{ !Auth::check() ? 'http://user.' . app()->environment() . '/login?ret=' . urlencode(URL::full()) : '#' }}" class="button_1 btn_apply">报名参加</a>
					@endif
					<span class="count">已有 <strong>{{ $meeting->enroll_count }}</strong> 人报名</span>
				</div>
			</div>
			<div class="content">{{ $meeting->intro }}</div>
		</div>

		@if (strtotime($meeting->enrolldate) < time())
			<div class="spacer_1"></div>
			<h2 class="header_6"><a name="comment" href="#comment">用户评论</a></h2>
			@if ($comments->getTotal() > 0)
			<div class="answer_list">
				@foreach ($comments as $comment)
				<div class="answer">
					<div class="avatar"></div>
					<div class="content">
						<span class="corner"></span>
						{{ $comment->content }}
						<div class="info">
							<span>
								@if (!Auth::check())
								<a href="{{ 'http://user.' . app()->environment() . '/login?ret=' . urlencode(URL::full()) }}" class="report">举报</a>
								@elseif ($comment->isReported)
								<a class="report disabled" href="#">已举报</a>
								@else
								<a tid="{{ $comment->commentid }}" rtype="{{ \Ca\ReportType::comment }}" class="report" href="#">举报</a>
								@endif
							</span> |
							<span>{{ $comment->userName }}</span>
							<span>于{{ Ca\Common::time_ago($comment->createdate) }}</span>
						</div>
					</div>
					<div class="clear"></div>
				</div>
				@endforeach
				@if ($comments->getLastPage() > 1)
				{{ $comments->appends(array('id' => $meeting->meetingid))->addAnchor('comment')->links() }}
				@endif
			</div>
			@else
			<div class="answer_list none"><div class="answer" style="height:80px; line-height:80px; text-align:center;">暂无评论！</div></div>
			@endif

			@if ((Auth::check() && $applied) || !Auth::check())
			<div class="spacer_1"></div>
			<div class="meeting_comment">
				@if (Auth::check() && $applied)
				<form action="/meeting/comment" id="commentForm" method="post">
					<input type="hidden" name="meetingid" value="{{ $meeting->meetingid }}" />
					<input type="hidden" name="_token" value="{{ csrf_token() }}">
					<textarea class="textbox_1 validate[required]" placeholder="请填写评论" id="content" name="content"></textarea>
					<span class="error"></span>
					<div class="spacer_1"></div>
					<div class="actions">
						<a href="#" class="button_1 btn_submit"><span>提交评论</span></a>
					</div>
				</form>
				@else
				<div class="textarea_disabled">
					<div class="login">
						<a href="http://user.{{ app()->environment() }}/login?ret={{ urlencode(URL::full()) }}">登录后评论</a> |
						<a href="http://user.{{ app()->environment() }}/register">还未注册</a>
					</div>
				</div>
				@endif
			</div>
			@endif
		@endif
	</div>

	<div class="frame_1_r">
		{{ Ca\Service\AdService::show('210w_ad_meeting1', 1, 'ad_1') }}
		@include ('share.partials.side.meeting_hot')
		<div class="spacer_1"></div>
		{{ Ca\Service\AdService::show('210w_ad_meeting2', 1, 'ad_1') }}
		@include ('share.partials.side.meeting_tag_cloud')
		<div class="spacer_1"></div>
		@include('share.partials.side.document_rank', array('documents' => $hot_document, 'rankTitle' => '文档排行榜'))
	</div>
	<div class="clear"></div>
</div>