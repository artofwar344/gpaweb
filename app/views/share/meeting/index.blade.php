<div class="spacer_1"></div>
<div class="frame_1 home_content">
	<div class="frame_1_l">
		<h1 class="header_1">
			<span>{{ $title }}</span>
		</h1>
		<div class="spacer_1"></div>
		@foreach ($data as $key => $value)
			@include('share.partials.meeting.list', $value)
		@endforeach
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