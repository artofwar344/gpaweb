<script type="text/javascript">
$(function(){
	var searchForm = $(".knows_list form.search");
	var searchTextBox = $(":input[name='q']", searchForm);
	$(":submit", searchForm).click(function() {
		if ($.trim(searchTextBox.val()) == "") {
			searchTextBox.select();
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
				<span>最新提问</span>
				<form class="search" action="/knows/search" method="post">
					<input type="text" class="textbox_1" name="q" placeholder="搜索问答中心">
					<input type="submit" value="搜索" class="button_1 btn_1_search">
					<a class="button_2 button_2_a btn_2_question" href="/knows/new">我要提问</a>
				</form>
			</h1>
			<div class="spacer_1"></div>
			@foreach ($data as $key => $value)
				@if (count($value['questions']) > 0)
					@include('share.partials.knows.list', $value)
				@endif
			@endforeach
		</div>
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