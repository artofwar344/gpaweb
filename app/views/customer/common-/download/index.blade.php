
<div class="product_banner"></div>
@if (!in_array('microsoft', $blockdownloadproducts))
<div class="frame_1 main_content product_download">
	<h1 class="header_1">微软应用下载</h1>

	{? $index = 1 ?}
	@if (!in_array('windows8', $blockdownloadproducts))
	<div class="product @if ($index%2 == 0) even @endif">
		{? $index++ ?}
		<a href="/download/windows8.html"><img src="{{ Config::get('app.asset_url') . 'images/customer/common/product_1.jpg' }}"></a>
		<h2>Windows 8 专业版</h2>
		<ul>
			<li>超炫的系统界面，让人一见好心情</li>
			<li>5秒开机，1秒链接无线网！</li>
			<li>速度超快的完美浏览器！</li>
			<li>Win 8 平板、手机共享云服务</li>
		</ul> 
		<a href="/download/windows8.html" class="button_2" style="background-color:#0b87bf">查看详细</a>
	</div>
	@endif
	@if (!in_array('windows8.1', $blockdownloadproducts))
	<div class="product @if ($index%2 == 0) even @endif">
		{? $index++ ?}
		<a href="/download/windows8_1.html"><img src="{{ Config::get('app.asset_url') . 'images/customer/common/product_1.jpg' }}"></a>
		<h2>Windows 8.1专业版</h2>
		<ul>
			<li>超炫的系统界面，让人一见好心情</li>
			<li>5秒开机，1秒链接无线网！</li>
			<li>速度超快的完美浏览器！</li>
			<li>Win 8 平板、手机共享云服务</li>
		</ul>
		<a href="/download/windows8_1.html" class="button_2" style="background-color:#0b87bf">查看详细</a>
	</div>
	@endif
	@if (!in_array('windows10', $blockdownloadproducts))
	<div class="product @if ($index%2 == 0) even @endif">
		{? $index++ ?}
		<a href="/download/windows10.html"><img src="{{ Config::get('app.asset_url') . 'images/customer/common/product_10.jpg' }}"></a>
		<h2>Windows 10 专业版</h2>
		<ul>
			<li>Multi Touch多点触控</li>
			<li>Aero Shake视窗晃动</li>
			<li>Snap Tool屏幕剪取工具</li>
			<li>Sticky Notes自粘便签</li>
		</ul>
		<a href="/download/windows10.html" class="button_2" style="background-color:#0b87bf">查看详细</a>
	</div>
	@endif
	@if (!in_array('windows7', $blockdownloadproducts))
	<div class="product @if ($index%2 == 0) even @endif">
		{? $index++ ?}
		<a href="/download/windows7.html"><img src="{{ Config::get('app.asset_url') . 'images/customer/common/product_3.jpg' }}"></a>
		<h2>Windows 7 专业版</h2>
		<ul>
			<li>Multi Touch多点触控</li>
			<li>Aero Shake视窗晃动</li>
			<li>Snap Tool屏幕剪取工具</li>
			<li>Sticky Notes自粘便签</li>
		</ul>
		<a href="/download/windows7.html" class="button_2" style="background-color:#0b87bf">查看详细</a>
	</div>
	@endif
	@if (!in_array('office2010', $blockdownloadproducts))
	<div class="product @if ($index%2 == 0) even @endif last">
		{? $index++ ?}
		<a href="/download/office2010.html"><img src="{{ Config::get('app.asset_url') . 'images/customer/common/product_4.jpg' }}"></a>
		<h2>Office 2010 专业增强版</h2>
		<ul>
			<li>必备套装办公软件</li>
			<li>更强的视觉效果</li>
			<li>更高效率，简化工作</li>
			<li>新增的SmartArt 图形布局</li>
		</ul>
		<a href="/download/office2010.html" class="button_2" style="background-color:#b63e00">查看详细</a>
	</div>
	@endif
	@if (!in_array('office2013', $blockdownloadproducts))
	<div class="product @if ($index%2 == 0) even @endif last">
		{? $index++ ?}
		<a href="/download/office2013.html"><img src="{{ Config::get('app.asset_url') . 'images/customer/common/product_2.jpg' }}"></a>
		<h2>Office 2013 专业增强版</h2>
		<ul>
			<li>必备套装办公软件</li>
			<li>在云中保存和共享文件</li>
			<li>使用 Office更便捷地工作</li>
			<li>随时随地登录 Office</li>
		</ul>
		<a href="/download/office2013.html" class="button_2" style="background-color:#b63e00">查看详细</a>
	</div>
	@endif
	@if (!in_array('office2016', $blockdownloadproducts))
	<div class="product @if ($index%2 == 0) even @endif last">
		{? $index++ ?}
		<a href="/download/office2016.html"><img src="{{ Config::get('app.asset_url') . 'images/customer/common/product_2016.jpg' }}"></a>
		<h2>Office 2016 专业增强版</h2>
		<ul>
			<li>必备套装办公软件</li>
			<li>在云中保存和共享文件</li>
			<li>使用 Office更便捷地工作</li>
			<li>随时随地登录 Office</li>
		</ul>
		<a href="/download/office2016.html" class="button_2" style="background-color:#b63e00">查看详细</a>
	</div>
	@endif
	@if (!in_array('macoffice', $blockdownloadproducts))
	<div class="product @if ($index%2 == 0) even @endif last">
		{? $index++ ?}
		<a href="/download/macoffice.html"><img src="{{ Config::get('app.asset_url') . 'images/customer/common/product_6.jpg' }}"></a>
		<h2>Office:mac 2011  &nbsp;&nbsp;&nbsp;</h2>
		<ul>
			<li>与他人更好地写作</li>
			<li>使用熟知的软件节约时间</li>
			<li>使用专业人士所用的工具</li>
			
		</ul>
		<a href="/download/macoffice.html" class="button_2" style="background-color:#b63e00">查看详细</a>
	</div>
	@endif
	@if (!in_array('office2016', $blockdownloadproducts))
	<div class="product @if ($index%2 == 0) even @endif last">
		{? $index++ ?}
		<a href="/download/macoffice2016.html"><img src="{{ Config::get('app.asset_url') . 'images/customer/common/product_2016.png' }}"></a>
		<h2>Mac Office 2016 正式版</h2>
		<ul>
			<li>专业设计的在线模板</li>
			<li>Retina 屏幕更清晰</li>
			<li>多点触控笔势</li>
			<li>与 OneDrive、OneDrive for Business 和 SharePoint 集成</li>
		</ul>
		<a href="/download/macoffice2016.html" class="button_2" style="background-color:#b63e00">查看详细</a>
	</div>
	@endif
	<div class="clear"></div>
</div>
@endif
@if (!in_array('adobe', $blockdownloadproducts))
<div class="frame_1 main_content product_download">
	<h1 class="header_1">Adobe应用下载</h1>

	@if (!in_array('acrobat', $blockdownloadproducts))
	<div class="product_adobe">
		<a href="/download/acrobat.html"><img src="{{ Config::get('app.asset_url') . 'images/customer/common/acrobatxi.jpg' }}"></a>
		<h2>Adobe® Acrobat® XI Pro</h2>
		<p>PDF文档制作软件</p>
		<a href="/download/acrobat.html" class="button_2" style="background-color:#0b87bf">查看详细</a>
	</div>
	@endif
	@if (!in_array('aftereffects', $blockdownloadproducts))
	<div class="product_adobe">
		<a href="/download/aftereffects.html"><img src="{{ Config::get('app.asset_url') . 'images/customer/common/aftereffectscs6.jpg' }}"></a>
		<h2>Adobe® After Effects® CS6</h2>
		<p>视频后期特效编辑处理</p>
		<a href="/download/aftereffects.html" class="button_2" style="background-color:#0b87bf">查看详细</a>
	</div>
	@endif
	@if (!in_array('premiere', $blockdownloadproducts))
	<div class="product_adobe">
		<a href="/download/premiere.html"><img src="{{ Config::get('app.asset_url') . 'images/customer/common/premiereprocs6.jpg' }}"></a>
		<h2>Adobe® Premiere Pro® CS6</h2>
		<p>视频编辑软件</p>
		<a href="/download/premiere.html" class="button_2" style="background-color:#0b87bf">查看详细</a>
	</div>
	@endif
	@if (!in_array('photoshop', $blockdownloadproducts))
	<div class="product_adobe">
		<a href="/download/photoshop.html"><img src="{{ Config::get('app.asset_url') . 'images/customer/common/photoshopcs6.jpg' }}"></a>
		<h2>Adobe® Photoshop® CS6 Extended</h2>
		<p>图像处理软件</p>
		<a href="/download/photoshop.html" class="button_2" style="background-color:#0b87bf">查看详细</a>
	</div>
	@endif
	@if (!in_array('dreamweaver', $blockdownloadproducts))
	<div class="product_adobe">
		<a href="/download/dreamweaver.html"><img src="{{ Config::get('app.asset_url') . 'images/customer/common/dreamweavercs6.jpg' }}"></a>
		<h2>Adobe® Dreamweaver® CS6</h2>
		<p>网页制作软件</p>
		<a href="/download/dreamweaver.html" class="button_2" style="background-color:#0b87bf">查看详细</a>
	</div>
	@endif
	@if (!in_array('fireworks', $blockdownloadproducts))
	<div class="product_adobe">
		<a href="/download/fireworks.html"><img src="{{ Config::get('app.asset_url') . 'images/customer/common/fireworkscs6.jpg' }}"></a>
		<h2>Adobe® Fireworks® CS6</h2>
		<p>网页原型、草图设计软件</p>
		<a href="/download/fireworks.html" class="button_2" style="background-color:#0b87bf">查看详细</a>
	</div>
	@endif
	@if (!in_array('flashprofessional', $blockdownloadproducts))
	<div class="product_adobe">
		<a href="/download/flashprofessional.html"><img src="{{ Config::get('app.asset_url') . 'images/customer/common/flashcs6.jpg' }}"></a>
		<h2>Adobe® Flash Professional® CS6</h2>
		<p>交互游戏、富媒体制作软件</p>
		<a href="/download/flashprofessional.html" class="button_2" style="background-color:#0b87bf">查看详细</a>
	</div>
	@endif
	@if (!in_array('illustrator', $blockdownloadproducts))
	<div class="product_adobe">
		<a href="/download/illustrator.html"><img src="{{ Config::get('app.asset_url') . 'images/customer/common/illustratorcs6.jpg' }}"></a>
		<h2>Adobe® Illustrator® CS6</h2>
		<p>矢量图绘图软件</p>
		<a href="/download/illustrator.html" class="button_2" style="background-color:#0b87bf">查看详细</a>
	</div>
	@endif
	@if (!in_array('indesign', $blockdownloadproducts))
	<div class="product_adobe">
		<a href="/download/indesign.html"><img src="{{ Config::get('app.asset_url') . 'images/customer/common/indesigncs6.jpg' }}"></a>
		<h2>Adobe® InDesign® CS6</h2>
		<p>专业排版版、面设计软件</p>
		<a href="/download/indesign.html" class="button_2" style="background-color:#0b87bf">查看详细</a>
	</div>
	@endif
	@if (!in_array('creativesuitemastercollection', $blockdownloadproducts))
	<div class="product_adobe">
		<a href="/download/creativesuitemastercollection.html"><img src="{{ Config::get('app.asset_url') . 'images/customer/common/mastercollectioncs6.jpg' }}"></a>
		<h2>Adobe® Creative Suite® 6 Master Collection</h2>
		<p>跨媒体设计软件集</p>
		<a href="/download/creativesuitemastercollection.html" class="button_2" style="background-color:#0b87bf">查看详细</a>
	</div>
	@endif
	@if (!in_array('productionpremium', $blockdownloadproducts))
	<div class="product_adobe">
		<a href="/download/productionpremium.html"><img src="{{ Config::get('app.asset_url') . 'images/customer/common/productionpremiumcs6.jpg' }}"></a>
		<h2>Adobe® Creative Suite® 6 Production Premium</h2>
		<p>创意专业人员的必备解决方案</p>
		<a href="/download/productionpremium.html" class="button_2" style="background-color:#0b87bf">查看详细</a>
	</div>
	@endif
	@if (!in_array('creativesuitedesignstandard', $blockdownloadproducts))
	<div class="product_adobe">
		<a href="/download/creativesuitedesignstandard.html"><img src="{{ Config::get('app.asset_url') . 'images/customer/common/designstandardcs6.jpg' }}"></a>
		<h2>Adobe® Creative Suite® 6 Design Standard</h2>
		<p>专业设计类软件基础套装</p>
		<a href="/download/creativesuitedesignstandard.html" class="button_2" style="background-color:#0b87bf">查看详细</a>
	</div>
	@endif
	@if (!in_array('creativesuitedesignwebpremium', $blockdownloadproducts))
	<div class="product_adobe">
		<a href="/download/creativesuitedesignwebpremium.html"><img src="{{ Config::get('app.asset_url') . 'images/customer/common/designwebcs6.jpg' }}"></a>
		<h2>Adobe® Creative Suite® 6 Design & Web Premium</h2>
		<p>网页制作、设计类软件高级套装</p>
		<a href="/download/creativesuitedesignwebpremium.html" class="button_2" style="background-color:#0b87bf">查看详细</a>
	</div>
	@endif
	<div class="clear"></div>
</div>
@endif