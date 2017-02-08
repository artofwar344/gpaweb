@if ($document->extension =='mp4')
<script type="text/javascript" src="{{ Config::get('app.asset_url') . 'scripts/flowplayer/flowplayer-3.2.12.min.js' }}"></script>
@else
<script type="text/javascript" src="{{ Config::get('app.asset_url') . 'scripts/pdf2htmlEX.js' }}"></script>
<link href="{{ Config::get('app.asset_url') . 'css/base.css' }}" rel="stylesheet" type="text/css" />
<link href="{{ Config::get('app.asset_url') . 'documents/' . $document->swffile . '/main.css' }}" rel="stylesheet" type="text/css" />
@endif
<script type="text/javascript">
	var perPage = 5;
	try {
		pdf2htmlEX.defaultViewer = new pdf2htmlEX.Viewer({ preload_pages : perPage});
	} catch(e) {}

	$(window).scroll(function() {
		pdf2htmlEX.defaultViewer.render();
	});

	$(function() {
		var from_documentid = <?php echo ($document->from_documentid == null ? $document->documentid : $document->from_documentid); ?>;
		var dialogReport = $("div#dialogReport");
		var dialogReported = $("div#dialogReported");
		var dialogParams = { autoOpen: false, modal: true, resizable: false, width: 285, height: "auto", minHeight: 0 };
		dialogReported.dialog(dialogParams);
		dialogParams.width = 350;
		dialogReport.dialog(dialogParams);

		@if (Auth::check() && Auth::user()->userid != $document->userid && !$isfavorite)
		$(".fav").on("click", function() {
			var btnfav = $(this);
			if (btnfav.hasClass("button_2_disabled")) return false;
			btnfav.text("收藏中").addClass("button_2_disabled");
			$.post("/document/favorites", { "id": from_documentid }, function(ret) {
				btnfav.text("已收藏");
			}, "json");
			return false;
		});
		@endif

		@if (Auth::check())
//		$(".download").click(function() {
//			$.post("/document/downloads", { "id": from_documentid }, function(ret) {
//				if (ret.status == 1) {
//					window.open(ret["url"]);
//				}
//				return false;
//			}, "json");
//			return false;
//		});

		@endif

		@if ($document->extension =='mp4')
		flowplayer("player", "{{ \Config::get('app.asset_url') . 'scripts/flowplayer/flowplayer-3.2.16.swf' }}");
		@else

		@endif


		@if (!Auth::check() || (Auth::check() && !$data_rating['israting']))
		var ratingContainer = $("#rating_container");
		var starts = $("b", ratingContainer);

		var resetStar = function(score) {
			var full_count = Math.floor(score);
			for (var i = 1; i <= 5; i++) {
				if (i <= full_count) {
					starts.eq(i-1)
						.removeClass("icon_score_big_3 icon_score_big_2")
						.addClass("icon_score_big_1");
				} else if (score > full_count && i == full_count + 1) {
					starts.eq(i-1)
						.removeClass("icon_score_big_1 icon_score_big_3")
						.addClass("icon_score_big_2");
				} else {
					starts.eq(i-1)
						.removeClass("icon_score_big_1 icon_score_big_2")
						.addClass("icon_score_big_3");
				}
			}
		};

		$(".my_rating", ratingContainer).on("mouseleave", function() {
			resetStar({{ $data_rating['score'] }});
		});

		starts
			.on('mouseenter', function() {
				$(this).prevAll()
					.removeClass("icon_score_big_3 icon_score_big_2")
					.addClass("icon_score_big_1");
				$(this)
					.removeClass("icon_score_big_3 icon_score_big_2")
					.addClass("icon_score_big_1");
				$(this).nextAll()
					.removeClass("icon_score_big_1 icon_score_big_2")
					.addClass("icon_score_big_3");
			})
			.click(function() {
				@if (!Auth::check())
				window.location.href="{{ 'http://user.' . app()->environment() . '/login?ret=' . urlencode(URL::full()) }}";
				@else
				var score = $(this).attr("score");
				$.post("/document/rating", { "id": from_documentid, "score": score }, function(ret) {
					if (ret.status == 1) {
						starts.unbind("mouseenter");
						$(".my_rating", ratingContainer).unbind("mouseleave");
						resetStar(ret.score);
						$("#ratingCount").text(ret.count);
						$("#ratingScore").text(ret.score);
					}
				},"json");
				@endif
			});

		@endif


		$("#page-container").on("click", "div.switch_fullscreen", function() {
			var zoom = 1.5;
			var pd = $(".pd");
//			var viewNext = $("#viewNext");
			var pdHeight = pd.height();
			var index = pd.index($(this).parent());
			var pageContent = $("#page-container");
			if (pageContent.hasClass("fullscreen")) {
				window.setTimeout(function() {
					$(document.body).css("overflow", "auto").scrollTop(pageContent.position().top + pdHeight * index);
					pd.css({"top": 0});
//					viewNext.css({"top": 0});
				}, 10);
				pageContent.prependTo($(".document_content"));
				pageContent.removeClass("fullscreen");
				$(".switch_fullscreen").removeClass("switch_fullscreen_2");
			} else {
				pageContent.prependTo($(document.body));
				$(document.body).css("overflow", "hidden").scrollTop(0);
				window.setTimeout(function() {
					pageContent.scrollTop(pdHeight * zoom * index);
					if (navigator.userAgent.indexOf("Firefox") >= 0) {
						$.each(pd, function(i, element) {
							$(element).css({"top": pdHeight * zoom * i / 3});
						});
//						viewNext.css({"top": pdHeight * zoom * perPage / 3})
					}
				}, 10);
				pageContent.addClass("fullscreen");
				$(".switch_fullscreen").addClass("switch_fullscreen_2");
			}
		});
	});
</script>

@include('share.partials.common.report_block', array('data' => Ca\Consts::$report_document_reason_texts, 'dialogTitle' => '举报文档', 'reportUrl' => '/document/report'))

<div class="spacer_1"></div>
<div class="frame_1">
	<div class="frame_1_l document_detail">
		<div class="header_5"><a href="/document/detail?id={{ $document->documentid }}">{{ $document->name }}</a></div>
		<div class="info">
			<span>{{ $document->uname }}</span>
			<span>{{ Ca\Common::time_ago($document->createdate) }}</span> |
			@if ($document->categoryid !== null)
			<span>类别:<a href="/document/list/{{ $document->categoryid }}">{{ $document->category_name }}</a></span>
			@endif
		</div>
		<div class="info">
			<div id="rating_container" class="rating_container">
				<p>
					<span class="my_rating">
					{{ Ca\Service\DocumentRatingService::rating_star_html($data_rating['score'], 'big') }}
					</span>
					<span class="rate-value" id="ratingScore">{{ $data_rating['score'] }} </span>
					@if (!Auth::check() || (Auth::check() && !$data_rating['israting']))
					@endif
					( <span class="font_color" id="ratingCount">{{ $data_rating['count'] }}</span>人评价 )
				<p>
			</div> |
			<span>{{ $document->views }}人阅读</span> |
			<span>{{ $document->count_download }}人下载</span> |
			<span>
				@if (!Auth::check())
				<a href="{{ 'http://user.' . app()->environment() . '/login?ret=' . urlencode(URL::full()) }}" class="report">举报</a>
				@elseif ($isReported)
				<a class="report disabled" href="#">已举报</a>
				@else
				<a tid="{{ $document->documentid }}" rtype="{{ \Ca\ReportType::document }}" class="report" href="#">举报</a>
				@endif
			</span>
			<div class="document_actions">
				@if (!Auth::check())
				<a href="{{ 'http://user.' . app()->environment() . '/login?ret=' . urlencode(URL::full()) }}" class="button_2 button_2_a btn_2_fav fav"> &nbsp;收藏文档</a>
				@else
					@if (Auth::user()->userid == $document->userid || $isfavorite)
						<a class="button_2 btn_2_fav button_2_disabled fav">已收藏</a>
					@else
						<a class="button_2 button_2_a btn_2_fav fav">收藏文档</a>
					@endif
				@endif

				<a class="button_2 button_2_b btn_2_download download"
				@if (!Auth::check())
				href="{{ 'http://user.' . app()->environment() . '/login?ret=' . urlencode(URL::full()) }}"
				@else
				href="/document/downloads?id={{ $document->documentid }}" target="_blank"
				@endif
				>下载文档</a>
			</div>
		</div>
		@if ($document->extension =='mp4')
		<div class="document_content video_box">
<!--			<a id="player" href="{{ Ca\Service\FastdfsService::gen_download_url($document->originalfile) }}"></a>-->
			<a id="player" href="{{ Config::get('app.asset_url') . $document->originalfile }}"></a>
		</div>
		@else
		<?php
//		$perPage = 5;
//		$pages = ceil($document->pages / $perPage);
//		$page  = min(max(1, InputExt::get('p')), $pages);
		?>

		<div class="document_content">
			<div id="page-container">
				@foreach (range(1, 5) as $i)
				@if ($document->pages >= $i)
				<div class="pd w0 h0">
					<div id="pf{{ dechex($i) }}" class="pf" data-page-no="{{ dechex($i) }}" data-page-url="/document/detailpage?id={{ $document->documentid . '-' . $i }}"></div>
					<div style="position:relative; z-index:999; text-align:center; top:40%"><img src="{{ Config::get('app.asset_url') . 'images/loading_3.gif' }}" /></div>
				</div>
				@endif
				@endforeach
			</div>
			<a class="button_more download"
			@if (!Auth::check())
			href="{{ 'http://user.' . app()->environment() . '/login?ret=' . urlencode(URL::full()) }}"
			@else
			href="/document/downloads?id={{ $document->documentid }}" target="_blank"
			@endif
			>点击下载文档，查看完整内容</a>
		</div>
		@endif
		<div class="spacer_1"></div>
		@include('share.partials.document.list1', $data)
	</div>
	<div class="frame_1_r">
		@if (count($attachments) > 0)
		<div class="ranking_block">
			<h1>文档附件列表</h1>
			<table>
				@foreach ($attachments as $key => $document)
				<tr>
					<td class="name">
						<a class="title" title="{{ $document->name }}"
						@if (!Auth::check())
						href="{{ 'http://user.' . app()->environment() . '/login?ret=' . urlencode(URL::full()) }}"
						@else
						href="/document/downloads?id={{ $document->documentid }}" target="_blank"
						@endif
						>{{ $document->name }}</a>
					</td>
					<td class="pages">
						<a
						@if (!Auth::check())
						href="{{ 'http://user.' . app()->environment() . '/login?ret=' . urlencode(URL::full()) }}"
						@else
						href="/document/downloads?id={{ $document->documentid }}" target="_blank"
						@endif
						>下载</a>
					</td>
				</tr>
				@endforeach
			</table>
		</div>
		<div class="clear"></div>
		@endif
		<div class="spacer_1"></div>
		@include('share.partials.side.document_rank', array('documents' => $hot_document, 'rankTitle' => '文档排行榜'))
	</div>
	<div class="clear"></div>

</div>
