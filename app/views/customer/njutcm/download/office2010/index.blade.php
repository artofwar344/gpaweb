<script type="text/javascript">
	$(function() {
		$(".tab").hide().eq(0).show();
		$(".tabsheet_1").find("a").click(function() {
			$(".tab").hide().eq($(this).index()).show();
			$(this).addClass("hot").siblings().removeClass("hot");
			return false;
		});

		var downloads = [
			{ "size" : "1.4G", "md5" : "1d8ab3e8f27c24e9a7d9ab18f80bc5c6" },
			{ "size" : "1.6G", "md5" : "cd10758727f2f2527a5226a49a10b5ae" },
			{ "size" : "527M", "md5" : "8f7b9fd4b61373618ad437dac6195624" }
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
				<a href="/download/office2010.html" style="background-image:url({{ Config::get('app.asset_url') . 'images/customer/common/office2010.gif' }})" class="item hot">Office 2010 专业增强版</a>
				<a href="/download/windows8.html" style="background-image:url({{ Config::get('app.asset_url') . 'images/customer/common/win8pro.gif' }})" class="item">Windows 8 专业版</a>
				<a href="/download/office2013.html" class="item" style="background-image:url({{ Config::get('app.asset_url') . 'images/customer/common/office2013.gif' }})">Office 2013 专业增强版</a>
			</div>
		</div>
	</div>
	<div class="frame_1_r">
		<h1 class="header_1">微软® Office 2010 专业增强版</h1>
		<div class="content product_main">
			<img src="{{ Config::get('app.asset_url') . 'images/customer/common/office2010/office2010_main.jpg' }}">
			<form class="download" target="_blank" method="POST" action="/download/file">
				<input type="hidden" name="name" value="office2010" />
				<h2>选择 Office 2010 专业增强版 版本: </h2>
				<ul>
					<li>
						<label>系统架构:</label>
						<select name="bit" class="select_1">
							<option value="0">32位sp1</option>
							<option value="1">64位sp1</option>
							<option value="2">64位sp2</option>
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
				<h2 class="header_2">Office 2010先睹为快</h2>
				<div class="product_gallery">
					<img src="{{ Config::get('app.asset_url') . 'images/customer/common/office2010/office2010_new_1.jpg' }}" />
					<img src="{{ Config::get('app.asset_url') . 'images/customer/common/office2010/office2010_new_2.jpg' }}" />
					<img src="{{ Config::get('app.asset_url') . 'images/customer/common/office2010/office2010_new_3.jpg' }}" />
					<img src="{{ Config::get('app.asset_url') . 'images/customer/common/office2010/office2010_new_4.jpg' }}" />
					<img src="{{ Config::get('app.asset_url') . 'images/customer/common/office2010/office2010_new_5.jpg' }}" />
					<div class="clear"></div>
				</div>
				<h2 class="header_2">超酷的Office 2010</h2>
				<div class="product_video"><a href="#"><img src="{{ Config::get('app.asset_url') . 'images/customer/common/office2010/office2010_video_1.jpg' }}"/></a></div>
				<h2 class="header_2">我和Office的进化史</h2>
				<div class="product_history">
					<dl>
						<dt><strong>1. 创建简历</strong> -- 使用Word创建一份得体的简历，是自己给用人单位留下的第一印象</dt>
						<dd>
							<p>Office2010内带有不同的简历模板，用来创建简历再好不过了。</p>
							<p>简历不仅展示自己，还能展示自己运用办公软件的技能，这也是可以为你的面试加分哦</p>
						</dd>
						<dt><strong>2. Email</strong> -- 原来公司里面有这么多同事，每天都快被Email淹没了</dt>
						<dd>
							<p>Outlook会帮你管理所有的联系人，电子邮件，以及帮你做好日程安排</p>
							<p>职场新手不要紧，Office可是以及专注于办公业务很多年啰</p>
						</dd>
						<dt><strong>3. 开始独当一面</strong> -- 菜鸟也会长大，开始独立撰写工作报表还有合同</dt>
						<dd>
							<p>前辈们都会抱怨世界上最烦人的事情就是写报表了，Office2010中的Excel能将你从这个死胡同中解放出来。新的Excel更加关注于提高工作效率，将繁琐的事物变得像娱乐一样简单而又好玩。</p>
						</dd>
						<dt><strong>4. 成为公司良将</strong> -- 怎样给客户，还有公司内部做一次精彩的演说？</dt>
						<dd>
							<p><strong>告诉你一个小秘密：所有的部门经理都是ppt制作高手</strong></p>
							<p>Office2010中的PowerPoint将是Office历史上最变革的一代，他致力于为用户以最简单的方法创建充满想象力的演示。想象下您在关着灯的会议室进行一场重要的演示，除了您精彩的口才以外，还有什么能比一份配合恰当的电子演示更能增加您的个人魅力？</p>
						</dd>
					</dl>
				</div>
				<h2 class="header_2">Office 2010 不同版本比较</h2>
				<div>
					<img src="{{ Config::get('app.asset_url') . 'images/customer/common/office2010/office2010_compare_1.jpg' }}" />
				</div>
			</div>
			<div class="content product_office2013_configuration tab">
				<h2 class="header_2">系统要求</h2>
				<div class="product_requirement">
					<ul>
						<li>处理器500 MHz 或更快的处理器</li>
						<li>内存256 MB 或更大的 RAM。</li>
						<li>硬盘1.5 GB；如果将原始下载软件包从硬盘驱动器上删除，则软件安装后将会释放一部分磁盘空间。</li>
						<li>驱动器CD-ROM 或 DVD 驱动器</li>
						<li>显示器1024x768 或更高分辨率的显示器</li>
						<li>操作系统Windows XP Service Pack (SP) 3（32 位）、Windows Vista SP1（32 位或 64 位）、Windows Server 2003 R2（32 位或 64 位）、Windows Server 2008 SP2（32 位或 64 位）、Windows 7（32 位或 64 位）。支持终端服务器和 Windows on Windows (WOW)（即允许在 64 位操作系统上安装 32 位版本的 Office 2010）。</li>
						<li>其他您不需要更换能够运行 Office 2007 的硬件；这种硬件将会支持 Office 2010。</li>
						<li>其他使用图形硬件加速要求具有 64 MB 或更大视频内存的 DirectX 9.0c 兼容图形卡。系统要求和产品功能可能会随系统配置和操作系统的不同而不同。</li>
					</ul>
				</div>
			</div>
			<div class="content download_page tab">
				<h2>Office 2010 专业版安装</h2>
				<h3 class="header_6"><span>软件安装</span></h3>
				<h4>1. 双击打开,进入准备安装阶段</h4>
				<p class="image"><img src="{{ Config::get('app.asset_url') . 'images/customer/common/office2010/office2010_1.jpg' }}" /></p>
				<h4>2. 勾选“我接受此协议的条款”后，点击继续</h4>
				<p class="image"><img src="{{ Config::get('app.asset_url') . 'images/customer/common/office2010/office2010_2.jpg' }}" /></p>
				<h4>3. 选择“自动安装”或者“自定义”</h4>
				<p class="image"><img src="{{ Config::get('app.asset_url') . 'images/customer/common/office2010/office2010_3.jpg' }}" /></p>
				<h4>4. 显示安装进度</h4>
				<p class="image"><img src="{{ Config::get('app.asset_url') . 'images/customer/common/office2010/office2010_4_1.jpg' }}" /></p>
				<p class="image"><img src="{{ Config::get('app.asset_url') . 'images/customer/common/office2010/office2010_4_2.jpg' }}" /></p>
				<p class="image"><img src="{{ Config::get('app.asset_url') . 'images/customer/common/office2010/office2010_4_3.jpg' }}" /></p>
				<h4>5. 完成安装</h4>
				<p class="image"><img src="{{ Config::get('app.asset_url') . 'images/customer/common/office2010/office2010_5.jpg' }}" /></p>
			</div>
		</div>
	</div>
<div class="clear"></div>
</div>