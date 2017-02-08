<script type="text/javascript">
	$(function() {
		$(".tab").hide().eq(0).show();
		$(".tabsheet_1").find("a").click(function() {
			$(".tab").hide().eq($(this).index()).show();
			$(this).addClass("hot").siblings().removeClass("hot");
			return false;
		});

		var downloads = [
			{ "size" : "2.5G", "md5" : "32ce202cc8670492e85a91cb62c7397a" },
			{ "size" : "3.4G", "md5" : "0c28901e7f8c670584cbf9f3e3b0f13e" }
		];

		$(".download select").change(function() {
			var id = $("option:selected", $(this)).index();
			var size = downloads[id]["size"];
			var md5 = downloads[id]["md5"];
			$(".download .size").text(size);
			$(".download .md5").text(md5);
		}).change();
		$(".btn_submit").click(function() {
			$(".download").submit();
			return false;
		});
	});
</script>
<div class="product_banner"></div>
<div class="frame_1 main_content">
	<div class="frame_1_l">
		<div class="product_menu">
			<h1>Microsoft® 产品</h1>
			<div class="list">
				<a href="/download/windows7.html" style="background-image:url({{ Config::get('app.asset_url') . 'images/customer/common/win7pro.gif' }})" class="item">Windows 7 专业版</a>
				<a href="/download/office2010.html" style="background-image:url({{ Config::get('app.asset_url') . 'images/customer/common/office2010.gif' }})" class="item">Office 2010 专业增强版</a>
				<a href="/download/windows8.html" style="background-image:url({{ Config::get('app.asset_url') . 'images/customer/common/win8pro.gif' }})" class="item hot">Windows 8 专业版</a>
				<a href="/download/office2013.html" class="item" style="background-image:url({{ Config::get('app.asset_url') . 'images/customer/common/office2013.gif' }})">Office 2013 专业增强版</a>
			</div>
		</div>
	</div>
	<div class="frame_1_r">
		<h1 class="header_1">微软® Windows 8 专业版</h1>
		<div class="content product_main">
			<img src="{{ Config::get('app.asset_url') . 'images/customer/common/win8/win8pro_main.jpg' }}">
			<form class="download" target="_blank" method="POST" action="/download/file">
				<input type="hidden" name="name" value="windows8" />
				<h2>选择 Windows 8 Pro 版本: </h2>
				<ul>
					<li>
						<label>系统架构:</label>
						<select name="bit" class="select_1">
							<option value="0">32位</option>
							<option value="1">64位</option>
						</select>
					</li>
					<li>
						<label>软件大小:</label> <span class="size"></span>
					</li>
					<li><label>MD5校验码:</label> <span class="md5"></span></li>
					<li>
						@if (ParamsService::get('login2downloadproduct') != 1)
						<a href="#" class="button_2 btn_submit">立即下载</a>
						@else
						<a href="{{ Ca\Common::link_to_login() }}" class="button_2">立即下载</a>
						@endif
					</li>
				</ul>
			</form>
			<div class="clear"></div>
		</div>
		<div class="tabsheet_1">
			<a class="intro hot" href="#"><span class="fixpng">商品介绍</span></a>
			<a class="requirement" href="#"><span class="fixpng">配置要求</span></a>
			<a class="download" href="#"><span class="fixpng">安装步骤</span></a>
		</div>
		<div class="clear"></div>
		<div class="tabsheet_1_tabs">
			<div class="content tab">
				<h2>选择Win 8 的原因</h2>
				<div class="win8_introduction">
					<div class="content">
						<h2>1、更新更炫</h2>
						<span>全球最新的操作系统，超炫的系统界面，让人一见好心情</span>
						<img src="{{ Config::get('app.asset_url') . 'images/customer/common/win8/introduction_1.jpg' }}">
					</div>
					<div class="content right">
						<h2>2、超快的反应</h2>
						<span>超快的启动速度，5秒开机，1秒链接无线网！</span>
						<img src="{{ Config::get('app.asset_url') . 'images/customer/common/win8/introduction_2.jpg' }}">
					</div>
					<div class="content">
						<h2>3、IE10</h2>
						<span>稳定安全，速度超快的完美浏览器！</span>
						<img src="{{ Config::get('app.asset_url') . 'images/customer/common/win8/introduction_3.jpg' }}">
					</div>
					<div class="content right">
						<h2>4、应用商店</h2>
						<span>目前win 8 应用商店已经超过万款软件！</span>
						<img src="{{ Config::get('app.asset_url') . 'images/customer/common/win8/introduction_4.jpg' }}">
					</div>
					<div class="content">
						<h2>5、一体化平台</h2>
						<span>你将可以与你的win 8 平板、手机共享云服务</span>
						<img src="{{ Config::get('app.asset_url') . 'images/customer/common/win8/introduction_5.jpg' }}">
					</div>
					<div class="content right">
						<h2>6、便携办公</h2>
						<span>购买win 8 专业版产品有机会免费获得offcie 2013！</span>
						<img src="{{ Config::get('app.asset_url') . 'images/customer/common/win8/introduction_6.jpg' }}">
					</div>
				</div>
				<div class="clear"></div>
				<h2 class="header_2">高效、便捷的应用</h2>
				<p>Windows 8 中的应用将进行协作，帮助你快速完成任务。从 Windows 应用商店购买这些应用。</p>
				<div class="product_win8">
					<img src="{{ Config::get('app.asset_url') . 'images/customer/common/win8/win8_skill_1.jpg' }}">
					<ul>
						<li>
							<h3>1、使用应用完成更多任务</h3>
							<p>拥有的应用越多，用户体验更佳。应用可以相互协作和共享信息，方便你更轻松地执行所需操作。</p>
						</li>
						<li>
							<h3>2、随时了解信息</h3>
							<p>适合每个人的程序。浏览常用应用列表、查看工作人员建议，并根据已有应用进行个性化选择。访问简单易行—应用商店直接内置在 Windows 8 中。</p>
						</li>
						<li>
							<h3>3、有了主意？</h3>
							<p>现在就是构建应用，在全球范围开展业务的绝佳时机。可以从 Windows开发人员中心下载免费工具和示例，找到设计和代码资源，并可获得专家帮助。</p>
						</li>
					</ul>
					<div class="clear"></div>
				</div>
				<h2 class="header_2">你的 Windows，可以随处使用</h2>
				<p>一旦登录，Windows 8 就会在你与你的文件、照片、人脉和设置之间建立连接。</p>
				<div class="product_win8">
					<img src="{{ Config::get('app.asset_url') . 'images/customer/common/win8/win8_skill_2.jpg' }}">
					<ul>
						<li>
							<h3>1、你的 Windows，可以随处使用</h3>
							<p>一次完美设置，今后始终拥有。登录到任何运行 Windows 8 的设备，并随时使用自己的个性化设置和应用。</p>
						</li>
						<li>
							<h3>2、连接你的人脉</h3>
							<p>通过与朋友和家人的随时连接来装点你的应用。来自你所连接的服务（Hotmail、Messenger、Facebook、Twitter、LinkedIn 等）的信息就会出现在邮件、消息和人脉应用中。</p>
						</li>
						<li>
							<h3>3、连接你的文件</h3>
							<p>你使用多台电脑和一部电话。现在，你可以通过这些设备连接到 SkyDrive、Facebook、Flickr 等服务，然后即可顺利访问你的所有照片和文件</p>
						</li>
					</ul>
					<div class="clear"></div>
				</div>
				<h2 class="header_2">全面覆盖的网络</h2>
				<p>Internet Explorer 10 Release Preview 可为你在各种尺寸的屏幕上提供身临其境的网络浏览体验。 </p>
				<div class="product_win8">
					<img src="{{ Config::get('app.asset_url') . 'images/customer/common/win8/win8_skill_3.jpg' }}">
					<ul>
						<li>
							<h3>1、快速而流畅 </h3>
							<p>Internet Explorer 为提高速度而构建，将为你提供更快速的网络浏览体验。无论你要加载站点、共享站点还是从站点转到应用，其速度全都可与电脑上的应用相比。</p>
						</li>
						<li>
							<h3>2、直观</h3>
							<p>轻松进行浏览。在需要时，动动手指即可让选项卡和导航控件显示出来。只需轻扫轻触即可与朋友分享网站。还可以将网站固定到“开始”屏幕，单击一次即可访问。</p>
						</li>
						<li>
							<h3>3、更值得信赖的网络</h3>
							<p>更安全，更私密，更安心：Internet Explorer 在 SmartScreen 和跟踪保护等创新技术的基础上构建，旨在与 Windows 的安全平台一起使用，使你更好地控制你的个人信息，并帮助你抵御最新的网络威胁。</p>
						</li>
					</ul>
					<div class="clear"></div>
				</div>
				<h2 class="header_2">Windows 8 全新功能</h2>
				<p><h3>1、登陆Windows 8 你将看到一个全新的“开始”屏幕 </h3></p>
				<div class="product_win8">
					<img src="{{ Config::get('app.asset_url') . 'images/customer/common/win8/win8_skill_4.jpg' }}">
					<ul>
						<li>
							常用的网站、应用程序、文件夹都会在这里呈现，甚至可以随时了解某些应用的最新状态：当前天气、新邮件、新消息、好友动态……而我们熟悉的“传统桌面” 相当于Windows 8 开始屏幕中的一个应用，只要点击开始屏幕中的“桌面”，即可回到熟悉的传统桌面了。
						</li>
					</ul>
					<div class="clear"></div>
				</div>
				<p><h3>2、找到所有应用</h3></p>
				<div class="product_win8">
					<img src="{{ Config::get('app.asset_url') . 'images/customer/common/win8/win8_skill_5.jpg' }}">
					<ul>
						<li>
							在“开始”屏幕空白区域点击鼠标右键，会在屏幕底部弹出的应用栏中出现“所有应用”，点击这里即可找到我们安装的所有应用以及控制面板等系统程序。
						</li>
					</ul>
					<div class="clear"></div>
				</div>
				<p><h3>3、管理你的应用</h3></p>
				<div class="product_win8">
					<img src="{{ Config::get('app.asset_url') . 'images/customer/common/win8/win8_skill_6.jpg' }}">
					<ul>
						<li>
							在一个或多个应用图标上点击鼠标右键，可以对他们进行固定（或取消固定），以及卸载等相关操作。
						</li>
					</ul>
					<div class="clear"></div>
				</div>
				<p><h3>4、常用功能</h3></p>
				<div class="product_win8">
					<img src="{{ Config::get('app.asset_url') . 'images/customer/common/win8/win8_skill_7.jpg' }}">
					<ul>
						<li>
							在不同的应用中点击鼠标右键，操作也会有所不同，比如Internet Explorer 10 中点击右键，可以调出地址栏和切换页面选项卡等操作。
						</li>
					</ul>
					<div class="clear"></div>
				</div>
				<p><h3>5、多任务切换</h3></p>
				<div class="product_win8">
					<img src="{{ Config::get('app.asset_url') . 'images/customer/common/win8/win8_skill_8.jpg' }}">
					<ul>
						<li>
							如果你打开了多个应用，可以将鼠标移动至屏幕左上角，会出现其他应用的预览图，点击即可切换。向下移动鼠标还可以通过列表选取。
						</li>
					</ul>
					<div class="clear"></div>
				</div>
				<p><h3>6、快速返回开始屏幕</h3></p>
				<div class="product_win8">
					<img src="{{ Config::get('app.asset_url') . 'images/customer/common/win8/win8_skill_9.jpg' }}">
					<ul>
						<li>
							在任何应用中，只要将鼠标移动至屏幕左下角，会出现开始屏幕的缩略图，点击便可以快速回到开始屏幕，再次移动至左下角还可以返回刚才的应用。
						</li>
					</ul>
					<div class="clear"></div>
				</div>
				<p><h3>7、调出超级按钮</h3></p>
				<div class="product_win8">
					<img src="{{ Config::get('app.asset_url') . 'images/customer/common/win8/win8_skill_10.jpg' }}">
					<ul>
						<li>
							在任何界面下，将鼠标移动到屏幕的右上角或右下角，都可以调出“超级按钮”，通过它可以返回开始屏幕、搜索文件、调整设置等。
						</li>
					</ul>
					<div class="clear"></div>
				</div>
				<p><h3>8、在超级按钮中设置网络连接</h3></p>
				<div class="product_win8">
					<img src="{{ Config::get('app.asset_url') . 'images/customer/common/win8/win8_skill_11.jpg' }}">
					<ul>
						<li>
							在“超级按钮”中点击“设置”，会弹出设置菜单，即可设置网络连接。在设置菜单顶部区域还会出现当前应用的设置选项，比如在 Internet Explorer 10 中便是对 Internet Explorer 10 进行设置。
						</li>
					</ul>
					<div class="clear"></div>
				</div>
				<p><h3>9、在超级按钮中关机和重启</h3></p>
				<div class="product_win8">
					<img src="{{ Config::get('app.asset_url') . 'images/customer/common/win8/win8_skill_12.jpg' }}">
					<ul>
						<li>
							在“超级按钮”的“设置”菜单中，点击下方的“电源”按钮，便可对电脑进行睡眠，重启和关机。
						</li>
					</ul>
					<div class="clear"></div>
				</div>
				<p><h3>10、是否需要“关闭应用”</h3></p>
				<div class="product_win8">
					<img src="{{ Config::get('app.asset_url') . 'images/customer/common/win8/win8_skill_13.jpg' }}">
					<ul>
						<li>
							您不必再像以前的使用习惯那样频繁的关闭应用程序，因为Windows 8 在后台程序和内存管理方面做了诸多改进，通过“程序挂起”的方式，在不占用过多系统资源的基础上，方便您快速打开和快速切换。
						</li>
						<li>
							当然，如果您还是想关闭某个应用，只需要将鼠标移动至屏幕顶部，当鼠标箭头变成“小手”时，点击并向下拖拽至屏幕底部后松手，这样就可以关闭了。
						</li>
					</ul>
					<div class="clear"></div>
				</div>
			</div>
			<div class="content product_required tab">
				<h2>Windows 8系统要求</h2>
				<h3>如果您想要在电脑上运行Windows 8, 请在下面查看它所需的配置:</h3>
				<ul>
					<li>系统要求：Windows XP SP3以上、Windows Vista 或 Windows 7</li>
					<li>处理器: 1 GHz或更快并支持PAE,NX,和SSE2 (更多信息请见windows.com/upgrade)</li>
					<li>RAM: 1 GB(32位)或2 GB(64位)</li>
					<li>硬盘空间: 16 GB(32位)或20 GB(64位)</li>
					<li>图形卡: 带有WDDM驱动程序的Microsoft DirectX 9 图形设备</li>
				</ul>
				<h3>若需使用某些特定功能，还有下面一些附加要求:</h3>
				<ul>
					<li>若要使用触控, 您需要支持多点触控的平板电脑或者显示器 (更多信息请见windows.com/upgrade)。</li>
					<li>若要访问 Windows 应用商店并下载和运行程序，您需要有效的 Internet 连接及至少 1024 x 768 的屏幕分辨率。<br></li>
					<li>若要贴靠程序，您需要至少 1366 x 768 的屏幕分辨率。</li>
					<li>Internet 访问（可能会有网络宽带费）</li>
					<li>安全引导需要固件支持UEFI v2.3.1 Errata B并且在UEFI特征数据库中包含微软Windows认证授权</li>
					<li>一些游戏和程序可能需要图形卡与<a target="_blank" href="http://go.microsoft.com/fwlink/?LinkId=165551">DirectX 10</a>或<a target="_blank" href="http://windows.microsoft.com/zh-CN/windows7/products/features/directx-11">更高版本</a>兼容，以获得最佳性能。</li>
					<li>部分功能需要微软账户去实现<br></li>
					<li>观看DVD需要单独的播放软件(更多信息请见windows.com/upgrade)</li>
					<li>Windows媒体中心许可单独出售(更多信息请见windows.com/upgrade)</li>
					<li><a target="_blank" href="http://windows.microsoft.com/zh-CN/windows7/products/features/bitlocker">BitLocker To Go</a>需要 USB 闪存驱动器(只适用于Windows 8专业版)</li>
					<li><a target="_blank" href="http://windows.microsoft.com/zh-CN/windows7/products/features/bitlocker">BitLocker</a>需要受信任的平台模块 (TPM) 1.2或USB闪存驱动器(只适用于Windows 8专业版)</li>
					<li>Hyper – V客户端要求64位系统与二级地址转换(SLAT)功能和额外的2 GB的RAM(只适用于Windows 8专业版)</li>
					<li>若要在<a target="_blank" href="http://windows.microsoft.com/zh-CN/windows7/products/features/windows-media-center">Windows 媒体中心</a>播放和录制直播电视，需要电视调谐器 (只适用于Windows 8专业包和Windows 8的媒体中心包)</li>
					<li>免费网络电视内容根据地域不同,一些内容可能需要额外的费用(只适用于Windows 8 专业包和Windows 8的媒体中心包)</li>
				</ul>
			</div>
			<div class="content download_page tab">
				<h2>微软® Windows 8 专业版 下载安装</h2>
				<h3 class="header_6">
					<span>安装选项</span>
				</h3>
				<h4>1. 启动安装程序。</h4>
				<img src="{{ Config::get('app.asset_url') . 'images/customer/common/win8/win8pro_install_1.jpg' }}" />
				<h4>2. 正在安装。</h4>
				<img src="{{ Config::get('app.asset_url') . 'images/customer/common/win8/win8pro_install_2_1.jpg' }}" />
				<img src="{{ Config::get('app.asset_url') . 'images/customer/common/win8/win8pro_install_2_2.jpg' }}" />
				<img src="{{ Config::get('app.asset_url') . 'images/customer/common/win8/win8pro_install_2_3.jpg' }}" />
				<img src="{{ Config::get('app.asset_url') . 'images/customer/common/win8/win8pro_install_3.jpg' }}" />
				<h4>3. 正在重启。</h4>
				<img src="{{ Config::get('app.asset_url') . 'images/customer/common/win8/win8pro_install_4.jpg' }}" />
				<h4>4. 正在准备。</h4>
				<img src="{{ Config::get('app.asset_url') . 'images/customer/common/win8/win8pro_install_5.jpg' }}" />
				<img src="{{ Config::get('app.asset_url') . 'images/customer/common/win8/win8pro_install_6.jpg' }}" />
				<img src="{{ Config::get('app.asset_url') . 'images/customer/common/win8/win8pro_install_7.jpg' }}" />
				<img src="{{ Config::get('app.asset_url') . 'images/customer/common/win8/win8pro_install_8.jpg' }}" />
				<img src="{{ Config::get('app.asset_url') . 'images/customer/common/win8/win8pro_install_9_1.jpg' }}" />
				<img src="{{ Config::get('app.asset_url') . 'images/customer/common/win8/win8pro_install_9_2.jpg' }}" />
				<img src="{{ Config::get('app.asset_url') . 'images/customer/common/win8/win8pro_install_9_3.jpg' }}" />
				<img src="{{ Config::get('app.asset_url') . 'images/customer/common/win8/win8pro_install_9_4.jpg' }}" />
				<img src="{{ Config::get('app.asset_url') . 'images/customer/common/win8/win8pro_install_9_5.jpg' }}" />
				<img src="{{ Config::get('app.asset_url') . 'images/customer/common/win8/win8pro_install_9_6.jpg' }}" />
				<h4>5. 进入“开始”界面。</h4>
				<img src="{{ Config::get('app.asset_url') . 'images/customer/common/win8/win8pro_install_10.jpg' }}" />

			</div>
		</div>
	</div>
	<div class="clear"></div>
</div>
