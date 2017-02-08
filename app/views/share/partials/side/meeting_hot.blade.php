{? $hot_meeting = \Ca\Service\MeetingService::get_hot_meeting(); ?}
@if (count($hot_meeting) > 0)
<div class="ranking_block">
	<h1>热门讲座</h1>
	<table>
		@foreach ($hot_meeting as $key => $meeting)
			<tr>
				<td class="name">
					<span class="index_{{ $key }}">{{ $key + 1 }}</span>
					<a class="title" title="{{ $meeting->name }}" href="{{ url('meeting/detail?id=' . $meeting->meetingid) }}">{{ $meeting->name }}</a>
				</td>
				<td class="pages">{{ $meeting->apply_count }}</td>
			</tr>
		@endforeach
	</table>
</div>
<div class="clear"></div>
@endif