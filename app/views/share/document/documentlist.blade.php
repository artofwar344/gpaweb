<div class="spacer_1"></div>
<div class="frame_1 home_content">
	<div class="frame_1_l">
		<h1 class="header_1"><span>{{ $title }}</span></h1>
		@include('share.partials.document.list2', $data)
	</div>
	<div class="frame_1_r">
		@include('share.partials.side.document_rank', array('documents' => $hot_document, 'rankTitle' => '文档排行榜'))
		<div class="spacer_1"></div>
		{{ Ca\Service\AdService::show('210w_ad4', 1, 'ad_1') }}
	</div>
	<div class="clear"></div>
</div>
