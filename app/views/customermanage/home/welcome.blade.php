<!DOCTYPE html public "-//W3C//DTD XHTML 1.0 Transitional//EN"
	"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
	<title>{{ '管理中心 - ' . Ca\Consts::$app_name . '(' . Ca\Service\ParamsService::get('customername') . ')' }}</title>
	<link rel='shortcut icon' href='{{ Config::get('app.asset_url') }}images/CA.ico' type='image/x-icon' />
	<link rel="stylesheet" href="{{ Config::get('app.asset_url') }}css/backend.css?ver={{ Ca\Consts::$ca_version }}" />
	<link rel="stylesheet" href="{{ Config::get('app.asset_url') }}css/manage.css?ver={{ Ca\Consts::$ca_version }}" />
	<link rel="stylesheet" href="{{ Config::get('app.asset_url') }}css/introjs.css?ver={{ Ca\Consts::$ca_version }}" />
	<script type="text/javascript" src="{{ Config::get('app.asset_url') }}scripts/jquery.js"></script>
	<script type="text/javascript" src="{{ Config::get('app.asset_url') }}scripts/jquery-ui-1.9.2.custom.min.js"></script>
	<script type="text/javascript" src="{{ Config::get('app.asset_url') }}scripts/jquery.resize.js"></script>
	<script type="text/javascript" src="{{ Config::get('app.asset_url') }}scripts/jquery.hoverIntent.minified.js"></script>
	<script type="text/javascript" src="{{ Config::get('app.asset_url') }}scripts/jquery.validate.min.js"></script>
	<script type="text/javascript" src="{{ Config::get('app.asset_url') }}scripts/intro.js"></script>
	<?php if ($department_count == 0) :?>
	<script type="text/javascript">
//		$(function() {
//			var stepOne = $(".main_nav").find("ul.popup:eq(0)");
//			if (stepOne.text() == "部门管理") {
//				stepOne.show();
//				stepOne.on("mouseout", function() {intro.exit();});
//				stepOne.find("li:eq(0)").attr("data-step", "1").attr("data-intro", "点击这里添加部门").attr("data-position", "right");
//				var intro = introJs().setOptions({"skipLabel":"确定", "showStepNumbers":false}).start();
//			}
//		});
	</script>
	<?php endif;?>
	<script type="text/javascript">
		$(function() {
			$(window).resize(function() {
				$(".main_content").height($(window).height() - 117);
			}).resize();
			$(".main_welcome").fadeIn();
		});
	</script>
</head>
<body>
@include('customermanage/partials/mainheader')
@include('customermanage/partials/mainmenu')
<div class="main_content main_welcome">
	<h1>授权产品</h1>
	<div class="table_1">
		<table>
			<tbody>
			<tr>
				<th>编号</th>
				<th>产品</th>
				<th>总套数</th>
				<th>分配给下级部门</th>
				<th>分配给用户</th>
				<th>可用量</th>
			</tr>
			@foreach ($products as $product)
			<tr>
				<td>{{ $product['productid'] }}</td>
				<td>{{ $product['name'] }}</td>
				<td>{{ $product['keycount'] }}</td>
				<td>{{ $product['departmentassigncount'] }}</td>
				<td>{{ $product['assigncount'] }}</td>
				<td class="count">{{ $product['keycount'] - $product['departmentassigncount'] - $product['assigncount'] }}</td>
			</tr>
			@endforeach
			</tbody>
		</table>
	</div>
	<h1>授权产品组</h1>
	<div class="table_1">
		<table class="table_1">
			<tbody>
			<tr>
				<th>编号</th>
				<th>产品组名称</th>
				<th>总套数</th>
				<th>分配给下级部门</th>
				<th>分配给用户</th>
				<th>可用量</th>
			</tr>
			@foreach ($keys as $key)
			<tr>
				<td>{{ $key['keyid'] }}</td>
				<td>{{ $key['name'] }}</td>
				<td>{{ $key['keycount'] }}</td>
				<td>{{ $key['departmentassigncount'] }}</td>
				<td>{{ $key['assigncount'] }}</td>
				<td class="count">{{ $key['keycount'] - $key['departmentassigncount'] - $key['assigncount'] }}</td>
			</tr>
			@endforeach
			</tbody>
		</table>
	</div>
	<h1>最新请求记录</h1>
	<div class="table_1">
		<table>
			<tbody>
			<tr>
				<th>编号</th>
				<th>姓名</th>
				<th>请求商品</th>
				<th>请求数量</th>
				<th>请求日期</th>
				<th>请求理由</th>
				<th>状态</th>
			</tr>
			@foreach ($keyassigns as $keyassign)
			<tr>
				<td>{{ $keyassign['userkeyid'] }}</td>
				<td>{{ $keyassign['user_name'] }}</td>
				<td>{{ $keyassign['product_name'] }}</td>
				<td>{{ $keyassign['requestcount'] }}</td>
				<td>{{ $keyassign['requestdate'] }}</td>
				<td>{{ $keyassign['reason'] }}</td>
				<td>{{ $keyassign['status_text'] }}</td>
			</tr>
			@endforeach
			</tbody>
		</table>
	</div>
	<h1>最新激活记录</h1>
	<div class="table_1">
		<table>
			<tbody>
			<tr>
				<th>编号</th>
				<th>姓名</th>
				<th>产品</th>
				<th>密钥</th>
				<th>IP</th>
				<th>机器号</th>
				<th>错误代码</th>
				<th>开始时间</th>
				<th>结束时间</th>
				<th>状态</th>
			</tr>
			@foreach ($usages as $usage)
			<tr>
				<td>{{ $usage['usageid'] }}</td>
				<td>{{ $usage['name'] }}</td>
				<td>{{ $usage['product_name'] }}</td>
				<td>{{ $usage['key_name'] }}</td>
				<td>{{ $usage['ip_text'] }}</td>
				<td>{{ $usage['computerid'] }}</td>
				<td>{{ $usage['errorcode'] }}</td>
				<td>{{ $usage['begindate'] }}</td>
				<td>{{ $usage['enddate'] }}</td>
				<td>{{ $usage['status_text'] }}</td>
			</tr>
			@endforeach
			</tbody>
		</table>
	</div>
</div>
@include('customermanage/partials/mainfooter')
</body>
</html>