<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<title>@if (!empty($title)) {{ $title }} - @endif {{ $sitename }}</title>
	<link rel="shortcut icon" href="{{ Config::get('app.asset_url') . 'images/share/logo.ico' }}" type="image/x-icon" />
	<link href="{{ Config::get('app.asset_url') . 'css/activate.css?' . Ca\Consts::$ca_version }}" rel="stylesheet" type="text/css" />
	<script type="text/javascript" src="{{ Config::get('app.asset_url') }}scripts/jquery.js"></script>
	<script type="text/javascript" src="{{ Config::get('app.asset_url') }}scripts/jquery-ui-1.9.2.custom.min.js"></script>
		<script type="text/javascript">

		$(function() {
			var nav = $(".main_menu .frame_1").children('a');
			var navname = '<?php echo (empty($nav) ? '' : $nav); ?>';
			nav.each(function(){
				if($(this).text() == navname){
					$(this).addClass('hot');
				}
			});

		});
	</script>
</head>
<body>
<div class="frame_1 main_header">
	<a href="/" class="logo"></a>
	<div class="sublogo">
		<img src="{{ Config::get('app.asset_url') . 'images/customer/' . App::make('customer')->alias . '.jpg' }}" />
	</div>
	<div class="account">
		{{ HtmlExt::headerLogin() }}
	</div>
</div>
<div class="main_menu">
	<div class="frame_1">
		<a href="/">激活管理</a>
		<a href="/request">申请记录</a>
		<a href="/usage">激活记录</a>
	</div>
</div>
{{ $content }}
<div class="frame_1 main_footer">
	<ul>
		<li>京ICP备12014130号 ? 2005-2013 版权所有，并保留所有权利</li>
		<li>支持部门: 中华人民共和国教育部科技发展中心</li>
		<li>服务提供商: 赛尔网络 CERNET</li>
	</ul>
</div>
</body>
</html>