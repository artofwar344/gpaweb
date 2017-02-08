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
		<h1 class="header_1">卸载 Microsoft Office 套件</h1>
		<div class="help_content">
			<p><b>方法1：从“控制面板”卸载 Microsoft Office 套件</b></p>
			<p>解决步骤</p>
			<p>1.打开控制面板</p>
			<p class="img"><img src="{{ Config::get('app.eurasia_url') . 'images/customer/common/help/uninstall_office/uninstall_1.png' }}"/></p>
			<p>2.点击”卸载程序”</p>				
			<p class="img"><img src="{{ Config::get('app.eurasia_url') . 'images/customer/common/help/uninstall_office/uninstall_2.png' }}"/></p>
			<p>3.在当前安装的程序列表中找到Microsoft Office项</p>
			

			<p>4.选择该项，然后单击”卸载”（在Windows XP中为”删除”）</p>
			<p class="img"><img src="{{ Config::get('app.eurasia_url') . 'images/customer/common/help/uninstall_office/uninstall_3.png' }}"/></p>
			<p>5.如果列表中存在多个Office 产品，请重复此操作</p>
			<p>6.重新启动计算机</p>
			<p><b>方法 2：使用 Microsoft Fix it 卸载 Microsoft Office 套件</b></p>
			<p>
				1.	<a href="http://10.50.0.95/MicrosoftEasyFix50154.msi">MicrosoftEasyFix50154</a> 工具用来卸载office 2007 <br/><br/>
				2.	<a href="http://10.50.0.95/MicrosoftEasyFix50416.msi">MicrosoftEasyFix50416</a> 工具用来卸载office 2003 <br/><br/>
				3.	<a href="http://10.50.0.95/MicrosoftEasyFix50450.msi">MicrosoftEasyFix50450</a> 工具用来卸载office 2010 <br/><br/>
				4.	<a href="http://10.50.0.95/o15ctrremove.diagcab">o15ctrremove.diagcab</a> 工具用来卸载office 2013、office2016、office365<br/><br/>
				注意 Fix it 解决方案不会删除单独安装在计算机上的个别 Office 程序。例如，如果您安装了 Microsoft Office Professional 2007 和 Microsoft Office Visio 2007，则 Fix it 解决方案仅删除 Microsoft Office Professional 2007，而不会删除 Visio 2007。<br/>
				此外，如果您使用 Fix it 解决方案之一，则必须重新启动计算机。

			</p>
		</div>
	</div>
	<div class="clear"></div>
</div>
