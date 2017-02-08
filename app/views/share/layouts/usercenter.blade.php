@include ('share.layouts.header')
<script type="text/javascript">
var informationContent = "{{ Session::get(('information')) }}";
var hasInformation = informationContent != "";
</script>
<script type="text/javascript">
var showInformation = function(text, informationContainer, type) {
	var informationContainer = $(".information_1");
	type = type || "success";
	$("div", informationContainer).removeClass().addClass(type).text(text);
	informationContainer.slideDown();
	setTimeout(function() {
		informationContainer.slideUp("normal");
	}, 3000);

	$(".close", informationContainer).click(function () {
		informationContainer.slideUp("normal");
		return false;
	});
};
$(function() {
	if (hasInformation)
		showInformation("{{ Session::get(('information')) }}");
});
</script>

<div class="main_content">
	<div class="spacer_1"></div>
	<div class="frame_1">
		<h1 class=" header_1">
			<span>个人中心</span>
		</h1>
		<div class="user_center">
			<div class="frame_2_l user_menu">
				<div class="avatar">
					<a target="_blank" href="{{ 'http://user.' . app()->environment() . '/profile' }}"><img src="{{ Config::get('app.asset_url') . '/images/user/avatar_32.gif' }}" /></a>
				</div>
				<dl class="menu_1">
					<dt><a class="subject">消息中心</a></dt>
					<dd {{ $title == '系统消息' ? 'class="hot"' : '' }}><a href="/usercenter/message">系统消息</a></dd>
					<dt><a class="file">我的资源</a></dt>
					@if (in_array(Ca\Service\CurrentUserService::$user_id, Ca\Consts::$have_my_document))
					<dd {{ $title == '我的文档' ? 'class="hot"' : '' }}><a href="/usercenter/document">我的文档</a></dd>
					@endif
					<dd {{ $title == '已收藏文档' ? 'class="hot"' : '' }}><a href="/usercenter/document/favorite">已收藏文档</a></dd>
					<dd {{ $title == '已下载文档' ? 'class="hot"' : '' }}><a href="/usercenter/document/download">已下载文档</a></dd>
					<dd {{ $title == '我的专题' ? 'class="hot"' : '' }}><a href="/usercenter/topic">我的专题</a></dd>
					<dd {{ $title == '已收藏专题' ? 'class="hot"' : '' }}><a href="/usercenter/topic/favorite">已收藏专题</a></dd>
					<dt><a class="answer">我的问答</a></dt>
					<dd {{ $title == '我来解答' ? 'class="hot"' : '' }}><a href="/usercenter/knows/attention">我来解答</a></dd>
					<dd {{ $title == '我的问题' ? 'class="hot"' : '' }}><a href="/usercenter/knows/ask">我的问题</a></dd>
					<dd {{ $title == '我参与问题' ? 'class="hot"' : '' }}><a href="/usercenter/knows/answer">我参与问题</a></dd>
					<dd {{ $title == '已收藏问题' ? 'class="hot"' : '' }}><a href="/usercenter/knows/favorite">已收藏问题</a></dd>
					<dt><a class="file">我的讲座</a></dt>
					<dd {{ $title == '参与讲座' ? 'class="hot"' : '' }}><a href="/usercenter/meeting">参与讲座</a></dd>
				</dl>
				<div class="clear"></div>
			</div>
			<div class="frame_2_r">
				{{ $content }}
			</div>
			<div class="clear"></div>
		</div>
	</div>
</div>
@include ('share.layouts.footer')

