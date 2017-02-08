<div class="spacer_1"></div>
<div class="frame_1">
	<div class="frame_1_l">
		<div class="frame_1_l">
			<h1 class="header_1"><span>文档专题</span></h1>
			<div class="spacer_1"></div>
			<div class="subject_list">
				@foreach ($topics as $topic)
				<div class="item">
					<a class="title" href="{{ '/topic/detail?id=' . $topic->topicid }}">{{ $topic->name }}</a>
					<div class="rating">
						{{ Ca\Service\DocumentRatingService::rating_star_html($topic->topic_score, 'big') }}
						<span><strong>{{ $topic->rating_user_count }}</strong>人评价</span>
					</div>
					<div class="info">
						{{ $topic->user_name }} 创建于{{ Ca\Common::datetime_to_date($topic->createdate) }}
					</div>
					<div class="content">
						{{ $topic->intro }}
					</div>
					<div class="amount">
						<strong>{{ $topic->favorite_user_count }}</strong> 收藏&nbsp;&nbsp;|&nbsp;&nbsp;
						<strong>{{ $topic->views }}</strong> 浏览
					</div>
				</div>
				@endforeach
			</div>
			@if ($topics->getLastPage() > 1)
			{{ $topic->links() }}
			@endif
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