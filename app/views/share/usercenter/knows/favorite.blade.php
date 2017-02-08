<script type="text/javascript" src="{{ Config::get('app.asset_url') }}scripts/share.usercenter.js"></script>

<script type="text/javascript">
$(function() {
	$.shareUserCenter({
		deleteUrl: "/usercenter/knows/deletefavorite",
		emptyRow: '<tr class="none"><td colspan="3"><span>你还没有收藏问答</span></td></tr>'
	});

});
</script>
<div class="dialog_1" id="dialogDelete">
	<div class="header"><span>删除问答收藏</span><a class="close"></a></div>
	<div class="confirm">是否删除选择的问答收藏?</div>
	<div class="actions">
		<a href="#" class="button_1 submit"><span>确定</span></a>
		<a class="close" href="#">取消</a>
	</div>
</div>

<div class="account_questions account_questions_fav">
	<ul class="tabsheet_2">
		<li @if ($condition == 'all') class="selected" @endif><a href="/usercenter/knows/favorite">所有问题</a></li>
		<li><span>|</span></li>
		<li @if ($condition == 'answered') class="selected" @endif><a href="/usercenter/knows/favorite/answered">已解答</a></li>
		<li><span>|</span></li>
		<li @if ($condition == 'unanswered') class="selected" @endif><a href="/usercenter/knows/favorite/unanswered">未解答</a></li>
		<li class="info"><span>共 <strong>{{ $questions->getTotal() }}</strong> 条</span></li>
	</ul>
	<div class="spacer_1"></div>
	<div class="information_1" style="display:none"><div class="success"></div><span class="close"></span></div>
	<div class="main_actions">
		<a class="button_3 button_3_disabled btn_3_del_file" href="#"><span>删除</span></a>
		<div class="clear"></div>
	</div>
	<table class="table_1">
		<tr>
			<th style="text-align:left" colspan="2">问答标题</th>
			<th style="width:80px; text-align:right">提问日期</th>
		</tr>
		@foreach ($questions as $question)
		<tr type="1" eid="{{ $question->questionid }}" >
			<td class="check">
				<input class="check" type="checkbox" value="{{ $question->questionid }}" />
			</td>
			<td style="text-align:left">
				<span class="title
				@if ($question->best_answer_count > 0)
					answered
				@else
					unanswered
				@endif
				">
					<a target="_blank" class="category" href="/knows/list/{{ $question->categoryid }}" >{{ $question->category_name }}</a>
					<a target="_blank" href="/knows/question?id={{ $question->questionid }}" title="{{ Ca\Service\SensitiveService::replace($question->title) }}">
						{{ Ca\Service\SensitiveService::replace($question->title) }}
					</a>
				</span>
			</td>
			<td style="text-align:right">{{ Ca\Common::datetime_to_date($question->date, 'Y-m-d') }}</td>
		</tr>
		@endforeach
		@if ($questions->getTotal() == 0)
		<tr class="none">
			<td colspan="3">
				<span>你还没有收藏问答</span>
			</td>
		</tr>
		@endif
	</table>
	{{ $questions->links() }}
</div>