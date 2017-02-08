<div class="dialog_1" id="dialogDelete">
	<div class="header"><span>删除问答</span><a class="close"></a></div>
	<div class="confirm">是否删除选择的问答?</div>
	<div class="actions">
		<a href="#" class="button_1 submit"><span>确定</span></a>
		<a class="close" href="#">取消</a>
	</div>
</div>

<div class="account_questions">
	<ul class="tabsheet_2">
		<li @if ($condition == 'all') class="selected" @endif><a href="/usercenter/knows/ask">所有问题</a></li>
		<li><span>|</span></li>
		<li @if ($condition == 'answered') class="selected" @endif><a href="/usercenter/knows/ask/answered">已解答</a></li>
		<li><span>|</span></li>
		<li @if ($condition == 'unanswered') class="selected" @endif><a href="/usercenter/knows/ask/unanswered">未解答</a></li>
		<li class="info"><span>共 <strong>{{ $questions->getTotal() }}</strong> 条</span></li>
	</ul>
	<table class="table_1">
		<tr>
			<th style="text-align:left">问题</th>
			<th style="width:60px">回答</th>
			<th style="width:80px; text-align:right">提问日期</th>
		</tr>
		@foreach ($questions as $question)
		<tr did="{{ $question->questionid }}">
			<td style="text-align:left">
				<span class="title
				@if ($question->best_answer_count > 0)
					answered
				@else
					unanswered
				@endif
				" title="{{ Ca\Service\SensitiveService::replace($question->title) }}">
					<a target="_blank" class="category" href="/knows/list/{{ $question->categoryid }}" >{{ $question->category_name }}</a>
					<a target="_blank" href="/knows/question?id={{ $question->questionid }}">
						{{ Ca\Service\SensitiveService::replace($question->title) }}
					</a>
				</span>
			</td>
			<td>{{ $question->answer_count }}</td>
			<td style="text-align:right">{{ Ca\Common::datetime_to_date($question->date, 'Y-m-d') }}</td>
		</tr>
		@endforeach
		@if ($questions->getTotal() == 0)
		<tr class="none">
			<td colspan="6">
				<span>你还没有提问</span>
			</td>
		</tr>
		@endif
	</table>
	{{ $questions->links() }}
</div>