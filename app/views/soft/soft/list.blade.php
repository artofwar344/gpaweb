<div class="frame_1 site_path">
	当前位置：
	<a href="/">首页</a> &gt;
	软件更新
</div>
<div class="frame_2">
	<div class="frame_2_l software_list">
		@foreach ($softs as $soft)
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
		{{ $softs->links() }}
	</div>
	@include('soft.partials.slider')
	<div class="clear"></div>
</div>