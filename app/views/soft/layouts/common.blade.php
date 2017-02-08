<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<title>@if (!empty($title)) {{ $title }} - @endif {{ $sitename }}</title>
	<link rel="shortcut icon" href="{{ Config::get('app.asset_url'). '/images/share/logo.ico' }}" type="image/x-icon" />
	<link href="{{ Config::get('app.asset_url') . 'css/soft.css?' . Ca\Consts::$ca_version }}" rel="stylesheet" type="text/css" />
	<script type="text/javascript" src="{{ Config::get('app.asset_url') }}scripts/jquery.js"></script>
	<script type="text/javascript" src="{{ Config::get('app.asset_url') }}scripts/jquery-ui-1.9.2.custom.min.js"></script>
	<script type="text/javascript" src="{{ Config::get('app.asset_url') }}scripts/jquery.resize.js"></script>
	<script type="text/javascript" src="{{ Config::get('app.asset_url') }}scripts/jquery.hoverIntent.minified.js"></script>
<!--	<script type="text/javascript" src="{{ Config::get('app.asset_url') }}scripts/validate.min.js"></script>-->
</head>

<body>
@include('soft.partials.mainheader')
{{ $body }}
@include('soft.partials.mainfooter')
