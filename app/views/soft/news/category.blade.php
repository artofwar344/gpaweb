<style>
	.news_box{
		width: 700px;
		float: left;
	}
</style>
<div class="frame_1 site_path">
	当前位置：
	<a href="/">首页</a> &gt;
	<a href="/news">新闻</a> &gt;{{ $category->name }}</div>
<div class="frame_1 news_page">
<div class="left software_list">

	<div class="news_list news_box">
		<div class="header_1">
			<h1><a href="/news/list-{{ $category->categoryid }}.html">{{ $category->name }}</a></h1>
		</div>
		<ul class="box_l">
			@foreach ($articles as $key => $result)
			@if ($key % 2 == 0)
			<li><a href="/news/{{ $result->articleid }}.html">{{ $result->title }}</a></li>
			@endif
			@endforeach
		</ul>
		<ul class="box_r">
			@foreach ($articles as $key => $result)
			@if ($key % 2 == 1)
			<li><a href="/news/{{ $result->articleid }}.html">{{ $result->title }}</a></li>
			@endif
			@endforeach
		</ul>
	</div>

	{{ $articles->links() }}
	@include('soft.partials.slider')
</div>


<div class="clear"></div>
</div>