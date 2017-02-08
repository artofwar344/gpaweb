{? $hot_question = \Ca\Service\KnowsService::get_hot_question() ?}
@if (count($hot_question) > 0)
<div class="ranking_block">
	<h1>问题排行榜</h1>
	<table>
		@foreach ($hot_question as $key => $question)
			<tr>
				<td class="name">
					<span class="index_{{ $key }}">{{ $key + 1 }}</span>
					<a class="title" title="{{ $question->title }}" href="{{ url('knows/question?id=' . $question->questionid) }}">{{ $question->title }}</a>
				</td>
				<td class="pages">{{ $question->answer_count }}</td>
			</tr>
		@endforeach
	</table>
</div>
<div class="clear"></div>
@endif