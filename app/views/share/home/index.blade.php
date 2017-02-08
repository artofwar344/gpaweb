<div class="main_banner"><img src="{{ Config::get('app.asset_url')}}images/share/banner.jpg" /></div>
<div class="spacer_1"></div>
<div class="frame_1">
	<div class="frame_1_l">
		<h1 class="header_1"><span>推荐文档</span></h1>
		<div class="recommend_document">
			<div class="document_list">
				<div class="content_wrap">
					<div class="content">
						@foreach ($recommended_document as $document)
						<div class="document document_2">
							<a title="{{ $document->name }}" href="{{ '/document/detail?id=' . $document->documentid }}">
							<span class="preview" style="background-image:url('{{ \Ca\Service\DocumentService::thumbnail($document) }}')">
								<span class="border">
									<span class="icon icons_1 icons_{{ $document->extension }}"></span>
									@if ($document->pages)
									<span class="pages">{{ $document->pages }}页</span>
									@endif
								</span>
							</span>
							<span class="name">{{ $document->name }}</span>
							</a>
						</div>
						@endforeach
					</div>
				</div>
			</div>
		</div>
		{{ \Ca\Service\AdService::show('750w_ad1', 1, 'ad_1') }}
		<div class="spacer_1"></div>
		<h1 class="header_1"><span>热门资源</span></h1>
		@foreach ($data as $value)
			@include('share.partials.document.list1', $value)
			@if ($value['title'] == '课件专区')
			{{ \Ca\Service\AdService::show('750w_ad2', 1, 'ad_1') }}
			@endif
			@if ($value['title'] == '专业资料')
			{{ \Ca\Service\AdService::show('750w_ad3', 1, 'ad_1') }}
			@endif
		@endforeach
	</div>

	<div class="frame_1_r">
		{{ \Ca\Service\AdService::show('210w_ad1', 1, 'ad_1') }}
		@include('share.partials.side.document_rank', array('documents' => $hot_document, 'rankTitle' => '文档排行榜'))
		<div class="spacer_1"></div>
		{{ \Ca\Service\AdService::show('210w_ad2', 1, 'ad_1') }}
		@include ('share.partials.side.knows_rank')
		<div class="spacer_1"></div>
		@include ('share.partials.side.meeting_hot')
	</div>
	<div class="clear"></div>
</div>
