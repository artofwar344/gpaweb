<div class="group">
@if (isset($title))
<h2 class="header_5">
	<span class="@if (isset($type)) icon icon_{{ $type }} @endif"></span>
	@if (isset($parent_url_more))
	<a href="{{ $parent_url_more }}">{{ $parent_title }}</a> &gt;
	@endif
	@if (isset($url_more) && $url_more)
	<a href="{{ $url_more }}">{{ $title }}</a>
	@else
	{{ $title }}
	@endif
	@if (isset($more_link) && $more_link)
	<span class="more"><a href="{{ $url_more }}">更多</a></span>
	@endif
</h2>
@endif
@foreach ($questions as $key => $question)
<ul class="{{ $key == count($questions) - 1 ? 'last' : '' }}">
	<li class="amount">
		<div class="view {{ $question->views >= 10 ? ($question->views >= 50 ? 'view_more50' : 'view_more10') : '' }}">
			<span>{{ $question->views }}</span><p>浏览</p>
		</div>
	</li>
	<li class="amount"><div class="answers
		@if ($question->answer_count > 0) answered @endif
		@if ($question->best_answer_count > 0) accept @endif"><span>{{ $question->answer_count }}</span><p>回答</p></div>
	</li>
	<li class="title
	@if (!$question->tags)
		none_tags
	@endif
	">
		<a class="question" href="{{ url('/knows/question?id=' . $question->questionid ) }}">{{ Ca\Service\SensitiveService::replace($question->title) }}</a>
		@if ($question->tags)
		<div class="tags">
			@foreach ($question->tags as $tag)
			<a href="{{ url('/knows/tag/' . $tag->tagid) }}">{{ $tag->name }}</a>
			@endforeach
		</div>
		@endif
		<div class="info">
			<span class="icon category"><a href="/knows/list/{{ $question->categoryid }}">{{ $question->category_name }}</a></span>
			<span class="icon author">{{ $question->user_name }}</span>
			<span>{{ Ca\Common::time_ago($question->date) }}</span>
		</div>
	</li>
	<li class="clear"></li>
</ul>
@endforeach
<div class="clear"></div>
</div>
