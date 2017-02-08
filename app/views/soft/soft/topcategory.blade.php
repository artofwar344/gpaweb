<div class="frame_1 site_path">
	当前位置：
	<a href="/">首页</a> &gt;
	{{ $category_parent_name }}
</div>
<div class="frame_2">
	<div class="frame_2_l">
		@foreach ($softcategories as $softcategory)
		@if (sizeof($softcategory['results']) > 0)
		<div class="category_list">
			<h1 class="header_1 header_1_a">
				<span>{{ $softcategory['category_name'] }}</span>
				<a class="more" href="/category/{{ $softcategory['categoryid'] }}">【更多】</a>
			</h1>
			<div class="software_list">
			@foreach ($softcategory['results'] as $key => $soft)
				<div class="software">
					<a class="name" href="/soft/{{ $soft->softid }}.html">
						<span style="background-image:url({{ Ca\Service\SoftService::icon($soft->softid) }})">{{ $soft->name }} {{ $soft->version }}</span>
					</a>
					<div class="intro">{{ $soft->description }}</div>
					<div class="info">
						{{ Ca\Consts::$soft_licensetype_texts[$soft->licensetype] }}&nbsp;&nbsp;|&nbsp;&nbsp;
						软件大小: {{ Ca\Common::format_filesize($soft->filesize) }}&nbsp;&nbsp;|&nbsp;&nbsp;
						人气: {{ $soft->views }}&nbsp;&nbsp;|&nbsp;&nbsp;
						更新: {{ $soft->updatedate }}&nbsp;&nbsp;|&nbsp;&nbsp;
						运行环境: {{ $soft->platform }}
					</div>
				</div>
			@endforeach
			</div>
		</div>
		@endif
		@endforeach
	</div>

	@include('soft.partials.slider')
	<div class="clear"></div>
</div>
