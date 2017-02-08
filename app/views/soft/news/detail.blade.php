<div class="frame_1 site_path">
	当前位置：
	<a href="/">首页</a> &gt;
	<a href="/news/list-{{$article->categoryid}}.html">{{$article->name}}</a>
</div>
<div class="frame_2">
	<div class="frame_2_l">
		<div class="news_detail">
			<h1>{{ $article->title }}</h1>
			<div class="info">
				<span>{{ $article->createdate }}</span>
				{{--<span>中关村在线</span>--}}
			</div>
			<div class="content">
				{{ $article->content }}
			</div>
		</div>
	</div>

	@include('soft.partials.slider')
	<div class="clear"></div>
</div>