<div class="frame_2_r">
	<div class="block_2">
		<h1 class="header_2 header_2_download">常用软件下载</h1>
		<?php $popular = Ca\Service\SoftService::soft_popular(20); ?>
		<ul class="content">
			@foreach ($popular as $key => $soft)
			<li><a href="/soft/{{ $soft->softid }}.html">{{ $soft->name }} <span>{{ $soft->version }}</span></a><strong>{{ Ca\Common::datetime_to_date($soft->updatedate) }}</strong></li>
			@endforeach
		</ul>
	</div>
	<div class="ad_1">{{ Ca\Service\AdService::show('270h_rb2', 'soft') }}</div>
	<div class="splitter_1"></div>
	<div class="block_1">
		<h1 class="header_2 header_2_news">热点新闻</h1>
		<?php $hotest = Ca\Service\ArticleService::article_hot(20); ?>
		<ul class="content">
			@foreach ($hotest as $article)
			<li><a href="/news/{{ $article->articleid }}.html">{{ $article->title }}</a></li>
			@endforeach
		</ul>
	</div>
</div>