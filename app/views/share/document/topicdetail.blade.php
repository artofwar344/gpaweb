<script>
	$(function() {
		@if (Auth::check() && !$isfavorite)
		$(".subject_page .fav .button_2").click(function() {
			var btn_fav = $(this);
			if (btn_fav.hasClass("button_2_disabled")) return false;
			btn_fav.text(" 收藏中").addClass("button_2_disabled");
			$.post("/document/topic/favorites", { "topicid": "{{ $topic->topicid }}" }, function(ret) {
				if (ret.status) {
					btn_fav.text(" 已收藏");
				}
			});
			return false;
		});
		@endif
	});
</script>
<div class="spacer_1"></div>
<div class="frame_1">
	<div class="frame_1_l">
		<div class="subject_page">
			<div class="subject">
				<h1 class="header_5"><span class="icon icon_subject"></span><a href="{{ '/topic/detail?id=' . $topic->topicid }}">{{ $topic->name }}</a></h1>
				<div class="fav">
					@if (!Auth::check())
						<a href="{{ 'http://user.' . app()->environment() . '/login?ret=' . urlencode(URL::full()) }}" class="button_2 button_2_a btn_2_fav"> &nbsp;收藏专题</a>
					@else
						@if (Auth::user()->userid != $topic->userid)
							@if ($isfavorite)
								<a class="button_2 button_2_disabled btn_2_fav"> &nbsp;已收藏</a>
							@else
								<a class="button_2 button_2_a btn_2_fav"> &nbsp;收藏专题</a>
							@endif
						@endif
					@endif
				</div>
				<div class="spacer_1"></div>
				<div class="content">
					<div class="rating">
						{{ Ca\Service\DocumentRatingService::rating_star_html($topic->topic_score, 'big') }}
						<span><strong>{{ $topic->rating_user_count }}</strong>人评价</span>
					</div>
					<div class="info">
						{{ $topic->user_name }}
						创建于{{ Ca\Common::datetime_to_date($topic->createdate) }}
						修改于{{ Ca\Common::datetime_to_date($topic->updatedate) }}
						<strong>{{ $topic->views }}</strong> 浏览
					</div>
					<div class="intro">{{ $topic->intro }}</div>
					<div class="amount">
						<strong>{{ $topic->favorite_user_count }}</strong> 收藏&nbsp;&nbsp;|&nbsp;&nbsp;
						<strong>{{ $topic->views }}</strong> 浏览
					</div>
				</div>
			</div>
			<h2 class="header_6">关联<strong>{{ $documents->getTotal() }}</strong>个文档</h2>
			<div class="docs">
				<table class="table_1">
					<tr>
						<th style="text-align:left">文档名称</th>
						<th style="width:100px">评分</th>
						<th style="width:50px">浏览量</th>
						<th style="width:50px">下载量</th>
					</tr>
					@foreach ($documents as $document)
					<tr>
						<td style="text-align:left">
							<a href="{{ '/document/detail?id=' . $document->documentid }}" target="_blank">{{ $document->name }}</a>
						</td>
						<td>
							{{ Ca\Service\DocumentRatingService::rating_star_html($document->document_score, 'big') }}
						</td>
						<td>{{ $document->views }}</td>
						<td>{{ $document->count_download }}</td>
					</tr>
					@endforeach
					@if ($documents->getLastPage() > 1)
					<tr>
						<td colspan="3">{{ $documents->appends(array('id' => $topic->topicid))->links() }}</td>
					</tr>
					@endif
				</table>
			</div>
		</div>
	</div>
	<div class="frame_1_r">
		@include ('share.partials.side.topic_rank')
		<div class="spacer_1"></div>
		{{ Ca\Service\AdService::show('210w_ad4', 1, 'ad_1') }}
		@include('share.partials.side.document_rank', array('documents' => $hot_document, 'rankTitle' => '文档排行榜'))
	</div>
	<div class="clear"></div>

</div>