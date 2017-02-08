@if(count($documents) > 0)
<div class="document_list">
	<div class="header_5">
		<span class="icon icon_category"></span>{{ $title }}
		@if (isset($url_more) && $url_more != null)
			<a class="more" href="{{ $url_more }}">更多</a>
		@endif
	</div>
	<div class="content_wrap">
		<div class="content">
			@foreach ($documents as $document)
			<div class="document">
				<a title="{{ $document->name }}" href="{{ '/document/detail?id=' . $document->documentid }}">
					<span class="preview">
						<span class="border">
							<img src="{{ Ca\Service\DocumentService::thumbnail($document) }}" alt="{{ $document->name }}"/>
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
<div class="spacer_1"></div>
@endif