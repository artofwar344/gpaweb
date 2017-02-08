<script type="text/javascript">
	$(function() {
		$(".tab").hide().eq(0).show();
		$(".tabsheet_1").find("a").click(function() {
			$(".tab").hide().eq($(this).index()).show();
			$(this).addClass("hot").siblings().removeClass("hot");
			return false;
		});

		var downloads = [
			{ "size" : "2.4G", "md5" : "ebc9e41ca8c31654d33e43be66153bae" },
			{ "size" : "3.1G", "md5" : "4385b3fa450ae8c91a7df3594dc62c14" }
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
				<a href="/download/windows7.html" style="background-image:url({{ Config::get('app.asset_url') . 'images/customer/common/win7pro.gif' }})" class="item hot">Windows 7 专业版</a>
				<a href="/download/office2010.html" style="background-image:url({{ Config::get('app.asset_url') . 'images/customer/common/office2010.gif' }})" class="item">Office 2010 专业增强版</a>
				<a href="/download/windows8.html" style="background-image:url({{ Config::get('app.asset_url') . 'images/customer/common/win8pro.gif' }})" class="item">Windows 8 专业版</a>
				<a href="/download/office2013.html" class="item" style="background-image:url({{ Config::get('app.asset_url') . 'images/customer/common/office2013.gif' }})">Office 2013 专业增强版</a>
			</div>
		</div>
	</div>
	<div class="frame_1_r">
		<h1 class="header_1">微软® Windows 7 专业版</h1>
		<div class="content product_main">
			<img src="{{ Config::get('app.asset_url') . 'images/customer/common/win7/win7_main.jpg' }}">
			<form class="download" target="_blank" method="POST" action="/download/file">
				<input type="hidden" name="name" value="windows7" />
				<h2>选择 Windows 7 Pro 版本: </h2>
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
				<script type="text/javascript">
					$(document).ready(function() {
						$(".product_video").click(function() {
							var play = '<object width="700" height="376" data="data:application/x-silverlight-2," type="application/x-silverlight-2">\
					<param value="http://www.microsoft.com/showcase/silverlight/player/1/player-zh.xap" name="source">\
					<param value="true" name="enableHtmlAccess">\
					<param value="black" name="background">\
					<param value="3.0.40624.0" name="minRuntimeVersion">\
					<param value="true" name="autoUpgrade">\
					<param value="Culture=zh-CN,Uuid=188977e8-ecc2-499e-a6e6-1afd1d962e2d,Autoplay=true,MarketingOverlayText=访问此视频的网站,ShowMarketingOverlay=true,ShowMenu=True,Tabs=Embed;Email;Share;Info" name="initParams"><a href="http://go.microsoft.com/fwlink/?LinkID=149156&amp;v=3.0.40624.0" border="0" onmousedown="javascript:new Image().src = \'http://m.webtrends.com/dcsygm2gb10000kf9xm7kfvub_9p1t/dcs.gif?dcsdat=\' + new Date().getTime() + \'&amp;dcssip=www.microsoft.com&amp;dcsuri=\' + window.location.href + \'&amp;WT.tz=-8&amp;WT.bh=16&amp;WT.ul=zh-CN&amp;WT.cd=32&amp;WT.jo=Yes&amp;WT.ti=&amp;WT.js=Yes&amp;WT.jv=1.5&amp;WT.fi=Yes&amp;WT.fv=10.0&amp;WT.sli=Not%20Installed&amp;WT.slv=Version%20Unavailable&amp;WT.dl=1&amp;WT.seg_1=Not%20Logged%20In&amp;WT.vt_f_a=2&amp;WT.vt_f=2&amp;WT.vt_nvr1=2&amp;WT.vt_nvr2=2&amp;WT.vt_nvr3=2&amp;WT.vt_nvr4=2&amp;vp_site=Embedded&amp;wtEvtSrc=\' + window.location.href + \'&amp;vp_sli=Embedded\'"><img src="http://img.microsoft.com/showcase/Content/img/resx/zh-CN/installSL.gif" alt="Get Microsoft Silverlight" style="border-style: none;"> </a><noscript>&lt;div&gt;&lt;img alt="DCSIMG" id="DCSIMG" width="1" height="1" src="http://m.webtrends.com/dcsygm2gb10000kf9xm7kfvub_9p1t/njs.gif?dcsuri=/nojavascript&amp;WT.js=No"/&gt;&lt;/div&gt;</noscript></object>\
					';
							$(".product_video").html(play);
							return false;
						});
					});
				</script>
				<h2 class="header_2">Windows 7先睹为快</h2>
				<div class="product_gallery">
					<img title="<img src='{{ Config::get('app.asset_url') . 'images/customer/common/win7/win7_new_1_b.jpg' }}' />" src="{{ Config::get('app.asset_url') . 'images/customer/common/win7/win7_new_1.jpg' }}" />
					<img title="<img src='{{ Config::get('app.asset_url') . 'images/customer/common/win7/win7_new_2_b.jpg' }}' />" src="{{ Config::get('app.asset_url') . 'images/customer/common/win7/win7_new_2.jpg' }}" />
					<img title="<img src='{{ Config::get('app.asset_url') . 'images/customer/common/win7/win7_new_3_b.jpg' }}' />" src="{{ Config::get('app.asset_url') . 'images/customer/common/win7/win7_new_3.jpg' }}" />
					<img title="<img src='{{ Config::get('app.asset_url') . 'images/customer/common/win7/win7_new_4_b.jpg' }}' />" src="{{ Config::get('app.asset_url') . 'images/customer/common/win7/win7_new_4.jpg' }}" />
					<img title="<img src='{{ Config::get('app.asset_url') . 'images/customer/common/win7/win7_new_5_b.jpg' }}' />" src="{{ Config::get('app.asset_url') . 'images/customer/common/win7/win7_new_5.jpg' }}" />
					<div class="clear"></div>
				</div>
				<h2 class="header_2">Windows 7意味着什么？</h2>
				<ul class="list_2">
					<li>Windows 7是微软公司在操作系统历史中最具有里程碑意义的版本之一。</li>
					<li>这是Windows系列至今最关注用户体验的一个版本，他有着更快速的系统反应，对计算机性能的优化有极大的提升。</li>
					<li>还记得那个陪伴我们多年的任务栏了吗？在Windows 7中他将以全新的姿态出场。</li>
					<li>近年来，电脑系统一直在朝人性化方向发展，Windows 7在这方面下了很多功夫，她的桌面和多媒体体验与WindowsXp相比，已经不再是简单的升级，而是一次变革，让您感觉面对的不再是一台冷冰冰的计算机，而是一份对用户的体贴。</li>
					<li>还很抽象？那么请关注下面的视频短片吧!</li>
				</ul>
				<div class="product_video"><a href="#"><img src="{{ Config::get('app.asset_url') . 'images/customer/common/win7/win7_video_1.jpg' }}"/></a></div>
				<h2 class="header_2">Windows 7 不同版本比较</h2>
				<div><img src="{{ Config::get('app.asset_url') . 'images/customer/common/win7/win7_compare.jpg' }}" /></div>
				<h2 class="header_2">Windows 7, 小功能，大智慧</h2>
				<div class="product_tips">
					<ul>
						<li>
							<div class="intro">
								<h3>1. 实时预览</h3>
								<div class="clear"></div>
								<h4>Windows 7任务栏中方便的窗口预览, 让更多Windows 7 先期体念一族流连忘返.</h4>
								<p>将鼠标移动到任务栏中的程序图标上, 就会自动出现这个程序已打开窗口的缩略图; 如果将鼠标悬停在缩略图上, 则窗口将展开为全屏预览; 您甚至可以直接从缩略图关闭窗口.</p>
							</div>
							<img src="{{ Config::get('app.asset_url') . 'images/customer/common/win7/win7_skill_1.jpg' }}" />
							<div class="clear"></div>
						</li>
						<li>
							<div class="intro">
								<h3>2. 锁定任务栏</h3>
								<div class="clear"></div>
								<h4>常用的程序可以锁定到任务栏上, 让您轻松实现 "我的任务栏我做主"</h4>
								<p>在常用的程序图标上点击鼠标右键, 既可以将其锁定在任务栏上, 比如将"截图工具"锁定在任务栏上, 当您再次使用这一程序时, 就可以直接从任务栏打开了.</p>
							</div>
							<img src="{{ Config::get('app.asset_url') . 'images/customer/common/win7/win7_skill_2.jpg' }}" />
							<div class="clear"></div>
						</li>
						<li>
							<div class="intro">
								<h3>3. "钉"住文档</h3>
								<div class="clear"></div>
								<h4>在"深不可测"的层层文件夹中找到常用文档, 不再是挑战</h4>
								<p>Windows 7 跳转列表自动列出了最常用和最近访问的内容, 所有查找最喜爱的歌曲或昨天使用过的文件会更加省时. 点击鼠标右键, 还可以"钉"住常用文档, 从而能够随时快速访问.</p>
							</div>
							<img src="{{ Config::get('app.asset_url') . 'images/customer/common/win7/win7_skill_3.jpg' }}" />
							<div class="clear"></div>
						</li>
						<li>
							<div class="intro">
								<h3>4. 窗口对对碰</h3>
								<div class="clear"></div>
								<h4>只需鼠标左右拖拽, 即可让两个窗口在屏幕上平分秋色</h4>
								<p>只要用鼠标把窗口往屏幕左边或者右边拖拽, 窗口会自动以左半屏状态显示, 这和需要复制文件内容或比较两个窗口的内容时非常有用.</p>
							</div>
							<img src="{{ Config::get('app.asset_url') . 'images/customer/common/win7/win7_skill_4.jpg' }}" />
							<div class="clear"></div>
						</li>
						<li>
							<div class="intro">
								<h3>5. 透视桌面和鼠标晃动</h3>
								<div class="clear"></div>
								<h4>即使窗口很多, 也能随时让桌面一览无余; 并且鼠标晃一下, 就可以只留下您想要的那个窗口</h4>
								<p>透视桌面: 将鼠标悬停在任务栏的最右端, 所有已经打开的窗口将变得透明.</p>
								<p>鼠标晃动: 鼠标点住某个窗口并晃动它, 其他所有窗口都会最小化到任务栏, 再次晃动此窗口可以还原所有窗口</p>
							</div>
							<img src="{{ Config::get('app.asset_url') . 'images/customer/common/win7/win7_skill_5.jpg' }}" />
							<div class="clear"></div>
						</li>
						<li>
							<div class="intro">
								<h3>6. 一键联网</h3>
								<div class="clear"></div>
								<h4>不需要再打开单独的设置面板即可一键链接到各种网络</h4>
								<p>在任务栏右下角点击网络图标, 所有可用网络都会一览无余, 点击一下即可连接.</p>
							</div>
							<img src="{{ Config::get('app.asset_url') . 'images/customer/common/win7/win7_skill_6.jpg' }}" />
							<div class="clear"></div>
						</li>
					</ul>
				</div>
			</div>
			<div class="content product_required tab">
				<h2 class="header_2">硬件要求</h2>
				<div class="product_requirement">
					<ul>
						<li>配备 1.6 GHz 或更快的处理器的计算机</li>
						<li>1024 MB RAM（如果在虚拟机上运行，则为 1.5 GB）</li>
						<li>20 GB 的可用硬盘空间</li>
						<li>5400 RPM 硬盘</li>
						<li>以 1024 x 768 或更高的显示分辨率运行的支持 DirectX 9 的视频卡</li>
						<li>DVD-ROM 驱动器（光盘安装可选)</li>
					</ul>
				</div>
			</div>
			<div class="content download_page tab">
				<h3 class="header_6"><span>准备安装</span></h3>
				<dl class="list_3">
					<dt>DVD安装</dt>
					<dd>1. 准备一台带有DVD刻录光驱的电脑，以及一张DVD空白刻录光盘</dd>
					<dd>2. 使用刻录软件将下载的iso文件刻录于该空白光盘<br />
						&nbsp;&nbsp;&nbsp;&nbsp;推荐刻录软件：<a href="http://www.onlinedown.net/soft/3186.htm#down" target="_blank">nero</a><br />
						&nbsp;&nbsp;&nbsp;&nbsp;推荐阅读:<a href="http://wenku.baidu.com/view/a3f6d70590c69ec3d5bb754e.html" target="_blank">刻录简明教程</a></dd>
					<dd>3. 确认您要安装的硬盘分区有20G预留空间</dd>
					<dd>4. 将刻录好的DVD光盘放进要安装Windows 7的计算机光驱</dd>
					<dd>5. 设置该计算机从光盘启动<br />
						&nbsp;&nbsp;&nbsp;&nbsp;推荐阅读:<a href="http://wenku.baidu.com/view/2203ddeb172ded630b1cb6c0.html" target="_blank">如何从光盘启动计算机</a></dd>
					<dd>6. 重启计算机，计算机将从光盘启动并进入安装界面</dd>
					<dd>提示:Windows 7安装包容量约为4GB,需使用DVD光盘进行刻录，请注意不要使用CD-ROM光盘</dd>
				</dl>
				<dl class="list_3">
					<dt>硬盘安装</dt>
					<dd>1. 请确认您要安装的计算机已安装虚拟光驱软件<br />
						&nbsp;&nbsp;&nbsp;&nbsp;推荐虚拟光驱软件:<a href="http://www.onlinedown.net/soft/3616.htm" target="_blank">DAEMON Tools</a><br />
						&nbsp;&nbsp;&nbsp;&nbsp;推荐阅读:<a href="http://wenku.baidu.com/view/faa503225901020207409c63.html" target="_blank">虚拟光驱使用简明教程</a></dd>
					<dd>2. 做好您的硬盘重要数据备份<br />
						&nbsp;&nbsp;&nbsp;&nbsp;如您打算在C盘安装Windows 7，请将C盘您想保留的数据进行备份</dd>
					<dd>3. 确认您要安装的硬盘分区有20G预留空间</dd>
					<dd>4. 使用虚拟光驱软件解压下载的img文件<br />
						&nbsp;&nbsp;&nbsp;&nbsp;使用DAEMON Tools时，先点击“添加文件”，选择“所有文件（*.*）”才可以看到后缀为.img的文件名，添加img文件后点击“装载”即可看到安装包文件 </dd>
					<dd>5. 运行安装包内的后缀为exe的可执行文件，进入安装界面</dd>
					<dd>提示:Windows 7安装包容量约为4GB,需使用DVD光盘进行刻录，请注意不要使用CD-ROM光盘</dd>
				</dl>
				<h3 class="header_6"><span>安装选项</span></h3>
				<h4>1. 选择您要使用的语言，例如简体中文</h4>
				<p class="image"><img src="{{ Config::get('app.asset_url') . 'images/customer/common/win7/win7pro_1.jpg' }}" /></p>
				<h4>2. 进入程序安装后，出现是否获取安装的重要更新，如果您想马上进行安装，请选择“不获取最新安装更新”</h4>
				<p class="image"><img src="{{ Config::get('app.asset_url') . 'images/customer/common/win7/win7pro_2.jpg' }}" /></p>
				<h4>3. 接受许可条款</h4>
				<p class="image"><img src="{{ Config::get('app.asset_url') . 'images/customer/common/win7/win7pro_3.jpg' }}" /></p>
			</div>
		</div>
	</div>
	<div class="clear"></div>
</div>
