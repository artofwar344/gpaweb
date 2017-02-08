<div class="frame_1 site_path">
	当前位置：
	<a href="/">首页</a> &gt;
	<a href="/top/{{ $soft->parentid }}">{{ Ca\Consts::$soft_top_categories[$soft->parentid] }}</a> &gt;
	<a href="/category/{{ $soft->categoryid }}">{{ $soft->category_name }} </a> > {{ $soft->name }} {{ $soft->version }}
</div>
<div class="frame_2">
	<div class="frame_2_l">
		<div class="download_info">
			<div class="name">
				<span style="background-image:url({{ Ca\Service\SoftService::icon($soft->softid) }})">{{ $soft->name }} {{ $soft->version }}</span>
			</div>
			<ul>
				<li><span>软件类别:</span>{{ Ca\Consts::$soft_top_categories[$soft->parentid] }}/{{ $soft->category_name }}</li>
				<li><span>软件大小:</span>{{ Ca\Common::format_filesize($soft->filesize) }}</li>
				<li><span>软件授权:</span>{{ Ca\Consts::$soft_licensetype_texts[$soft->licensetype] }}</li>
				<li><span>人气指数:</span>{{ $soft->views }}</li>
				<li><span>运行环境:</span>{{ $soft->platform }}</li>
				<li><span>软件语言:</span>{{ Ca\Consts::$soft_language_texts[$soft->language] }}</li>
				<li><span>更新时间:</span>{{ $soft->updatedate }}</li>
		</div>
		<div class="bshare-custom download_share">
			<div class="bsPromo bsPromo2"></div>
			<a title="分享到QQ空间" class="bshare-qzone"></a>
			<a title="分享到新浪微博" class="bshare-sinaminiblog"></a>
			<a title="分享到搜狐微博" class="bshare-sohuminiblog" href="javascript:void(0);"></a>
			<a title="分享到人人网" class="bshare-renren"></a>
			<a title="分享到i贴吧" class="bshare-itieba" href="javascript:void(0);"></a>
			<a title="分享到网易微博" class="bshare-neteasemb" href="javascript:void(0);"></a>
			<a title="分享到腾讯微博" class="bshare-qqmb"></a>
			<a title="分享到天涯" class="bshare-tianya" href="javascript:void(0);"></a>
			<a title="分享到豆瓣" class="bshare-douban" href="javascript:void(0);"></a>
			<a title="分享到开心网" class="bshare-kaixin001" href="javascript:void(0);"></a>
			<a title="分享到朋友网" class="bshare-qqxiaoyou" href="javascript:void(0);"></a>
			<a title="更多平台" class="bshare-more bshare-more-icon more-style-addthis"></a>
			<span class="BSHARE_COUNT bshare-share-count" style="float: none; ">0</span>
		</div>
		@if (!empty($soft->description))
		<div class="download_intro">
			<h2 class="header_1"><span>{{ $soft->name }} 软件介绍</span></h2>
			<p class="intro">{{ $soft->description }}</p>
		</div>
		@endif
		@if (!empty($soft->feature))
		<div class="download_intro">
			<h2 class="header_1"><span>{{ $soft->name }} 最新特性</span></h2>
			<p class="intro">{{ $soft->feature }}</p>
		</div>
		@endif

		<div class="download_links">
			<h2 class="header_1"><span> 下载地址</span></h2>
			<div class="links"><a class="button_1 button_1_download" href="/soft/download/{{ $soft->softid }}">本地教育网下载</a></div>
		</div>
		<div class="ad_1">{{ Ca\Service\AdService::show('700w_softdetail_1', 'soft') }}</div>
		@if (!empty($recommend))
		<div class="download_recommend">
			<h2 class="header_1"><span>本类推荐</span></h2>
			@foreach ($recommend as $soft)
			<div class="software">

				<a style="background-image:url({{ Ca\Service\SoftService::icon($soft->softid) }})" href="/soft/{{ $soft->softid }}.html">{{ $soft->name }}<span>{{ $soft->version }}</span></a>
			</div>
			@endforeach
		</div>@endif
	</div>
	@include('soft.partials.slider')
	<div class="clear"></div>
</div>
<script type="text/javascript" charset="utf-8" src="http://static.bshare.cn/b/buttonLite.js#style=-1&amp;uuid=&amp;pophcol=2&amp;lang=zh"></script><script type="text/javascript" charset="utf-8" src="http://static.bshare.cn/b/bshareC0.js"></script>