<link href="{{ Config::get('app.asset_url') . 'css/ubbeditor.css' }}" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="{{ Config::get('app.asset_url') }}scripts/wysibb/jquery.wysibb.min.js"></script>
<script type="text/javascript" src="{{ Config::get('app.asset_url') }}scripts/categorySelector.js"></script>

<script>
$(function() {
	var form = $("form#answerForm");
	var updateQuestionForm = $("form#updateQuestionForm");
	var updateCategoryForm = $("form#updateCategoryForm");
	var containerAskMore = $(".container_ask_more");
	var askMoreForm = $("form", containerAskMore);
	var questionId = "{{ $question->questionid }}";
	var dialogAccept = $("div#dialogAccept");
	var questionUpdate =$(".question .question_update");
	var dialogParams = { autoOpen: false, modal: true, resizable: false, width: 285, height: "auto", minHeight: 0 };
	dialogAccept.dialog(dialogParams);

	$("#content").wysibb({
		buttons: "bold,italic,underline,strike,img,link,removeFormat"
	});
	$(".knows_page .answer textarea, .wysibb-text-editor").focus();

	//收藏
	@if (Auth::check() && !$isfavorite)
		$(".fav .btn_2_fav").on("click", function() {
			var self = $(this);
			self.addClass("button_2_disabled").text("收藏中");
			$.post("{{ '/knows/favorites' }}", { "id": "{{ $question->questionid }}" }, function(ret) {
				self.text("已收藏");
			}, "json");
			return false;
		});
	@endif


	//修改问题内容
	$(".btn_update_question").click(function() {
		if ($(this).hasClass("button_1_disabled"))  return false;
		questionUpdate.hide();
		$(".update_question").slideDown("fast");
		return false;
	});

	$(".btn_update_category").click(function() {
		if ($(this).hasClass("button_1_disabled"))  return false;
		$(".error", updateCategoryForm).empty();
		questionUpdate.hide();
		$(".update_category").slideDown("fast");
		return false;
	});

	$(".btn_cancel_update").click(function() {
		questionUpdate.slideUp("fast");
		return false;
	});

	$("#textarea_update_question").wysibb({
		buttons: "bold,italic,underline,strike,img,link,removeFormat"
	});

	$(".actions .btn_submit", updateQuestionForm).click(function() {
		if ($(this).hasClass("button_1_disabled"))  return false;
		$("textarea", updateQuestionForm).sync();
//		if ($.trim($("textarea", updateQuestionForm).val()) == "") {
//			$(".error", updateQuestionForm).text("内容不能为空");
//			return false;
//		}
		$(this).addClass("button_1_disabled");
		$("span", this).text("处理中");
		updateQuestionForm.submit();
		return false;
	});

	//修改分类
	var settings = {
		"categories": jQuery.parseJSON('{{ json_encode($categories) }}'),
		"baseCss": { height: "180px" }
	};
	var categorySelector = $(".category_selector").categorySelector(settings);

	$(".actions .btn_submit", updateCategoryForm).click(function() {
		if ($(this).hasClass("button_1_disabled"))  return false;
		var info = $(".error", updateCategoryForm);
		info.empty();
		if (categorySelector.selectedCategory == null) {
			info.html("你还未选择分类");
			return false;
		}
		updateCategoryForm.append(
			$("<input/>").attr({ "type": "hidden", "name": "questionid" }).val("{{ $question->questionid }}"),
			$("<input/>").attr({ "type": "hidden", "name": "categoryid" }).val(categorySelector.selectedCategory)
		);
		$(this).addClass("button_1_disabled");
		$("span", this).text("处理中");
		updateCategoryForm.submit();
		return false;
	});
	//end修改分类

	//回答问题
	$(".btn_answer").click(function() {
		var form = $(this).closest("form");
		$("textarea", form).sync();
		if ($.trim($("textarea", form).val()) == "") {
			$(".error", form).text("内容不能为空");
			return false;
		}
		$(this).addClass("button_1_disabled");
		$("span", this).text("处理中");
		form.submit();
		return false;
	});

	//采纳提问
	var acceptId = null;
	$(".btn_accept").click(function() {
		acceptId =  $(this).attr('aid');
		$(".submit", dialogAccept).removeClass("button_1_disabled");
		$(".submit span", dialogAccept).text("确定");
		dialogAccept.dialog("open");
		return false;
	});
	$(".actions .submit", dialogAccept).click(function() {
		$(this).addClass("button_1_disabled");
		$("span", this).text("处理中");
		$.post("{{ url('/knows/accept') }}", { "answerid": acceptId }, function(ret) {
			document.location.href = "{{ '/knows/question?id=' . $question->questionid }}";
		}, "json");
		return false;
	});

	//继续追问和回答

	$("textarea", askMoreForm).wysibb({
		buttons: "bold,italic,underline,strike,img,link,removeFormat"
	});

	$(".answer .btn_ask_more").click(function() {
		var answerId = $(this).attr("aid");
		var type = $(this).text() == "继续追问" ? "{{ \Ca\AnswerType::askMore }}": "{{ \Ca\AnswerType::answerMore }}";
		$(":input[name='answerid']", askMoreForm).val(answerId);
		$(":input[name='questionid']", askMoreForm).val(questionId);
		$(":input[name='type']", askMoreForm).val(type);
		containerAskMore.appendTo($(this).closest(".answer")).show();
		return false;
	});


	$(".answer .btn_cancel_ask").click(function() {
		var typeText = $(this).text() == "取消追问" ? "追问" : "回答";
		containerAskMore.hide();
		$(this).removeClass("btn_cancel_ask")
			.addClass("btn_ask_more")
			.text("继续" + typeText);
		return false;
	});

	$(".btn_sub_ask_more", askMoreForm).click(function() {
		$("textarea", askMoreForm).sync();
		if ($.trim($("textarea", askMoreForm).val()) == "") {
			$(".error", askMoreForm).text("内容不能为空");
			return false;
		}
		$(this).addClass("button_1_disabled");
		$("span", this).text("处理中");
		askMoreForm.submit();
		return false;
	});

	$(".btn_cancel_ask_more", containerAskMore).click(function() {
		containerAskMore.hide();
		var typeText = $(".btn_cancel_ask").text() == "取消追问" ? "追问" : "回答";
		$(".btn_cancel_ask")
			.addClass("btn_ask_more")
			.removeClass("btn_cancel_ask")
			.text("继续" + typeText);
		return false;
	});

	$(".wysibb-text-editor").on("focus", function() {
		$(this).closest(".wysibb").addClass("wysibb_hot");
	}).on("blur", function() {
		$(this).closest(".wysibb").removeClass("wysibb_hot");
	});

	//end继续追问和回答

});
</script>

@include('share.partials.common.report_block', array('data' => Ca\Consts::$report_reason_text, 'dialogTitle' => '举报问答', 'reportUrl' => '/knows/report'))

<div class="dialog_1" id="dialogAccept">
	<div class="header"><span>采纳回答</span><a class="close"></a></div>
	<div class="confirm">是否采纳该回答?</div>
	<div class="actions">
		<a href="#" class="button_1 submit"><span>确定</span></a>
		<a class="close" href="#">取消</a>
	</div>
</div>

<div class="container_ask_more">
	<form action="/knows/askmore" method="post">
		<input type="hidden" name="answerid">
		<input type="hidden" name="questionid">
		<input type="hidden" name="type">
		<input type="hidden" name="_token" value="{{ csrf_token() }}">
		<textarea class="textbox_1" name="ask_more_content"></textarea>
		<span class="error"></span>
		<div class="actions">
			<a href="#" class="button_1 btn_sub_ask_more"><span>提交</span></a>&nbsp;&nbsp;
			<a href="#" class="btn_cancel_ask_more">取消</a>
		</div>
	</form>
</div>


<div class="spacer_1"></div>
<div class="frame_1">
	<div class="frame_1_l">
		<div class="knows_page">
			<div class="question">
				<h1 class="header_5"><span class="icon icon_question"></span><a href="{{ '/knows/question?id=' . $question->questionid }}">{{ Ca\Service\SensitiveService::replace($question->title) }}</a></h1>
				<div class="info">
					<span>{{ $question->user_name }}</span>
					<span>于{{ Ca\Common::time_ago($question->createdate) }}</span> |
					<span class="category"><a href="{{ '/knows/list/' . $question->categoryid }}">{{ $question->category_name }}</a></span> |
					<span>浏览: {{ $question->views }}</span> |
					<span>
						@if (!Auth::check())
						<a href="{{ 'http://user.' . app()->environment() . '/login?ret=' . urlencode(URL::full()) }}" class="report">举报</a>
						@elseif ($isReported)
						<a class="report disabled" href="#">已举报</a>
						@else
						<a class="report" href="#" tid="{{ $question->questionid }}" rtype="{{ \Ca\ReportType::question }}">举报</a>
						@endif
					</span>
				</div>
				<div class="fav">
					@if (!$userid)
					<a href="{{ 'http://user.' . app()->environment() . '/login?ret=' . urlencode(URL::full()) }}" class="button_2 button_2_a btn_2_fav">收藏问题</a>
					@else
					@if ($userid != $question->userid)
					@if ($isfavorite)
					<a class="button_2 button_2_disabled btn_2_fav">已收藏</a>
					@else
					<a class="button_2 button_2_a btn_2_fav">收藏问题</a>
					@endif
					@endif
					@endif
				</div>
				<div class="spacer_1"></div>
				@if ($question->content || $question->tags)
					<div class="detail">
						@if ($question->content)
							<div class="content">{{ Ca\Common::ubb(Ca\Service\SensitiveService::replace($question->content)) }}</div>
						@endif
						@if ($question->tags)
							<div class="tags
							@if (!$question->content)
							only_tags
							@endif
							">
								@foreach ($question->tags as $tag)
								<a href="{{ '/knows/tag/' . $tag->tagid }}">{{ $tag->name }}</a>
								@endforeach
								<div class="clear"></div>
							</div>
						@endif
					</div>
				@endif
				@if ($userid && $userid == $question->userid)
					<div class="status">
					@if ($best_answer == null)
						@if ($answers->getTotal() == 0)
							<div>收到您的提问啦，大家正积极为您解答，请等一会儿吧！</div>
						@else
							<div>您收到了{{ $answers->getTotal() }}个回答，快去看看有没有可采纳的满意回答吧！</div>
						@endif
						<div class="update">
							你还可以:
							<a class="btn_update_question" href="#">修改内容</a> |
							<a class="btn_update_category" href="#">修改分类</a>
						</div>
						<div class="question_update update_question">
							<form action="/knows/updatequestion" id="updateQuestionForm" method="post">
								<input type="hidden" name="questionid" value="{{ $question->questionid }}" />
								<input type="hidden" name="_token" value="{{ csrf_token() }}">
								<textarea class="textbox_1" id="textarea_update_question" name="update_question_content">
									{{ $question->content }}
								</textarea>
								<span class="error"></span>
								<div class="actions">
									<a href="#" class="button_1 btn_submit"><span>确定</span></a>&nbsp;
									<a href="#" class="btn_cancel_update">取消</a>
								</div>
							</form>
						</div>
						<div class="question_update update_category">
							<form action="/knows/updatecategory" id="updateCategoryForm" method="post" style="width:100%">
								<input type="hidden" name="_token" value="{{ csrf_token() }}">
								<div class="category_selector"></div>
								<div class="error"></div>
								<div class="spacer_1"></div>
								<div class="actions">
									<a href="#" class="button_1 btn_submit"><span>确定</span></a>&nbsp;
									<a href="#" class="btn_cancel_update">取消</a>
								</div>
							</form>
						</div>
					@else
						<div>恭喜您！您的疑惑终于解决啦！</div>
					@endif
					</div>
				@endif
			</div>
			@if ($best_answer != null)
			<div class="answer_list best_answer">
				<h1 class="header_6"><span class="icon icon_award"></span>提问者采纳</span></h1>
				<div class="answer">
					<div class="avatar"></div>
					<div class="content">
						<span class="corner"></span>
						<div>{{ Ca\Common::ubb(Ca\Service\SensitiveService::replace($best_answer->content)) }}</div>
						<div class="info">
							<span>
								@if (!Auth::check())
								<a href="{{ 'http://user.' . app()->environment() . '/login?ret=' . urlencode(URL::full()) }}" class="report">举报</a>
								@elseif ($best_answer->isReported)
								<a class="report disabled" href="#">已举报</a>
								@else
								<a tid="{{ $best_answer->answerid }}" rtype="{{ \Ca\ReportType::answer }}" class="report" href="#">举报</a>
								@endif
							</span> |
							<span>{{ $best_answer->user_name }}</span>
							<span>于{{ Ca\Common::time_ago($best_answer->createdate) }}</span>
						</div>
					</div>
					@foreach ($best_answer->answermore as $answermore)
					<div class="communicate">
						@if ($answermore->type == \Ca\AnswerType::askMore)
						<label>追问</label>&nbsp;
						@elseif ($answermore->type == \Ca\AnswerType::answerMore)
						<label>回答</label>&nbsp;
						@endif
						{{ Ca\Common::ubb(Ca\Service\SensitiveService::replace($answermore->content)) }}
						<div class="info"><span>{{ $answermore->user_name }} 于{{ Ca\Common::time_ago($answermore->createdate) }}</span></div>
					</div>
					@endforeach
					<div >

					</div>
					<div class="clear"></div>
				</div>
			</div>

			@elseif (!$userid || ($userid && $userid != $question->userid && !$isanswered))
			<div class="answer">
				<label class="header" for="content">
					@if ($answers->getTotal() > 0)
					我有更好的答案
					@else
					我知道答案
					@endif
				</label>
				@if ($userid)
					<form action="/knows/answer" id="answerForm" method="post">
						<input type="hidden" name="question_id" value="{{ $question->questionid }}" />
						<input type="hidden" name="_token" value="{{ csrf_token() }}">
						<textarea placeholder="请尽量保证您的回答准确、详细和有效" id="content" name="content" class="textbox_1"></textarea>
						<span class="error"></span>
						<div class="actions">
							<a href="#" class="button_1 btn_answer"><span>提交回答</span></a>
						</div>
					</form>
				@else
					<div class="textarea_disabled">
						<div class="login">
							<a href="http://user.{{ app()->environment() }}/login?ret={{ urlencode(URL::full()) }}">登录后回答</a> |
							<a href="http://user.{{ app()->environment() }}/register">还未注册</a>
						</div>
					</div>
				@endif
			</div>
			@endif

			@if ($question->answer_count > 0)
			<div class="answer_list">
				<h2 class="header_6">
					<strong>{{ $question->answer_count }}</strong> 条@if ($best_answer != null) {{ '其它' }} @endif回答
				</h2>
				@foreach ($answers as $answer)
				<div class="answer">
					<div class="avatar"></div>
					<div class="content">
						<span class="corner"></span>
						{{ Ca\Common::ubb(Ca\Service\SensitiveService::replace($answer->content)) }}
						<div class="info">
							<span>
								@if (!Auth::check())
								<a href="{{ 'http://user.' . app()->environment() . '/login?ret=' . urlencode(URL::full()) }}" class="report">举报</a>
								@elseif ($answer->isReported)
								<a class="report disabled" href="#">已举报</a>
								@else
								<a tid="{{ $answer->answerid }}" rtype="{{ \Ca\ReportType::answer }}" class="report" href="#">举报</a>
								@endif
							</span> |
							<span>{{ $answer->user_name }}</span>
							<span>于{{ Ca\Common::time_ago($answer->createdate) }}</span>
						</div>
					</div>
					@foreach ($answer->answermore as $answermore)
					<div class="content communicate">
						@if ($answermore->type == \Ca\AnswerType::askMore)
						<label>追问</label>&nbsp;
						@elseif ($answermore->type == \Ca\AnswerType::answerMore)
						<label>回答</label>&nbsp;
						@endif
						{{ Ca\Common::ubb(Ca\Service\SensitiveService::replace($answermore->content)) }}
						<div class="info">
							<span>{{ $answermore->user_name }} 于{{ Ca\Common::time_ago($answermore->createdate) }}</span>
						</div>
					</div>
					@endforeach

					@if ($best_answer == null && $userid && $userid == $question->userid)
						<div class="more_actions">
							<a aid="{{ $answer->answerid }}" class="btn_accept" href="#">采纳回答</a>
							@if (count($answer->answermore) % 2 == 0)
								<a aid="{{ $answer->answerid }}" class="btn_ask_more" href="#">继续追问</a>
							@endif
						</div>
					@endif
					@if ($best_answer == null && $userid && $userid == $answer->userid && count($answer->answermore) % 2 == 1)
						<div class="more_actions">
							<a aid="{{ $answer->answerid }}" class="btn_ask_more" href="#">继续回答</a>
						</div>
					@endif
					<div class="clear"></div>
				</div>
				@endforeach
			</div>
			@endif
			{{ $answers->appends(array('id' => $question->questionid))->links() }}
			@if (count($similarquestions) > 0)
			<div class="more_question">
				<h1 class="header_5"><span class="icon icon_question"></span>其他类似问题</h1>
				<ul>
				@foreach ($similarquestions as $question)
					<li>
						<a href="{{ '/knows/question?id=' . $question->questionid }}">{{ Ca\Service\SensitiveService::replace($question->title) }}</a>
						<span>{{ $question->answer_count }}条回答</span>
						<span>于{{ Ca\Common::time_ago($question->createdate) }}</span>
					</li>
				@endforeach
				</ul>
			</div>
			@endif
		</div>
	</div>

	<div class="frame_1_r">
		{{ Ca\Service\AdService::show('210w_ad_knows1', 1, 'ad_1') }}
		@include ('share.partials.side.knows_rank')
		<div class="spacer_1"></div>
		{{ Ca\Service\AdService::show('210w_ad_knows2', 1, 'ad_1') }}
		@include ('share.partials.side.knows_tag_cloud')
		<div class="spacer_1"></div>
	</div>
	<div class="clear"></div>
</div>