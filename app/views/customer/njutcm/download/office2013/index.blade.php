<script type="text/javascript">
	$(function() {
		$(".tab").hide().eq(0).show();
		$(".tabsheet_1").find("a").click(function() {
			$(".tab").hide().eq($(this).index()).show();
			$(this).addClass("hot").siblings().removeClass("hot");
			return false;
		});

		var downloads = [
			{ "size" : "811MB", "md5" : "6a55679004456e032b7b78aaaa2b1cd6" },
			{ "size" : "915MB", "md5" : "6888fa11c0e59ac8652645a5067d5f1b" }
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
				<a href="/download/windows8.html" style="background-image:url({{ Config::get('app.asset_url') . 'images/customer/common/win8pro.gif' }})" class="item">Windows 8 专业版</a>
				<a href="/download/office2013.html" class="item hot" style="background-image:url({{ Config::get('app.asset_url') . 'images/customer/common/office2013.gif' }})">Office 2013 专业增强版</a>
			</div>
		</div>
	</div>
	<div class="frame_1_r">
		<h1 class="header_1">微软® Office 2013 专业增强版</h1>
		<div class="content product_main">
			<img src="{{ Config::get('app.asset_url') . 'images/customer/common/office2013/office2013_main.jpg' }}">
			<form class="download" target="_blank" method="POST" action="/download/file">
				<input type="hidden" name="name" value="office2013" />
				<h2>选择 Office 2013 专业增强版 版本: </h2>
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
				<h2>包含组件</h2>
				<div class="product_office2013_assembly">
					<ul>
						<li>Word</li>
						<li class="excel">Excel</li>
						<li class="ppt">PowerPoint</li>
						<li class="onenote">OneNote</li>
						<li class="outlook">Outlook</li>
						<li class="publisher">Publisher</li>
						<li class="access">Access</li>
						<li class="infopath">InfoPath</li>
						<li class="lync">Lync</li>
					</ul>
				</div>
				<h2>使用 Office更便捷地工作</h2>
				<div class="product_office2013_info">
					<img src="{{ Config::get('app.asset_url') . 'images/customer/common/office2013/introduction_1.jpg' }}">
					<p>最适合那些希望在一台 Windows PC 上拥有完整 Office 程序的用户专业标准在不断发展。升级到最新洁、时尚的界面，帮助您更快地创建内容和进行通信。此外，还可将文档联机保存在 SkyDrive 中以便可以从几乎任何位置进行访问。</p>
					<div class="clear"></div>
				</div>
				<h3>简化共享方式</h3>
				<div class="product_office2013_info">
					<ul>
						<li>使用免费的 Office Web Apps，在任何具有 Internet 连接的设备上访问、编辑和共享文档</li>
						<li>使用 Outlook 中全套的电子邮件、日程安排和任务列表工具装备自己</li>
						<li>无需切换屏幕，即可在 Outlook 中一目了然地查看约会、电子邮件或联系人详细信息</li>
						<li>使用 OneNote 捕获、整理和共享所有类型的信息</li>
					</ul>
				</div>
				<h3>您的个性化 Office</h3>
				<div class="product_office2013_info">
					<img src="{{ Config::get('app.asset_url') . 'images/customer/common/office2013/introduction_2.jpg' }}">
					<ul>
						<li>最新版本的 Word、Excel、PowerPoint、OneNote、Outlook、Access 和 Publisher，每次都按您的方式进行个性化</li>
						<li>文档将自动保存至 SkyDrive，以便轻松在线访问和共享</li>
					</ul>
					<div class="clear"></div>
				</div>
				<h2>Office 2013 中的新增功能</h2>
				<div class="product_video" style="height:387px; margin-bottom:10px">
					<iframe width="700" height="387" frameborder="0" marginwidth="0" marginheight='0' scrolling="no" src="http://hub.video.msn.com/embed/066b2ade-53c8-43c4-9776-f3f1236dbd97/?vars=bWt0PXpoLWNuJmNvbmZpZ05hbWU9c3luZGljYXRpb25wbGF5ZXImc3luZGljYXRpb249dGFnJmxpbmtvdmVycmlkZTI9aHR0cCUzQSUyRiUyRm9mZmljZS5taWNyb3NvZnQuY29tJTJGemgtY24lMkZzdXBwb3J0JTJGSEExMDI4MzAyMTMuYXNweCUzRnZpZGVvSWQlM0QlN0IwJTdEJTI2ZnJvbSUzRCZsaW5rYmFjaz1odHRwJTNBJTJGJTJGdmlkZW8uY24ubXNuLmNvbSUyRiZmcj1zaGFyZWVtYmVkLXN5bmRpY2F0aW9uJmNvbmZpZ0NzaWQ9TVNOVmlkZW8%3D">
						<a href="http://office.microsoft.com/zh-cn/support/HA102830213.aspx?videoId=066b2ade-53c8-43c4-9776-f3f1236dbd97&from=shareembed-syndication&src=v5:embed:syndication:" target="_new" title="视频：Office 2013 中的新增功能">视频: 视频：Office 2013 中的新增功能</A>
					</iframe>
				</div>
				<p>打开 Microsoft Office 时首先会看到简约的新外观。但是，您熟知的以及经常使用的功能仍保留在此处，另外，还引入了可大大节省时间的新增功能。新版 Office 还可以在智能手机、平板电脑和云中使用，甚至在未安装 Office 的 PC 上也能使用。因此，不管您身居何处，使用了什么设备，始终都能访问您的重要文件。</p>
				<h3 style="line-height:30px">1、随时随地登录 Office</h3>
				<div class="product_requirement">
					<ul>
						<li>使用 Microsoft 帐户安装 Office。</li>
						<li>将 Office 程序以流的方式传输到另一台计算机。</li>
						<li>将文件保存到 SkyDrive，以便轻松访问和共享。</li>
						<li>随时随地保留个人设置。</li>
					</ul>
					<div class="clear"></div>
				</div>
				<div class="product_requirement">
					<div class="product_video" style="height:387px; margin-bottom:10px">
						<iframe width="700" height="387" frameborder="0" marginwidth="0" marginheight='0' scrolling="no" src="http://hub.video.msn.com/embed/4b18d261-c447-4d2c-9e5c-9ebcc7e47ae8/?vars=ZnI9c2hhcmVlbWJlZC1zeW5kaWNhdGlvbiZjb25maWdDc2lkPU1TTlZpZGVvJm1rdD16aC1jbiZjb25maWdOYW1lPXN5bmRpY2F0aW9ucGxheWVyJnN5bmRpY2F0aW9uPXRhZyZsaW5rb3ZlcnJpZGUyPWh0dHAlM0ElMkYlMkZvZmZpY2UubWljcm9zb2Z0LmNvbSUyRnpoLWNuJTJGc3VwcG9ydCUyRkhBMTAyODMwMjEzLmFzcHglM0Z2aWRlb0lkJTNEJTdCMCU3RCUyNmZyb20lM0QmbGlua2JhY2s9aHR0cCUzQSUyRiUyRnZpZGVvLmNuLm1zbi5jb20lMkY%3D">
							<A href="http://office.microsoft.com/zh-cn/support/HA102830213.aspx?videoId=4b18d261-c447-4d2c-9e5c-9ebcc7e47ae8&from=shareembed-syndication&src=v5:embed:syndication:" target="_new" title="视频：充分利用 Office 订阅">视频: 视频：充分利用 Office 订阅</a></iframe>					</div>
					<div class="clear"></div>
				</div>
				<div class="product_win8 product_office2013">
					<img src="{{ Config::get('app.asset_url') . 'images/customer/common/office2013/office_2013_skill_1.jpg' }}">
					<ul>
						<li>
							您甚至不需要在自己的计算机面前即可使用 Office。只要您已连接到 Internet，就可以使用 Office on Demand 临时流式传输完整版本的 Word、Excel 、PowerPoint、Access 和 Publisher 到运行 Win 7（或更高版本）的 PC。这样，您可以创建文档或继续处理已保存到 SkyDrive 的文档。
						</li>
					</ul>
					<div class="clear"></div>
				</div>
				<h3>2、在云中保存和共享文件</h3>
				<div class="product_win8 product_office2013">
					<img src="{{ Config::get('app.asset_url') . 'images/customer/common/office2013/office_2013_skill_2.jpg' }}">
					<ul>
						<li>云就相当于天上的文件存储。每当您联机时，就可以访问云。现在，您可以轻松地将 Office 文件保存到自己的 SkyDrive 或组织的网站中。在这些位置，您可以访问和共享 Word 文档、Excel 电子表格和其他 Office 文件。您甚至还可以与同事共同处理同一个文件。</li>
					</ul>
					<div class="clear"></div>
				</div>
				<h3>3、共享会议</h3>
				<div class="product_win8 product_office2013">
					<img src="{{ Config::get('app.asset_url') . 'images/customer/common/office2013/office_2013_skill_3.jpg' }}">
					<ul>
						<li>加入联机会议并共享 PowerPoint 幻灯片、Word 文档、Excel 电子表格和 OneNote 笔记。即使未安装 Office，与会者也可以查看这些文件。</li>
					</ul>
					<div class="clear"></div>
				</div>
				<h3>4、更多入门选项</h3>
				<div class="product_win8 product_office2013">
					<img src="{{ Config::get('app.asset_url') . 'images/customer/common/office2013/office_2013_skill_4.jpg' }}">
					<ul>
						<li>现在您可以在最近的文件或最喜爱的模板（而不是一个空白文件）之间进行选择。在 OneNote 中，您可以从 Web 或本机登录和打开笔记本。</li>
					</ul>
					<div class="clear"></div>
				</div>
				<h3>5、改进了“另存为”和“打开”功能</h3>
				<div class="product_win8 product_office2013">
					<img src="{{ Config::get('app.asset_url') . 'images/customer/common/office2013/office_2013_skill_5.jpg' }}">
					<ul>
						<li>再也无需在对话框中执行浏览和滚动操作。一开始就显示最常用的文件夹。也可固定某个位置，以便随时可用。</li>
					</ul>
					<div class="clear"></div>
				</div>
				<h3>6、更容易共享文件</h3>
				<div class="product_win8 product_office2013">
					<img src="{{ Config::get('app.asset_url') . 'images/customer/common/office2013/office_2013_skill_6.jpg' }}">
					<ul>
						<li>现在，Office 程序中的“文件”>“共享”在一个位置集中了用于与其他人共享文件的所有选项。</li>
					</ul>
					<div class="clear"></div>
				</div>
			</div>
			<div class="content product_office2013_configuration tab">
				<h2>Office 2013 专业增强版系统要求</h2>
				<table>
					<tbody>
					<tr class="even">
						<th style="width:120px">组件</th>
						<th>要求</th>
					</tr>
					<tr class="">
						<td>计算机和处理器</td>
						<td>使用 SSE2 指令集的 1GHz 或更快的 x86 或 64 位处理器。</td>
					</tr>
					<tr class="even">
						<td>内存</td>
						<td>1 GB RAM（32 位）；2 GB RAM（64 位）。</td>
					</tr>
					<tr class="">
						<td>硬盘</td>
						<td>3.0 GB 的可用磁盘空间。</td>
					</tr>
					<tr class="even">
						<td>显示器</td>
						<td>图形硬件加速需要 DirectX10 图形卡和 1366 x 768 分辨率。</td>
					</tr>
					<tr class="">
						<td>操作系统</td>
						<td>Windows 7、Windows 8、Windows Server 2008 R2 或 Windows Server 2012。</td>
					</tr>
					<tr class="even">
						<td>浏览器</td>
						<td>Microsoft Internet Explorer 8、9 或 10；Mozilla Firefox 10.x 或更高版本；Apple Safari 5；Google Chrome 17.x。</td>
					</tr>
					<tr class="">
						<td>.Net 版本</td>
						<td>3.5、4.0 或 4.5。</td>
					</tr>
					<tr class="even">
						<td>多点触控</td>
						<td>需要支持触控的设备才能使用任一多点触控功能。但使用键盘、鼠标或其他标准输入设备或可访问的输入设备则始终可以使用所有功能。请注意，新的触控功能针对与 Windows 8 配合使用进行了优化。</td>
					</tr>
					</tbody>
				</table>
			</div>
			<div class="content download_page tab">
				<h2>Office 2013 专业增强版下载安装</h2>
				<h3 class="header_6">
					<span>软件安装</span>
				</h3>
				<h4>1. 运行安装程序</h4>
				<img src="{{ Config::get('app.asset_url') . 'images/customer/common/office2013/01.jpg' }}">
				<h4>2. 选择安装方式</h4>
				<img src="{{ Config::get('app.asset_url') . 'images/customer/common/office2013/02.jpg' }}">
				<h4>3. 选择要安装的组件、安装位置（注：这一步是选择上图的’自定义‘后出现的）</h4>
				<img src="{{ Config::get('app.asset_url') . 'images/customer/common/office2013/03.jpg' }}">
				<h4>4. 正在安装</h4>
				<img src="{{ Config::get('app.asset_url') . 'images/customer/common/office2013/04_1.jpg' }}">
				<img src="{{ Config::get('app.asset_url') . 'images/customer/common/office2013/04_2.jpg' }}">
				<h4>5. 安装完成</h4>
				<img src="{{ Config::get('app.asset_url') . 'images/customer/common/office2013/05.jpg' }}">
			</div>
		</div>
	</div>
<div class="clear"></div>
</div>