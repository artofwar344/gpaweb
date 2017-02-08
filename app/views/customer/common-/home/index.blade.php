@if (Input::get('download') == 1)
<script type="text/javascript">
	@if (Auth::check())
	window.setTimeout(function() {
		document.location.href = "/down/GP({{ App::make('customer')->alias }})-{{ $clientversion }}.exe";
	}, 1000);
	@else
	document.location.href = "/";
	@endif
</script>@endif
<div class="frame_1 main_content">
	<div class="block_1">
		<h1>GP优势</h1>
		<img src="{{ Config::get('app.asset_url') . 'images/customer/cernet/block_1_bg.jpg' }}">
		<ul>
			<li><span>以最低成本享受正版软件的服务</span></li>
			<li>GP方案的突出优势是让学术和教育机构以软件年度性订购授权的方式在计算机上合法地使用最新桌面软件产品，又可以兼顾预算上的要求，一年只计算一次教职员的人数，就可以为全部的计算机取得授权；学校在合约期间，不必按照单点购买那样去一个一个核实产品授权。只要购买了合约，就有权在授权期间之内，合法使用合约中所授权的软件，还可以在协议期间自由升级（upgrade）或降级（downgrade）所用的软件。并可免费使用更高的版本软件。</li>
		</ul>
	</div>
	<div class="block_1">
		<h1>GP服务体系</h1>
		<img src="{{ Config::get('app.asset_url') . 'images/customer/cernet/block_1_bg2.jpg' }}">
		<ul>
			<li><span>全面解决了正版软件在校园的难题</span></li>
			<li>我公司推出的以“正版软件管理平台”为中心的项目服务体系为正确高效简单安全使用正版软件协议提供了保障。该平台是公司根据校园正版化（GP）项目在我国高校中的具体使用需求自行研发的软件管理平台系统，同时也是我国第一套针对校园正版化软件如何正确有效安全使用的解决方案。在满足正版软件身份认证、分发激活，数据统计等全方位软件管理需求的同时，也为用户带来良好的实践体验。</li>
		</ul>
	</div>
	<div class="main_function">
		<ul class="box_1">
			<li><h1>问题咨询</h1></li>
			<li class="phone_number">{{ Ca\Service\ParamsService::get('servicephone', '400-686-9667') }}</li>
			<li><a href="mailto:service@gpa.edu.cn" class="mail">{{ Ca\Service\ParamsService::get('serviceemail', 'service@gpa.edu.cn') }}</a></li>
			<li style="line-height: 18px;">周一至周五 8:30-17:15 <br/>(节假日除外)</li>
		</ul>
		<ul class="box_2">
			<li><h1>GP客户端下载</h1></li>
			<li>管理并激活GP平台提供的应用软件</li>
			<li class="new"><strong>最新版本:</strong>{{ $clientversion }}</li>
			<li><a href="@if (!Auth::check())
			{{ Ca\Common::link_to_login(Request::url() . '?download=1') }} @else /down/GP({{ App::make('customer')->alias }})-{{ $clientversion }}.exe@endif" class="download">下载最新客户端</a></li>
		</ul>
		<ul class="box_3">
			<li><h1>应用软件下载</h1></li>
			<li>下载GP平台提供的正版软件, 可以通过GP客户端进行激活管理</li>
			<li><a href="/download.html" class="more">进入下载页面</a></li>
		</ul>
	</div>
	<div class="clear"></div>
</div>