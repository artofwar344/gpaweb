<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<title>
		{{ empty($title) ? '' : $title . ' - ' }}
		{{ $sitename }}
	</title>
	<link rel="shortcut icon" href="{{ Config::get('app.asset_url') . 'images/share/logo.ico' }}" type="image/x-icon" />
	<link href="{{ Config::get('app.asset_url') . 'css/share.css?' . Ca\Consts::$ca_version }}" rel="stylesheet" type="text/css" />
	<script type="text/javascript" src="{{ Config::get('app.asset_url') }}scripts/jquery.js"></script>
	<script type="text/javascript" src="{{ Config::get('app.asset_url') }}scripts/jquery-ui-1.10.2.custom.min.js"></script>
	<script type="text/javascript" src="{{ Config::get('app.asset_url') . 'scripts/share.js?' . Ca\Consts::$ca_version }}"></script>
	<script type="text/javascript">
		var current = '<?php echo isset($nav) ? $nav : '' ?>';
		var dialogParams = { autoOpen: false, modal: true, resizable: false, width: 300, height: "auto", minHeight: 0 };
		$(function() {
			$(".main_header .search .button").on("click", function() {
				var textBox = $(this).prev();
				var form = $(this).closest("form");

				if ($.trim(textBox.val()) == "") {
					textBox.select();
					return false;
				}
				form.submit();
				return false;
			});

			$(".main_menu a").filter(function() {
				return $(this).text() == current;
			}).addClass("hot");

			$(".dialog_1").draggable({handle: ".header"});
		});
	</script>
	@if (Auth::check())
	<script type="text/javascript">
		$(function() {
			var messageContainer = $("#message_container");
			$(".btn_delete", messageContainer).on("click",function() {
				messageContainer.slideUp();
				return false;
			});
			//读取新消息
			var getNewMsg = function() {
				$.post("/message/newmessage", function(ret) {
					if (ret.status == 1) {
						var messages = ret.messages;

						$("ul", messageContainer).empty();
						$.each(messages, function(index, message) {
							var li = $("<li/>");
							switch (message["type"] >> 0) {
								case {{ Ca\MessageType::getNewAnswer }}:
								li.html("您收到了<span>" + message['message_count'] + "</span>个新的回答");
								break;
								case {{ Ca\MessageType::acceptAnswer }}:
								li.html("您有<span>" + message['message_count'] + "</span>个回答被采纳");
								break;
								case {{ Ca\MessageType::moreAnswer }}:
								li.html("您有<span>" + message['message_count'] + "</span>个回答有新的追问");
								break;
								case {{ Ca\MessageType::updateQuestion }}:
								li.html("您有<span>" + message['message_count'] + "</span>个问题被修正");
								break;
							}
							$("ul", messageContainer).append(li);
						});
						messageContainer.slideDown();
					} else {
						messageContainer.hide();
					}
				}, "json");
			};
			getNewMsg();
		});
	</script>
	@endif
</head>
<body>
<div id="message_container" class="message_container">
	<a href="/usercenter/message"><ul></ul></a>
	<a class="btn_delete" href="#"></a>
</div>

<div class="main_header">
	<div class="frame_1">
		<a href="/" class="logo" {{ \Ca\Service\ParamsService::get('logourl') ? 'style="background-image: url(\'' .  str_replace(array('{0}', '{1}'), array(Config::get('app.asset_url') . 'images', '_share'), \Ca\Service\ParamsService::get('logourl')) . '\')"' : '' }}></a>
		@if (\Ca\Service\ParamsService::get('showsublogo', 1) == 1)
		<span class="sublogo">
			<img src="{{ Config::get('app.asset_url') . 'images/customer/' . App::make('customer')->alias . '.jpg' }}">
		</span>
		@endif
		<div class="userinfo">
			@if (!Auth::check())
				欢迎来到GP资源共享网站!
				<a href="http://user.{{ app()->environment() }}/login?ret={{ urlencode(URL::full()) }}" >登录</a>
				<span>
				@if (Ca\Service\ParamsService::get('register'))
				|</span>
				<a href="http://user.{{ app()->environment() }}/register">注册</a>
			@endif
			@else
				<span>欢迎您! {{ Auth::user()->name }}</span>
				<a href="{{ 'http://user.' . app()->environment() . '/profile' }}" target="_blank">用户信息</a>
				<span>|</span>
				<a href="{{ 'http://user.' . app()->environment() . '/logout?ret=' . urlencode(URL::full()) }}">登出</a>
			@endif
		</div>

		<div class="search">
			<form action="/search" method="get" id="form_search">
				<div class="textbox">
					<input placeholder="输入搜索关键词" type="text" name="text_search" 
						@if (isset($condition)) 
							value="{{ $condition['name'] }}" 
						@endif
					>
					<a href="#" class="button">文件搜索</a>
				</div>
				<div class="options">
					<input type="radio" id="search_all" name="ext" value="all"
					@if (!isset($condition) || (isset($condition) && $condition['extension'] == 'all')) checked="checked" @endif
					><label>全部</label>
					<input type="radio" id="search_doc" name="ext" value="doc"
						@if (isset($condition) && $condition['extension'] == 'doc') checked="checked" @endif
					><label for="search_doc">DOC</label>
					<input type="radio" id="search_ppt" name="ext" value="ppt"
						@if (isset($condition) && $condition['extension'] == 'ppt') checked="checked" @endif
					><label for="search_ppt">PPT</label>
					<input type="radio" id="search_pdf" name="ext" value="pdf"
						@if (isset($condition) && $condition['extension'] == 'pdf') checked="checked" @endif
					><label for="search_pdf">PDF</label>
					<input type="radio" id="search_xls" name="ext" value="xls"
						@if (isset($condition) && $condition['extension'] == 'xls') checked="checked" @endif
					><label for="search_xls">XLS</label>
					<input type="radio" id="search_mp4" name="ext" value="mp4"
						@if (isset($condition) && $condition['extension'] == 'mp4') checked="checked" @endif
					><label for="search_mp4">MP4</label>
				</div>
			</form>
		</div>
		<div class="clear"></div>
	</div>
</div>

<div class="main_menu">
	<div class="frame_1">
		<a href="/">资源首页</a>
		<a href="/edu">课件专区</a>
		<a href="/pro">专业资料</a>
		<a href="/form">应用文书</a>
		<a href="/topic">文档专题</a>
		<a class="addon usercenter" href="/usercenter">个人中心</a>
		<a class="addon" href="/meeting">知识讲座</a>
		<a class="addon" href="/knows">知识问答</a>
	</div>
</div>