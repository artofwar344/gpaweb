<table class="table_1">
	<tr>
		<th style="text-align:left">问题</th>
		<th style="width:60px">回答</th>
		<th style="width:80px; text-align:right">提问日期</th>
	</tr>
	@foreach ($questions as $question)
	<tr did="{{ $question->questionid }}">
		<td style="text-align:left">
			<span class="title unanswered" title="{{ Ca\Service\SensitiveService::replace($question->title) }}">
				@if (!($type == 'category' && $displayid))
				<a target="_blank" class="category" href="/knows/list/{{ $question->categoryid }}" >{{ $question->category_name }}</a>
				@endif
				<a target="_blank" href="/knows/question?id={{ $question->questionid }}">
					{{ Ca\Service\SensitiveService::replace($question->title) }}
				</a>
			</span>
		</td>
		<td>{{ $question->answer_count }}</td>
		<td style="text-align:right">{{ Ca\Common::datetime_to_date($question->createdate, 'Y-m-d') }}</td>
	</tr>
	@endforeach
	@if ($questions->getTotal() == 0)
	<tr class="none">
		<td colspan="3">
			<span>没有符合条件的提问</span>
		</td>
	</tr>
	@endif
</table>
@if ($questions->getLastPage() > 1)
{{ $questions->appends(array('type' => $type, 'displayid' => $displayid))->links() }}
@endif