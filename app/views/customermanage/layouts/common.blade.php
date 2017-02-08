<!DOCTYPE html public "-//W3C//DTD XHTML 1.0 Transitional//EN"
	"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<meta http-equiv="content-type" content="text/html; charset=UTF-8" />
	<title>@if (!empty($title)) {{ $title }} - @endif {{ $sitename }}</title>
	<link rel='shortcut icon' href='{{ Config::get('app.asset_url') }}images/CA.ico' type='image/x-icon' />
	<link rel="stylesheet" href="{{ Config::get('app.asset_url') }}css/backend.css?ver={{ Ca\Consts::$ca_version }}" />
	<link rel="stylesheet" href="{{ Config::get('app.asset_url') }}css/manage.css?ver={{ Ca\Consts::$ca_version }}" />
	<link rel="stylesheet" href="{{ Config::get('app.asset_url') }}css/jquery.simple-dtpicker.css?ver={{ Ca\Consts::$ca_version }}" />
	<link rel="stylesheet" href="{{ Config::get('app.asset_url') }}css/introjs.css?ver={{ Ca\Consts::$ca_version }}" />
	<script type="text/javascript" src="{{ Config::get('app.asset_url') }}scripts/jquery.js"></script>
	<script type="text/javascript" src="{{ Config::get('app.asset_url') }}scripts/jquery-ui-1.9.2.custom.min.js"></script>
	<script type="text/javascript" src="{{ Config::get('app.asset_url') }}scripts/jquery.resize.js"></script>
	<script type="text/javascript" src="{{ Config::get('app.asset_url') }}scripts/jquery.hoverIntent.minified.js"></script>
	<script type="text/javascript" src="{{ Config::get('app.asset_url') }}scripts/jquery.validate.min.js"></script>
	<script type="text/javascript" src="{{ Config::get('app.asset_url') }}scripts/main.js?{{ Ca\Consts::$ca_version }}"></script>
	<script type="text/javascript" src="{{ Config::get('app.asset_url') }}scripts/jquery.simple-dtpicker.js"></script>
	<script type="text/javascript" src="{{ Config::get('app.asset_url') }}scripts/intro.js"></script>
</head>
<body @if (Input::get("inner")) class="inner_body" @endif>
@if (!Input::get("inner"))
	@include('customermanage/partials/mainheader')
	@include('customermanage/partials/mainmenu')
@endif
<div class="loading"><span>正在加载中…</span></div>

<div id="tipdg" class="dialog_1">
	<h1>操作提示</h1>
	<p class="info">操作失败, 该纪录正在使用!</p>
	<div class="actions">
		<a href="#" class="button_1 button_1_a close">确定</a>
	</div>
	<a href="#" class="close header_close"></a>
</div>
<div class="main_content">
	{{ $body }}
</div>
@if (!Input::get("inner"))
	@include('customermanage/partials/mainfooter')
@endif