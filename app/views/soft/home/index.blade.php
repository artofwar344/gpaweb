<script type="text/javascript">
	$(function() {
		$(".download_main .header_3  ul a").click(function() {
			$(".download_main .header_3  ul a").removeClass('hot');
			$(this).addClass("hot");
			$(".download_main .content").hide().eq($(this).attr("page")).show();
			return false;
		});
	});
</script>
<div class="frame_1">
	<div class="frame_1_l">
		<div class="ad_1">{{ Ca\Service\AdService::show('320h_banner_1', 'soft') }}</div>
		<div class="block_3 block_3_viewpoint">
			<h1 class="header_2 header_2_viewpoint">今日视点</h1>
			@if (!empty($viewpoints))
			<div class="content">
				<h2>
					<span class="label">热点关注</span>
					<a href="/news/{{$viewpoints[0]->articleid}}.html">{{ Ca\Common::cut_string($viewpoints[0]->title, 27, '') }}</a>
				</h2>
				<p class="detail">
					{{ Ca\Common::cut_string(strip_tags($viewpoints[0]->content), 150) }}
					<a href="/news/{{$viewpoints[0]->articleid}}.html">[详细]</a>
				</p>
				<ul>
					@foreach ($viewpoints as $key => $article)
					@if ($key > 0)
					<li><a href="/news/{{$article->articleid}}.html">{{ $article->title }}</a></li>
					@endif
					@endforeach
				</ul>
			</div>
			@endif
		</div>
		<div class="block_4 download_main">
			<div class="header_3 header_3_download">
				下载排行
				<ul>
					<li><a class="hot" href="#" page="0">年</a></li>
					<li><a href="#" page="1">月</a></li>
					<li><a href="#" page="1">周</a></li>
				</ul>
				<div class="clear"></div>
			</div>
			<div class="content">
				<ul>
					@foreach ($top_download['year'] as $key => $soft)
					<li class="index_{{ $key + 1 }}">
						<span class="index">{{ $key + 1 }}</span>
						<a href="/soft/{{ $soft->softid }}.html">{{ $soft->name }} <span class="version">{{ $soft->version }}</span></a>
					</li>
					@endforeach
				</ul>
			</div>
			<div class="content" style="display:none">
				<ul>
					@foreach ($top_download['month'] as $key => $soft)
					<li class="index_{{ $key + 1 }}">
						<span class="index">{{ $key + 1 }}</span>
						<a href="/soft/{{ $soft->softid }}.html">{{ $soft->name }} <span class="version">{{ $soft->version }}</span></a>
					</li>
					@endforeach
				</ul>
			</div>
			<div class="content" style="display:none">
				<ul>
					@foreach ($top_download['week'] as $key => $soft)
					<li class="index_{{ $key + 1 }}">
						<span class="index">{{ $key + 1 }}</span>
						<a href="/soft/{{ $soft->softid }}.html">{{ $soft->name }} <span class="version">{{ $soft->version }}</span></a>
					</li>
					@endforeach
				</ul>
			</div>
			<div class="clear"></div>
		</div>
	</div>

	<div class="frame_1_c software_new">
		<h1 class="header_1">
			<span>软件更新</span>
			<a class="more" href="/newest">【更多】</a>
		</h1>
		<ul>
			@foreach ($softs as $soft)
			<li>
				<span class="category">
					<a  href="/category/{{ $soft->categoryid }}">[{{ $soft->category_name }}]</a>
				</span>
				<a  class="name" href="/soft/{{ $soft->softid }}.html">{{ $soft->name }} <span>{{ $soft->version }}</span></a>
				<em class="version">{{ date_format(new DateTime($soft->updatedate), 'Y-m-d') }}</em>
			</li>
			@endforeach
		</ul>

		<div class="news_main">
			<h1 class="header_1">
				<span>最新新闻</span>
				<a class="more" href="/news">【更多】</a>
			</h1>
			<div>
				@foreach ($articles as $key => $article)
				@if ($key == 0)<h2><a href="/news/{{ $article->articleid }}.html">{{ $article->title }}</a></h2>
				@endif
				@endforeach
			</div>
			<ul>
				@foreach ($articles as $key => $article)
				@if ($key >= 1)
				<li><a href="/news/{{ $article->articleid }}.html">{{ $article->title }}</a></li>
				@endif
				@endforeach
			</ul>
		</div>
	</div>

	<div class="frame_1_r">
		<div class="block_4 block_4_recommendation">
			<h1 class="header_2 header_2_download"><span>推荐下载</span></h1>
			<ul class="content">
				@foreach ($recommend as $soft)
				<li>
					<img src="{{ \ca\Service\SoftService::icon($soft->softid) }}" style="display: block;width: 32px;height: 32px;float: left;margin-right: 5px;">
					<a   href="/soft/{{ $soft->softid }}.html">
						{{ $soft->name }} <span style="display: inline;">{{ $soft->version }}</span>
					</a>
				</li>
				@endforeach
			</ul>
		</div>
		<div class="ad_1">
			{{ Ca\Service\AdService::show('300h_rb', 'soft') }}
		</div>
	</div>
	<div class="clear"></div>
</div>
<div class="frame_1 pc_essential">
	<h1 class="header_1"><span>PC 装机必备</span></h1>
	<div class="group">
		<h2 style="background-color:#f8bd27">网络工具</h2>
		<ul class="column">
			@if (!empty($indispensably[5]))
			@foreach ($indispensably[5] as $key => $soft)
			<li><a title="{{ $soft->name }}" href="/soft/{{ $soft->softid }}.html"><img src="{{ Ca\Service\SoftService::icon($soft->softid) }}"><span>{{ $soft->name }}</span></a></li>
			@if (($key + 1) % 4 == 0 && $key < 15 && ($key + 1) != count($indispensably[5]))
		</ul><ul class="column">
			@endif
			@endforeach
			@endif
		</ul>
	</div>

	<div class="group group_even">
		<h2 style="background-color:#5eb990">系统工具</h2>
		<ul class="column">
			@if (!empty($indispensably[2]))
			@foreach ($indispensably[2] as $key => $soft)
			<li><a title="{{ $soft->name }}" href="/soft/{{ $soft->softid }}.html"><img src="{{ Ca\Service\SoftService::icon($soft->softid) }}"><span>{{ $soft->name }}</span></a></li>
			@if (($key + 1) % 4 == 0 && $key < 15 && ($key + 1) != count($indispensably[2]))
		</ul><ul class="column">
			@endif
			@endforeach
			@endif
		</ul>

	</div>

	<div class="group">
		<h2 style="background-color:#4bc1ed">应用工具</h2>
		<ul class="column">
			@if (!empty($indispensably[3]))
			@foreach ($indispensably[3] as $key => $soft)
			<li><a title="{{ $soft->name }}" href="/soft/{{ $soft->softid }}.html"><img src="{{ Ca\Service\SoftService::icon($soft->softid) }}"><span>{{ $soft->name }}</span></a></li>
			@if (($key + 1) % 4 == 0 && $key < 15 && ($key + 1) != count($indispensably[3]))
		</ul><ul class="column">
			@endif
			@endforeach
			@endif
		</ul>
	</div>
	<div class="group group_even">
		<h2 style="background-color:#f16149">联络聊天</h2>
		<ul class="column">
			@if (!empty($indispensably[17]))
			@foreach ($indispensably[17] as $key => $soft)
			<li><a title="{{ $soft->name }}" href="/soft/{{ $soft->softid }}.html"><img src="{{ \ca\Service\SoftService::icon($soft->softid) }}"><span>{{ $soft->name }}</span></a></li>
			@if (($key + 1) % 4 == 0 && $key < 15 && ($key + 1) != count($indispensably[17]))
		</ul><ul class="column">
			@endif
			@endforeach
			@endif
		</ul>
	</div>

	<div class="group">
		<h2 style="background-color:#95d54b">图形图像</h2>
		<ul class="column">
			@if (!empty($indispensably[18]))
			@foreach ($indispensably[18] as $key => $soft)
			<li><a title="{{ $soft->name }}" href="/soft/{{ $soft->softid }}.html"><img src="{{ Ca\Service\SoftService::icon($soft->softid) }}"><span>{{ $soft->name }}</span></a></li>
			@if (($key + 1) % 4 == 0 && $key < 15 && ($key + 1) != count($indispensably[18]))
		</ul><ul class="column">
			@endif
			@endforeach
			@endif
		</ul>
	</div>

	<div class="group group_even">
		<h2 style="background-color:#995df2">其他软件</h2>
		<ul class="column">
			@if (!empty($indispensably[19]))
			@foreach ($indispensably[19] as $key => $soft)
			<li><a title="{{ $soft->name }}" href="/soft/{{ $soft->softid }}.html"><img src="{{ Ca\Service\SoftService::icon($soft->softid) }}"><span>{{ $soft->name }}</span></a></li>
			@if (($key + 1) % 4 == 0 && $key < 15 && ($key + 1) != count($indispensably[19]))
		</ul><ul class="column">
			@endif
			@endforeach
			@endif
		</ul>
	</div>
	<div class="clear"></div>
</div>