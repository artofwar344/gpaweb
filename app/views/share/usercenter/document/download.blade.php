<script type="text/javascript" src="{{ Config::get('app.asset_url') }}scripts/share.usercenter.js"></script>

<script>
	$(function() {
		$.shareUserCenter({
			deleteUrl: "/usercenter/document/deletedownload",
			emptyRow: '<tr class="none"><td colspan="4"><span>你还没有下载文档</span></td></tr>'
		});
	});
</script>
<div class="dialog_1" id="dialogDelete">
	<div class="header"><span>删除文档</span><a class="close"></a></div>
	<div class="confirm">是否删除选择的文档?</div>
	<div class="actions">
		<a href="#" class="button_1 submit"><span>确定</span></a>
		<a class="close" href="#">取消</a>
	</div>
</div>

<div class="documents_download">
	<ul class="tabsheet_2">
		<li class="selected"><a>已下载文档</a></li>
		<li class="info"><span>共 <strong class="count_document">{{ $downloads->getTotal() }}</strong> 份</span></li>
	</ul>
	<div class="spacer_1"></div>
	<div class="information_1" style="display:none"><div class="success"></div><span class="close"></span></div>
	<div class="main_actions">
		<a class="button_3 button_3_disabled btn_3_del_file" href="#"><span>删除</span></a>
		<div class="clear"></div>
	</div>
	<table class="table_1">
		<tr>
			<th style="text-align:left" colspan="2">文档名称</th>
			<th style="width:70px">上传用户</th>
			<th style="width:80px; text-align:right">下载日期</th>
		</tr>
		@foreach ($downloads as $download)
		<tr type="{{ $download->type }}" eid="{{ $download->documentid }}">
			<td class="check">
				<input class="check" type="checkbox" value="{{ $download->documentid }}" />
			</td>
			<td style="text-align:left">
				<a class="title file" href="/document/detail?id={{ $download->documentid }}" target="_blank">{{ $download->name }}</a>
			</td>
			<td>{{ $download->uname }}</td>
			<td style="text-align:right">{{ Ca\Common::datetime_to_date($download->downloaddate, 'Y-m-d') }}</td>
		</tr>
		@endforeach
		@if (count($downloads) == 0)
		<tr class="none">
			<td colspan="4">
				<span>你还没有下载文档</span>
			</td>
		</tr>
		@endif
	</table>
	{{ $downloads->links() }}
</div>