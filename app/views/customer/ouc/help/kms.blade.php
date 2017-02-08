<div class="frame_1 main_content">
	<div class="frame_1_l">
		@if (View::exists('customer.' . App::make('customer')->alias . '.partials.help.menu'))
		@include('customer.' . App::make('customer')->alias . '.partials.help.menu')
		@else
		@include('customer.common.partials.help.menu')
		@endif
	</div>

	<div class="frame_1_r">
		<h1 class="header_1">KMS激活脚本使用说明</h1>
		<div class="help_content">
			<p>1) <a href="{{ Config::get('app.asset_url') }}files/OUC.KMS.bat" class="download">点击这里</a>下载激活脚本</p>
			<p class="img"><img src="{{ Config::get('app.asset_url') . 'images/customer/ouc/help/kms/kms_1.jpg' }}"/></p>
			<p>2) 右键点击脚本选择“以管理员身份运行”，如图：</p>
			<p class="img"><img src="{{ Config::get('app.asset_url') . 'images/customer/ouc/help/kms/kms_2.jpg' }}"/></p>
			<p>3) 在出现的界面中输入你要激活的项目编号，并按回车键，如图：</p>
			<p class="img"><img src="{{ Config::get('app.asset_url') . 'images/customer/common/help/kms/kms_3.png' }}"/></p>

		</div>
	</div>
	<div class="clear"></div>
</div>
