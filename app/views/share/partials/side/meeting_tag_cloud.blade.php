
<script type="text/javascript" src="{{ Config::get('app.asset_url') }}scripts/tinysort.js"></script>
<link href="{{ Config::get('app.asset_url') . 'css/tags.css?' . Ca\Consts::$ca_version }}" rel="stylesheet" type="text/css" />
<div class="tags_block">
	<ul>
		@foreach (Ca\Service\MeetingService::get_hot_tag() as $tag)
			<li class="tag{{ rand(1,5) }}"><a href="{{ '/meeting/tag/' . $tag->tagid }}">{{ $tag->name }}</a></li>
		@endforeach
	</ul>
</div>
<div class="clear"></div>
