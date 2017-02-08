{? $uri = Request::path(); ?}
@if ($product['category'] == 'microsoft' && !in_array('microsoft', $blockdownloadproducts))
<div class="product_menu">
	<h1>Microsoft® 产品</h1>
	<div class="list">
		@if (!in_array('windows7', $blockdownloadproducts))
		<a href="/download/windows7.html" style="background-image:url({{ Config::get('app.asset_url') . 'images/customer/common/win7pro.gif' }})" class="item @if($uri == 'download/windows7.html') hot @endif">Windows 7 专业版</a>
		@endif
		@if (!in_array('office2010', $blockdownloadproducts))
		<a href="/download/office2010.html" style="background-image:url({{ Config::get('app.asset_url') . 'images/customer/common/office2010.gif' }})" class="item @if($uri == 'download/office2010.html') hot @endif">Office 2010 专业增强版</a>
		@endif
		@if (!in_array('windows8', $blockdownloadproducts))
		<a href="/download/windows8.html" style="background-image:url({{ Config::get('app.asset_url') . 'images/customer/common/win8pro.gif' }})" class="item @if($uri == 'download/windows8.html') hot @endif">Windows 8 专业版</a>
		@endif
		@if (!in_array('windows10', $blockdownloadproducts))
		<a href="/download/windows10.html" style="background-image:url({{ Config::get('app.asset_url') . 'images/customer/common/win8pro.gif' }})" class="item @if($uri == 'download/windows10.html') hot @endif">Windows 10 专业版</a>
		@endif
		@if (!in_array('office2013', $blockdownloadproducts))
		<a href="/download/office2013.html" class="item @if($uri == 'download/office2013.html') hot @endif" style="background-image:url({{ Config::get('app.asset_url') . 'images/customer/common/office2013.gif' }})">Office 2013 专业增强版</a>
		@endif
		@if (!in_array('windows8.1', $blockdownloadproducts))
		<a href="/download/windows8_1.html" class="item @if($uri == 'download/windows8_1.html') hot @endif" style="background-image:url({{ Config::get('app.asset_url') . 'images/customer/common/win8pro.gif' }})">Windows 8.1 专业版</a>
		@endif
		@if (!in_array('office2016', $blockdownloadproducts))
		<a href="/download/office2016.html" class="item @if($uri == 'download/office2016.html') hot @endif" style="background-image:url({{ Config::get('app.asset_url') . 'images/customer/common/office2013.gif' }})">Office 2016 专业增强版</a>
		@endif
		@if (!in_array('macoffice', $blockdownloadproducts))
		<a href="/download/macoffice.html" class="item @if($uri == 'download/macoffice.html') hot @endif" style="background-image:url({{ Config::get('app.asset_url') . 'images/customer/common/office2013.gif' }})">微软® Mac Office 2011</a>
		@endif
		@if (!in_array('macoffice2016', $blockdownloadproducts))
		<a href="/download/macoffice2016.html" class="item @if($uri == 'download/macoffice2016.html') hot @endif" style="background-image:url({{ Config::get('app.asset_url') . 'images/customer/common/office2013.gif' }})">微软® Mac Office 2016</a>
		@endif

	</div>
</div>
@elseif ($product['category'] == 'adobe' && !in_array('adobe', $blockdownloadproducts))
<div class="product_menu">
	<h1>Adobe® 产品</h1>
	<div class="list">
		@if (!in_array('acrobat', $blockdownloadproducts))
		<a href="/download/acrobat.html" class="item @if($uri == 'download/acrobat.html') hot @endif" style="background-image:url({{ Config::get('app.asset_url') . 'images/customer/common/acrobatxi.gif' }})"><span>Acrobat® XI Pro</span></a>
		@endif
		@if (!in_array('aftereffects', $blockdownloadproducts))
		<a href="/download/aftereffects.html" class="item @if($uri == 'download/aftereffects.html') hot @endif" style="background-image:url({{ Config::get('app.asset_url') . 'images/customer/common/aftereffectscs6.gif' }})"><span>After Effects® CS6</span></a>
		@endif
		@if (!in_array('premiere', $blockdownloadproducts))
		<a href="/download/premiere.html" class="item @if($uri == 'download/premiere.html') hot @endif" style="background-image:url({{ Config::get('app.asset_url') . 'images/customer/common/premiereprocs6.gif' }})"><span>Premiere Pro® CS6</span></a>
		@endif
		@if (!in_array('photoshop', $blockdownloadproducts))
		<a href="/download/photoshop.html" class="item @if($uri == 'download/photoshop.html') hot @endif" style="background-image:url({{ Config::get('app.asset_url') . 'images/customer/common/photoshopcs6.gif' }})"><span>Photoshop® CS6 Extended</span></a>
		@endif
		@if (!in_array('dreamweaver', $blockdownloadproducts))
		<a href="/download/dreamweaver.html" class="item @if($uri == 'download/dreamweaver.html') hot @endif" style="background-image:url({{ Config::get('app.asset_url') . 'images/customer/common/dreamweavercs6.gif' }})"><span>Dreamweaver® CS6</span></a>
		@endif
		@if (!in_array('fireworks', $blockdownloadproducts))
		<a href="/download/fireworks.html" class="item @if($uri == 'download/fireworks.html') hot @endif" style="background-image:url({{ Config::get('app.asset_url') . 'images/customer/common/fireworkscs6.gif' }})"><span>Fireworks® CS6</span></a>
		@endif
		@if (!in_array('flashprofessional', $blockdownloadproducts))
		<a href="/download/flashprofessional.html" class="item @if($uri == 'download/flashprofessional.html') hot @endif" style="background-image:url({{ Config::get('app.asset_url') . 'images/customer/common/flashcs6.gif' }})"><span>Flash Professional® CS6</span></a>
		@endif
		@if (!in_array('illustrator', $blockdownloadproducts))
		<a href="/download/illustrator.html" class="item @if($uri == 'download/illustrator.html') hot @endif" style="background-image:url({{ Config::get('app.asset_url') . 'images/customer/common/indesigncs6.gif' }})"><span>Illustrator® CS6</span></a>
		@endif
		@if (!in_array('indesign', $blockdownloadproducts))
		<a href="/download/indesign.html" class="item @if($uri == 'download/indesign.html') hot @endif" style="background-image:url({{ Config::get('app.asset_url') . 'images/customer/common/illustratorcs6.gif' }})"><span>InDesign® CS6</span></a>
		@endif
		@if (!in_array('creativesuitemastercollection', $blockdownloadproducts))
		<a href="/download/creativesuitemastercollection.html" class="item @if($uri == 'download/creativesuitemastercollection.html') hot @endif" style="background-image:url({{ Config::get('app.asset_url') . 'images/customer/common/mastercollectioncs6.gif' }})"><span>Creative Suite® 6 Master Collection</span></a>
		@endif
		@if (!in_array('productionpremium', $blockdownloadproducts))
		<a href="/download/productionpremium.html" class="item @if($uri == 'download/productionpremium.html') hot @endif" style="background-image:url({{ Config::get('app.asset_url') . 'images/customer/common/productionpremiumcs6.gif' }})"><span>Creative Suite® 6 Production Premium</span></a>
		@endif
		@if (!in_array('creativesuitedesignstandard', $blockdownloadproducts))
		<a href="/download/creativesuitedesignstandard.html" class="item @if($uri == 'download/creativesuitedesignstandard.html') hot @endif" style="background-image:url({{ Config::get('app.asset_url') . 'images/customer/common/designstandardcs6.gif' }})"><span>Creative Suite® 6 Design Standard</span></a>
		@endif
		@if (!in_array('creativesuitedesignwebpremium', $blockdownloadproducts))
		<a href="/download/creativesuitedesignwebpremium.html" class="item @if($uri == 'download/creativesuitedesignwebpremium.html') hot @endif" style="background-image:url({{ Config::get('app.asset_url') . 'images/customer/common/designwebcs6.gif' }})"><span>Creative Suite® 6 Design & Web Premium</span></a>
		@endif
	</div>
</div>
@endif