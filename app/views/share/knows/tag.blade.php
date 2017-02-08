<script>
	$(function(){
		$(":submit", "form.search").click(function() {
			if ($.trim($(":input[name='q']", "form.search").val()) == "") {
				$(":input[name='q']", "form.search").focus();
				return false;
			}
		});
	});

</script>

<div class="spacer_1"></div>
<div class="frame_1 home_content">
	<div class="frame_1_l">
		<div class="knows_list">
			<h1 class="header_1">
				<span>知识问答</span>
				<form class="search" action="/knows/search" method="post">
					<input type="text" class="textbox_1" name="q" placeholder="搜索问答中心">
					<input type="submit" value="搜索" class="button_1">
					<a class="button_2 button_2_a btn_2_question" href="{{ url('/knows/new') }}">我要提问</a>
				</form>
			</h1>
			<div class="spacer_1"></div>
			@include('share.partials.knows.list', $data)
		</div>
		@if (isset($pages))
		{{ $pages }}
		@endif
	</div>

	<div class="frame_1_r">
		{{ Ca\Service\AdService::show('210w_ad_knows1', 1, 'ad_1') }}
		@include ('share.partials.side.knows_rank')
		<div class="spacer_1"></div>
		{{ Ca\Service\AdService::show('210w_ad_knows2', 1, 'ad_1') }}
		@include ('share.partials.side.knows_tag_cloud')
	</div>
	<div class="clear"></div>
</div>