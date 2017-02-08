@if (!empty($meetings))
	<div class="meeting_list">
		@if (isset($title))
		<h2 class="header_5">
			<span class="icon icon_{{ $type }}"></span>
			<a href="{{ $url_more }}">{{ $title }}</a>
			@if (isset($more_link) && $more_link)
			<span class="more"><a href="{{ $url_more }}">更多</a></span>
			@endif
		</h2>
		@endif
		@foreach ($meetings as $key => $meeting)
		<ul class="{{ $key == count($meetings) - 1 ? 'last' : '' }}">
			<li>
				<div class="number
							@if ($meeting->apply_count > 0) number_more @endif
							@if ($meeting->is_end == 1) number_closed @endif
							">
					<span>{{ $meeting->apply_count }}</span><p>报名</p>
				</div>
			</li>
			<li class="detail">
				{{ $meeting->cost == 0 ? '<span class="free">免费</span>' : '<span class="pay">' . $meeting->cost . '元</span>' }}&nbsp;
				<a class="title" href="{{ '/meeting/detail?id=' . $meeting->meetingid }}">
					{{ $meeting->name }}
				</a>
				<div class="tags">
					@foreach ($meeting->tags as $tag)
					<a href="{{ '/meeting/tag/' . $tag->tagid }}">{{ $tag->name }}</a>
					@endforeach
				</div>
				<div class="info">
					<span class="icon address">{{ $meeting->address }}</span>
					<span>|</span>
					<span class="icon begin_date">{{ Ca\Common::datetime_to_date($meeting->begindate) }}</span>
					<div class="clear"></div>
				</div>
			</li>
			<li class="clear"></li>
		</ul>
		<div class="clear"></div>
		@endforeach
	</div>
@endif