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
	<div class="block_1 activate_list">
		<table class="table_1">
			<tr><th class="align_left">商品名称</th><th>激活模式</th><th>申请数量</th><th>分配数量</th><th>申请原因</th><th>审批状态</th><th class="datetime">申请时间</th><th class="datetime">分配时间</th></tr>
			@if ($requests->getTotal() > 0)
			@foreach ($requests as $request)
			<tr pid = '{{ $request->productid }}'>
				<td class="align_left name" style="background-image:url({{ Config::get('app.asset_url') . 'images/activate/product/' . $request->productid . '.png' }})"><a target="_blank" href="http://{{ app()->environment() }}/download.html">{{ $request->product_name }}</a></td>
				<td>{{ $request->product_type }}</td>
				<td>{{ $request->requestcount }}</td>
				<td>{{ $request->assigncount }}</td>
				<td>{{ $request->reason }}</td>
				<td class="status_{{ $request->userkey_status }}">{{ \Ca\Consts::$managekey_status_texts[$request->userkey_status] }}</td>
				<td>{{ $request->requestdate }}</td>
				<td>{{ $request->assigndate == null ? '无' : $request->assigndate }}</td>
			</tr>
			@endforeach
			@else
			<tr class="none"><td colspan="8"><span>无申请记录</span></td></tr>
			@endif
		</table>
		@if ($requests->getLastPage() > 1)
		{{ $requests->links() }}
		@endif
		<div class="clear"></div>
	</div>
</div>