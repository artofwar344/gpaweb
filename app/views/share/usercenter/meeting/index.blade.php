<div class="my_meetings">
	<ul class="tabsheet_2">
		<li @if ($condition == 'all') class="selected" @endif><a href="/usercenter/meeting">所有讲座</a></li>
		<li><span>|</span></li>
		<li @if ($condition == 'active') class="selected" @endif><a href="/usercenter/meeting/active">正在报名</a></li>
		<li><span>|</span></li>
		<li @if ($condition == 'over') class="selected" @endif><a href="/usercenter/meeting/over">报名结束</a></li>
		<li class="info"><span>共 <strong>{{ $meetings->getTotal() }}</strong> 条</span></li>
	</ul>
	<div class="spacer_1"></div>
	<table class="table_1">
		<tr>
			<th style="text-align:left">讲座</th>
			<th style="width:60px">报名</th>
		</tr>
		@foreach ($meetings as $meeting)
		<tr type="1" did="{{ $meeting->meetingid }}" >
			<td style="text-align:left">
				{{ $meeting->cost == 0 ? '<span class="free">免费</span>' : '<span class="pay">' . $meeting->cost . '元</span>' }}
				<a class="title" target="_blank" href="/meeting/detail?id={{ $meeting->meetingid }}" title="{{ $meeting->name }}">
					{{ $meeting->name }}
				</a><br/>
				<div class="info">
					<span class="address">{{ $meeting->address }}</span>&nbsp; |
					<span class="begin_date">{{ Ca\Common::datetime_to_date($meeting->begindate, 'Y-m-d') }}</span>
				</div>
			</td>
			<td>{{ $meeting->apply_count }}</td>
		</tr>
		@endforeach
		@if ($meetings->getTotal() == 0)
		<tr class="none">
			<td colspan="5">
				<span>你还没有已报名的讲座</span>
			</td>
		</tr>
		@endif
	</table>
	@if ($meetings->getLastPage() > 1)
	{{ $meetings->links() }}
	@endif
</div>