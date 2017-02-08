@if (Input::get('download') == 1)
<script type="text/javascript">
	
</script>
@endif
<div class="frame_1 main_content">
	<div class="frame_1_l">
		@if (View::exists('customer.' . App::make('customer')->alias . '.partials.help.menu'))
		@include('customer.' . App::make('customer')->alias . '.partials.help.menu')
		@else
		@include('customer.common.partials.help.menu')
		@endif
	</div>
<style type="text/css">
	.help_content .img{
		background: #fff;
	}
	.help_content p{
		font-size:14px; 
		color: black;
	}
</style>
	<div class="frame_1_r">
		<h1 class="header_1">Office安装时遇到的错误及解决方法</h1>
		<div class="help_content">
			<p>1.Win7系统缺少SP1补丁</p>
			<p class="img">问题：如果所使用的win7系统没有装SP1补丁，那么在该系统上安装office2016时会出现如下图所示错误：</p>
			<p class="img"><img src="{{ Config::get('app.eurasia_url') . 'images/customer/common/help/office_error/office_error1.png' }}"/></p>
			<p>解决方法：</p>
			<p class="img">
				方法1：安装SP1补丁<a href="{{url('/help/sp1')}}">(参见win7安装SP1补丁文档)</a><br/>
				方法2：在ms.eurasia.edu上下载带SP1补丁的专业版win7镜像，然后重装系统<br/>
				方法3：在ms.eurasia.edu上下载win10镜像，将现有win7升级到win10系统
			</p>
			<p>2.Win7系统缺少更新</p>
			<p class="img">问题：如果所使用的win7系统缺少KB2999226更新，在该系统上安装office2016时会出现如下图所示错误：</p>
			<p class="img"><img src="{{ Config::get('app.eurasia_url') . 'images/customer/common/help/office_error/office_error2.png' }}"/></p>
			<p>解决方法：</p>
			<p class="img">
				1）64位系统安装 <a href="http://10.50.0.95/Windows6.1-KB2999226-x64.msu">Windows6.1-KB2999226-x64.msu</a> 更新<br/>
				2）32位系统安装 <a href="http://10.50.0.95/Windows6.1-KB2999226-x86.msu">Windows6.1-KB2999226-x86.msu</a> 更新</p>

		</div>
	</div>
	<div class="clear"></div>
</div>
