@if (count($documents) > 0)
<div class="ranking_block">
	<h1>{{ $rankTitle }}</h1>
	<table>
		@foreach ($documents as $key => $document)
			<tr>
				<td class="name">
					<span class="index_{{ $key }}">{{ $key + 1 }}</span>
					<a class="title" title="{{ $document->name }}" href="{{ url('document/detail?id=' . $document->documentid) }}">{{ $document->name }}</a>
				</td>
				<td class="pages">
					@if ($document->extension == 'mp4') 视频 @endif
					@if ($document->pages != null) {{ $document->pages }}P @endif
				</td>
			</tr>
		@endforeach
	</table>
</div>
<div class="clear"></div>
@endif

