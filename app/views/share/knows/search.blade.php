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
				<span>问答搜索</span>
				<form class="search" action="/knows/search" method="post">
					<input type="text" value="{{ $keyword }}" class="textbox_1" name="q" placeholder="搜索问答中心">
					<input type="submit" value="搜索" class="button_1">
					<a class="button_2 button_2_a btn_2_question" href="{{ url('/knows/new') }}">我要提问</a>
				</form>
			</h1>
			<div class="spacer_1"></div>
			@if ($data['questions']->getTotal() > 0)
				@include('share.partials.knows.list', $data)
			@else
				<div class="search_none">对不起，未查找到相关问答</div>
			@endif
		</div>
		@if ($data['questions']->getLastPage() > 1)
		{{ $data['questions']->links() }}
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