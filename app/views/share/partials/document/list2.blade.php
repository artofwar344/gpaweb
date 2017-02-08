@if($documents->getTotal() > 0)
<div class="document_list">
	<div class="header_5">
		<span class="icon icon_category"></span>{{ $title }}
		@if (isset($url_more) && $url_more != null)
		<a class="more" href="{{ $url_more }}">更多&gt;</a>
		@endif
	</div>
	<div class="content_wrap">
		<div class="content">
			@foreach ($documents as $key => $document)
			<div class="document document_1">
				<a class="thumb" href="{{ '/document/detail?id=' . $document->documentid }}">
							<span class="preview">
								<span class="border">
									<img src="{{ Ca\Service\DocumentService::thumbnail($document) }}" style="width:79px; height:98px" alt="{{ $document->name }}"/>
									<span class="icon icons_1 icons_{{ $document->extension }}"></span>
									@if ($document->pages)
									<span class="pages">{{ $document->pages }}页</span>
									@endif
								</span>
							</span>
				</a>
				<dl class="detail">
					<dt><a title="{{ $document->name }}" href="{{ '/document/detail?id=' . $document->documentid }}" class="title" title="{{ $document->uname }}">{{ $document->name }}</a></dt>
					<dd>上传者：{{ $document->uname }}</dd>
					<dd>下载量：{{ $document->download_count }}次</dd>
					<dd>上传时间：{{ Ca\Common::datetime_to_date($document->createdate) }}</dd>
				</dl>
			</div>
			@endforeach
			<div class="clear"></div>
		</div>
	</div>
</div>
@endif
@if ($documents->getTotal() > 1 )
{{ $documents->links() }}
@endif