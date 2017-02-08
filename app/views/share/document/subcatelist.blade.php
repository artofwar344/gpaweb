<div class="spacer_1"></div>
<div class="frame_1 home_content">
	<div class="frame_1_l">
		<h1 class="header_1"><span>{{ $category->name }}</span></h1>
		@foreach ($data as $value)
		@include('share.partials.document.list1', $value)
		@endforeach
	</div>
	<div class="frame_1_r">
		{{ \Ca\Service\AdService::show('210w_ad3', 1, 'ad_1') }}
		<div class="spacer_1"></div>
		@include('share.partials.side.document_rank', array('documents' => $hot_category_document, 'rankTitle' => $category->name . '排行榜'))
		<div class="spacer_1"></div>
		{{ \Ca\Service\AdService::show('210w_ad3', 1, 'ad_1') }}
		<div class="spacer_1"></div>
		@include('share.partials.side.document_rank', array('documents' => $hot_document, 'rankTitle' => '文档排行榜'))
	</div>
</div>
<div class="clear"></div>


