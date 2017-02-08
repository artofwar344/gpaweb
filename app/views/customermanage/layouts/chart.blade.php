<!DOCTYPE html public "-//W3C//DTD XHTML 1.0 Transitional//EN"
	"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<meta http-equiv="content-type" content="text/html; charset=UTF-8" />
	<title>@if (!empty($title)) {{ $title }} - @endif {{ $sitename }}</title>
	<link rel='shortcut icon' href='{{ Config::get('app.asset_url') }}images/CA.ico' type='image/x-icon' />
	<link rel="stylesheet" href="{{ Config::get('app.asset_url') }}css/backend.css?ver={{ Ca\Consts::$ca_version }}" />
	<link rel="stylesheet" href="{{ Config::get('app.asset_url') }}css/manage.css?ver={{ Ca\Consts::$ca_version }}" />
	<script type="text/javascript" src="{{ Config::get('app.asset_url') }}scripts/jquery.js"></script>
	<script type="text/javascript" src="{{ Config::get('app.asset_url') }}scripts/jquery-ui-1.9.2.custom.min.js"></script>
	<script type="text/javascript" src="{{ Config::get('app.asset_url') }}scripts/jquery.validate.min.js"></script>
	<script type="text/javascript" src="{{ Config::get('app.asset_url') }}scripts/chart.js"></script>
	<script type="text/javascript" src="{{ Config::get('app.asset_url') }}scripts/chart/highcharts.js"></script>
	<script type="text/javascript" src="{{ Config::get('app.asset_url') }}scripts/chart/modules/exporting.js"></script>
</head>
<body>
@include('customermanage/partials/mainheader')
@include('customermanage/partials/mainmenu')
<div class="loading"><span>正在加载中…</span></div>
<div class="main_content">
	{{ $body }}
	<div id="chart"></div>
</div>
@include('customermanage/partials/mainfooter')