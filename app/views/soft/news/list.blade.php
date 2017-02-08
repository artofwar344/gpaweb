<div class="frame_1 site_path">
	当前位置：
	<a href="/">首页</a> &gt;
	<a href="/news">新闻</a></div>
		
<div class="frame_2">

	<div class="frame_2_l">
		@foreach ($articles as $article)
		<div class="news_list">
			<h1 class="header_1 header_1_a"><span>{{ $article['category_name'] }}</span></h1>
			<ul class="box_l">
				@foreach ($article['results'] as $key => $result)
				@if ($key % 2 == 0)
				<li><a href="/news/{{ $result->articleid }}.html">{{ $result->title }}</a></li>
				@endif
				@endforeach
			</ul>
			<ul class="box_r">
				@foreach ($article['results'] as $key => $result)
				@if ($key % 2 == 1)
				<li><a href="/news/{{ $result->articleid }}.html">{{ $result->title }}</a></li>
				@endif
				@endforeach
			</ul>
			<div class="clear"></div>
		</div>
		@endforeach
	</div>
	<div class="frame_2_r">
		@include('soft.partials.slider')
	</div>
	<div class="clear"></div>
</div>