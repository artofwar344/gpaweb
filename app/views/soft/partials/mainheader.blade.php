<div class="frame_1 header_main">
	<a href="/" class="logo"></a>
	<script type="text/javascript">
		$(function() {
			var form = $(".search_form");
			$(".search_submit").click(function() {
				form.submit();
			});
		})
	</script>
	<div class="search">
		<form class="search_form" action="/search" method="post">
		<input type="text" class="text_box" name="kw" value="{{ htmlspecialchars(\Input::get('kw')) }}" />
		<a href="#" class="search_submit">软件搜索</a>
		</form>
	</div>
	<div class="clear"></div>
</div>
<div class="main_menu">

	<ul class="frame_1">
		<li><a href="/" @if($_SERVER['REQUEST_URI']=='/')class="home" @endif>下载首页</a></li>
		<li><a href="/top/1" @if($_SERVER['REQUEST_URI']=='/top/1')class="home" @endif>网络工具</a></li>
		<li><a href="/top/2" @if($_SERVER['REQUEST_URI']=='/top/2')class="home" @endif>系统工具</a></li>
		<li><a href="/top/3" @if($_SERVER['REQUEST_URI']=='/top/3')class="home" @endif>应用工具</a></li>
		<li><a href="/top/4" @if($_SERVER['REQUEST_URI']=='/top/4')class="home" @endif>联络聊天</a></li>
		<li><a href="/top/5" @if($_SERVER['REQUEST_URI']=='/top/5')class="home" @endif>图形图像</a></li>
		<li class="last"><a href="/top/6" @if($_SERVER['REQUEST_URI']=='/top/6')class="home" @endif>其他软件</a></li>
		<li class="clear"></li>
	</ul>
</div>