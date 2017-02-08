<div class="frame_1">
	<div class="frame_1 main_header">
		@if(App::make('customer')->alias == 'ccnu')

		<div class="account" style="color:#666666">
			{{ HtmlExt::headerLogin() }}
		</div>
		@else
		<div class="account">
			{{ HtmlExt::headerLogin() }}
		</div>
		@endif
		
		@if(App::make('customer')->alias == 'jssvc' )
			<img src="{{ Config::get('app.asset_url') }}images/customer/{{ App::make('customer')->alias }}2.jpg" />
		@elseif(App::make('customer')->alias == 'ccnu')
			<a href="/" class="logo logo_ccnu" {{ \Ca\Service\ParamsService::get('logourl') ? 'style="background-image: url(\'' .  str_replace(array('{0}', '{1}'), array(Config::get('app.asset_url') . 'images', ''), \Ca\Service\ParamsService::get('logourl')) . '\')"' : '' }}></a>
			@if (\Ca\Service\ParamsService::get('showsublogo', 1) == 1)
			<span class=" sublog_ccnu"><img src="{{ Config::get('app.asset_url') }}images/customer/{{ App::make('customer')->alias }}.png" /></span>
			@endif
		@else
			<a href="/" class="logo" {{ \Ca\Service\ParamsService::get('logourl') ? 'style="background-image: url(\'' .  str_replace(array('{0}', '{1}'), array(Config::get('app.asset_url') . 'images', ''), \Ca\Service\ParamsService::get('logourl')) . '\')"' : '' }}></a>
			@if (\Ca\Service\ParamsService::get('showsublogo', 1) == 1)
			<span class="sublogo"><img src="{{ Config::get('app.asset_url') }}images/customer/{{ App::make('customer')->alias }}.jpg" /></span>
			@endif
		@endif
		<div class="help"><a href="#"></a></div>
		<ul>
			<li><a href="/">平台首页</a></li>
			<!--<li><a href="#">GP项目简介</a></li>-->
			<!--<li><a href="#">GP技术支持</a></li>-->
			<li><a href="/download.html">应用下载</a></li>
		@if(App::make('customer')->alias == 'njutcm' )	
			<li><a href="/help/kms">帮助中心</a></li>
		@else
			<li><a href="/help/client">帮助中心</a></li>
		@endif
		</ul>
	</div>
</div>