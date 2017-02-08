<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<link rel='shortcut icon' href='{{ Config::get('app.asset_url') . 'images/share/logo.ico' }}' type='image/x-icon' />
	<title>@if (!empty($title)) {{ $title }} - @endif {{ $sitename }}</title>
	<script type="text/javascript" src="{{ Config::get('app.asset_url') }}scripts/jquery.js"></script>
	<script type="text/javascript" src="{{ Config::get('app.asset_url') }}scripts/user.js?{{ Ca\Consts::$ca_version }}"></script>
	<link rel="stylesheet" href="{{ Config::get('app.asset_url') }}css/user.css?{{ Ca\Consts::$ca_version }}" />
</head>
<body>
<div class="frame_1 main_header">
	<a href="http://{{ app()->environment() }}" class="logo" {{ \Ca\Service\ParamsService::get('logourl') ? 'style="background-image: url(\'' .  str_replace(array('{0}', '{1}'), array(Config::get('app.asset_url') . 'images', '_user'), \Ca\Service\ParamsService::get('logourl')) . '\')"' : '' }}></a>
	@if (\Ca\Service\ParamsService::get('showsublogo', 1) == 1)
	<div class="sublogo"><img src="{{ Config::get('app.asset_url') . 'images/customer/' . App::make('customer')->alias . '.jpg' }}" /></div>
	@endif
	<div class="account">
		{{ HtmlExt::headerLogin() }}
	</div>
</div>
{{ $content }}
<div class="frame_1 main_footer">
	<ul>
		<li><a href="http://www.miitbeian.gov.cn" rel="nofollow" target="_blank">{{ Ca\Consts::$icp }}</a> © 2005-2013 版权所有，并保留所有权利</li>
		<li>支持部门: 中华人民共和国教育部科技发展中心</li>
		<li>服务提供商: 赛尔网络 CERNET</li>
	</ul>
</div>
</body>
</html>