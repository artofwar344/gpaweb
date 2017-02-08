<!DOCTYPE html public "-//W3C//DTD XHTML 1.0 Transitional//EN"
	"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
	<title>正版软件管理与服务平台</title>
	<link rel='shortcut icon' href='{{ Config::get('app.asset_url') }}images/CA.ico' type='image/x-icon' />
	<link rel="stylesheet" href="{{ Config::get('app.asset_url') }}css/backend.css?{{ Ca\Consts::$ca_version }}" type="text/css" />
	<link rel="stylesheet" href="{{ Config::get('app.asset_url') }}css/manage.css?{{ Ca\Consts::$ca_version }}" type="text/css" />
	<script type="text/javascript" src="{{ Config::get('app.asset_url') }}scripts/jquery.js"></script>
	<script type="text/javascript" src="{{ Config::get('app.asset_url') }}scripts/jquery-ui-1.9.2.custom.min.js"></script>
	<script type="text/javascript" src="{{ Config::get('app.asset_url') }}scripts/jquery.resize.js"></script>
	<script type="text/javascript" src="{{ Config::get('app.asset_url') }}scripts/jquery.hoverIntent.minified.js"></script>
	<script type="text/javascript" src="{{ Config::get('app.asset_url') }}scripts/jquery.validate.min.js"></script>
	<script type="text/javascript" src="{{ Config::get('app.asset_url') }}scripts/main.js?{{ Ca\Consts::$ca_version }}"></script>
</head>
<body>
@include('manage/partials/mainheader')
@include('manage/partials/mainmenu')
<script type="text/javascript">
	$(function() {
		var paging = $(".paging");
		$.get("/home/status", function(ret) {
			$.each(ret, function(i, item) {
				$("tr." + item.alias).find("td:eq(2)").html("新用户:" + item.user_count + "<br >"
					+ "用户网页登录:" + item.weblogin_count + "<br >"
					+ "用户客户端登录:" + item.clientlogin_count + "<br >"
					+ "用户请求激活:" + item.requestkey_count + "<br >"
					+ "管理员分配激活:" + item.assignkey_count + "<br >"
					+ "用户激活:" + item.keyusage_count
				);
			});

		}, 'json');
		$('a', paging).click(function() {
			var page;
			var current;
			if ($(this).hasClass("disabled")) {
				return false;
			}
			if ($(this).hasClass("prev")) {
				current = $(".current", paging).text() >> 0;
				page = current - 1;
			} else if ($(this).hasClass("next")) {
				current = $(".current", paging).text() >> 0;
				page = current + 1;
			} else {
				page = $(this).text() >> 0;
			}
			document.location.href = "{{ Request::url() }}?page=" + page;
			return false;
		});
	});
</script>
<div class="main_content main_welcome" style="display: block">
	<h1>所有客户</h1>
	<div class="table_1">
		<table>
			<tbody>
			<tr>
				<th>编号</th>
				<th>客户名称</th>
				<th>最近一周</th>
			</tr>
			@foreach ($customers as $customer)
			<tr class="{{ $customer->alias }}">
				<td>{{ $customer->customerid }}</td>
				<td>{{ $customer->name }}</td>
				<td style="width:300px"></td>
			</tr>
			@endforeach
			<tr>
				<td colspan="3" class="paging" style="">
					共 {{ $customers_count }} 条&nbsp;&nbsp;
					@if ($pages > 1)
					<a href="#" class="@if ($page == 1)disabled @endifprev">上一页</a>
					@foreach (range(1, $pages) as $i)
					@if ($i - $page <= 2 && $page - $i <= 2)
					<a @if ($i == $page)class="current" @endif href="#">{{ $i }}</a>
					@endif
					@endforeach
					<a href="#" class="@if ($page == $pages)disabled @endifnext">下一页</a>
					@endif
				</td>
			</tr>
			</tbody>
		</table>
	</div>
</div>
@include('manage/partials/mainfooter')
</body>
</html>