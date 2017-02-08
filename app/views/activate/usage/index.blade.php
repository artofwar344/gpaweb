<script>
	$(function() {
		$(".table_1 tr:not(:first)").hover(function() {
			$(this).addClass("hover");
		}, function() {
			$(this).removeClass("hover");
		});
	});

</script>
<div class="frame_1">
	<div class="block_1 usage_list">
		<table class="table_1">
			<tr><th class="align_left">商品名称</th><th>激活模式</th><th>IP地址</th><th>机器号</th><th>错误代码</th><th>激活状态</th><th class="datetime">开始时间</th><th class="datetime">结束时间</th></tr>
			@if ($keys->getTotal() > 0)
				@foreach ($keys as $key)
				<tr pid = '{{ $key->productid }}'>
					<td class="align_left name" style="background-image:url({{ Config::get('app.asset_url') . 'images/activate/product/' . $key->productid . '.png' }})"><a target="_blank" href="http://{{ app()->environment() }}/download.html">{{ $key->product_name }}</a></td>
					<td>{{ $key->product_type }}</td>
					<td>{{ long2ip($key->ip) }}</td>
					<td>{{ $key->computerid }}</td>
					{{ $key->errorcode == null ? '<td class="empty">无' : '<td>' . $key->errorcode }}</td>
					<td class="status_{{ $key->keyusage_status }}">{{ \Ca\Consts::$keyusage_status_texts[$key->keyusage_status] }}</td>
					<td>{{ $key->begindate }}</td>
					{{ $key->enddate == null ? '<td class="empty">无' : '<td>' . $key->enddate }}</td>
				</tr>
				@endforeach
			@else
				<tr class="none"><td colspan="8"><span>无激活记录</span></td></tr>
			@endif
		</table>
		@if ($keys->getLastPage() > 1)
		{{ $keys->links() }}
		@endif
		<div class="clear"></div>
	</div>
</div>