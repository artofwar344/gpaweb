{? $uri = Request::path(); ?}
<div class="product_menu">
	<h1>使用帮助</h1>
	<div class="list">
		<a href="/help/client" style="background-image:url({{ Config::get('app.asset_url') . 'images/customer/common/help/gpclient_logo.png' }})"
		   class="item @if($uri == 'help/client') hot @endif">
			GP激活客户端{{ \Ca\Service\ParamsService::get('clientpublishversion') == 3 ? '3.0' : '' }}
			
		</a>
		<a href="/help/u" style="background-image:url({{ Config::get('app.eurasia_url') . 'images/customer/common/help/USB.png' }})"
		   class="item @if($uri == 'help/client') hot @endif">
			
			制作U盘启动盘
		</a>
		<a href="/help/sp1" style="background-image:url({{ Config::get('app.eurasia_url') . 'images/customer/common/help/sp1.png' }})"
		   class="item @if($uri == 'help/client') hot @endif">
			
			Win7安装SP1补丁
		</a>
		<a href="/help/office_error" style="background-image:url({{ Config::get('app.eurasia_url') . 'images/customer/common/help/office.png' }});line-height: 30px;" class="item @if($uri == 'help/client') hot @endif">
			
			Office安装时遇到的错误及解决方法
		</a>
		<a href="/help/activate_error" style="background-image:url({{ Config::get('app.eurasia_url') . 'images/customer/common/help/Error.png' }});line-height: 30px;" class="item @if($uri == 'help/client') hot @endif">
			
			关于激活错误0xC004F035解决方案
		</a>
		<a href="/help/uninstall" style="background-image:url({{ Config::get('app.eurasia_url') . 'images/customer/common/help/Uninstallt.png' }});line-height: 30px;" class="item @if($uri == 'help/client') hot @endif">
			
			卸载 Microsoft Office 套件
		</a>
	</div>
</div>
