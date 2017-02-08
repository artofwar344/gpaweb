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
		<h1 class="header_1">制作U盘启动盘</h1>
		<div class="help_content">
			<p>1.下载并安装UltraISO（软碟通）软件</p>
			<p class="img">下载地址：http://www.ezbsystems.com/dl1.php?file=uiso9_cn.exe</p>
			<p>2.打开UltraISO，选择’继续试用’</p>
			<p class="img"><img src="{{ Config::get('app.eurasia_url') . 'images/customer/common/help/u/u1.png' }}"/></p>
			<p class="img"><img src="{{ Config::get('app.eurasia_url') . 'images/customer/common/help/u/u2.png' }}"/></p>
			<p>3.点击菜单栏的’文件’-‘打开’，找到下载的系统镜像并打开</p>
			<p class="img"><img src="{{ Config::get('app.eurasia_url') . 'images/customer/common/help/u/u3.png' }}"/></p>
			<p class="img"><img src="{{ Config::get('app.eurasia_url') . 'images/customer/common/help/u/u4.png' }}"/></p>
			<p>4.将U盘插到电脑上（U盘里的文件要提前备份到其他地方，制作启动盘时会删除U盘里的所有数据），点击菜单栏的’启动’-‘写入硬盘映像’</p>
			<p class="img"><img src="{{ Config::get('app.eurasia_url') . 'images/customer/common/help/u/u5.png' }}"/></p>
			<p class="img"><img src="{{ Config::get('app.eurasia_url') . 'images/customer/common/help/u/u6.png' }}"/></p>
			<p>5.开始写入</p>
			<p class="img"><img src="{{ Config::get('app.eurasia_url') . 'images/customer/common/help/u/u7.png' }}"/></p>
			<p>6.写入完成</p>
			<p class="img"><img src="{{ Config::get('app.eurasia_url') . 'images/customer/common/help/u/u8.png' }}"/></p>
			<p>至此，U盘启动盘已制作完成，可以使用U盘启动来安装系统了。</p>

			
			
		</div>
	</div>
	<div class="clear"></div>
</div>
