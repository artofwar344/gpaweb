<tr pid="{{ $parent_id }}">
	<th style="text-align:left" colspan="2">文档名称</th>
	<th style="width:40px">来源</th>
	<th style="width:50px">状态</th>
	<th style="width:80px; text-align:right">创建日期</th>
</tr>
@foreach ($documents as $document)
{? $is_file = $document->type == \Ca\DocumentType::file ?}
@if ($document->from != \Ca\DocumentSource::upload || !$is_file || $document->status != \Ca\DocumentStatus::normal)
{? $can_share = 2 ?}
@elseif ($document->publish == \Ca\DocumentPublish::public_d)
{? $can_share = 1 ?}
@else
{? $can_share = 3 ?}
@endif
<tr type="{{ $document->type }}" did="{{ $document->documentid }}" share="{{ $can_share }}" favorite="{{ $document->from == \Ca\DocumentSource::favorite }}" del="{{ $document->status != \Ca\DocumentStatus::converting }}">
	<td class="check">
		<input class="check" type="checkbox" value="{{ $document->documentid }}" />
	</td>
	<td style="text-align:left">
		<span style="position:relative">
			@if ($document->status == \Ca\DocumentStatus::normal)
			<a class="title {{ $is_file ? 'file' : 'folder' }}" @if ($is_file) target="_blank" href="/document/detail?id={{ $document->documentid }}" @else href="/usercenter/document#{{ $document->documentid }}" @endif >{{ $document->name }}</a>
			@else
			<span class="title {{ $is_file ? 'file' : 'folder' }}">{{ $document->name }}</span>
			@endif
		</span>
	</td>
	<td>
		@if ($document->type == \Ca\DocumentType::file)
			@if ($document->from == \Ca\DocumentSource::upload)
			上传
			@else
			收藏
			@endif
		@endif
	</td>
	<td>
		@if ($document->type != \Ca\DocumentType::file)
		@elseif ($document->status == \Ca\DocumentStatus::normal)
			@if ($document->publish == \Ca\DocumentPublish::private_d)
				私有
			@elseif ($document->publish == \Ca\DocumentPublish::public_d)
				审核中
			@elseif ($document->publish == \Ca\DocumentPublish::submit_d)
				发布
			@endif
		@elseif ($document->status == \Ca\DocumentStatus::converting)
		处理中
		@elseif ($document->status == \Ca\DocumentStatus::convertfailed)
		处理失败
		@endif
	</td>
	<td style="text-align:right">{{ Ca\Common::datetime_to_date($document->createdate, 'Y-m-d') }}</td>
</tr>
@endforeach
@if (count($documents) == 0)
<tr class="none">
	<td colspan="5">
		<span>该目录没有文档, 请上传!</span>
	</td>
</tr>
@endif